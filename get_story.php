<?php
include '_conn.php';

$sql = $pdo->prepare('SELECT * FROM story_info ORDER BY `id` DESC LIMIT 1000;');
$sql->execute();
$result = $sql->fetchAll();

echo json_encode($result);
?>