<?php
include '_conn.php';

$id = $_POST['id'];
$status = $_POST['status'];

$sql = $pdo->prepare('UPDATE story_task SET status = :status WHERE id = :id');
$sql->bindParam(':status', $status, PDO::PARAM_INT);
$sql->bindParam(':id', $id, PDO::PARAM_INT);
$sql->execute();
?>