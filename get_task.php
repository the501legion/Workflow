<?php
include '_conn.php';

$sql = $pdo->prepare('SELECT * FROM story_task ORDER BY `id` DESC');
$sql->execute();
$result = $sql->fetchAll();

echo json_encode($result);
?>