<?PHP
require_once __DIR__ . '/../rb/db.php';

if ($_SESSION['logged_user']->role == "Администратор") {
  $isAdmin = true;
  $users = R::findAll('users');

} else {
  $isAdmin = false;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_SESSION['logged_user'])) {
    $userId = $_SESSION['logged_user']['id'];

    echo "Идентификатор пользователя: $userId";
  } else {
    header("Location: login.php");
    exit();
  }
  header("Location: main_page.php");
  exit();
} if (!$_SESSION['logged_user']) {
  header("Location:http://three.js-dev2/login.php");
  exit();
}

if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

}
require_once 'php_date.php';
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>

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
<p class="text-justify category-p">Последние проекты </p>
<hr>
<div class="d-flex">
<?php
$projects = R::find('projects', 'preview_url != "-" AND preview_url != "" ORDER BY id DESC LIMIT 12');

// Генерация HTML для каждой модели
foreach ($projects as $model) {
  $substring = "three.js-dev/examples/";
  $fixurl = $model->preview_url;
  $id = $model->id;
  $megabytes = round($model->size/ 1024 / 1024, 2);
  $newUrl = str_replace($substring, "", $fixurl);
  $translatedDate = translateEnglishToRussianMonths($model->date);
  echo '<div class="card justify-content-end">';
  if ($_SESSION['logged_user']->role == "Администратор") {
    echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
          data-table-class="projects" 
          data-table-id="' . htmlspecialchars($model->id) . '" 
          data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
  }
  echo'<picture><img s src="' . $newUrl . '" alt="" loading="lazy"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
        <h3 class="card-title">' . htmlspecialchars($model->name) . '</h3>
        <h4 class="card-text" id="fileSize">Размер:' . htmlspecialchars($megabytes) . ' MB</h4>
        <form action="viewing_project.php" method="post">
        <div class="d-flex justify-content-start p-2">
        <input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($id) . '">
        <button type="submit" class="btn btn-primary" name="submit">🔍</button>
        </form>
        <form action="download.php" method="POST">
          <input type="hidden" name="project_id" value="'.htmlspecialchars($id).'">
          <button type="submit" class="btn btn-primary">
          <i class="fa fa-floppy-o" aria-hidden="true"></i> 
          </button>
        </form>
        <button type="button" class="btn btn-primary mt-2 openModal" data-table="projects" data-id="'.$model->id.'" data-secondClass="fa-archive" data-tagInput="projects" >
        <i class="fa fa-tags" aria-hidden="true"></i>
        </button>
        </div>
        </div>';
}
?>
</div>
<hr>
<p class="text-justify category-p">Последние модели </p>
<hr>
<div class="d-flex">
<?php
$models = R::find('models', 'content_types = "model" ORDER BY id DESC LIMIT 12');
foreach ($models as $model) {
  $substring = "three.js-dev/examples/";
  $fixurl = $model->preview;
  $idmodels = $model->id;
  $newUrl = str_replace($substring, "", $fixurl);
  $File = $model->path;
  $newUrlFile = str_replace($substring, "", $File);
  $filesize = $model->size;
  $filedate = $model->date;
  $fileformat = $model->format;
  $fileidproject = $model->id_project;
  $filename = $model->name;
  $idprojects=$model->id_project;
  $rand_modal=rand()+$idmodels;
  $translatedDate = translateEnglishToRussianMonths($filedate);
  $megabytes = round($filesize / 1024 / 1024, 2);
  echo'<div class="card justify-content-end ">';
  if ($_SESSION['logged_user']->role == "Администратор") {
    // Кнопка с передачей данных: CSRF токена и данных для удаления
    echo'<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
            data-table-class="models" 
            data-table-id="' . htmlspecialchars($model->id) . '" 
            data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
  }
  echo'<picture><img src="' . $newUrl . '"  alt="' .$fileTitle. '" loading="lazy"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
        <h3 class="card-title">'. pathinfo($filename, PATHINFO_FILENAME).'</h3>
        <h4 class="card-text" id="fileSize" >Размер:' . htmlspecialchars($megabytes) . ' MB</h4>
        <h4 class="card-text">Формат:' . htmlspecialchars($model->format) . '</h4>
        <form action="viewing_3d_model.php" method="post">
          <input type="hidden" name="model_path" value="' . htmlspecialchars($newUrlFile) . '">
          <input type="hidden" name="filesize" value="' .   htmlspecialchars($filesize)  . '">
          <input type="hidden" name="fileformat" value="' . htmlspecialchars($fileformat)  . '">
          <input type="hidden" name="filedate" value="' . htmlspecialchars($filedate)  . '">
          <input type="hidden" name="fileproject" value="' . htmlspecialchars($fileidproject)  . '">
          <input type="hidden" name="filename" value="' . htmlspecialchars($filename)  . '">
          <input type="hidden" name="id" value="' . htmlspecialchars($idmodels)  . '">
          <div class="d-flex  justify-content-start">
          <button type="submit" class="btn btn-primary" name="submit">🔍</button>
        </form>
        <form action="download.php" method="POST">
          <input type="hidden" name="filesFromCardsDoc" value="'.htmlspecialchars($model->path).'">
          <button type="submit" class="btn btn-primary">
          <i class="fa fa-floppy-o" aria-hidden="true"></i> 
          </button>
        </form>
        <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($idprojects) . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
        <button type="button" class="btn btn-primary mt-2 openModal" data-table="models" data-id="'.$idmodels.'" data-secondClass="fa-cube" data-tagInput="models" >
        <i class="fa fa-tags" aria-hidden="true"></i>
        </button>
        </div>
        </div>';
}
?>
</div>
<hr>
<p class="text-justify category-p">Последние видео </p>
<hr>
<div class="d-flex">
      <?php
      $videos = R::find('models', 'content_types = "video" ORDER BY id DESC LIMIT 12');
      foreach ($videos as $index => $video) {
        $playerFile =$video->path;
        $substring = "three.js-dev/examples/";
        $fixurl = $video->preview;
        $newUrl = str_replace($substring, "", $playerFile);
        $filenamevideo=$video->name;
        $playerId = 'player' . ($index + 1);
        $megabytes = round($video->size / 1024 / 1024, 2);
        $idprojects=$video->id_project;
        $translatedDate = translateEnglishToRussianMonths($video->date);
        echo '<div class="card align_player">
        <picture><div id="' . $playerId . '" style="border-radius: 12px;"></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
        <h3 class="card-title">'.htmlspecialchars(pathinfo($filenamevideo, PATHINFO_FILENAME)).'</h3>';
        if ($_SESSION['logged_user']->role == "Администратор") {
          echo'<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                  data-table-class="video" 
                  data-table-id="' . htmlspecialchars($video->id) . '" 
                  data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
        }
        echo '<h4 class="card-text" id="fileSize" >Размер:' . htmlspecialchars($megabytes) . ' MB</h4>
              <h4 class="card-text">Формат:' . htmlspecialchars($video->format) . '</h4>
              <script>
              var ' . $playerId . ' = new Playerjs({id: "' . $playerId . '", file: "' . htmlspecialchars($newUrl) . '", player:2});
              document.getElementById("' . $playerId . '").style.borderRadius = "12px";
              document.getElementById("' . $playerId . '").style.marginTop = "15px";
              document.getElementById("' . $playerId . '").style.marginBottom = "15px";
              document.getElementById("' . $playerId . '").classList.add("custom-picture");
              </script>
              <div class="d-flex">
              <form action="download.php" method="POST">
                <input type="hidden" name="filesFromCardsDoc" value="'.htmlspecialchars($video->path).'">
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> 
                </button>
              </form>
                <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($idprojects) . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
              <button type="button" class="btn btn-primary mt-2 openModal" data-table="video" data-id="'.$video->id.'" data-secondClass="fa-video-camera" data-tagInput="video" >
              <i class="fa fa-tags" aria-hidden="true"></i>
              </button>
              </div>
              </div>';
      }
      ?>
</div>
<hr>
<p class="text-justify category-p">Последние аудио </p>
<hr>
<div class="d-flex">
<?
$audio = R::find('models', 'content_types = "audio" ORDER BY id DESC LIMIT 12');
foreach ($audio as $index => $model) {
    $playerFile = $model->path;
    $substring = "three.js-dev/examples/";
    $fixurl = $model->preview;
    $newUrl = str_replace($substring, "", $playerFile);
    $filenameaudio=$model->name;
    $playerId = 'player' . ($index + 1);
    $megabytes = round($model->size / 1024 / 1024, 2);
    $idprojects=$model->id_project;
    $translatedDate = translateEnglishToRussianMonths($model->date);
    echo '<div class="card align_player" ><picture style="--bs: -8px;"><div id="' . $playerId . '" ></div><figcaption>' . $translatedDate . '</figcaption></picture>';
    if ($_SESSION['logged_user']->role == "Администратор") {
      echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
            data-table-class="audio" 
            data-table-id="' . htmlspecialchars($model->id) . '" 
            data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
    }
    echo '<h3 class="card-title">'.pathinfo($filenameaudio, PATHINFO_FILENAME).'</h3>
          <h4 class="card-text" id="fileSize" >Размер:' . $megabytes . ' MB</h4>
          <h4 class="card-text">Формат:'.htmlspecialchars($model->format) .'</h4>
          <script>
          var ' . $playerId . ' = new Playerjs({id: "' . $playerId . '", file: "' . htmlspecialchars($newUrl) . '"});
          document.getElementById("' . $playerId . '").style.marginTop = "15px";
          document.getElementById("' . $playerId . '").style.marginBottom = "15px";
          document.getElementById("' . $playerId . '").classList.add("custom-picture2");
          </script>
          <div class="d-flex ">
          <form action="download.php" method="POST">
            <input type="hidden" name="filesFromCardsDoc" value="'.htmlspecialchars($model->path).'">
            <button type="submit" class="btn btn-primary">
            <i class="fa fa-floppy-o" aria-hidden="true"></i> 
            </button>
          </form>
          <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . $idprojects . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
            <button type="button" class="btn btn-primary mt-2 openModal" data-table="audio" data-id="'.$model->id.'" data-secondClass="fa-music" data-tagInput="audio" >
            <i class="fa fa-tags" aria-hidden="true"></i>
          </button>
          </div>
          </div>';
}
?>
    </div>
    <hr>
    <p class="text-justify category-p">Последние Материалы </p>
    <hr>
    <div class="d-flex">
      <?php
      $materials = R::find('models', 'content_types = "material" ORDER BY id DESC LIMIT 8');
      foreach ($materials as $material) {
        $substring = "three.js-dev/examples/";
        $fixurl = $material->preview;
        $newUrl = str_replace($substring, "", $fixurl);
        $megabytes = round($material->size / 1024 / 1024, 2);
        $idprojects= $material->id_project;
        $translatedDate = translateEnglishToRussianMonths($material->date);
        echo'<div class="card justify-content-end ">';
        if ($_SESSION['logged_user']->role == "Администратор") {
          echo'<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
                data-table-class="material" 
                data-table-id="' . htmlspecialchars($material->id) . '" 
                data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
      }
        echo '<picture><img src="' . htmlspecialchars($newUrl) . '" class="" alt="' . htmlspecialchars($material->name) . '" loading="lazy" style="padding:32px;"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>
              <h3 class="card-text">'.htmlspecialchars($material->name).'</h3>
              <h4 class="card-text" id="fileSize">Размер:' . htmlspecialchars($megabytes) . ' MB</h4>
              <div class="d-flex justify-content-start">
              <form action="download.php" method="POST">
                <input type="hidden" name="filesFromCardsMaterial" value="'.htmlspecialchars($material->path).'">
                <button type="submit" class="btn btn-primary">
                <i class="fa fa-floppy-o" aria-hidden="true"></i> 
              </button>
              </form>
                <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . $idprojects . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
              <button type="button" class="btn btn-primary mt-2 openModal" data-table="material" data-id="'.$material->id.'" data-secondClass="fa-paint-brush" data-tagInput="material" >
              <i class="fa fa-tags" aria-hidden="true"></i>
              </button>
              </div>
              </div>';
      }
?>
</div>
<hr>
<p class="text-justify category-p">Последние Тектсуры </p>
<hr>
<div class="d-flex">
<?php
$textures = R::findAll('models', 'content_types = "texture" ORDER BY id DESC LIMIT 8');
foreach ($textures as $texture) {
$folderPath = $texture->path; 
$images = glob($folderPath . '/*.jpg'); 
$images = array_merge($images, glob($folderPath . '/*.jpeg')); 
$images = array_merge($images, glob($folderPath . '/*.png')); 
$megabytes = round($texture->size / 1024 / 1024, 2);
$idprojects=$texture->id_project;
$format=$texture->format;
$translatedDate = translateEnglishToRussianMonths($texture->date);
echo '<div class="card justify-content-end">';
if ($_SESSION['logged_user']->role == "Администратор") {
  echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
      data-table-class="textures" 
      data-table-id="' . htmlspecialchars($texture->id) . '" 
      data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
}
echo '<picture class="collage-container" style="padding: 14px;margin-top: 15px;margin-bottom: 25px;"><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption>
      <div class="custom-picture" style="z-index:1; padding: 31px;">';
$imageCount = count($images);
if ($imageCount >= 4) {
  // Верхние два изображения
  echo '<div class="d-flex">';
  for ($i = 0; $i < 2; $i++) {
  echo '<img src="' . htmlspecialchars($images[$i]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84"  >';
  }
  echo '</div>';

  // Нижние два изображения
  echo '<div class="d-flex">';
  for ($i = 2; $i < 4; $i++) {
  echo '<img src="' . htmlspecialchars($images[$i]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84" >';
  }
  echo '</div>';
} elseif ($imageCount == 3) {
  // Для 3 изображений
  echo '<div class="d-flex">';
  for ($i = 0; $i < 3; $i++) {
  echo '<img src="' . htmlspecialchars($images[$i]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84" >';
  }
  echo '</div>';
} elseif ($imageCount == 2) {
  // Для 2 изображений
  echo '<div class="d-flex">';
  for ($i = 0; $i < 2; $i++) {
  echo '<img src="' . htmlspecialchars($images[$i]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84" >';
  }
  echo '</div>';

} elseif ($imageCount == 1) {
  // Для 1 изображения
  echo '<div class="d-flex justify-content-center">
        <img src="' . htmlspecialchars($images[0]) . '" class="img-thumbnail m-2" alt="Изображение" width="84" height="84" >
        </div>';
}
echo '<i class="fa fa-search-plus position-absolute"  data-toggle="modal" data-target="#modal-' . $texture->id . '"></i>
      </div>
      </picture>
      <h3 class="card-title">' . htmlspecialchars($texture->name) . '</h3>
      <h4 class="card-text" id="fileSize">Размер: ' . $megabytes . ' MB</h4>
      <h4 class="card-text" id="fileSize">Размер: ' . htmlspecialchars($format) . '</h4>
      <div class="d-flex justify-content-start">
      <form action="download.php" method="POST">
        <input type="hidden" name="filesFromCardsTexture" value="'.htmlspecialchars($texture->path).'">
        <button type="submit" class="btn btn-primary">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> 
        </button>
      </form>
        <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . $idprojects . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
      <button type="button" class="btn btn-primary mt-2 openModal" data-table="texture" data-id="'.$texture->id.'" data-secondClass="fa-object-group" data-tagInput="texture" >
      <i class="fa fa-tags" aria-hidden="true"></i>
      </button>
      </div>
      </div>
      <div class="modal fade" id="modal-' . $texture->id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLongTitle">' . htmlspecialchars($texture->name) . '</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span style="color:red;" aria-hidden="true">&times;</span>
      </button>
      </div>
      <div class="modal-body">';

// Выводим все изображения из папки
foreach ($images as $image) {
  echo '<img src="' . htmlspecialchars($image) . '" class="img-thumbnail m-2" alt="Изображение">
        <div class="text-center">
        <a href="' . htmlspecialchars($image) . '" download>
        <i class="fa fa-arrow-down">Скачать</i>
        </a>
        </div>';
}
echo '</div> 
      </div>
      </div>
      </div>'; 
}?>
</div>
<hr>
<p class="text-justify category-p">Последние Изображения </p>
<hr>
<div class="d-flex">
<?php
$imageList = R::find('models', 'content_types = "image" ORDER BY id DESC LIMIT 8');
foreach ($imageList as $image) {
  if (strpos(htmlspecialchars($image->path), "three.js-dev/examples/") !== false) {
    $newUrl = str_replace("three.js-dev/examples/", "", htmlspecialchars($image->preview));
  } else {
    $newUrl = htmlspecialchars($image->path);
  }
  $megabytes = round(htmlspecialchars($image->size) / 1024 / 1024, 2);
  $idprojects=htmlspecialchars($image->id_project);
  $translatedDate = translateEnglishToRussianMonths($image->date);
  echo '<div class="card"><div class="shadow">';
  if ($_SESSION['logged_user']->role == "Администратор") {
    echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
            data-table-class="image" 
            data-table-id="' . htmlspecialchars($image->id) . '" 
            data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
  }
  echo '<picture><img src="' . htmlspecialchars($newUrl) . '" class="" alt="' . htmlspecialchars($image->name) . '" loading="lazy"><figcaption>' . $translatedDate . '</figcaption></picture>
        <div class="card-body">
        <h3 class="card-title">'.htmlspecialchars(pathinfo($image->name, PATHINFO_FILENAME)).'</h3>
        <h4 class="card-text">Размер:' . $megabytes . ' MB</h4>
        <h4 class="card-text">Формат:' . htmlspecialchars($image->format) . '</h4>
        <p class="card-text">'.htmlspecialchars($image->description).'</p>
        </div><div class="d-flex justify-content-start">
        <form action="download.php" method="POST">
          <input type="hidden" name="filesFromCardsImage" value="'.htmlspecialchars($image->path).'">
          <button type="submit" class="btn btn-primary">
          <i class="fa fa-floppy-o" aria-hidden="true"></i> 
          </button>
        </form>
          <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($idprojects) . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
        <button type="button" class="btn btn-primary mt-2 openModal" data-table="image" data-id="'.$image->id.'" data-secondClass="fa-picture-o" data-tagInput="image" ><i class="fa fa-tags" aria-hidden="true"></i></button>
        </div>
        </div></div>';
}
?>

</div>
<hr>
<p class="text-justify category-p">Последние Документы </p>
<hr>
<div class="d-flex">
<?php 
$documents = R::find('models', 'content_types = "document"  ORDER BY id DESC LIMIT 8');
foreach ($documents as $document) {
  $substring = "three.js-dev/examples/";
  $fixurl = htmlspecialchars($document->path);
  $newUrl = str_replace($substring, "", $fixurl);
  $megabytes = round(htmlspecialchars($document->size) / 1024 / 1024, 2);
  $idprojects = htmlspecialchars($document->id_project);
  $translatedDate = translateEnglishToRussianMonths($document->date);
  if (strpos($document->path, $substring) !== 0) {
    $newUrl = "error.png";
  }
  if (!file_exists($newUrl) || filesize($newUrl) <= 0) {
    $newUrl = "error.png";
  }
  echo '<div class="card">';
  if ($_SESSION['logged_user']->role == "Администратор") {
    echo '<button class="btn btn-danger delete-btn position-absolute top-0 end-0 m-2" 
          data-table-class="documents" 
          data-table-id="' . htmlspecialchars($document->id) . '" 
          data-csrf="' . $_SESSION['csrf_token'] . '">X</button>';
  }
  if ($newUrl == "error.png") {
    echo '<picture><div class="texture"><img src="' . $newUrl . '" alt="File not found" width="50" height="50"></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>';
  } else {
    $fileExtension = pathinfo($newUrl, PATHINFO_EXTENSION);
  if ($fileExtension == 'pdf') {
    echo '<picture><div class="texture"><canvas id="pdf-preview' . $document->id . '"></canvas></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>';
  } elseif ($fileExtension == 'doc' || $fileExtension == 'docx') {
    echo '<picture><div class="texture"><i class="fa fa-file-word-o" style="font-size: 50px; color: #007bff;"></i></div><figcaption>' . htmlspecialchars($translatedDate) . '</figcaption></picture>';
  } else {
    echo '<div class="texture"><img src="error.png" alt="File not supported" width="50" height="50"><p>Формат файла не поддерживается для превью</p></div>';
  }
  }
  echo '<div class="card-body ">
        <h5 class="card-title" align-items-center >'.htmlspecialchars($document->name).'</h5>
        <h5 class="card-text">Размер:' . $megabytes . ' MB</h5>
        <h5 class="card-text">Формат:' . htmlspecialchars($document->format) . '</h5>
        </div>
        <div class="d-flex justify-content-start "><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal' . htmlspecialchars($document->id) . '">🔍</button>
          <form action="viewing_project.php" method="post"><input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($idprojects) . '"><button type="submit" class="btn btn-primary" name="submit"><i class="fa fa-book" aria-hidden="true"></i></button></form>
        <form action="download.php" method="POST">
          <input type="hidden" name="filesFromCardsDoc" value="'.htmlspecialchars($document->path).'">
          <button type="submit" class="btn btn-primary">
          <i class="fa fa-floppy-o" aria-hidden="true"></i> 
          </button>
        </form>
        <button type="button" class="btn btn-primary mt-2 openModal" data-table="documents" data-id="'.$document->id.'" data-secondClass="fa-file" data-tagInput="documents" >
        <i class="fa fa-tags" aria-hidden="true"></i>
        </button>
        </div></div>

        <div class="modal fade" id="modal' . htmlspecialchars($document->id) . '" tabindex="-1" role="dialog" aria-labelledby="modalLabel' . htmlspecialchars($document->id) . '" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title text-break" id="modalLabel' . htmlspecialchars($document->id) . '">' . htmlspecialchars($document->name) . '</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span style="color:red;" aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body" style="height: 100vh; padding: 0;">'; 
  if ($fileExtension == 'pdf') {
    echo '<iframe src="' . htmlspecialchars($newUrl) . '" width="100%" height="100%" style="border: none;" ></iframe>';
  } elseif ($fileExtension == 'docx') {
    echo '<div id="docx-content' . htmlspecialchars($document->id) . '" class="docx-content"></div>';
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
        var canvas = document.getElementById("pdf-preview' . htmlspecialchars($document->id) . '");
        var cardBody = canvas.parentElement;
        canvas.setAttribute("aria-label", "PDF preview");
        var parentWidth = cardBody.offsetWidth;
        var parentHeight = cardBody.offsetHeight;
        var context = canvas.getContext("2d");
        var loadingTask = pdfjsLib.getDocument(url);
        const pdf = await loadingTask.promise;
        const page = await pdf.getPage(1);
        // Вычисляем смещения для выравнивания canvas по центру родителя
        var offsetX = (parentWidth - canvas.width) ;
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
    echo '<script>
          document.addEventListener("DOMContentLoaded", function () {
          var previewElement = document.getElementById("docx-content' . htmlspecialchars($document->id) . '");
          previewElement.style.backgroundColor = "black";
          previewElement.style.color = "white";
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
?>
</div>
</div>
</div>
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
<script src="js/main.js"></script>
</body>
<footer>
<img src="Gazprom-Logo-rus.svg.png" alt="Газпром" class="logo">
</footer>
</html>
