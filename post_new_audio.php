<?PHP
$date = $_POST['date'];
$db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");
$query = "SELECT count(*) FROM sermons where filename like '%$date%'";
if($db->query($query)->rowCount() != 0) exit("allready set");

$stmt = $db->prepare("INSERT INTO sermons (title, date, body, filename)
VALUES (:title, :date, :body, :filename)");
$stmt->bindParam(':title', $_POST['title']);
$stmt->bindParam(':date', $_POST['date']);
$stmt->bindParam(':body', $_POST['body']);
$fn = "audio/Epiphany_" . $_POST['date'] . ".mp3";
$stmt->bindParam(':filename', $fn);
if($stmt->execute() == 1){
  echo "podcast added return to <a href=\"scan_audio.php\">the list</a>";
} else {
  print_r($db->errorInfo());
}
?>
