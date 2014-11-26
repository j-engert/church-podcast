<?PHP
$db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");
$id = $_GET["id"];
$sta = $db->prepare("SELECT * FROM sermons WHERE id = :id");
$sta->bindParam(":id", $id);
$sta->execute();
$sermon = $sta->fetchAll(PDO::FETCH_ASSOC);
$sermon = $sermon[0];
?>
<HTML>
  <HEAD>
    <TITLE><?=$sermon['title']?></TITLE>
    <link rel="stylesheet" type="text/css" href="css/editor.css">
    <script type="text/javascript" src="//sslstatic.wix.com/services/js-sdk/1.40.0/js/wix.min.js"></script>
  </HEAD>
  <BODY>
    <H2><?=$sermon['title']?></H2>
    <p style='date'><?=strftime("%B %d, %Y", strtotime($sermon['date']))?></p>
    <?PHP
    $start = '<audio controls>
    <source src="{filename}" type="audio/mpeg">
    <a href = {filename}>downlad</a>
    </audio>';
    $player = str_replace('{filename}', $sermon['filename'], $start);
    $body = str_replace('{audio}', $player, $sermon['body']);
    ?>
    <p style='body'>
      <?=$body?>
    </p>
  </BODY>
</HTML>
