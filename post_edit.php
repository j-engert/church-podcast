<?PHP
$id = (int)$_POST['id'];
$db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");
$query = "SELECT count(*) FROM sermons where id = $id";
if($db->query($query)->fetch() == 0) exit("That ID does not exist");

$stmt = $db->prepare("UPDATE sermons (title, date, body, filename)
VALUES (:title, :date, :body, :filename) WHERE id = $id");
$stmt->bindParam(':title', $_POST['title']);
$stmt->bindParam(':date', $_POST['date']);
$stmt->bindParam(':body', $_POST['body']);
$fn = "audio/Epiphany_" . $_POST['date'] . ".mp3";
$stmt->bindParam(':filename', $fn);
if($stmt->execute() == 1){
  echo "podcast edited return to <a href=\"scan_audio.php\">the list</a>";
} else {
  print_r($db->errorInfo());
}
?>
