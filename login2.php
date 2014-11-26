<?PHP include 'liblogin.php';
$session = startsession();
?>
<HTML>
  <HEAD>
    <meta charset="UTF-8">
    <TITLE>Login</TITLE>
  </HEAD>
  <BODY>

<?PHP
use Facebook\GraphSessionInfo;
use Facebook\GraphUser;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
if (!is_string($session)) {
  // Logged in.
  $request = new FacebookRequest($session, 'GET', '/me');
  $response = $request->execute();
  $FBinfo = $response->getGraphObject(GraphUser::className());
  $_SESSION['FBid'] = $session->getToken();
  if($FBinfo->getId() == '10152357488176218' or $FBinfo->getId() == '614788770'){
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
      session_destroy();
    }

  } else {
    echo $session;
  }
  ?>
</BODY>
</HTML>
