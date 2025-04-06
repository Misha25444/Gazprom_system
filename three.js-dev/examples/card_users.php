<link rel="stylesheet" href="css/card-user.css">
<?php
    $userId = $_SESSION['logged_user']['id'];
    $user = R::load('users', $userId);
    $login = $user->login;
    $email = $user->email;
    $phone = $user->phone;
    $fio = $user->fio;
    $colorusers = $_SESSION['logged_user']->role == "Администратор" ? "red" : "blue";
    $imageusers = $_SESSION['logged_user']->role == "Администратор" ? "icone-utilisateur.png" : "icone-utilisateur.png";
    echo '<div class="profile-wrapper">
    <div class="profile-header">
    <img src="'.$imageusers.'" alt="User" class="profile-avatar" style="border: 3px '.$colorusers.' ; background :'.$colorusers.';">
    <h2 class="profile-name">'.$fio.'</h2>
    <div class="settings-form">
    <h3 class="form-title">Профиль</h3>

    <form id="profile-form" method="post" action="update_profile_user.php">
    <div class="form-group">
    <label for="login">ФИО:</label>
    <input type="text" class="form-control-users" id="fio" name="fio" value="' . $fio . '" required>
    </div>
    <div class="form-group">
    <label for="login">Логин:</label>
    <input type="text" class="form-control-users" id="login" name="login" value="' . $login . '" required>
    </div>
    <div class="form-group">
    <label for="email">Email:</label>
    <input type="email" class="form-control-users" id="email" name="email" value="' . $email . '" required>
    </div>
    <div class="form-group">
    <label for="phone">Номер телефона:</label>
    <input type="tel" class="form-control-users" id="phone" name="phone" value="' . $phone . '" placeholder="+7 (___) ___-__-__" required>
    </div>
    <div class="form-group">
    <label for="old_password">Старый пароль:</label>
    <input type="password" class="form-control-users" id="old_password" name="old_password" required>
    </div>
    <div class="form-group">
    <label for="password2">Новый пароль:</label>
    <input type="password2" class="form-control-users" id="password" name="password">
    </div>
    <input type="hidden" name="userId" value="'. $userId.'">
    <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
    </div>
    <div class="admin-btn-container">
    <form action="logout.php" method="post">
    <button type="submit" class="btn btn-logout"><i class="fa fa-power-off" aria-hidden="true"></i></button>
    </form>
    </div>';
    if ($_SESSION['logged_user']->role == "Администратор") {
        echo'<div class="admin-btn-container">
            <button type="button" class="btn btn-admin" data-toggle="modal" data-target="#userListModal">
                <i class="fa fa-users" aria-hidden="true"></i>
            </button>
        </div>';
    }
    echo'</div>';
?>

 
