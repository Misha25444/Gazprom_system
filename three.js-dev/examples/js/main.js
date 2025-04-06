
document.addEventListener("DOMContentLoaded", function() {
$(document).ready(function(){
  $("#phone").inputmask("+7 (999) 999-99-99");
});
});
//Обновление данных пользователя
document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector('#profile-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault(); 
        const formData = new FormData(form); 
        try {
            const response = await fetch('update_profile_user.php', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Ошибка запроса: ' + response.status);
            }
            const responseData = await response.json();
            if (responseData.success) {
                alert('Успешно');
            } else {
                alert(responseData.message); 
            }
        } catch (error) {
            console.error('Произошла ошибка:', error);
            alert('Произошла ошибка при сохранении изменений.');
        }
    });
});


    document.addEventListener("DOMContentLoaded", function() {
    const tagInput = document.getElementById('tagInputSearch');
    const tagsDiv = document.getElementById('tags');
    if (tagInput && tagsDiv) { 
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
            console.log('Search query:', nonTagWords.join(' '));
        });
    } else {
        console.error("Элемент #tagInputSearch или #tags не найден!");
    }
});
// управление навигационным баром 
$(document).ready(function() {
    $('.list-group-item').click(function() {
        $(this).next('.sub-category').collapse('toggle');
    });
});
        function toggleMenu(menuNumber) {
          var menu = document.getElementById("menu" + menuNumber);
          var isMobile = window.matchMedia("(max-width: 768px), (max-height: 500px) and (orientation: landscape)").matches;
          if (menuNumber==10){
          var targetWidth = isMobile ? "100%" : "70%";}
          else{var targetWidth = isMobile ? "100%" : "30%";}
          if (menu.style.width === targetWidth) {
              menu.style.width = "0%";
              menu.style.fontSize = "0%";
          } else {
              closeAllMenus();
              menu.style.width = targetWidth;
              menu.style.fontSize = ""; 
          }
      }
    function closeMenu(menuNumber) {
      var menu = document.getElementById("menu" + menuNumber);
      menu.style.width = "0%";


    }
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.card-title').forEach(el => {
        if (el.scrollWidth > el.offsetWidth) {
            el.setAttribute('title', el.innerText.trim());
        }
    });
});

   
    $('.delete-btn').click(function() {
      if (!confirm('Вы уверены, что хотите удалить этот элемент?')) {
          return; 
      }
  
      var imageId = $(this).data('table-id');
      var classname = $(this).data('table-class');
      var csrfToken = $(this).data('csrf'); 
      $.ajax({
          type: 'POST',
          url: 'delete.php',
          data: {
              image_id: imageId,
              class_name: classname,
              csrf_token: csrfToken 
          },
          success: function(response) {
              console.log('Ответ сервера: ', response);
  

              try {
                  var result = JSON.parse(response); 
                  if (result.status === 'success') {
                      alert(result.message);
                      var scrollPos = window.scrollY;
                      location.reload();
                      window.scrollTo(0, scrollPos);
                  } else if (result.status === 'debug') {
                      alert('Отладочная информация: ' + result.message);
                      console.log('Сессионный токен: ' + result.session_token);
                      console.log('Полученный токен: ' + result.received_token);
                  } else {
                      alert(result.message); 
                  }
              } catch (e) {
                  console.error('Ошибка парсинга ответа: ', e);
                  alert('Произошла ошибка при обработке ответа сервера');
              }
          },
          error: function(xhr, status, error) {
              console.error('Ошибка: ', xhr.responseText);
              alert('Произошла ошибка при удалении элемента');
          }
      });
  });
  
    function closeAllMenus() {
      var menus = document.querySelectorAll('.menu');
      menus.forEach(function(menu) {
        menu.style.width = "0px";
      });
    }

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
  

  
    function changeButtonIcon(iconClass) {
      document.getElementById('selectedIcon').className = iconClass;
      var secondClassName = iconClass.split(' ')[1]; 
      document.getElementById('submitButton').setAttribute('data-second-class', secondClassName); 
    }

    document.addEventListener("DOMContentLoaded", function() {
        const submitButton = document.getElementById('submitButton');
        const tagInput = document.getElementById('tagInput');
        const tagsDiv = document.getElementById('tags');
        if (submitButton && tagInput && tagsDiv) {
            submitButton.addEventListener('click', function() {
                var tagInputValue = tagInput.value.trim();
                if (tagInputValue !== '') {
                    var tagDiv = document.createElement('div');
                    tagDiv.className = 'tag';
                    tagDiv.textContent = '#' + tagInputValue;
                    tagsDiv.appendChild(tagDiv);
                    tagInput.value = ''; 
                }
    
                var tagsContent = tagsDiv.innerHTML;
                var secondClass = submitButton.getAttribute('data-second-class');
                var formData = new FormData();
                formData.append('tagsContent', tagsContent);
                formData.append('secondClass', secondClass);
                fetch('search_result.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text()) 
                .then(data => console.log('Server Response:', data))
                .catch(error => console.error('Error:', error));
            });
        } else {
            console.error("Элемент #submitButton, #tagInput или #tags не найден!");
        }
    });
  
  
    function changeButtonIcon(iconClass) {
      document.getElementById('selectedIcon').className = iconClass;
      var secondClassName = iconClass.split(' ')[1]; 
      document.getElementById('secondClassInput').value = secondClassName; 
    }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.card-title').forEach(el => {
        if (el.scrollWidth > el.offsetWidth) {
            el.setAttribute('title', el.innerText.trim());
        }
    });
});
//секция получения добавления тегов удаления 
$(document).ready(function() {
  $(document).ready(function() {
    $(".openModal").click(function() {
        let modelId = $(this).data("id");
        let tableName = $(this).data("table");
        let tagContainer = $("#tagContainer");
        let secondClass = $(this).data("secondclass");  

        console.log("Model ID: " + modelId + ", Table Name: " + tableName); 

        $("#customTagModal").modal("show");
        tagContainer.html(""); 

        $.ajax({
            url: "get_tags.php",
            type: "POST",
            data: { model_id: modelId, table: tableName }, 
            dataType: "json",
            success: function(response) {
                console.log(response);  
                if (response.status === "success") {
                    response.tags.forEach(tag => {

                        let formElement = $("<form action='search_result.php' method='POST'>"
                            + "<span class='tag-card'>" + tag.name + 
                            " <span class='remove-tag' data-table= '"+tableName+"' data-id='" + tag.tag_link_id + "'>&times;</span>"
                            + "<input type='hidden' name='tagInput' value='#" + tag.name + "'>"
                            + "<input type='hidden' name='tag_type' value='" + tableName + "'>"
                            + "<input type='hidden' name='secondClass' value='" + secondClass + "'>"
                            + "</span>"
                            + "</form>");
                        tagContainer.append(formElement);  
                    });
                } else {
                    tagContainer.html("<p>Тегов нет</p>");
                }
            },
            error: function() {
                tagContainer.html("<p>Ошибка загрузки тегов</p>");
            }
        });
        $(".addTagButton").data("id", modelId);
        $(".addTagButton").data("table", tableName);
    });


    $(document).on("click", ".tag-card", function() {
        let formElement = $(this).closest("form");  
        formElement.submit(); 
    });



    $(".addTagButton").click(function() {
    let modelId = $(this).data("id");
    let tableName = $(this).data("table");
    let tagInput = $("#tagInput_card");
    let tagContainer = $("#tagContainer");
    let tagName = tagInput.val().trim();
    let secondClass = $(this).data("secondclass");  
    if (tagName === "") {
        alert("Введите тег!");
        return;
    }

    $.ajax({
        url: "add_tag.php",
        type: "POST",
        data: { tag: tagName, model_id: modelId, table: tableName },
        dataType: "json",
        success: function(response) {
    if (response.status === "success") {
        let newTag = $("<form action='search_result.php' method='POST'>"
            + "<span class='tag-card'>" + response.tag + 
            " <span class='remove-tag' data-table='" + tableName + "' data-id='" + response.tag_id + "'>&times;</span>"
            + "<input type='hidden' name='tagInput' value='#" + response.tag + "'>"
            + "<input type='hidden' name='tag_type' value='" + tableName + "'>"
            + "<input type='hidden' name='secondClass' value='" + response.icon + "'>" 
            + "</span>"
            + "</form>");

        tagContainer.append(newTag);
        tagInput.val(""); 
    } else {
        alert(response.message);
    }
},
        error: function() {
            alert("Ошибка при добавлении тега");
        }
    });
});

$(document).on("click", ".remove-tag", function(event) {
    event.preventDefault(); 
    event.stopPropagation(); 

    let tagId = $(this).data("id");
    let tagElement = $(this).closest(".tag-card");
    let tableName = $(this).data("table"); 
    
    $.ajax({
        url: "delete_tag.php",
        type: "POST",
        data: { id: tagId, table: tableName },
        dataType: "json",
        success: function(response) {
            if (response.success) {
                tagElement.fadeOut(300, function() { $(this).remove(); });
            } else {
              alert(response.message);
            }
        },
        error: function() {
            alert("Ошибка соединения");
        }
    });
});


$(document).on("click", ".tag-card", function(event) {
    if ($(event.target).hasClass("remove-tag")) {
        event.preventDefault();
        return;
    }
    $(this).closest("form").submit();
});

});
});
function confirmDelete() {
  return confirm('Вы уверены, что хотите удалить этого пользователя? Это действие необратимо.');
}