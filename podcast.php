<?PHP
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
?>
<HTML>
  <HEAD>
    <meta charset="UTF-8">
    <title>Epiphany Sermons</title>
    <link rel="stylesheet" type="text/css" href="css/editor.css">
    <style>
      h2, p{
        padding: 0em 1em;
      }
    </style>
  </HEAD>
  <BODY>
    <H1>Latest Sermons from Epiphany UCC</H1>

    <?PHP
    $offset = $_GET['o'];
    $sermons = array();
    if (!is_null($offset) and $offset > 0 and $offset < $count){
      $sermons = grab_some($db, $offset);
    } else {
      $sermons = grab_some($db);
    }
    $start = '<audio controls preload="none">
    <source src="{filename}" type="audio/mpeg">
    <a href = {filename}>downlad</a>
    </audio>';
    $link = false;
    foreach($sermons as $sermon) {
      $player = str_replace('{filename}', $sermon['filename'], $start);
      if(preg_match("/(.*<p>\{audio\}<\/p>)(.*)/", $sermon['body'], $match)){
        $body = $match[1];
        if(strlen($match[2]) > 5)
          $link = true;
      } elseif (preg_match("/(.*\{audio\})(.*)/", $sermon['body'], $match)){
        $body = $match[1];
        if(strlen($match[2]) > 5)
          $link = true;
      } else $body = $sermon['body'];
      if($link){
        $title = "<a href=\"/text.php?id=" .  $sermon['id'] . 
        "\">" . $sermon['title'] . "</a>";
      } else { $title = $sermon['title']; }
      $body = str_replace('{audio}', $player, $body);
      ?>
      <H2><?= $title ?></H2>
      <p style='date'><?=strftime("%b %d, %Y", strtotime($sermon['date']))?></p>
      <p style='body'><?=$body?></p>

      <?PHP
    }
    ?>
  </BODY>
</HTML>
