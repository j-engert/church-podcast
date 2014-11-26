<?PHP
$id = (int)$_GET['id'];
$db = new PDO("sqlite:".getcwd()."/epiphany_sermons.sqlite");
$query = "SELECT count(*) FROM sermons where id = $id";
if($db->query($query)->fetch() == 0) exit("That ID does not exist");

$stmt = $db->prepare("UPDATE sermons SET title = :title, date = :date, body = :body
WHERE id = $id");
$stmt->bindParam(':title', $_POST['title']);
$stmt->bindParam(':date', $_POST['date']);
$stmt->bindParam(':body', $_POST['body']);
if($stmt->execute() == 1){
  echo "podcast edited return to <a href=\"login2.php\">the list</a>";
} else {
  print_r($db->errorInfo());
}
?>
