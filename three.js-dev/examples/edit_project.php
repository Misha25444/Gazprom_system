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
  } else {
    header("Location: login.php");
    exit();
  }
  if (isset($_POST['id'])) {
    $idr = $_POST['id'];
    global $idr;
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
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf_viewer.min.css">
<link type="text/css" rel="stylesheet" href="main.css">
<link rel="stylesheet" href="css/style.css">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/compressorjs@1.1.4/dist/compressor.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfobject/2.1.1/pdfobject.min.js"></script>
<script type="text/javascript" src="./css3d_periodictable_files/jquery.min.js.Без названия"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script type="text/javascript" src="jsm/playerjs_for_v_and_a.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>


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
}

.loader {
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
  function clearFileInput(button) {
    const inputGroup = button.parentNode.parentNode;
    const fileInput = inputGroup.querySelector('.custom-file-input');
    const label = inputGroup.querySelector('.custom-file-label');
    fileInput.value = '';  
    label.innerHTML = 'Выберите файл';  
}
</script>
<title>Изменить проект</title>
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
      <p class="text-justify category-p">Изменить проект: </p>
      <hr>
      <div class="d-flex" style="margin-top: -50px ;padding: auto;">
        <div class="container mt-5">
        <form id="uploadForm" method="post" enctype="multipart/form-data" style="margin-bottom: 120px;">
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
                  <input type="file" class="custom-file-input file_input" id="FilePrewiew" name="FilePrewiew" onchange="validateFile(this,  ['png', 'jpg']); updateLabel(this)"  >
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
                  <input type="file" class="custom-file-input file_input" id="archiveFile" name="FileProject" onchange="validateFile(this, ['rar', 'zip']); updateLabel(this)">
                  <label class="custom-file-label" for="archiveFile"> <i class="fa fa-upload"></i> Загрузить файл</label>
                </div>
                <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="clearFileInput(this)"><i class="fa fa-times"></i></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="tagInput" >Введите теги:</label>
              <div class="tag-input " id="tagInput" >
                <input type="text" class="form-control" id="tagInputField" style="min-width: 100%;">
                <div id="suggestions" class="suggestions-list" style="display:none;"></div>
              </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $idr; ?>">
            <button class="btn btn-primary mt-3"  id="uploadButton">Изменить</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>

    $(document).ready(function() {
    function addTag(tagText) {
        const tag = $('<div class="tag"><span class="tag-text">' + tagText + '</span><span class="tag-close">x</span></div>');
        $('.tag-input').append(tag);
    }

    $('.tag-input').on('click', '.tag-close', function() {
        $(this).parent().remove();
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
    
    $(document).click(function(e) {
        if (!$(e.target).closest('.tag-input-container, #suggestions').length) {
            $('#suggestions').hide();
        }
    });
});

$(document).ready(function() {
    $('.tag-input').on('click', '.tag-close', function() {
      $(this).parent().remove();
    });

    $('#tagInputField').on('keypress', function(e) {
      if (e.which === 13) {
        e.preventDefault();
        var tagText = $(this).val();
        if (tagText) {
          var tag = $('<div class="tag"><span class="tag-text">' + tagText + '</span><span class="tag-close" style="color: #ff0000;">x</span></div>');
          $('.tag-input').append(tag);
          $(this).val('');
        }
      }
    });
  });

       $('.list-group-item').click(function() {
      $(this).next('.sub-category').collapse('toggle');
    });

  </script>
     <script src="js/main.js"></script>
</body>
<footer>
<img src="Gazprom-Logo-rus.svg.png" alt="Газпром" class="logo">
</footer>
<script>
  function updateLabel(input) {
    const fileName = input.files[0].name;
    const label = input.nextElementSibling;
    label.innerHTML = fileName;
  }

  function validateFile(input, validTypes) {
      const fileName = input.value;
      const fileType = fileName.substring(fileName.lastIndexOf('.') + 1).toLowerCase();
      if (!validTypes.includes(fileType)) {
        alert('Недопустимый тип файла. Выберите файл с правильным расширением: ' + validTypes.join(', '));
        input.value = '';
      }
    }

  document.getElementById('uploadForm').addEventListener('submit', async function(event) {
          event.preventDefault();
          const formData = new FormData(this);
          const tagElements = document.getElementsByClassName("tag-text");
          const tags = Array.from(tagElements, tag => tag.textContent);
          formData.append('tags', JSON.stringify(tags));
          try {
              const response = await fetch('editing_project.php', {
                  method: 'POST',
                  body: formData,
              });
            
              alert('Данные отправлены!');
          } catch (error) {
              console.error('Ошибка:', error);
          }
      });


 
    $('.list-group-item').click(function() {
      $(this).next('.sub-category').collapse('toggle');
    });

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
          console.error('Ошибка:', error);
        });
    });
  </script>

</html>
