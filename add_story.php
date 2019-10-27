<?php
include '_conn.php';

$name = $_POST['name'];
$description = $_POST['description'];

$sql = $pdo->prepare('INSERT INTO `story_info`(`name`, `description`) VALUES (:name, :description)');
$sql->bindParam(':name', $name, PDO::PARAM_STR, 256);
$sql->bindParam(':description', $description, PDO::PARAM_STR, 1024);
$sql->execute();

$sql = $pdo->prepare('SELECT LAST_INSERT_ID() as id;');
$sql->execute();
$result = $sql->fetch();

echo json_encode($result);
?>