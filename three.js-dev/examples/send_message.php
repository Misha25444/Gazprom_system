<?php

require_once __DIR__ . '/../rb/db.php';
$user = R::findOne('users');
$admin_id=$user->id;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    if ($userId > 0 && !empty($message)) {
        $msg = R::dispense('messages');
        $msg->user_id = $userId; 
        $msg->message = $message; 
        $msg->sender_role = $admin_id; 
        $msg->timestamp = R::isoDateTime();
        $msg->admin_read = false; 
        $msg->user_read = true;  
        R::store($msg);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
