<?php
require_once __DIR__ . '/../rb/db.php';
$user = R::findOne('users');
if (isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $adminId = $user->id;
    R::exec('UPDATE messages SET admin_read = 1 WHERE user_id = ? AND sender_role = ?', [$userId, $adminId]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User ID is required']);
}
?>