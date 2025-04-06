<?php
require_once __DIR__ . '/../rb/db.php';
$useradmin = R::findOne('users');
$id_user=$useradmin->id;
if (isset($_POST['user_id']) && isset($_POST['message'])) {
    $userId = intval($_POST['user_id']);
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $msg = R::dispense('messages');
        $msg->user_id = $id_user; 
        $msg->message = $message; 
        $msg->sender_role = $userId; 
        $msg->timestamp = date('Y-m-d H:i:s'); 
        $msg->admin_read = true;  
        $msg->user_read = false;  
        R::store($msg);
        echo json_encode(['status' => 'success', 'message' => 'Сообщение отправлено']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Сообщение не может быть пустым']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Необходимые параметры отсутствуют']);
}
?>
