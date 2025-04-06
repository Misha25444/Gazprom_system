<?php  
require_once __DIR__ . '/../rb/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['project_id']) || isset($_POST['filesFromCardsImage']) || isset($_POST['filesFromCardsDoc'])||isset($_POST['filesFromCardsMaterial']) ||isset($_POST['filesFromCardsTexture']))) { 
    $projectId = isset($_POST['project_id']) ? intval($_POST['project_id']) : null; 
    $filesFromCards = isset($_POST['filesFromCardsImage']) ? $_POST['filesFromCardsImage'] : null;
    $filesFromCardsDoc =isset($_POST['filesFromCardsDoc']) ? $_POST['filesFromCardsDoc'] : null;
    $filesFromCardsMat =isset($_POST['filesFromCardsMaterial']) ? $_POST['filesFromCardsMaterial'] : null;
    $filesFromCardsTex =isset($_POST['filesFromCardsTexture']) ? $_POST['filesFromCardsTexture'] : null;
    if ($projectId) {
        $dirPath = realpath(__DIR__ . "/projects/" . $projectId);
        if (!$dirPath || !is_dir($dirPath)) {
            die('Неверная директория проекта: ' . $dirPath);
        }
        $zipPath = sys_get_temp_dir() . '/' . $projectId . '.zip';
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            die('Не удалось создать архив.');
        }
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $file) {
            $zip->addFile($file, str_replace($dirPath . DIRECTORY_SEPARATOR, '', $file));
        }
        $zip->close();
        if (file_exists($zipPath)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $projectId . '.zip"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            unlink($zipPath);
            exit;
        } else {
            die("Ошибка при создании архива.");
        }
    } elseif ($filesFromCards) {
        $filePath = str_replace("three.js-dev/examples/", "", $filesFromCards);
        if ($filePath && file_exists($filePath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            die("Файл не найден.".$filePath);
        }
    }
     elseif ($filesFromCardsDoc) {
        $filePath = str_replace("three.js-dev/examples/", "", $filesFromCardsDoc);
        if ($filePath && file_exists($filePath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            die("Файл не найден.".$filePath);
        }
    }
    elseif ($filesFromCardsMat) {
        $filePath = str_replace("three.js-dev/examples/", "", $filesFromCardsMat);
        if ($filePath && file_exists($filePath)) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            die("Файл не найден.".$filePath);
        }
    }

elseif ($filesFromCardsTex) {
        $dirPath = str_replace("three.js-dev/examples/", "", $filesFromCardsTex);
        if (is_dir($dirPath)) {
            $zipPath = sys_get_temp_dir() . '/' . basename($dirPath) . '.zip';
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                die('Не удалось создать архив.');
            }
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $file) {
                $zip->addFile($file, str_replace($dirPath . DIRECTORY_SEPARATOR, '', $file));
            }
            $zip->close();
            if (file_exists($zipPath)) {
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . basename($zipPath) . '"');
                header('Content-Length: ' . filesize($zipPath));
                readfile($zipPath);
                unlink($zipPath); 
                exit;
            } else {
                die("Ошибка при создании архива.");
            }
        } else {
            die("Директория не найдена: " . $dirPath);
        }
    }
    
    else {
        die('Отсутствуют необходимые данные.');
    }
}
?>


