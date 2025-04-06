<?php
require_once __DIR__ . '/../rb/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка CSRF-защиты']);
        exit;
    }
    $image_id = $_POST["image_id"];
    $class_name= $_POST["class_name"];
    if ($class_name=="models" || $class_name=="image"){
        R::hunt('hashtagtomodels', 'typeid = ?', [$image_id]);
        $image = R::load('models', $image_id);
        if ($image) {
            $id_projects = $image->id_projects;
            $path=$image->path;
            $pathpreview=$image->preview; 
            $substring_to_remove = "three.js-dev/examples/";
            $result = str_replace($substring_to_remove, "", $path);
            $result2 = str_replace($substring_to_remove, "", $pathpreview);
            unlink($result);
            unlink($result2);
            R::trash($image);
            echo json_encode(['status' => 'success', 'message' => 'Файл успешно удален']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Файл не найден']);
        }
    }
    if ($class_name == "textures") {
        R::hunt('hashtagtomodels', 'typeid = ?', [$image_id]);
        $image = R::load('models', $image_id);
        if ($image && $image->id) { 
            $id_projects = $image->id_projects;
            $base_path = $_SERVER['DOCUMENT_ROOT']."//examples/";
            $result = realpath($base_path . $image->path);
            if ($result && is_dir($result)) {
                deleteDirectory($result);
                R::trash($image);
                echo json_encode(['status' => 'success', 'message' => 'Изображение успешно удалено']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Путь к изображению не найден: ' . $result]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Файл не найден в базе данных']);
        }
    }
    if ($class_name=="material"){
        R::hunt('hashtagtomodels', 'typeid = ?', [$image_id]);
        $image = R::load('models', $image_id);
        if ($image) {
            $id_projects = $image->id_projects;
            $path=$image->path;
            $path2=$image->preview;
            $substring_to_remove = "three.js-dev/examples/";
            $result = str_replace($substring_to_remove, "", $path);
            $result2 = str_replace($substring_to_remove, "", $path2);

            if (file_exists($result)|| file_exists($result2)) {
                unlink($result);
                unlink($result2);
            } else {
                $response = ['status' => 'error', 'message' => 'Файл материала не найден: ' . $result];
                echo json_encode($response);
                return;
            }
            R::trash($image);
            echo json_encode(['status' => 'success', 'message' => 'Файл успешно удален']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Файл не найден']);
        }
    }
 
    if ($class_name=="video"||$class_name=="audio"|| $class_name=="documents"){
        R::hunt('hashtagtomodels', 'typeid = ?', [$image_id]);
        $image = R::load('models', $image_id);
        if ($image) {
            $id_projects = $image->id_projects;
            $path=$image->path;
            $substring_to_remove = "three.js-dev/examples/";
            $result = str_replace($substring_to_remove, "", $path);
            
            if (file_exists($result)) {
                unlink($result);
            } else {
                $response = ['status' => 'error', 'message' => 'Файл материала не найден: ' . $result];
                echo json_encode($response);
                return;
            }
            R::trash($image);
            echo json_encode(['status' => 'success', 'message' => 'Файл успешно удален']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Файл не найден']);
        }
    }

   elseif ($class_name == "projects") {
        deleteDirectory('projects/'.$image_id);
        R::trashAll(R::find('hashtagtomodels', 'typeid = ?', [$image_id]));
        R::trashAll(R::find('hashtagtoproject', 'typeid = ?', [$image_id]));
        R::trashAll(R::find('models', 'id_project = ?', [$image_id]));
        R::trashAll(R::find('projects', 'id = ?', [$image_id]));
        $image = R::load('projects', $image_id);
        if ($image) {
            R::trash($image);
            echo json_encode(['status' => 'success', 'message' => 'Проект и связанные данные удалены']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Проект не найден']);
        }
    }

}
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return false;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . '/' . $file;
        if (is_file($path)) {
            unlink($path);
        } elseif (is_dir($path)) {
            deleteDirectory($path);
        }
    }
    return rmdir($dir);
}
?>
