<?php
require_once __DIR__ . '/../rb/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $fio = $_POST['fio'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['password'];
    $userId = $_POST['userId'];
    $user = R::findOne('users', "id = ?", [$userId]);
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
        exit;
    }
    if (password_verify($old_password, $user->password)) {
        $user->login = $login;
        $user->email = $email;
        $user->phone = $phone;
        $user->fio = $fio;
        $new_password = trim($new_password);
        if (!empty($new_password)) {
            $user->password = password_hash($new_password, PASSWORD_DEFAULT);
        }
        R::store($user);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Старый пароль неверен']);
    }
}
?>

