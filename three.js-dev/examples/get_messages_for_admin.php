<?php
require_once __DIR__ . '/../rb/db.php';
$userAdmin = R::findOne('users');
// ID администратора
$adminId = $userAdmin->id;
$userIds = R::getCol(
    'SELECT DISTINCT user_id 
     FROM messages 
     WHERE (sender_role = ? OR user_id = ?) 
     AND user_id != ?', [$adminId, $adminId, $adminId]
);

$output = [];
if (!empty($userIds)) {
    $users = R::findAll('users', 'id IN (' . R::genSlots($userIds) . ')', $userIds);
    foreach ($users as $user) {
        $query = '
        SELECT admin_read
        FROM messages
        WHERE sender_role = ? AND user_id= ? OR sender_role = ? AND user_id= ?
        ORDER BY timestamp DESC
        LIMIT 1';
        $adminReadStatus = R::getCell($query, [$user->id, $adminId,$adminId,$user->id]);
        if ($adminReadStatus === null) {
            $adminReadStatus = 0; 
        }
        $output[] = [
            'user_id' => $user->id,
            'name' => $user->fio, 
            'last_message' => $adminReadStatus,
            'admin_read' => $adminReadStatus 
        ];
    }
}
header('Content-Type: application/json');
echo json_encode($output);
?>
