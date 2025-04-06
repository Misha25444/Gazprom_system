
<style>
#tags-suggestions{
position: absolute; 
top: 100%; 
border-radius: 1.5em;
display: flex
;
background:white;
max-height: 120px; 
overflow-y: auto;
width: 100%; 
z-index: 999; 

}

#tags-suggestions::-webkit-scrollbar-track {
background: transparent;
margin-bottom: 60px;
}

#tags-suggestions::-webkit-scrollbar {
height: 8px;  
}

#tags-suggestions::-webkit-scrollbar-thumb {
background: linear-gradient(to right, transparent 0%, #888 50%, transparent 100%);
border-radius: 4px;
}

#tags-suggestions::-webkit-scrollbar-track {
background: transparent; 
}

#tags-suggestions::-webkit-scrollbar-corner {
background: transparent;
}
.autocomplete-tags-suggestions{padding: 5px 10px;
margin: 5px;
font-size: 14px;
font-weight: 400;
color: #fff; 
background-color: #6c757d; 
border-radius: 1.5em; 
cursor: pointer;
transition: background-color 0.2s ease-in-out;
}

#tags-suggestionshover {
background: #f0f0f0;
}

.search-container {
position: relative; 
}
</style>
<form action="search_result.php" method="POST" onsubmit="prepareHashtags(8)">
  <input type="hidden" id="secondClassInput8" name="secondClass" value="fa-archive">
    <input type="hidden" name="tag_type" value="projects"> 
    <input type="hidden" name="tagInput" id="tagInput8">
    <div class="menu" id="menu8" onmouseleave="closeMenu(8)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">
            Категория projects
            </li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT hashtagfromheshtag, COUNT(hashtagfromheshtag) AS count
                        FROM hashtagtoproject
                        GROUP BY hashtagfromheshtag
                        ORDER BY count DESC
                        LIMIT 12";
                $rows = R::getAll($sql);
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $count = $row['count'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    $hashtagName = $hashtag->name;

                    echo '<li class="list-group-item-bar">
                          <input type="checkbox" name="selected_hashtags[]" id="checkboxPRO' . $hashtagId . '" value="' . $hashtagName . '"> 
                          <label for="checkboxPRO' . $hashtagId . '">' . $hashtagName . '</label>
                          </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>

  <form action="search_result.php" method="POST" onsubmit="prepareHashtags(1); return true;">
    <input type="hidden" name="tag_type" value="audio">
    <input type="hidden" name="tagInput" id="tagInput1">
    <input type="hidden" name="formatsInput" id="formatsInput1">
    <input type="hidden" id="secondClassInput1" name="secondClass" value="fa-music">
    <div class="menu" id="menu1" onmouseleave="closeMenu(1)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория audio</li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                FROM hashtagtomodels h
                JOIN models m ON h.typeid = m.id
                WHERE m.content_types = 'audio'
                GROUP BY h.hashtagfromheshtag 
                ORDER BY count DESC 
                LIMIT 12";
                $rows = R::getAll($sql);
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_hashtags[]" id="checkboxAUX' . $hashtagId . '" value="' . htmlspecialchars($hashtag->name) . '" >
                        <label for="checkboxAUX' . $hashtagId . '">' . htmlspecialchars($hashtag->name) . '</label>
                        </li>';
                }
                ?>
            </ul>
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory3">Форматы audio</li>
            <ul id="subCategory3" class="list-group sub-category collapse">
                <?php
                $formats = R::getCol("SELECT DISTINCT format FROM models WHERE format IS NOT NULL AND format != '' AND content_types = 'audio' ");
                foreach ($formats as $index => $format) {
                    $escapedFormat = htmlspecialchars($format);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_formats[]" id="checkboxaux' . $index . '" value="' . $escapedFormat . '">
                        <label for="checkboxaux' . $index . '">' . $escapedFormat . '</label>
                        </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>
<form action="search_result.php" method="POST" onsubmit="prepareHashtags(2); return true;">
    <input type="hidden" name="tag_type" value="image">
    <input type="hidden" name="tagInput" id="tagInput2">
    <input type="hidden" name="formatsInput" id="formatsInput2">
    <input type="hidden" id="secondClassInput2" name="secondClass" value="fa-picture-o">
    <div class="menu" id="menu2" onmouseleave="closeMenu(2)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория image</li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                FROM hashtagtomodels h
                JOIN models m ON h.typeid = m.id
                WHERE m.content_types = 'image'
                GROUP BY h.hashtagfromheshtag 
                ORDER BY count DESC 
                LIMIT 12";
                $rows = R::getAll($sql);
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_hashtags[]" id="checkboxIMG' . $hashtagId . '" value="' . htmlspecialchars($hashtag->name) . '" >
                        <label for="checkboxIMG' . $hashtagId . '">' . htmlspecialchars($hashtag->name) . '</label>
                        </li>';
                }
                ?>
            </ul>
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory3">Форматы image</li>
            <ul id="subCategory3" class="list-group sub-category collapse">
                <?php
                $formats = R::getCol("SELECT DISTINCT format FROM models WHERE format IS NOT NULL AND format != '' AND content_types = 'image' ");
                
                foreach ($formats as $index => $format) {
                    $escapedFormat = htmlspecialchars($format);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_formats[]" id="checkboximg' . $index . '" value="' . $escapedFormat . '">
                        <label for="checkboximg' . $index . '">' . $escapedFormat . '</label>
                        </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>
  <form action="search_result.php" method="POST" onsubmit="prepareHashtags(3); return true;">
    <input type="hidden" name="tag_type" value="video">
    <input type="hidden" name="tagInput" id="tagInput3">
    <input type="hidden" name="formatsInput" id="formatsInput3">
    <input type="hidden" id="secondClassInput3" name="secondClass" value="fa-video-camera">
    <div class="menu" id="menu3" onmouseleave="closeMenu(3)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория video</li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                FROM hashtagtomodels h
                JOIN models m ON h.typeid = m.id
                WHERE m.content_types = 'video'
                GROUP BY h.hashtagfromheshtag 
                ORDER BY count DESC 
                LIMIT 12";
                $rows = R::getAll($sql);
                
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_hashtags[]" id="checkboxVID' . $hashtagId . '" value="' . htmlspecialchars($hashtag->name) . '" >
                        <label for="checkboxVID' . $hashtagId . '">' . htmlspecialchars($hashtag->name) . '</label>
                        </li>';
                }
                ?>
            </ul>
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory3">Форматы video</li>
            <ul id="subCategory3" class="list-group sub-category collapse">
                <?php
                $formats = R::getCol("SELECT DISTINCT format FROM models WHERE format IS NOT NULL AND format != ''  AND content_types = 'video' ");
                
                foreach ($formats as $index => $format) {
                    $escapedFormat = htmlspecialchars($format);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_formats[]" id="checkboxvid' . $index . '" value="' . $escapedFormat . '">
                        <label for="checkboxvid' . $index . '">' . $escapedFormat . '</label>
                        </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>
  <form action="search_result.php" method="POST" onsubmit="prepareHashtags(4); return true;">
    <input type="hidden" name="tag_type" value="documents">
    <input type="hidden" name="tagInput" id="tagInput4">
    <input type="hidden" name="formatsInput" id="formatsInput4">
    <input type="hidden" id="secondClassInput4" name="secondClass" value="fa-file">
    <div class="menu" id="menu4" onmouseleave="closeMenu(4)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория doc</li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                FROM hashtagtomodels h
                JOIN models m ON h.typeid = m.id
                WHERE m.content_types = 'document'
                GROUP BY h.hashtagfromheshtag 
                ORDER BY count DESC 
                LIMIT 12";
                $rows = R::getAll($sql);

                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_hashtags[]" id="checkboxDOC' . $hashtagId . '" value="' . htmlspecialchars($hashtag->name) . '" >
                        <label for="checkboxDOC' . $hashtagId . '">' . htmlspecialchars($hashtag->name) . '</label>
                        </li>';
                }
                ?>
            </ul>
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory3">Форматы doc</li>
            <ul id="subCategory3" class="list-group sub-category collapse">
                <?php
                $formats = R::getCol("SELECT DISTINCT format FROM models WHERE format IS NOT NULL AND format != '' AND content_types = 'document'");
                
                foreach ($formats as $index => $format) {
                    $escapedFormat = htmlspecialchars($format);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_formats[]" id="checkboxdoc' . $index . '" value="' . $escapedFormat . '">
                        <label for="checkboxdoc' . $index . '">' . $escapedFormat . '</label>
                        </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>
  <form action="search_result.php" method="POST" onsubmit="prepareHashtags(5); return true;">
    <input type="hidden" name="tag_type" value="models">
    <input type="hidden" name="tagInput" id="tagInput5">
    <input type="hidden" name="formatsInput" id="formatsInput5">
    <input type="hidden" id="secondClassInput5" name="secondClass" value="fa-cube">
    <div class="menu" id="menu5" onmouseleave="closeMenu(5)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория моделей</li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                FROM hashtagtomodels h
                JOIN models m ON h.typeid = m.id
                WHERE m.content_types = 'model'
                GROUP BY h.hashtagfromheshtag 
                ORDER BY count DESC 
                LIMIT 12";

                $rows = R::getAll($sql);
                
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_hashtags[]" id="checkbox3d' . $hashtagId . '" value="' . htmlspecialchars($hashtag->name) . '" >
                        <label for="checkbox3d' . $hashtagId . '">' . htmlspecialchars($hashtag->name) . '</label>
                        </li>';
                }
                ?>
            </ul>
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory3">Форматы моделей</li>
            <ul id="subCategory3" class="list-group sub-category collapse">
                <?php
                $formats = R::getCol("SELECT DISTINCT format FROM models WHERE format IS NOT NULL AND format != ''  AND content_types = 'model' ");
                
                foreach ($formats as $index => $format) {
                    $escapedFormat = htmlspecialchars($format);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_formats[]" id="checkbox3D' . $index . '" value="' . $escapedFormat . '">
                        <label for="checkbox3D' . $index . '">' . $escapedFormat . '</label>
                        </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>

<form action="search_result.php" method="POST" onsubmit="prepareHashtags(9); return true;">
    <input type="hidden" name="tag_type" value="texture">
    <input type="hidden" name="tagInput" id="tagInput9">
    <input type="hidden" name="formatsInput" id="formatsInput9">
    <input type="hidden" id="secondClassInput9" name="secondClass" value="fa-object-group">
    <div class="menu" id="menu9" onmouseleave="closeMenu(9)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория текстур</li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                FROM hashtagtomodels h
                JOIN models m ON h.typeid = m.id
                WHERE m.content_types = 'texture'
                GROUP BY h.hashtagfromheshtag 
                ORDER BY count DESC 
                LIMIT 12";
                $rows = R::getAll($sql);
                
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_hashtags[]" id="checkboxTEX' . $hashtagId . '" value="' . htmlspecialchars($hashtag->name) . '" >
                        <label for="checkboxTEX' . $hashtagId . '">' . htmlspecialchars($hashtag->name) . '</label>
                        </li>';
                }
                ?>
            </ul>
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory3">Форматы текстур</li>
            <ul id="subCategory3" class="list-group sub-category collapse">
                <?php
                $formats = R::getCol("SELECT DISTINCT format FROM models WHERE format IS NOT NULL AND format != ''  AND content_types = 'texture' ");
                
                foreach ($formats as $index => $format) {
                    $escapedFormat = htmlspecialchars($format);
                    echo '<li class="list-group-item-bar">
                        <input type="checkbox" name="selected_formats[]" id="checkboxtex' . $index . '" value="' . $escapedFormat . '">
                        <label for="checkboxtex' . $index . '">' . $escapedFormat . '</label>
                        </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>

<script>
function prepareHashtags(tag_name) {
    let selectedHashtags = Array.from(document.querySelectorAll('input[name="selected_hashtags[]"]:checked'))
        .map(checkbox => "#" + checkbox.value)
        .join(' ');
    document.getElementById('tagInput'+tag_name).value = selectedHashtags;


    let selectedFormats = Array.from(document.querySelectorAll('input[name="selected_formats[]"]:checked'))
        .map(checkbox => checkbox.value)
        .join(', ');
    document.getElementById('formatsInput'+tag_name).value = selectedFormats;

    console.log("Отправка формы:", { tagInput: selectedHashtags, formatsInput: selectedFormats });
}
</script>

<form action="search_result.php" method="POST" onsubmit="prepareHashtags(6)">
  <input type="hidden" id="secondClassInput6" name="secondClass" value="fa-paint-brush">
    <input type="hidden" name="tag_type" value="material">
    <input type="hidden" name="tagInput" id="tagInput6">
    <div class="menu" id="menu6" onmouseleave="closeMenu(6)">
        <ul id="categoryList" class="list-group">
            <li class="list-group-item-bar" data-toggle="collapse" data-target="#subCategory1">Категория Материалов
            </li>
            <ul id="subCategory1" class="list-group sub-category collapse">
                <?php
                 $sql = "SELECT h.hashtagfromheshtag, COUNT(h.hashtagfromheshtag) AS count 
                 FROM hashtagtomodels h
                 JOIN models m ON h.typeid = m.id
                 WHERE m.content_types = 'material'
                 GROUP BY h.hashtagfromheshtag 
                 ORDER BY count DESC 
                 LIMIT 12";
                $rows = R::getAll($sql);
                foreach ($rows as $row) {
                    $hashtagId = $row['hashtagfromheshtag'];
                    $count = $row['count'];
                    $hashtag = R::load('hashtags', $hashtagId);
                    $hashtagName = $hashtag->name;
                    echo '<li class="list-group-item-bar">
                          <input type="checkbox" name="selected_hashtags[]" id="checkboxM' . $hashtagId . '" value="' . $hashtagName . '"> 
                          <label for="checkboxM' . $hashtagId . '">' . $hashtagName . '</label>
                          </li>';
                }
                ?>
            </ul>
            <button type="submit" class="btn btn-primary">Найти</button>
        </ul>
    </div>
</form>
  
<div class="menu" id="menu10" onmouseleave="closeMenu(10)">
    <?  if ($_SESSION['logged_user']->role == "Администратор")  {
            include 'chat_for_admin.php';}
        else {
            include 'chat.php';}
    ?>

</div>

<div class="sidebar">
  <div class="top-icons">
  <div class="icon">
  <a href="main_page.php" class="top-icon-logo"><img src="4.png" alt="" width="25px" height="45px"></a>
  </div>
  <hr class="separator">
  <div class="icon"><i class="fa fa-archive align-middle" id="archive" aria-hidden="true" onclick="toggleMenu(8)"></i></div>
  <div class="icon"><i class="fa fa-music align-middle" id="music" aria-hidden="true" onclick="toggleMenu(1)"></i></div>
  <div class="icon"><i class="fa fa-picture-o align-middle" id="pic" aria-hidden="true" onclick="toggleMenu(2)"></i></div>
  <div class="icon"><i class="fa fa-video-camera align-middle" id="video" aria-hidden="true" onclick="toggleMenu(3)"></i></div>
  <div class="icon"><i class="fa fa-file align-middle" id="file" aria-hidden="true" onclick="toggleMenu(4)"></i></div>
  <div class="icon"><i class="fa fa-cube align-middle" id="cube" aria-hidden="true" onclick="toggleMenu(5)"></i></div>
  <div class="icon"><i class="fa fa-paint-brush align-middle" id="brush" aria-hidden="true" onclick="toggleMenu(6)"></i></div>
  <div class="icon"><i class="fa fa-object-group align-middle" id="object" aria-hidden="true" onclick="toggleMenu(9)"></i></div>
  <hr class="separator">
  <div class="icon"><i class="fa fa-weixin" aria-hidden="true" onclick="toggleMenu(10)"></i></div>
  <hr class="separator">
<?php 
  if ($_SESSION['logged_user']->role == "Администратор") {
    echo'<div class="icon">
        <a href="add_project.php"><i class="fa fa-plus-square" aria-hidden="true"></i></a> </div>
        <hr class="separator">';
    }; 
?>
  <div class="icon"><i class="fa fa-user-circle" aria-hidden="true" onclick="toggleMenu(7)"></i></div>
</div> 
</div> 
  <div class="content">
    <div class="container-fluid">
      <div class="container mt-3">
        <form action="search_result.php" method="POST" id="searchForm">
          <div class="search-container">
            <input type="text" class="form-control search-input" name="tagInput" id="tagInputSearch" placeholder="Введите тег или поисковый запрос">
            <div id="tags-suggestions"></div>
            <div class="dropdown">
            <button class="btn bg-light text-dark dropdown-toggle my-2 my-sm-0" type="button" style="border-radius: 0;" data-second-class="fa-archive align-middle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-archive align-middle" id="selectedIcon" style="font-size: 14px; margin-top:-3px;" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-archive align-middle')">Проекты</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-cube align-middle')">3д модели</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-paint-brush align-middle')">Материалы</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-video-camera align-middle')">Видео</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-picture-o align-middle')">Изображения</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-music align-middle')">Аудио</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-file align-middle')">Документ</p>
                <p class="dropdown-item" onclick="changeButtonIcon('fa fa-object-group align-middle')">Текстуры</p>
              </div>
            </div>
            <button class="btn bg-light text-dark my-2 my-sm-0" type="submit" id="submitButton">Найти</button>
          </div>
          <div class="tags" id="tags"></div>
          <input type="hidden" id="secondClassInput" name="secondClass" value="fa-archive">
          <div class="search-container">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="sorting">Сортировка по:</label>
                  <select class="form-control" id="sorting" name="sorting">
                    <option value="none">Выберите</option>
                    <option value="type">По типу</option>
                    <option value="name_asc">По алфавиту</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="sizes">Все размеры:</label>
                  <select class="form-control" id="sizes" name="sizes">
                    <option value="none">Выберите</option>
                    <option value="largest-to-smallest">От наибольшего к наименьшему</option>
                    <option value="smallest-to-largest">От наименьшего к наибольшему</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="date">Дата:</label>
                  <select class="form-control" id="date" name="date">
                    <option value="none">Выберите</option>
                    <option value="today">Сегодня</option>
                    <option value="yesterday">Вчера</option>
                    <option value="last-three-days">Последние три дня</option>
                    <option value="last-week">Последнюю неделю</option>
                    <option value="month">Месяц</option>
                    <option value="half-year">Полгода</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="unique-carousel-container">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
    <?php
    $projects = R::find('projects', 'preview_url != "-" AND preview_url != "" ORDER BY id DESC LIMIT 3');
    $active = true;
    foreach ($projects as $index => $project) {
        $imageUrl = $project->preview_url;
        $substring = "three.js-dev/examples/";
        $newUrl = str_replace($substring, "", $imageUrl);
        if ($imageUrl == "-" || empty($imageUrl)) {
            continue;
        }
        $activeClass = ($active) ? 'active' : '';
        $active = false;
        echo '<div class="carousel-item unique-carousel-item ' . $activeClass . '">
            <div class="unique-carousel-image-container">
            <form action="viewing_project.php" method="post">
            <input type="hidden" name="Allfilesprojects" value="' . htmlspecialchars($project->id) . '">
            <button type="submit" style="border: none; background: none; padding: 0;">
            <img class="unique-carousel-image-container" src="' . $newUrl . '" alt="Слайд ' . ($index + 1) . '" loading="lazy">
            <input type="hidden" name="submit" value="1">
            </button>
            </form>
            </div>
            </div>';
}
?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
<script>
const input = document.getElementById("tagInputSearch");
const suggestionsBox = document.getElementById("tags-suggestions");

input.addEventListener("input", function() {
    const currentWord = input.value.split(" ").pop().replace("#", "").trim(); // Убираем #, чтобы правильно искать
    suggestionsBox.innerHTML = "";

    if (currentWord.length > 0) {
        fetch(`adding_tags.php?query=${currentWord}`)
            .then(response => response.json())
            .then(tags => {
                const filtered = tags.filter(tag => tag.toLowerCase().startsWith(currentWord.toLowerCase()));
                filtered.forEach(tag => {
                    const div = document.createElement("div");
                    div.classList.add("autocomplete-tags-suggestions");
                    div.textContent = tag;
                    div.addEventListener("click", function() {
                        const parts = input.value.split(" ");
                        parts.pop(); 
                        parts.push("#" + tag); 
                        input.value = parts.join(" ") + ""; 
                        suggestionsBox.innerHTML = "";
                        input.focus();
                    });
                    suggestionsBox.appendChild(div);
                    
                });
            })
            .catch(error => console.error('Ошибка при получении тегов', error));
    }
    
});

document.addEventListener("DOMContentLoaded", function () {
    const tagInput = document.getElementById("tagInput_card");
    const suggestionsBox = document.getElementById("tagSuggestions");
    if (tagInput && suggestionsBox) {
        tagInput.addEventListener("input", function () {
            const inputValue = tagInput.value.trim();
            suggestionsBox.innerHTML = "";
            if (inputValue.length > 0) {
                fetch(`adding_tags.php?query=${inputValue}`)
                    .then(response => response.json())
                    .then(tags => {
                        if (tags.includes(inputValue)) {
                            suggestionsBox.style.display = "none"; 
                            return;
                        }
                        tags.forEach(tag => {
                            const div = document.createElement("div");
                            div.classList.add("autocomplete-tags-suggestions");
                            div.textContent = tag;
                            div.addEventListener("click", function () {
                                tagInput.value = tag; 
                                suggestionsBox.innerHTML = "";
                                suggestionsBox.style.display = "none";
                                tagInput.focus();
                            });
                            suggestionsBox.appendChild(div);
                        });
                        suggestionsBox.style.display = tags.length > 0 ? "block" : "none";
                    })
                    .catch(error => console.error("Ошибка загрузки тегов:", error));
            } else {
                suggestionsBox.style.display = "none";
            }
        });

        document.addEventListener("click", function (event) {
            if (!tagInput.contains(event.target) && !suggestionsBox.contains(event.target)) {
                suggestionsBox.style.display = "none";
            }
        });
    } else {
        console.warn("Элементы  не найдены");
    }
});

document.addEventListener("click", function(e) {
    if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
        suggestionsBox.innerHTML = "";

    }
});
</script>
<? include_once ('table_all_users.php'); ?>