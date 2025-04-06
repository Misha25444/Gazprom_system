<?php
require_once __DIR__ . '/../rb/db.php';
if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);
    $user = R::findOne('users');
    $adminId = $user->id;
    $messages = R::find('messages', '(user_id = ? AND sender_role = ?) OR (user_id = ? AND sender_role = ?)', [$userId, $adminId, $adminId, $userId]);
    $output = [];
    foreach ($messages as $message) {
        $output[] = [
            'user_id' => $message->user_id,
            'message' => $message->message,
            'sender_role' => $message->sender_role,
            'timestamp' => $message->timestamp,
        ];
    }
    echo json_encode($output);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Требуется id']);
}
?>
