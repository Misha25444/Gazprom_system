<?php
require_once __DIR__ . '/../rb/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tagId = $_POST['id'] ?? null;
    $tableName = $_POST['table'] ?? null;
    if (!$tagId || !$tableName) {
        echo json_encode(["success" => false, "message" => "Некорректные данные"]);
        exit;
    }
    $deleted = R::exec("DELETE FROM hashtagtomodels WHERE id = ?", [$tagId]);
    if ($deleted) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Ошибка удаления"]);
    }
}

?>