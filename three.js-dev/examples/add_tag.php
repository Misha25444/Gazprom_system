<?php
require_once __DIR__ . '/../rb/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tagName = trim($_POST['tag']);
    $modelId = intval($_POST['model_id']);
    $tableName = $_POST['table']; 
    if ($tagName === '' || $modelId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
        exit;
    }

    $hashtag = R::findOne('hashtags', 'name = ?', [$tagName]);

    if (!$hashtag) {
        $hashtag = R::dispense('hashtags');
        $hashtag->name = $tagName;
        R::store($hashtag);
    }
    switch ($tableName) {
        case "models":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-cube';
            break;
        case "video":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-video-camera';
            break;
        case "audio":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-music';
           break;
        case "documents":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-file';
            break;
        case "material":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-paint-brush';
            break;
        case "image":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-picture-o';
            break;
        case "texture":
                $tagRelationTable = 'hashtagtomodels';
                $icon_name='fa-object-group';
            break;
        case "projects":
                $tagRelationTable = 'hashtagtoproject';
                $icon_name='fa-archive';
            break;
        default:
            echo json_encode(['status' => 'error', 'message' => 'Неизвестная таблица']);
            exit;
    }

    $existingTag = R::findOne($tagRelationTable, 'hashtagfromheshtag = ? AND typeid = ?', [$hashtag->id, $modelId]);

    if ($existingTag) {
        echo json_encode(['status' => 'error', 'message' => 'Тег уже добавлен']);
        exit;
    }

    $tagModel = R::dispense($tagRelationTable);
    $tagModel->hashtagfromheshtag = $hashtag->id;
    $tagModel->typeid = $modelId;
    R::store($tagModel);

    echo json_encode([
        'status' => 'success',
        'tag' => $tagName,
        'tag_id' => $hashtag->id,
        'icon'=>$icon_name

    ]);
    exit;
}
?>
