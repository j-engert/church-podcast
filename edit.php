<?PHP
session_start();
include "vendor/autoload.php";

use Facebook\GraphSessionInfo;
use Facebook\GraphUser;
use Facebook\FacebookRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
FacebookSession::setDefaultApplication('380529472104778', '089df4189fd22acaccb75f581073b772');
$helper = new FacebookRedirectLoginHelper('http://podcast.geekjeff.us/login.php');
try {
  $session = $helper->getSessionFromRedirect();

  } catch(FacebookRequestException $ex) {
    // When Facebook returns an error
    exit();
  } catch(\Exception $ex) {
    // When validation fails or other local issues
    exit();
  }
  // lets run an update
  $db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");
  $id = $_GET['id'];
  $query = "SELECT * FROM sermons where id = $id";
  $old = $db->query($query)->fetch(PDO::FETCH_ASSOC);

?>
<html>
<head>
  <title>Post new audio</title>
  <script src="parser_rules/advanced.js"></script>
  <script src="dist/wysihtml5-0.3.0.min.js"></script>
  <link href="css/default.css" rel="stylesheet">
</head>
<body>
  <h1>updating a podcast</h1>
  <form name="input" action="post_update.php?id=<?=$id?>" method="post">
    <label for="title">Title: </label><input type="text" name="title" value="<?= $old['title'] ?>">
    <input type="submit" value="Submit" style="float:right"><br/>
    <label for="date">date: </label><input type="date" name="date" value=<?= $old['date'] ?>><br/>
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
    <textarea id="wysihtml5-editor" name="body" autofocus>

      <?= $old['body'] ?>
    </textarea>
  </section>

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
