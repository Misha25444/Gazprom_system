<?php
require_once __DIR__ . '/../rb/db.php';
if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user']->role !== "Администратор") {
    echo json_encode(["success" => false, "message" => "Недостаточно прав"]);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tagId = $_POST['id'] ?? null;
    $tableName = $_POST['table'] ?? null;
    if (!$tagId || !$tableName) {
        echo json_encode(["success" => false, "message" => "Некорректные данные: отсутствует id или table"]);
        exit;
    }
    $allowedTables = [
        "models" => "hashtagtomodels",
        "video" => "hashtagtomodels",
        "audio" => "hashtagtomodels",
        "documents" => "hashtagtomodels",
        "material" => "hashtagtomodels",
        "image" => "hashtagtomodels",
        "texture" => "hashtagtomodels",
        "projects" => "hashtagtoproject"
    ];

    if (!isset($allowedTables[$tableName])) {
        echo json_encode(['success' => false, 'message' => 'Неизвестная таблица']);
        exit;
    }
    $tagRelationTable = $allowedTables[$tableName];
    try {
        $deleted = R::exec("DELETE FROM {$tagRelationTable} WHERE id = ?", [$tagId]);

        if ($deleted) {
            echo json_encode(["success" => true, "message" => "Тег удалён"]);
        } else {
            echo json_encode(["success" => false, "message" => "Тег не найден"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Ошибка удаления: " . $e->getMessage()]);
    }
}

?>
