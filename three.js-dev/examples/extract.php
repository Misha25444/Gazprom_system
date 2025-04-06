
<?php 
require_once __DIR__ . '/../rb/db.php';
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['zip']) && isset($_GET['file'])) {
    $zipPath = urldecode($_GET['zip']);
    $filePath = urldecode($_GET['file']);
    if (file_exists($zipPath)) {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) === true) {
            if ($zip->locateName($filePath) !== false) {
                $tempFilePath = sys_get_temp_dir().'/'.basename($filePath);
                if ($zip->extractTo(sys_get_temp_dir(), $filePath)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
                    readfile($tempFilePath);
                    unlink($tempFilePath);
                    exit;
                }
            } else {
                die("Файл не найден в архиве.");
            }
            $zip->close();
        } else {
            die("Не удалось открыть архив.");
        }
    } else {
        die("Архив не найден.");
    }
}
?>
