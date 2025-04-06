<?php
require_once __DIR__ . '/../rb/db.php';
if (isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $user = R::findOne('users');
    $adminId = $user->id; 
    $messages = R::find('messages', '(user_id = ? AND sender_role = ?) OR (user_id = ? AND sender_role = ?)', [$userId, $adminId, $adminId, $userId]);
    if ($messages) {
        foreach ($messages as $message) {
            R::trash($message); 
        }
        echo json_encode(['status' => 'success', 'message' => 'Чат с пользователм удален.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Нет сообщений.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Требуется id ']);
}
?>