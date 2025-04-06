<?php
require_once __DIR__ . '/../rb/db.php';
if (isset($_GET['q'])) {
    $query = $_GET['q'];
    $tags = R::find('hashtags', 'name LIKE ?', ['%' . $query . '%']);
    $result = [];
    foreach ($tags as $tag) {
        $result[] = ['id' => $tag->id, 'name' => $tag->name];
    }
    header('Content-Type: application/json');
    echo json_encode($result);
}
?>