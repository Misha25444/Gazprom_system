<?PHP
require_once __DIR__ . '/../rb/db.php';
require_once 'php_date.php';

if ($_SESSION['logged_user']->role == "Администратор") {
  $isAdmin = true;
  $users = R::findAll('users');
} else {
  $isAdmin = false;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_SESSION['logged_user'])) {
    $userId = $_SESSION['logged_user']['id'];
  } else {
    header("Location: login.php");
    exit();
  }

}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tagInput = filter_input(INPUT_POST, 'tagInput', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $secondClass = filter_input(INPUT_POST, 'secondClass', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $sorting = filter_input(INPUT_POST, 'sorting', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $sizes = filter_input(INPUT_POST, 'sizes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $formatsInput = filter_input(INPUT_POST, 'formatsInput', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if ($page === false || $page < 1) {
        $page = 1;
    }
    if ($page < 2) {
        header("Location: search_result.php?page=1&tagInput=" . urlencode($tagInput) . 
               "&secondClass=" . urlencode($secondClass) . 
               "&sorting=" . urlencode($sorting) . 
               "&sizes=" . urlencode($sizes) . 
               "&date=" . urlencode($date) . 
               "&formatsInput=" . urlencode($formatsInput));
        exit();
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    $tagInput = filter_input(INPUT_GET, 'tagInput', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $secondClass = filter_input(INPUT_GET, 'secondClass', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $sorting = filter_input(INPUT_GET, 'sorting', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $sizes = filter_input(INPUT_GET, 'sizes', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $formatsInput = filter_input(INPUT_GET, 'formatsInput', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    $page = ($page === false || $page < 1) ? 1 : $page;
}
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
  }
$formatConditions = [];
if (!empty($formatsInput)) {
    $formats = array_map('trim', explode(',', $formatsInput));
    foreach ($formats as $format) {
        $formatConditions[] = "m.format = '$format'";
    }
}
  $stringsql = '';
  $hashtags = [];
  preg_match_all('/#([^#\s]+)/', $tagInput, $matches);
  if (!empty($matches[1])) {
      $hashtags = $matches[1];
  }
  $tagInputWithoutHashtags = trim(preg_replace('/#\S+\s*/', '', $tagInput));
  $words = array_filter(explode(' ', $tagInputWithoutHashtags));
  $conditions = [];
  if (!empty($hashtags)) {
      $tagConditions = [];
      foreach ($hashtags as $tag) {
          $tagConditions[] = "h.name = '$tag'";
      }
      $conditions[] = '(' . implode(' OR ', $tagConditions) . ')'; 
  }
  
  if (!empty($words)) {
      $wordConditions = [];
      foreach ($words as $word) {
          $wordConditions[] = "m.name LIKE '%$word%'";
      }
      $conditions[] = '(' . implode(' OR ', $wordConditions) . ')';
  }
  $stringsql = '';
  $enumeration_for_date='AND';

  if (!empty($conditions)) {
      $stringsql = " AND " . implode(' OR ', $conditions);
  }
  if (!empty($formatConditions)) {
    $conditions[] = '(' . implode(' OR ', $formatConditions) . ')';
    $stringsql = " AND " . implode(' AND ', $conditions); 
}
  
  $sortingCondition = '';
  if ($sorting === 'type') {
    $sortingCondition = 'm.format ASC';
  } else if ($sorting === 'name_asc') {
    $sortingCondition = 'm.name ASC';
  }
  
  $sizesCondition = '';
  if ($sizes === 'largest-to-smallest') {
    $sizesCondition = 'm.size DESC';
  } else if ($sizes === 'smallest-to-largest') {
    $sizesCondition = 'm.size ASC';
  }
  if ((strpos($stringsql, 'WHERE'))){ $enumeration_for_date='AND'; }
  $dateCondition = '';
  if ($date === 'today') {
        $dateCondition = $enumeration_for_date." DATE(m.date) = CURDATE()";
  } else if ($date === 'yesterday') {
        $dateCondition = $enumeration_for_date." DATE(m.date) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
  } else if ($date === 'last-three-days') {
        $dateCondition = $enumeration_for_date." DATE(m.date) >= DATE_SUB(CURDATE(), INTERVAL 3 DAY)";
  } else if ($date === 'last-week') {
        $dateCondition = $enumeration_for_date." DATE(m.date) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
  } else if ($date === 'month') {
        $dateCondition = $enumeration_for_date." MONTH(m.date) = MONTH(CURDATE())";
  } else if ($date === 'half-year') {
        $dateCondition = $enumeration_for_date." DATE(m.date) >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
  }
  $limit = 50;
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $offset = ($page - 1) * $limit;
  $sql = "";
  $countSql = "";  
  

  $searchConditions = [];
  if (!empty($sortingCondition)) {
    $searchConditions[] = $sortingCondition;
  }
  if (!empty($sizesCondition)) {
    $searchConditions[] = $sizesCondition;
  }
  $orderClause = '';
  if (!empty($searchConditions)) {
    $orderClause = 'ORDER BY ' . implode(', ', $searchConditions);
  }
 
?>
<!DOCTYPE html>
<html lang="RU">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf_viewer.min.css">
<link rel="stylesheet" href="main.css">
<link rel="stylesheet" href="css/style.css">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/compressorjs@1.1.4/dist/compressor.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script type="text/javascript" src="jsm/playerjs_for_v_and_a.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</script>
<title>Результат поиска</title>
</head>
<body>
<div class=" menu5" id="menu7">
<div class="profile-card"> 
<? include 'card_users.php';?>
</div>
</div>
</div>
</div>
<? include 'navigation_bar.php';?>
<hr>
<p class="text-justify category-p">Найденные объекты </p> 
<div class="d-flex">
<?php

if ($secondClass == "fa-cube") {
    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'model'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'model'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $modelsWithTags = R::getAll($sql);
    foreach ($modelsWithTags as $model) {
        $substring = "three.js-dev/examples/";
        $newUrl = str_replace($substring, "", $model['preview']);
        $newUrlFile = str_replace($substring, "", $model['path']);
        $megabytes = round($model['size'] / 1024 / 1024, 2);
        $translatedDate = translateEnglishToRussianMonths($model['date']);
        echo '<div class="card justify-content-end">';
        
        if ($_SESSION['logged_user']->role == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                    data-table-class="models" 
                    data-table-id="' . htmlspecialchars($model['id']) . '" 
                    data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        
        echo '<picture><img src="' . $newUrl . '" alt="' . htmlspecialchars($model['name']) . '" loading="lazy"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
                <h3 class="card-title">' . pathinfo($model['name'], PATHINFO_FILENAME) . '</h3>
                <h4 class="card-text" id="fileSize">Размер: ' . htmlspecialchars($megabytes) . ' MB</h4>
                <h4 class="card-text">Формат: ' . htmlspecialchars($model['format']) . '</h4>
                <form action="viewing_3d_model.php" method="post">
                <input type="hidden" name="model_path" value="' . htmlspecialchars($newUrlFile) . '">
                <input type="hidden" name="filesize" value="' . htmlspecialchars($model['size']) . '">
                <input type="hidden" name="fileformat" value="' . htmlspecialchars($model['format']) . '">
                <input type="hidden" name="filedate" value="' . htmlspecialchars($model['date']) . '">
                <input type="hidden" name="fileproject" value="' . htmlspecialchars($model['id_project']) . '">
                <input type="hidden" name="filename" value="' . htmlspecialchars($model['name']) . '">
                <input type="hidden" name="id" value="' . htmlspecialchars($model['id']) . '">
                <div class="d-flex justify-content-start">
                <button type="submit" class="btn btn-primary" name="submit">🔍</button>
                </form>
                <form action="download.php" method="POST">
                    <input type="hidden" name="filesFromCardsDoc" value="' . htmlspecialchars($model['path']) . '">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                </form>
                <form action="viewing_project.php" method="post">
                    <input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($model['id_project']) . '">
                    <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button>
                </form>
                <button type="button" class="btn btn-primary mt-2 openModal" data-table="models" data-id="'.htmlspecialchars($model['id']).'" data-secondClass="fa-cube" data-tagInput="models" >
                <i class="fa fa-tags" aria-hidden="true"></i>
                </button>
                </div>
                </div>';
}

}else if ($secondClass == "fa-paint-brush") {

    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'material'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'material'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $modelsWithTags = R::getAll($sql);
    foreach ($modelsWithTags as $material) {
        $substring = "three.js-dev/examples/";
        $fixurl = $material['preview'];
        $newUrl = str_replace($substring, "", $fixurl);
        $megabytes = round($material['size'] / 1024 / 1024, 2);
        $idprojects = $material['id_project'];
        $translatedDate = translateEnglishToRussianMonths($material['date']);
        echo '<div class="card">';
        if ($_SESSION['logged_user']->role == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" data-table-class="material" data-table-id="' . htmlspecialchars($material['id']) . '" data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        echo '<picture><img src="' . htmlspecialchars($newUrl) . '" class="" alt="' . htmlspecialchars($material['name']) . '" loading="lazy" style="padding:32px;"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
            <h3 class="card-text">' . htmlspecialchars($material['name']) . '</h3>
            <h4 class="card-text" id="fileSize">Размер: ' . htmlspecialchars($megabytes) . ' MB</h4>
            <div class="d-flex justify-content-start">
            <form action="download.php" method="POST">
                <input type="hidden" name="filesFromCardsMaterial" value="' . htmlspecialchars($material['path']) . '">
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </form>
            <form action="viewing_project.php" method="post">
                <input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($idprojects) . '">
                <button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button>
            </form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="material" data-id="'.$material['id'].'" data-secondClass="fa-paint-brush" data-tagInput="material" >
            <i class="fa fa-tags" aria-hidden="true"></i>
            </button>
            </div>
            </div>';
    }
    
  }
  else if ($secondClass == 'fa-music') {
    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'audio'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'audio'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $audioWithTags = R::getAll($sql);
    foreach ($audioWithTags as $index => $audio) {
        $playerFile = $audio['path'];
        $substring = "three.js-dev/examples/";
        $fixurl = $audio['preview'];
        $newUrl = str_replace($substring, "", $playerFile);
        $filenameaudio = $audio['name'];
        $playerId = 'player' . ($index + 1);
        $megabytes = round($audio['size'] / 1024 / 1024, 2);
        $idprojects = $audio['id_project'];
        $translatedDate = translateEnglishToRussianMonths($audio['date']);
        
        echo '<div class="card align_player">
            <picture style="--bs: -8px;"><div id="' . $playerId . '"></div><figcaption>' . $translatedDate . '</figcaption></picture>';
        
        if ($_SESSION['logged_user']->role == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                  data-table-class="audio" 
                  data-table-id="' . htmlspecialchars($audio['id']) . '" 
                  data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        
        echo '<h3 class="card-title">' . pathinfo($filenameaudio, PATHINFO_FILENAME) . '</h3>
            <h4 class="card-text" id="fileSize">Размер: ' . $megabytes . ' MB</h4>
            <h4 class="card-text">Формат: ' . htmlspecialchars($audio['format']) . '</h4>
            <script>
            var ' . $playerId . ' = new Playerjs({id: "' . $playerId . '", file: "' . htmlspecialchars($newUrl) . '"});
            document.getElementById("' . $playerId . '").style.marginTop = "15px";
            document.getElementById("' . $playerId . '").style.marginBottom = "15px";
            document.getElementById("' . $playerId . '").classList.add("custom-picture2");
            </script>
            <div class="d-flex">
            <form action="download.php" method="POST">
                <input type="hidden" name="filesFromCardsDoc" value="' . htmlspecialchars($audio['path']) . '">
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </form>
            
            <form action="viewing_project.php" method="post">
                <input type="hidden" name="Allfilesprojects" value="' . $idprojects . '">
                <button type="submit" class="btn btn-primary" name="submit">
                <i class="fa fa-book" aria-hidden="true"></i>
                </button>
            </form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="audio" data-id="'.htmlspecialchars($audio['id']).'" data-secondClass="fa-music" data-tagInput="audio" >
            <i class="fa fa-tags" aria-hidden="true"></i>
            </button>
            </div>
            
            </div>';
    }
    
}

else if ($secondClass == 'fa-video-camera') {
    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'video'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'video'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $videosWithTags = R::getAll($sql);
    foreach ($videosWithTags as $index => $video) { 
        $playerFile = $video['path'];
        $substring = "three.js-dev/examples/";
        $fixurl = $video['preview'];
        $newUrl = str_replace($substring, "", $playerFile);
        $filenamevideo = $video['name'];
        $playerId = 'player' . ($index + 1);
        $megabytes = round($video['size'] / 1024 / 1024, 2);
        $idprojects = $video['id_project'];
        $translatedDate = translateEnglishToRussianMonths($video['date']);
        
        echo '<div class="card align_player" style="border-radius: 12px;">
        <picture><div id="' . $playerId . '" style="border-radius: 12px;"></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
        <h3 class="card-title">' . htmlspecialchars(pathinfo($filenamevideo, PATHINFO_FILENAME)) . '</h3>';
        
        if ($_SESSION['logged_user']['role'] == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                    data-table-class="video" 
                    data-table-id="' . htmlspecialchars($video['id']) . '" 
                    data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        
        echo '<h4 class="card-text" id="fileSize">Размер: ' . htmlspecialchars($megabytes) . ' MB</h4>
            <h4 class="card-text">Формат: ' . htmlspecialchars($video['format']) . '</h4>
            <script>
            var ' . $playerId . ' = new Playerjs({id: "' . $playerId . '", file: "' . htmlspecialchars($newUrl) . '", player: 2});
            document.getElementById("' . $playerId . '").style.borderRadius = "12px";
            document.getElementById("' . $playerId . '").style.marginTop = "15px";
            document.getElementById("' . $playerId . '").style.marginBottom = "15px";
            document.getElementById("' . $playerId . '").classList.add("custom-picture");
            </script>
            <div class="d-flex">
            <form action="download.php" method="POST">
                <input type="hidden" name="filesFromCardsDoc" value="' . htmlspecialchars($video['path']) . '">
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </form>
            <form action="viewing_project.php" method="post">
                <input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($idprojects) . '">
                <button type="submit" class="btn btn-primary" name="submit">
                <i class="fa fa-book" aria-hidden="true"></i>
                </button>
            </form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="video" data-id="'.htmlspecialchars($video['id']).'" data-secondClass="fa-video-camera" data-tagInput="video" >
            <i class="fa fa-tags" aria-hidden="true"></i>
            </button>
            </div>
            </div>';
    }
    
}

else if ($secondClass == 'fa-object-group') {
    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'texture'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'texture'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $textureWithTags = R::getAll($sql);
    foreach ($textureWithTags as $texture) {
        $folderPath = $texture['path'];
        $images = glob($folderPath . '/*.jpg');
        $images = array_merge($images, glob($folderPath . '/*.jpeg'));
        $images = array_merge($images, glob($folderPath . '/*.png'));
        $megabytes = round($texture['size'] / 1024 / 1024, 2);
        $idprojects = $texture['id_project'];
        $translatedDate = translateEnglishToRussianMonths($texture['date']);
        
        echo '<div class="card justify-content-end">';
        if ($_SESSION['logged_user']->role == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                    data-table-class="textures" 
                    data-table-id="' . htmlspecialchars($texture['id']) . '" 
                    data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        echo '<picture class="collage-container" style="padding: 14px;margin-top: 15px;margin-bottom: 25px;">
                <figcaption>' . htmlspecialchars($translatedDate) . '</figcaption>
                <div class="custom-picture" style="z-index:1; padding: 31px;">';
        
        $imageCount = count($images);
        if ($imageCount >= 4) {
            echo '<div class="d-flex">';
            for ($i = 0; $i < 2; $i++) {
                echo '<img src="' . htmlspecialchars($images[$i]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84">';
            }
            echo '</div><div class="d-flex">';
            for ($i = 2; $i < 4; $i++) {
                echo '<img src="' . htmlspecialchars($images[$i]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84">';
            }
            echo '</div>';
        } elseif ($imageCount > 0) {
                echo '<div class="d-flex">';
            foreach ($images as $image) {
                echo '<img src="' . htmlspecialchars($image) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84">';
            }
            echo '</div>';
        }
        
        echo '<i class="fa fa-search-plus position-absolute" data-toggle="modal" data-target="#modal-' . $texture['id'] . '"></i>
            </div></picture>
            <h3 class="card-title">' . htmlspecialchars($texture['name']) . '</h3>
            <h4 class="card-text" id="fileSize">Размер: ' . $megabytes . ' MB</h4>
            <div class="d-flex justify-content-start">
                <form action="download.php" method="POST">
            <input type="hidden" name="filesFromCardsTexture" value="' . htmlspecialchars($texture['path']) . '">
            <button type="submit" class="btn btn-primary">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> 
            </button>
            </form>
            <form action="viewing_project.php" method="post">
                <input type="hidden" name="Allfilesprojects" value="' . $idprojects . '">
                <button type="submit" class="btn btn-primary" name="submit">
                <i class="fa fa-book" aria-hidden="true"></i>
                </button>
            </form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="texture" data-id="'.htmlspecialchars($texture['id']).'" data-secondClass="fa-object-group" data-tagInput="texture" >
            <i class="fa fa-tags" aria-hidden="true"></i>
            </button>
            </div></div>
            
            <div class="modal fade" id="modal-' . $texture['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">' . htmlspecialchars($texture['name']) . '</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">';
        
        foreach ($images as $image) {
            echo '<img src="' . htmlspecialchars($image) . '" class="img-thumbnail m-2" alt="Изображение">';
            echo '<div class="text-center">';
            echo '<a href="' . htmlspecialchars($image) . '" download>';
            echo '<i class="fa fa-arrow-down">Скачать</i>';
            echo '</a>';
            echo '</div>';
        }
        
        echo '</div></div></div></div>';
    }
    
}

else if ($secondClass == 'fa-archive') {

    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM projects m
    LEFT JOIN hashtagtoproject ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE 1=1
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM projects m
    LEFT JOIN hashtagtoproject ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE 1=1
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $archive = R::getAll($sql);
    foreach ($archive as $model) {
        $substring = "three.js-dev/examples/";
        $fixurl = $model['preview_url'];
        $id = $model['id'];
        $megabytes = round($model['size'] / 1024 / 1024, 2);
        $newUrl = str_replace($substring, "", $fixurl);
        $translatedDate = translateEnglishToRussianMonths($model['date']);
        echo '<div class="card justify-content-end">';
        if ($_SESSION['logged_user']['role'] == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                      data-table-class="projects" 
                      data-table-id="' . htmlspecialchars($model['id']) . '" 
                      data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        echo '<picture><img src="' . $newUrl . '" alt="" loading="lazy"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
            <h3 class="card-text">' . htmlspecialchars($model['name']) . '</h3>
            <h4 class="card-text" id="fileSize">Размер: ' . htmlspecialchars($megabytes) . ' MB</h4>
            <form action="viewing_project.php" method="post">
                <div class="d-flex justify-content-start p-2">
                <input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($id) . '">
                <button type="submit" class="btn btn-primary" name="submit">🔍</button>
            </form>
            <form action="download.php" method="POST">
                <input type="hidden" name="project_id" value="' . htmlspecialchars($id) . '">
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                </button>
            </form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="projects" data-id="'.htmlspecialchars($model['id']).'" data-secondClass="fa-archive" data-tagInput="projects" >
            <i class="fa fa-tags" aria-hidden="true"></i>
            </button>
            </div>
            </div>';
    }
    
}

elseif ($secondClass == 'fa-file') {

    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'document'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'document'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $modelsWithTags = R::getAll($sql);
    if(empty($modelsWithTags)) {
        echo "<h1>Нет данных </h1>";
    }

    foreach ($modelsWithTags as $document) { 
        $substring = "three.js-dev/examples/";
        $fixurl = $document['path'];
        $newUrl = str_replace($substring, "", $fixurl);
        $megabytes = round($document['size'] / 1024 / 1024, 2); 
        $translatedDate = translateEnglishToRussianMonths($document['date']);
        if (strpos($document['path'], $substring) !== 0) {
            $newUrl = "error.png";
        }
        if (!file_exists($newUrl) || filesize($newUrl) <= 0) {
            $newUrl = "error.png";
        }
        echo '<div class="card">';
        if ($_SESSION['logged_user']->role == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                    data-table-class="documents" 
                    data-table-id="' . $document['id'] . '" 
                    data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
    
        if ($newUrl == "error.png") {
            echo '<picture><div class="texture"><img src="' . $newUrl . '" alt="File not found" width="50" height="50"></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>';
        } else {
            $fileExtension = pathinfo($newUrl, PATHINFO_EXTENSION);
            if ($fileExtension == 'pdf') {
                echo '<picture><div class="texture"><canvas id="pdf-preview' . $document['id'] . '"></canvas></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>';
            } elseif ($fileExtension == 'docx') {
                echo '<picture><div class="texture"><i class="fa fa-file-word-o" style="font-size: 50px; color: #007bff;"></i></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>';
            } else {
                echo '<div class="texture"><img src="error.png" alt="File not supported" width="50" height="50"><p>Формат файла не поддерживается для превью</p></div>';
            }
        }
        echo '<div class="card-body ">
            <h5 class="card-title" align-items-center >'.htmlspecialchars($document['name']).'</h5>
            <h5 class="card-text">Размер:' . $megabytes . ' MB</h5>
            <h5 class="card-text">Формат:' . htmlspecialchars($document['format']) . '</h5>
            </div>
            <div class="d-flex justify-content-start "><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' . $document['id'] . '">🔍</button>
                <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($document['id_project']) . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
        
            <form action="download.php" method="POST">
                <input type="hidden" name="filesFromCardsDoc" value="'.htmlspecialchars($document['path']).'">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> 
                </button>
            </form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="documents" data-id="'.htmlspecialchars($document['id']) .'" data-secondClass="fa-file" data-tagInput="documents" >
            <i class="fa fa-tags" aria-hidden="true"></i>
            </button>
            </div></div>
            <div class="modal fade" id="modal' . htmlspecialchars($document['id']) . '" tabindex="-1" role="dialog" aria-labelledby="modalLabel' . htmlspecialchars($document['id']) . '" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title text-break" id="modalLabel' . htmlspecialchars($document['id']) . '">' . htmlspecialchars($document['name']) . '</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" style="height: 100vh; padding: 0;">';
        if ($fileExtension == 'pdf') {
            echo '<iframe src="' . htmlspecialchars($newUrl) . '" width="100%" height="100%" style="border: none;" ></iframe>';
        } elseif ($fileExtension == 'docx') {
            echo '<div id="docx-content' . htmlspecialchars($document['id']) . '" class="docx-content"></div>';
        }
        echo '</div>
                </div>
                </div>
                </div>';
    
        // JavaScript для отображения превью PDF на карточке
        if ($fileExtension == 'pdf') {
            echo '<script>
                pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js";
                document.addEventListener("DOMContentLoaded", async function () {
                var url = "' . htmlspecialchars($newUrl) . '";
                var canvas = document.getElementById("pdf-preview' . htmlspecialchars($document['id']) . '");
                var cardBody = canvas.parentElement;
                canvas.setAttribute("aria-label", "PDF preview");
                var parentWidth = cardBody.offsetWidth;
                var parentHeight = cardBody.offsetHeight;
                var context = canvas.getContext("2d");
                var loadingTask = pdfjsLib.getDocument(url);
                const pdf = await loadingTask.promise;
                const page = await pdf.getPage(1);
                var offsetX = (parentWidth - canvas.width);
                var offsetY = (parentHeight - canvas.height);
                const viewport = page.getViewport({ scale: 0.35 });
                const aspectRatio = viewport.width / viewport.height;
                var scaleX = parentWidth / viewport.width;
                var scaleY = parentHeight / viewport.height;
                var scale = Math.min(scaleX, scaleY);
                canvas.width = viewport.width * scale;
                canvas.height = viewport.height * scale;
                canvas.style.margin = "0";
                const renderContext = { canvasContext: context, viewport: viewport };
                await page.render(renderContext).promise;
            });
            </script>';
        }
    
        // Для рендеринга DOCX с помощью Mammoth.js
        if ($fileExtension == 'docx') {
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
                <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var previewElement = document.getElementById("docx-content' . htmlspecialchars($document['id']) . '");
                    fetch("' . htmlspecialchars($newUrl) . '")
                        .then(response => response.arrayBuffer())
                        .then(arrayBuffer => {
                            mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                                .then(function(result) {
                                    previewElement.innerHTML = result.value;
                                });
                        });
                });
                </script>';
        }
    }
    }
        
        

else if ($secondClass == 'fa-picture-o') {

    $sql = "
    SELECT m.*, GROUP_CONCAT(h.name) AS tags
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'image'
    $stringsql
    $dateCondition
    GROUP BY m.id
    $orderClause
    LIMIT $limit OFFSET $offset
    ";
    $countSql = "
    SELECT COUNT(DISTINCT m.id) AS total
    FROM models m
    LEFT JOIN hashtagtomodels ht ON m.id = ht.typeid
    LEFT JOIN hashtags h ON ht.hashtagfromheshtag = h.id
    WHERE m.content_types = 'image'
    $stringsql
    $dateCondition
    ";
    $totalModels = R::getCell($countSql);
    $totalPages = ceil($totalModels / $limit);
    $modelsWithTags = R::getAll($sql);
    foreach ($modelsWithTags as $image) {
        if (strpos($image['path'], "three.js-dev/examples/") !== false) {
            $newUrl = str_replace("three.js-dev/examples/", "", $image['preview']);
        } else {
            $newUrl = $image['path'];
        }
        $megabytes = round($image['size'] / 1024 / 1024, 2);
        $idprojects = $image['id_project'];
        $translatedDate = translateEnglishToRussianMonths($image['date']);
        echo '<div class="card">';
        if ($_SESSION['logged_user']->role == "Администратор") {
            echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                        data-table-class="image" 
                        data-table-id="' . $image['id'] . '" 
                        data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        echo '<picture><img src="' . $newUrl . '" class="" alt="' . htmlspecialchars($image['name']) . '" loading="lazy"><figcaption>' . $translatedDate . '</figcaption></picture>
        <div class="card-body">
        <h3 class="card-title">' . htmlspecialchars(pathinfo($image['name'], PATHINFO_FILENAME)) . '</h3>
        <h4 class="card-text">Размер: ' . $megabytes . ' MB</h4>
        <h4 class="card-text">Формат: ' . htmlspecialchars($image['format']) . '</h4>
        <p class="card-text">' . htmlspecialchars($image['description']) . '</p>
        </div><div class="d-flex justify-content-start">
        <form action="download.php" method="POST">
            <input type="hidden" name="filesFromCardsImage" value="' . $image['path'] . '">
            <button type="submit" class="btn btn-primary">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> 
            </button>
        </form>
        <form action="viewing_project.php" method="post">
            <input type="hidden" name="Allfilesprojects" value="' . $idprojects . '">
            <button type="submit" class="btn btn-primary" name="submit">
            <i class="fa fa-book" aria-hidden="true"></i>
            </button>
        </form>
        <button type="button" class="btn btn-primary mt-2 openModal" data-table="image" data-id="'.htmlspecialchars($image['id']).'" data-secondClass="fa-picture-o" data-tagInput="image" ><i class="fa fa-tags" aria-hidden="true"></i></button>
        </div>
        </div>';
    }
}
else {
    echo "<h1>Нет данных </h1>";
    }
?>
</div>
<div>
</div>
<?php 
$start = ($page - 1) * $limit + 1;
$end = min($start + $limit - 1, $totalModels);
echo "Показано моделей с $start по $end из $totalModels";
?>
  <div class="modal  custom-modal" id="customTagModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" >
    <div class="modal-dialog" role="document" style="top: 40%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление тегов</h5>
                <button type="button" class="close tag-close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div id="tagContainer" class="tag-container"></div>
            <input type="text" id="tagInput_card" class="form-control mt-2" placeholder="Введите тег">
            <div id="tagSuggestions" class="autocomplete-tags-box"></div> 
            <button class="btn btn-success mt-2 addTagButton">Добавить</button>
        </div>
        </div>
    </div>
</div>
 
<nav>
    <ul class="pagination">
        <?php if ($page > 1) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&tagInput=<?php echo urlencode($tagInput); ?>&secondClass=<?php echo urlencode($secondClass); ?>&sorting=<?php echo urlencode($sorting); ?>&sizes=<?php echo urlencode($sizes); ?>&date=<?php echo urlencode($date); ?>">
                ◀
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&tagInput=<?php echo urlencode($tagInput); ?>&secondClass=<?php echo urlencode($secondClass); ?>&sorting=<?php echo urlencode($sorting); ?>&sizes=<?php echo urlencode($sizes); ?>&date=<?php echo urlencode($date); ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages) : ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&tagInput=<?php echo urlencode($tagInput); ?>&secondClass=<?php echo urlencode($secondClass); ?>&sorting=<?php echo urlencode($sorting); ?>&sizes=<?php echo urlencode($sizes); ?>&date=<?php echo urlencode($date); ?>">
                ▶
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
</div>
<script src="js/main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.card-title').forEach(el => {
        if (el.scrollWidth > el.offsetWidth) {
            el.setAttribute('title', el.innerText.trim());
        }
    });
});
</script>
</body>
<footer>
<img src="Gazprom-Logo-rus.svg.png" alt="Газпром" class="logo">
</footer>
</html>
