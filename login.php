<?PHP session_start();
?>
<HTML>
  <HEAD>
    <TITLE>Test Login</TITLE>
  </HEAD>
  <BODY>

<?PHP
include "vendor/autoload.php";

use Facebook\GraphSessionInfo;
use Facebook\GraphUser;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
FacebookSession::setDefaultApplication('380529472104778', '089df4189fd22acaccb75f581073b772');
if ($_SESSION['FBid']){
  $session = new FacebookSession($_SESSION['FBid']);
} else {
  $helper = new FacebookRedirectLoginHelper('http://podcast.geekjeff.us/login.php');
  try {
    $session = $helper->getSessionFromRedirect();

  } catch(FacebookRequestException $ex) {
    // When Facebook returns an error
  } catch(\Exception $ex) {
    // When validation fails or other local issues
  }
}
if ($session) {
  // Logged in.
  $request = new FacebookRequest($session, 'GET', '/me');
  $response = $request->execute();
  $FBinfo = $response->getGraphObject(GraphUser::className());
  $_SESSION['FBid'] = $session->getToken();
  if($FBinfo->getId() == '10152357488176218'){
    echo "hello " . $FBinfo->getName() . "<br/>\n";
    echo '<a href="scan_audio.php">You may now create new podcasts</a>';
    require_once('mp3/getid3.lib.php');
    require_once('mp3/getid3.php');
    require_once('mp3/module.tag.id3v2.php');
    $db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");
    $sth = $db->query("SELECT count(*) FROM sermons");
    $count = $sth->fetch();
    $count = $count[0];
    function grab_some($db, $start = 0) {
      $sta = $db->prepare("SELECT * FROM sermons ORDER BY date DESC LIMIT 10 offset :start");
      $sta->bindParam(":start", $start);
      $sta->execute();
      $answer = $sta->fetchAll(PDO::FETCH_ASSOC);
      return $answer;
    }
    $offset = $_GET['o'];
    $sermons = array();
    if (!is_null($offset) and $offset > 0 and $offset < $count){
      $sermons = grab_some($db, $offset);
    } else if ($count > 0){
      $sermons = grab_some($db);
    }
    foreach ($sermons as $sermon){
      ?>
      <a href="edit.php?id=<?= $sermon['id'] ?>"><H2><?=$sermon['title']?></H2>
        <p style='date'><?=$sermon['date']?></p></a>
        <?PHP
      }

    }  else {
      echo "you are not authorized, ask Jeff to add your facebook ID: ".$FBinfo->getId;
    }

  } else {
    echo '<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a><br/>';
  }
  ?>
</BODY>
</HTML>
