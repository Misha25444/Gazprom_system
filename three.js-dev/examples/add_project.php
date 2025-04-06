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

} if (!$_SESSION['logged_user']) {
    header("Location:http://three.js-dev2/login.php");
  exit();
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
<style>
   .form-group .input-group {
    display: flex; 
  }
  .input-group-append {
    margin: -4px;
 

    margin-left: -1px;
}

 
    .loader-container {
    z-index: 150;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8); 
    backdrop-filter: blur(5px); 
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.loader {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    width: 80px; 
}

.dot {
    width: 10px;
    height: 10px;
    border-radius: 50%; 
    background-color: #3498db; 
    animation: bounce 0.6s infinite alternate; 
}
.tag-text{color:white;}
.dot:nth-child(2) {
    animation-delay: 0.2s;
}

.dot:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes bounce {
    0% { transform: translateY(0); }
    100% { transform: translateY(-20px); }
}

.material-box {
border: 1px solid #ccc;
padding: 10px;
border-radius: 5px;
text-align: center;
position: relative;
}
.upload-box {

height: 33px;

display: flex;
align-items: center;
justify-content: center;
cursor: pointer;
}

.upload-icon {
font-size: 30px;
color: #555;
position: absolute;
}
.upload-box input {

width: 100%;
height: 100%;
opacity: 0;
cursor: pointer;
}

.preview {
margin-right: auto;
margin-left: auto;
margin-top: 5px;
width: 100px;
height: 100px;
object-fit: cover;
}
.remove-material-btn {
margin-top: 10px;
width: 100%;
}
.custom-file-label {
color:white;
background-color:rgba(255, 255, 255, 0); 
}
.file_input{
color:white;
background-color:rgba(255, 255, 255, 0);
}
.custom-file-input:lang(ru)~.custom-file-label::after {
content: "Загрузить ";
display: none;
}

.model-upload {
text-align: center;
padding: 20px;
}

.model-upload .drop-zone {
border: 2px dashed rgb(255, 255, 255);
border-radius: 8px;
padding: 40px;

cursor: pointer;

}

.model-upload .drop-zone:hover,
.model-upload .drop-zone.dragover {

border-color:rgb(255, 255, 255);
}

.model-upload .drop-zone i {
font-size: 50px;
margin-bottom: 10px;
color:rgb(197, 197, 197);
}

.model-upload input[type="file"] {
display: none;
}

.model-upload .file-list {
margin-top: 15px;
list-style: none;
padding: 0;
}

.model-upload .file-list li {

padding: 8px 12px;
border-radius: 5px;
display: flex;
align-items: center;
justify-content: space-between;
margin-bottom: 5px;
font-size: 14px;
}

.model-upload .file-list li .file-remove {
cursor: pointer;
color: red;
}
        
</style>

<script>
  function previewImage(input) {
    const file = input.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = input.closest('.material-box').querySelector('.preview');
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  }

  function addMaterialInput() {
    const container = document.querySelector('.material-images-container');
    const materialGroup = document.querySelector('.material-group').cloneNode(true);
    container.appendChild(materialGroup);
  }

  function removeMaterialGroup(button) {
    const materialsContainer = document.querySelector('.material-images-container');
    if (materialsContainer.children.length > 1) {
      button.closest('.material-group').remove();
    } else {
      button.closest('.material-group').querySelectorAll('input[type=file]').forEach(input => input.value = '');
      button.closest('.material-group').querySelectorAll('.preview').forEach(img => {
        img.src = '';
        img.style.display = 'none';
      });
    }
  }
</script>
<title>Добавить проект</title>
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
      <p class="text-justify category-p">Добавить проект: </p>
      <hr>
      <div class="d-flex" style="margin-top: -50px ;padding: auto;">
        <div class="container mt-5">
        <form id="uploadForm" method="post" enctype="multipart/form-data" onkeydown="return event.key !== 'Enter';">
            <div class="form-group">
              <label for="TitleProject">Загаловок:</label>
              <input type="text" class="form-control file_input" id="TitleProject" name="TitleProject" placeholder="Введите загаловок:">
            </div>
            <div class="form-group">
              <label for="DescriptionProject">Описание :</label>
              <textarea class="form-control file_input" id="DescriptionProject" name="DescriptionProject" rows="3" placeholder="Введите описание:"></textarea>
            </div>
            <div class="form-group">
              <label>Загрузите Превью:</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input file_input" id="FileProject" name="FileProject" onchange="validateFile(this,  ['png', 'jpg']); updateLabel(this)" >
                  <label class="custom-file-label" for="FileProject"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label>Загрузите архив:</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input file_input" id="archiveFile" name="archiveFile" onchange="validateFile(this, ['rar', 'zip']); updateLabel(this)">
                  <label class="custom-file-label" for="archiveFile"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>
            <div class="container mt-5">
            <div class="model-upload">
                <label for="modelUpload" for="modelInput">Загрузите 3D-модели:</label>
                <div class="drop-zone" id="dropZone">
                    <i class="fa fa-cubes"></i>
                    <p>Перетащите сюда файлы или <span style="color: #007bff; text-decoration: underline;">выберите файлы</span></p>
                    <input type="file" id="modelInput" class="custom-file-input file_input" name="modelUpload[]" accept=".obj,.fbx,.stl,.glb,.gltf" multiple>
                </div>
                <ul class="file-list" id="fileList"></ul>
            </div>
            </div>
            <div class="form-group" id="materials-container">
              <label>Material Images:</label>
              <div class="material-images-container">
                <div class="material-group ">
                <div class="col-md-4 d-flex align-items-stretch ml-auto mb-1">
              <div class="d-flex w-100 " style="flex-wrap: nowrap;">
              <input type="text" class="form-control file_input" id="title" name="NameMaterial[]" placeholder="Название">
              <span class="btn-danger btn-sm d-flex align-items-center justify-content-center" 
                  onclick="removeMaterialGroup(this)" style="width: 40px;">
                <i class="fa fa-trash"></i>
            </span>
                </div>
                </div>
                  <div class="row material-images-row" style="padding: 2px;">
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Diffuse
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material1[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div></label>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Roughness 
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material2[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div></label>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Normal
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material3[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div></label>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>AO
                        <div class="upload-box">
                          <input type="file" class="file_input" class="fa fa-upload upload-icon" name="material4[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div></label>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Displacemen
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material5[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div></label>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Metalness </label>
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material6[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Specular</label>
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material7[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Emissive</label>
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material8[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Environment </label>
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material9[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                    <div class="col-md-3 mb-1" style="text-align: center;">
                      <div class="material-box">
                        <label>Bump </label>
                        <div class="upload-box">
                          <input type="file" class="file_input" name="material10[]" onchange="previewImage(this)">
                          <i class="fa fa-upload upload-icon"></i>
                        </div>
                        <img class="preview" style="display:none;" alt="Preview">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <button class="btn btn-outline-secondary" type="button" onclick="addMaterialInput()">+</button>
            </div>
            <div class="form-group">
              <label>Загрузите Image:</label>
              <div class="input-group">
                <div class="custom-file">
                <input type="file" class="custom-file-input file_input" id="imageFile" name="imageFile" onchange="validateFile(this, ['png', 'jpg','hdr','exr']); updateLabel(this)" multiple>
                 <label class="custom-file-label" for="imageFile"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="addFileInput('image', this)">+</button>
                  <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Загрузите аудио:</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input file_input" id="audioFile" name="audioFile" onchange="validateFile(this, ['mp3', 'ogg', 'wav']); updateLabel(this)">
                  <label class="custom-file-label" for="audioFile"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="addFileInput('audio', this)">+</button>
                  <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>
      
            <div class="form-group">
              <label>Загрузите Видео:</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input file_input" id="videoFile" name="videoFile" onchange="validateFile(this, ['mp4', 'avi']); updateLabel(this)">
                  <label class="custom-file-label" for="videoFile"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="addFileInput('video', this)">+</button>
                  <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>
      
            <div class="form-group">
              <label>Загрузите Текструры:</label>
              <div class="input-group">
              <div class="custom-file">
                <input type="file" class="custom-file-input file_input" id="textureFile" name="textureFile" onchange="validateFile(this, ['rar', 'zip']); updateLabel(this)">
                <label class="custom-file-label upload-label" for="textureFile">
                <i class="fa fa-upload"></i> Загрузить файл
                </label>
                </div>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="addFileInput('texture', this)">+</button>
                  <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div> 

            <div class="form-group">
              <label>Загрузите документ:</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" class="custom-file-input file_input" id="documentFile" name="documentFile" onchange="validateFile(this, ['doc', 'pdf','docx']); updateLabel(this)">
                  <label class="custom-file-label" for="documentFile"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="addFileInput('document', this)">+</button>
                  <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>
            <? echo '<input type="hidden" name="userId" value="'. $userId.'">'; ?>
            <div class="form-group">
              <label for="tagInput" >Введите теги:</label>
              <div class="tag-input " id="tagInput" >
                <input type="text" class="form-control" id="tagInputField" style="min-width: 100%;">
                <div id="suggestions" class="suggestions-list" style="display:none;"></div>
              </div>
            </div>
            <button class="btn btn-primary mt-3"  id="uploadButton">Загрузить и сделать скриншоты</button>
          </form>
        </div>
      </div>
    </div>
    </div>
  </div>
  </div>
  
  <div class="loader-container" style="display: none;">
    <p style="color:#3498db;font-size: 34px;">Подождите <br></p>
    <div class="loader">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>
</div>
<script>




$(document).ready(function() {
    
    function addTag(tagText) {
        if (!tagText.trim()) return; 
        const tag = $('<div class="tag"><span class="tag-text">' + tagText + '</span><span class="tag-close" style="color: #ff0000;"> x</span></div>');
        $('.tag-input').append(tag);
    }

    $('.tag-input').on('click', '.tag-close', function() {
        $(this).parent().remove();
    });

    $('#tagInputField').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const tagText = $(this).val().trim();
            if (tagText) {
                addTag(tagText);
                $(this).val(''); 
            }
        }
    });

    $('#tagInputField').on('input', function() {
        const query = $(this).val().trim();
        if (query.length >= 2) {
            $.getJSON("search_tags.php?q=" + encodeURIComponent(query), function(data) {
                const suggestionsDiv = $('#suggestions');
                suggestionsDiv.empty();

                if (data.length > 0) {
                    data.forEach(tag => {
                        const suggestion = $('<a href="#" class="list-group-item list-group-item-action">' + tag.name + '</a>');
                        suggestion.on('click', function(e) {
                            e.preventDefault();
                            addTag(tag.name);
                            $('#tagInputField').val('');
                            suggestionsDiv.hide();
                        });
                        suggestionsDiv.append(suggestion);
                    });
                    suggestionsDiv.show();
                } else {
                    suggestionsDiv.hide();
                }
            });
        } else {
            $('#suggestions').hide();
        }
    });


    $(document).on('click', function(e) {
        if (!$(e.target).closest('.tag-input-container, #suggestions').length) {
            $('#suggestions').hide();
        }
    });
});


$('.list-group-item').click(function() {
$(this).next('.sub-category').collapse('toggle');
});
    
function toggleSublist(id) {
  var sublist = document.getElementById(id);
  if (sublist.style.display === "block") {
    sublist.style.display = "none";
    
  } else {
    sublist.style.display = "block";
  }
}
function changeButtonIcon(iconClass) {
    var selectedIcon = document.getElementById('selectedIcon');
    selectedIcon.className = iconClass + " align-middle";
}
//Подключение внешнего Js 
</script>
<script src="js/main.js"></script>
</body>
<footer>
<img src="Gazprom-Logo-rus.svg.png" alt="Газпром" class="logo">
</footer>
<script>
  function addFileInput(type, button) {
    const inputGroup = document.createElement('div');
    inputGroup.className = 'input-group mt-2';
    inputGroup.innerHTML = `
        <div class="custom-file">
            <input type="file" class="custom-file-input file_input" id="${type}File" name="${type}File" onchange="validateFile(this, getValidFileTypes('${type}')); updateLabel(this)">
            <label class="custom-file-label" for="${type}File">Выберите файл</label>
        </div>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="addFileInput('${type}', this)">+</button>
            <button class="btn btn-outline-danger" type="button" onclick="removeFileInput(this)"><i class="fa fa-trash"></i></button>
            <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
        </div>
    `;
    button.parentNode.parentNode.insertBefore(inputGroup, button.parentNode.nextSibling);
}

function removeFileInput(button) {
    const inputGroup = button.parentNode.parentNode;
    inputGroup.parentNode.removeChild(inputGroup);
}

function updateLabel(input) {
    const fileName = input.files.length > 0 ? input.files[0].name : 'Выберите файл';
    const label = input.nextElementSibling;
    label.innerHTML = fileName;
}

function clearFileInput(button) {
    const inputGroup = button.parentNode.parentNode;
    const fileInput = inputGroup.querySelector('.custom-file-input');
    const label = inputGroup.querySelector('.custom-file-label');
    fileInput.value = '';  
    label.innerHTML = 'Выберите файл';  
}


  function validateFile(input, validTypes) {
      const fileName = input.value;
      const fileType = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
      if (!validTypes.includes(fileType)) {
        alert('Недопустимый тип файла. Выберите файл с правильным расширением: ' + validTypes.join(', '));
        input.value = '';
      }
    }

  function getValidFileTypes(type) {
    console.log(type);
    switch (type) {
      case 'image':
        return ['png', 'jpg','hdr','HDR','exr'];
      case 'texture':
        return ['rar', 'zip'];
      case 'preview':
        return ['png', 'jpg'];
      case 'archive':
        return ['rar', 'zip'];
      case 'model':
        return ['gltf', 'glb', 'fbx', 'obj','zip'];
      case 'audio':
        return ['mp3', 'ogg', 'wav'];
      case 'video':
        return ['mp4', 'avi'];
      case 'document':
        return ['doc', 'pdf','docx'];
      default:
        return [];
    }
  }
</script>


<script type="importmap">
{
  "imports": {
    "three": "../build/three.module.js",
    "three/addons/": "./jsm/"
  }
}
</script>
<script type="module">
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { FBXLoader } from 'three/addons/loaders/FBXLoader.js';
import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
import { STLLoader } from 'three/addons/loaders/STLLoader.js';

function addMaterialInput() {
    const container = document.querySelector('.material-images-container');
    const newRow = container.firstElementChild.cloneNode(true);
    newRow.querySelectorAll('input').forEach(input => {
        if (input.type === 'file') {
            input.value = ''; 
        }
    });
    container.appendChild(newRow);
}


document.getElementById('uploadForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    const formData = new FormData();
    const allInputs = document.querySelectorAll('.file_input, .text_input' );
    const modelFiles = document.getElementById('modelInput').files;
    const tagElements = document.getElementsByClassName("tag-text");
    const title = document.getElementById('TitleProject').value.trim();
    const description = document.getElementById('DescriptionProject').value.trim();
    const previewFile = document.getElementById('FileProject').files.length > 0;
    
    if (!title || !description || !previewFile ) {
        alert('Пожалуйста, заполните все обязательные поля:\n- Заголовок\n- Описание\n- Превью\n-');
        return; 
    }

    
    allInputs.forEach(input => {
        if (input.type === 'file') {
            Array.from(input.files).forEach(file => {
                if (!Array.from(modelFiles).includes(file)) { 
                    formData.append(`${input.name}[]`, file, file.name);
                }
            });
        } else {
             formData.append(input.name, input.value); 
        }
    });
    const tags = Array.from(tagElements, tag => tag.textContent);
    formData.append('tags', JSON.stringify(tags));
    const screenshotsPromises = Array.from(modelFiles).map((file, index) => createModelScreenshot(file, index));
    const screenshotsFiles = await Promise.all(screenshotsPromises);
    const materialRows = document.querySelectorAll('.material-images-row');
    const textureImagesPromises = [];
    materialRows.forEach((row, rowIndex) => {
        if (row.querySelector('input[name="material1[]"]')?.files[0]){
        const textureFiles = {
          diffuse: row.querySelector('input[name="material1[]"]')?.files[0],
          roughness: row.querySelector('input[name="material2[]"]')?.files[0],
          normal: row.querySelector('input[name="material3[]"]')?.files[0],
          ao: row.querySelector('input[name="material4[]"]')?.files[0],
          displacement: row.querySelector('input[name="material5[]"]')?.files[0],
          metalness: row.querySelector('input[name="material6[]"]')?.files[0],
          specular: row.querySelector('input[name="material7[]"]')?.files[0],
          emissive: row.querySelector('input[name="material8[]"]')?.files[0],
          environment: row.querySelector('input[name="material9[]"]')?.files[0],
          bump: row.querySelector('input[name="material10[]"]')?.files[0],
        };
        console.log(textureFiles);
        textureImagesPromises.push(createSphereImage(textureFiles, rowIndex));
      }
      
    });
    materialRows.forEach((row, rowIndex) => {
        row.querySelectorAll('input[type="file"]').forEach((input, inputIndex) => {
            const files = input.files;
            for (let i = 0; i < files.length; i++) {
                
                formData.append(`textures[row${rowIndex}_input${inputIndex}]`, files[i]);
            }
        });
    });
 
    const textureImages = await Promise.all(textureImagesPromises);


    textureImages.forEach((imageFile, index) => {
        if (imageFile) {
            formData.append(`sphereImages[${index}]`, imageFile, imageFile.name);
            
        }
    });

    screenshotsFiles.forEach((screenshotFile, index) => {
        if (screenshotFile) {
            formData.append(`screenshots[${index}]`, screenshotFile, screenshotFile.name);
            formData.append(`models[${index}]`, modelFiles[index], modelFiles[index].name);
        }
    });
    formData.append('userId', <?php echo $userId; ?>);
    document.querySelector('.loader-container').style.display = 'flex';
    try {
                const response = await fetch('adding_project.php', {
                    method: 'POST',
                    body: formData,
                });

                if (response.ok) {
                    alert('Данные отправлены!');
                    location.reload();
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                } else {
                    alert('Ошибка отправки данных!');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Произошла ошибка при отправке данных.');
            } finally {
                document.querySelector('.loader-container').style.display = 'none';
            }
});


async function createSphereImage(textureFiles, rowIndex) {
    return new Promise(async (resolve, reject) => {
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(45, 800 / 800, 0.1, 1000);
        camera.position.set(0, 0, 3);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(1024, 1024);
        const geometry = new THREE.SphereGeometry(1, 128, 128);  
        const material = new THREE.MeshPhongMaterial();
        const textureLoader = new THREE.TextureLoader();
        if (textureFiles.diffuse) {
            material.map = await loadTexture(textureLoader, textureFiles.diffuse);
        }
        if (textureFiles.roughness) {
            material.roughnessMap = await loadTexture(textureLoader, textureFiles.roughness);
        }
        if (textureFiles.normal) {
            material.normalMap = await loadTexture(textureLoader, textureFiles.normal);
        }
        if (textureFiles.ao) {
            material.aoMap = await loadTexture(textureLoader, textureFiles.ao);
        }
        if (textureFiles.displacement) {
            material.displacementMap = await loadTexture(textureLoader, textureFiles.displacement);
            material.displacementScale = 0.2; 
            material.displacementBias = -0.1; 
        }
        if (textureFiles.metalness) {
            material.metalnessMap = await loadTexture(textureLoader, textureFiles.metalness);
            material.metalness = 1.0; 
        }
        if (textureFiles.specular) {
          try {
                material.specularMap = await loadTexture(textureLoader, textureFiles.specular);
                console.log("specularMap загружен!", material.specularMap);
            } catch (error) {
                console.error("Ошибка загрузки specularMap:", error);
            }
        }
        if (textureFiles.emissive) {
            material.emissiveMap = await loadTexture(textureLoader, textureFiles.emissive);
            material.emissive.set(0xffffff); 
        }
        if (textureFiles.environment) {
            material.envMap = await loadTexture(textureLoader, textureFiles.environment);
            material.envMapIntensity = 1.0; 
        }
        if (textureFiles.bump) {
            material.bumpMap = await loadTexture(textureLoader, textureFiles.bump);
            material.bumpScale = 0.05; 
        }
        const sphere = new THREE.Mesh(geometry, material);
        scene.add(sphere);
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
        scene.add(ambientLight);
        const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);
        renderer.render(scene, camera);
        renderer.domElement.toBlob(blob => {
            const file = new File([blob], `sphere_${rowIndex}.png`, { type: 'image/png' });
            resolve(file);
        }, 'image/png');
    });
}

function loadTexture(loader, file) {
    return new Promise((resolve, reject) => {
        const url = URL.createObjectURL(file);
        loader.load(url, texture => {
            URL.revokeObjectURL(url); 
            resolve(texture);
        }, undefined, reject);
    });
}

function setupScene() {
    const scene = new THREE.Scene();
    scene.add(new THREE.AmbientLight(0xffffff, 0.7)); 
    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
    directionalLight.position.set(0, 1, 1);
    scene.add(directionalLight);
    return scene;
}

function chooseLoader(extension) {
    switch (extension) {
        case 'glb':
        case 'gltf':
            return new GLTFLoader();
        case 'fbx':
            return new FBXLoader();
        case 'obj':
            return new OBJLoader();
        case'stl':
        return  new STLLoader();
        default:
            return null; 
    }
}

function prepareModel(loadedObject, extension) {
    let model;
    if (['gltf', 'glb'].includes(extension) && loadedObject.scene !== undefined) {
        model = loadedObject.scene;
    } 
    else if (['fbx', 'obj'].includes(extension)) {
        model = loadedObject;
        model.traverse((child) => {
            if (child.isMesh) {
                child.material = new THREE.MeshStandardMaterial({
                    color: 0x555555,
                    metalness: 0.5,
                    roughness: 0.5,
                    side: THREE.DoubleSide 
                });
            }
        });
    } 
    else if (extension === 'stl') {
    model = new THREE.Mesh(
        loadedObject,
        new THREE.MeshStandardMaterial({
            color: 0xff0000,
            metalness: 0.5,
            roughness: 0.5,
            side: THREE.DoubleSide
        })
    );
}
    return model;
}

function dataURLtoFile(dataurl, filename) {
    let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new File([u8arr], filename, {type:mime});
}


async function createModelScreenshot(modelFile, index) {
  let url = URL.createObjectURL(modelFile);
  let extension = modelFile.name.split('.').pop().toLowerCase();
    if (extension === 'zip') {
        const zipData = await modelFile.arrayBuffer();
        const zip = await JSZip.loadAsync(zipData);

        const fileUrls = {};
        await Promise.all(Object.keys(zip.files).map(async (filename) => {
            const file = zip.files[filename];
            if (!file.dir) {
                const blob = await file.async('blob');
                fileUrls[filename] = URL.createObjectURL(blob);
            }
        }));
        const gltfFileName = Object.keys(fileUrls).find(name => name.endsWith('.gltf'));
        if (!gltfFileName) {
            console.error(' GLTF не найден в  ZIP архиве');
            return null;
        }
        url = fileUrls[gltfFileName]; 
        extension = 'gltf';
        const loadingManager = new THREE.LoadingManager();
        loadingManager.setURLModifier((path) => {
            const parsedUrl = new URL(path);
            const relativeUrl = parsedUrl.pathname.replace(parsedUrl.origin + '/', "");
            return fileUrls[relativeUrl] || path;
        });
        const scene = setupScene();
        const aspectRatio = 1;
        const camera = new THREE.PerspectiveCamera(75, aspectRatio, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ alpha: true });
        renderer.setSize(1024, 1024);
        const loader = new GLTFLoader(loadingManager);
        return new Promise((resolve, reject) => {
            loader.load(url, function(gltf) {
                const model = gltf.scene;
                scene.add(model);
                const boundingBox = new THREE.Box3().setFromObject(model);
            const size = boundingBox.getSize(new THREE.Vector3());
            const center = boundingBox.getCenter(new THREE.Vector3());
            let maxWidth = Math.max(size.x, size.z); 
            let cameraOffset = maxWidth * 1.2;


            if (size.x >= size.z) {
                camera.position.set(center.x, center.y, center.z + cameraOffset); 
            } else {
                camera.position.set(center.x + cameraOffset, center.y, center.z); 
            }
            camera.lookAt(center);
            camera.far = cameraOffset * 3;
            camera.updateProjectionMatrix();

                setTimeout(() => {
                    renderer.render(scene, camera);
                    const screenshotDataUrl = renderer.domElement.toDataURL('image/png');
                    const screenshotFile = dataURLtoFile(screenshotDataUrl, `screenshot_${index}.png`);

                    renderer.domElement.remove();
                    resolve(screenshotFile);

                }, 500);
            }, undefined, function(error) {
                console.error('Ошибка загрузки модели:', error);
                reject(error);
            });
        });
    }
    else {
    const scene = setupScene();
    const aspectRatio = 1; 
    const camera = new THREE.PerspectiveCamera(75, aspectRatio, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(1024, 1024); 

    const loader = chooseLoader(extension);
    if (!loader) {
        console.error('Неподдерживаемый файл:', extension);
        URL.revokeObjectURL(url);
        return null;
    }
    
    return new Promise((resolve, reject) => {
     
        loader.load(url, function(loadedObject) {
            const model = prepareModel(loadedObject, extension);
            scene.add(model);
            const boundingBox = new THREE.Box3().setFromObject(model);
            const size = boundingBox.getSize(new THREE.Vector3());
            const center = boundingBox.getCenter(new THREE.Vector3());
            let maxWidth = Math.max(size.x, size.z); // Сравниваем ширину (X и Z)
            let cameraOffset = maxWidth * 1.2;
            if (size.x >= size.z) {
                camera.position.set(center.x, center.y, center.z + cameraOffset); // Спереди (по оси Z)
            } else {
                camera.position.set(center.x + cameraOffset, center.y, center.z); // Сбоку (по оси X)
            }
            camera.lookAt(center);
            camera.far = cameraOffset * 3;
            camera.updateProjectionMatrix();
            setTimeout(() => {
                renderer.render(scene, camera);
                const screenshotDataUrl = renderer.domElement.toDataURL('image/png');
                const screenshotFile = dataURLtoFile(screenshotDataUrl, `screenshot_${index}.png`);
                renderer.domElement.remove();
                URL.revokeObjectURL(url);
                resolve(screenshotFile);
            }, 500);
        
          }, undefined, function(error) {
            console.error('Error loading model:', error);
            URL.revokeObjectURL(url);
            reject(error);
        });
        
    });

    }
}
</script>
<script>

   
tagInput.addEventListener('input', function() {
  const words = tagInput.value.split(' ');
  const tagWords = words.filter(word => word.startsWith('#'));
  const nonTagWords = words.filter(word => !word.startsWith('#'));
  tagsDiv.innerHTML = '';
  tagWords.forEach(tagWord => {
    const tag = document.createElement('div');
    tag.className = 'tag';
    tag.textContent = tagWord;
    tagsDiv.appendChild(tag);
  });
});

$('.list-group-item').click(function() {
  $(this).next('.sub-category').collapse('toggle');
});
function changeButtonIcon(iconClass) {
  var selectedIcon = document.getElementById('selectedIcon');
  selectedIcon.className = iconClass + " align-middle";
}

function changeButtonIcon(iconClass) {
  document.getElementById('selectedIcon').className = iconClass;
  var secondClassName = iconClass.split(' ')[1]; 
  document.getElementById('submitButton').setAttribute('data-second-class', secondClassName); 
}

document.getElementById('submitButton').addEventListener('click', function() {
  var tagInputValue = document.getElementById('tagInput').value.trim();
  var tagDiv = document.createElement('div');
  tagDiv.className = 'tag';
  if (tagInputValue !== '') {
    tagDiv.textContent = '#' + tagInputValue;
    document.getElementById('tags').appendChild(tagDiv);
  }
  var tagsContent = document.getElementById('tags').innerHTML;
  var secondClass = document.getElementById('submitButton').getAttribute('data-second-class'); 
  var formData = new FormData();
  formData.append('tagsContent', tagsContent);
  formData.append('secondClass', secondClass); 
  fetch('search_result.php', {
      method: 'POST',
      body: formData
    })
    .then(response => {
      console.log(response);
    })
    .catch(error => {
      console.error('Error:', error);
    });
});

function changeButtonIcon(iconClass) {
  document.getElementById('selectedIcon').className = iconClass;
  var secondClassName = iconClass.split(' ')[1]; 
  document.getElementById('secondClassInput').value = secondClassName; 
}

function previewImage(input) {
  const file = input.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = input.closest('.material-box').querySelector('.preview');
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
}

function addMaterialInput() {
  const container = document.querySelector('.material-images-container');
  const materialGroup = document.querySelector('.material-group').cloneNode(true);
  container.appendChild(materialGroup);
}

function removeMaterialGroup(button) {
  const materialsContainer = document.querySelector('.material-images-container');
  if (materialsContainer.children.length > 1) {
    button.closest('.material-group').remove();
  } else {
    button.closest('.material-group').querySelectorAll('input[type=file]').forEach(input => input.value = '');
    button.closest('.material-group').querySelectorAll('.preview').forEach(img => {
      img.src = '';
      img.style.display = 'none';
    });
  }
}

document.addEventListener("DOMContentLoaded", function() {
    let modelInput = document.getElementById('modelInput');
    if (modelInput) {
        modelInput.addEventListener('change', function() {
            let input = this;
            let label = input.nextElementSibling;
            let files = input.files;
            if (label) {
                label.innerText = 'Выбрано файлов: ' + files.length;
            }
        });
    }
});

    const dropZone = document.getElementById("dropZone");
    const fileInput = document.getElementById("modelInput");
    const fileList = document.getElementById("fileList");
    let fileArray = [];
    dropZone.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", (event) => handleFiles(event.target.files));
    dropZone.addEventListener("dragover", (e) => {
        e.preventDefault();
        dropZone.classList.add("dragover");
    });
    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("dragover");
    });
    dropZone.addEventListener("drop", (e) => {
        e.preventDefault();
        dropZone.classList.remove("dragover");
        handleFiles(e.dataTransfer.files);
    });
    function handleFiles(files) {
        for (let file of files) {
            if ([".obj", ".fbx", ".stl", ".glb", ".gltf"].some(ext => file.name.endsWith(ext))) {
                fileArray.push(file);
                addFileToList(file);
            }
        }
    }
    function addFileToList(file) {
        const listItem = document.createElement("li");
        listItem.innerHTML = `
            <span>${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
            <i class="fa fa-times file-remove"></i>
        `;
        listItem.querySelector(".file-remove").addEventListener("click", () => {
            removeFile(file);
            listItem.remove();
        });
        fileList.appendChild(listItem);
    }
    function removeFile(fileToRemove) {
        fileArray = fileArray.filter(file => file !== fileToRemove);
        const dataTransfer = new DataTransfer();
        fileArray.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }
</script>
</html>
