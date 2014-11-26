<HTML>
  <HEAD>
    <meta charset="UTF-8">
    <TITLE>Audio that is unpublished</TITLE>
  </HEAD>
  <BODY>
    <?PHP
require_once('mp3/getid3.lib.php');
require_once('mp3/getid3.php');
require_once('mp3/module.tag.id3v2.php');
$path = "audio/Epiphany_*.mp3";
function issetor(&$var, $default = false) {
    return isset($var) ? $var : $default;
}
$files = glob($path);
$getID3 = new getID3;
$db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");

foreach($files as $file){
  $link = preg_replace('/audio\/Epiphany_(.*).mp3/i','$1',$file);
  $sta = $db->prepare("SELECT count(*) FROM sermons where date='$link'");
  $sta->execute();
  $count = $sta->fetch();
  $count = $count[0];
  $count = $count;
  if($count == 1) continue; #if it is in the database, do not display it here
  $FI = $getID3->analyze($file);
  if (isset($FI['id3v2'])){
    $vals = $FI['id3v2']['comments'];
    $comment = preg_replace('/(.*)( and )(.*)/i', '<i>$1</i>$2<i>$3</i>',issetor($vals['comment'][0]));
    if(stripos($comment, '<i>') === false) $comment = '<i>'.$comment.'</i>';

    $date = strftime("%B %e, %Y", strtotime($link));
    print "<p class=\"event\"><a href=\"new_audio.php?date=$link\">".$file . "</a><br/>\n";
    print '<p class="date">' . $date . "</p>\n";
    print 'by: <span class="name">' . issetor($vals['artist'][0]) . "</span>";
    print "<br/>" . $comment . "</p>\n";
  } else {
    print $file . "\n";
    print_r($FI);
  }
}
?>
</BODY>
</HTML>
