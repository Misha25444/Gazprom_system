<?php
require_once __DIR__ . '/../rb/db.php'; 
$query = isset($_GET['query']) ? $_GET['query'] : ''; 
$tags = R::find('hashtags', 'name LIKE ?', ['%' . $query . '%']);
$tagsList = [];
foreach ($tags as $tag) {
    $tagsList[] = $tag->name;
}
echo json_encode($tagsList);
?>