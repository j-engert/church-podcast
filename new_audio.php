<?PHP
require_once('mp3/getid3.lib.php');
require_once('mp3/getid3.php');
require_once('mp3/module.tag.id3v2.php');
require_once('liblogin.php');
$session = startsession();
if (isset($_SESSION['FBid']) == FALSE ) {
  require 'liblogin.php';
  echo startsession($_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
  exit();
}
function issetor(&$var, $default = false) {
    return isset($var) ? $var : $default;
}
$getID3 = new getID3;
$file = "audio/Epiphany_".$_GET['date'].".mp3";
$FI = $getID3->analyze($file);
if (isset($FI['id3v2'])){
  $vals = $FI['id3v2']['comments'];
  $comment = preg_replace('/(.*)( and )(.*)/i', '<i>$1</i>$2<i>$3</i>',issetor($vals['comment'][0]));
  if(stripos($comment, '<i>') === false) $comment = '<i>'.$comment.'</i>';
  $date = $_GET["date"];
  $body = "<p class=\"event\">\n";
  $body .= 'by: <span class="name">' . issetor($vals['artist'][0]) . "</span>";
  $body .= "<br/>" . $comment . "<br/>{audio}</p>\n";
  $title = '"'.$vals['title'][0].'"';
} else {
  $body = "{audio}\n";
  $date = '"' . $_GET['date'] . '"';
}

?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Post new audio</title>
  <script src="parser_rules/advanced.js"></script>
  <script src="dist/wysihtml5-0.3.0.min.js"></script>
  <link href="css/default.css" rel="stylesheet">
</head>
<body>
  <h1>Form for making a new podcast</h1>
  <form name="input" action="post_new_audio.php" method="post">
    <label for="title">Title: </label><input type="text" name="title" value=<?= $title ?>><br/>
    <label for="date">date: </label><input type="date" name="date" value=<?= $date ?>><br/>
    body:<br/>
    <div id="wysihtml5-editor-toolbar">
      <header>
        <ul class="commands">
          <li data-wysihtml5-command="bold" title="Make text bold (CTRL + B)" class="command"></li>
          <li data-wysihtml5-command="italic" title="Make text italic (CTRL + I)" class="command"></li>
          <li data-wysihtml5-command="insertUnorderedList" title="Insert an unordered list" class="command"></li>
          <li data-wysihtml5-command="insertOrderedList" title="Insert an ordered list" class="command"></li>
          <li data-wysihtml5-command="createLink" title="Insert a link" class="command"></li>
          <li data-wysihtml5-command="insertImage" title="Insert an image" class="command"></li>
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Insert headline 1" class="command"></li>
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Insert headline 2" class="command"></li>
          <li data-wysihtml5-command-group="foreColor" class="fore-color" title="Color the selected text" class="command">
            <ul>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="silver"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="gray"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="maroon"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="purple"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="olive"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="navy"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue"></li>
            </ul>
          </li>
          <li data-wysihtml5-command="insertSpeech" title="Insert speech" class="command"></li>
          <li data-wysihtml5-action="change_view" title="Show HTML" class="action"></li>
        </ul>
      </header>      <div data-wysihtml5-dialog="createLink" style="display: none;">
        <label>
          Link:
          <input data-wysihtml5-dialog-field="href" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>

      <div data-wysihtml5-dialog="insertImage" style="display: none;">
        <label>
          Image:
          <input data-wysihtml5-dialog-field="src" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>
    </div>
    <section>
    <textarea id="wysihtml5-editor" placeholder="Enter your text ..." name="body" autofocus>

      <?= $body ?>
    </textarea>
  </section>
  <input type="submit" value="Submit">
        <script>
      var editor = new wysihtml5.Editor("wysihtml5-editor", {
        toolbar:     "wysihtml5-editor-toolbar",
        stylesheets: ["/css/reset-min.css", "css/editor.css"],
        parserRules: wysihtml5ParserRules
      });
      editor.on("load", function() {
        var composer = editor.composer,
            h1 = editor.composer.element.querySelector("h1");
        if (h1) {
          composer.selection.selectNode(h1);
        }
      });

    </script>
  </form>
</body>
</html>
    <?PHP
    ?>
