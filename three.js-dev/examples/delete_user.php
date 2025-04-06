 <?
require_once __DIR__ . '/../rb/db.php';
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('Неверный CSRF токен.');
}
if (!isset($_SESSION['logged_user']) || $_SESSION['logged_user']->role !== "Администратор") {
    header("Location: viewing_project.php");
    exit;
}
if (isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $user = R::load('users', $userId);
    $firstUser = R::findOne('users', 'ORDER BY id ASC');
    if ($user->id) {
        if ($user->id !== $firstUser->id) {
            R::trash($user); 
            header('Location: main_page.php'); 
        } else {
            header('Location: main_page.php');  
        }
    } else {
        header('Location: main_page.php'); 
    }
} else {
    header('Location: main_page.php'); 
}
 ?>

