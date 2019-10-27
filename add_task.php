<?php
include '_conn.php';

$name = $_POST['name'];
$description = $_POST['description'];
$story = $_POST['story'];

$sql = $pdo->prepare('INSERT INTO `story_task`(`name`, `description`, `story`) VALUES (:name, :description, :story);');
$sql->bindParam(':name', $name, PDO::PARAM_STR, 256);
$sql->bindParam(':description', $description, PDO::PARAM_STR, 1024);
$sql->bindParam(':story', $story, PDO::PARAM_INT);
$sql->execute();

$sql = $pdo->prepare('SELECT LAST_INSERT_ID() as id;');
$sql->execute();
$result = $sql->fetch();

echo json_encode($result);
?>