<?php
require_once __DIR__ . '/../rb/db.php';
if (!isset($_POST['model_id']) || !isset($_POST['table'])) {
  echo json_encode(['status' => 'error', 'message' => 'Не переданы данные']);
  exit;
}
$modelId = (int)$_POST['model_id'];
$table = $_POST['table']; 
$tagRelationTable = '';
switch ($table) {
    case "projects":
        $tagRelationTable = 'hashtagtoproject';
        break;
    default:
        $tagRelationTable = 'hashtagtomodels';
}
$tags = R::getAll('SELECT hm.id AS tag_link_id, h.name 
                  FROM hashtags h 
                  JOIN ' . $tagRelationTable . ' hm ON h.id = hm.hashtagfromheshtag 
                  WHERE hm.typeid = ?', [$modelId]);

echo json_encode(['status' => 'success', 'tags' => $tags]);
?>
