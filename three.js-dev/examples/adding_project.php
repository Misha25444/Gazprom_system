<?php
require_once __DIR__ . '/../rb/db.php';

R::ext('xdispense', function ($type) {
    return R::getRedBean()->dispense($type);
});


R::ext('type', function ($type) {
    return $type === 'hash_tags' ? 'hash_tags' : $type;
});

function extractZipArchive($zipFilePath, $extractPath) {
    $zip = new ZipArchive();
    if ($zip->open($zipFilePath) === TRUE) {
        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0777, true);
        }
        $zip->extractTo($extractPath);
        $zip->close();
    }
}

function getFolderSize($folderPath) {
    $size = 0;
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath, FilesystemIterator::SKIP_DOTS));
    foreach ($files as $file) {
        $size += $file->getSize();
    }
    return $size;
}

function insertFilesImage($tableName, $files, $projectId, $tagNames, $namesTable, $url) { 
    $logFile = __DIR__ . '/upload_log.txt';
    $log = fopen($logFile, 'a');

    foreach ($files['name'] as $key => $name) {
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $tmpName = $files['tmp_name'][$key]; 

        if (!is_uploaded_file($tmpName)) {
            fwrite($log, "Файл не загружен: " . $tmpName . "\n");
            continue;
        }
        $uploadDir = $_SERVER['DOCUMENT_ROOT']."//examples/" . $url;
        $originalPath = $uploadDir . "/" . $name;
        $compressedPath = $uploadDir . "/compressed_" . $name;
        $dbOriginalPath = "three.js-dev/examples/" . $url . "/" . $name;
        $dbCompressedPath = "three.js-dev/examples/" . $url . "/compressed_" . $name;
        fwrite($log, "Папка загрузки: " . $uploadDir . "\n");
        fwrite($log, "Оригинальный путь: " . $originalPath . "\n");
        fwrite($log, "Сжатый путь: " . $compressedPath . "\n");

        if (!file_exists($uploadDir)) {
            fwrite($log, "Папка не существует, создаем: " . $uploadDir . "\n");
            mkdir($uploadDir, 0777, true);
        }

        if (!move_uploaded_file($tmpName, $originalPath)) {
            fwrite($log, "Ошибка move_uploaded_file: " . json_encode(error_get_last()) . "\n");
            continue;
        } else {
            fwrite($log, "Файл успешно загружен: " . $originalPath . "\n");
        }
        $fileExtension = strtolower(pathinfo($Name, PATHINFO_EXTENSION));
        if (in_array($fileExtension, ['hdr', 'png', 'exr', 'jpg'])) {
            try {
                $image = new Imagick($originalPath);
                if (!$image) {
                    fwrite($log, "Ошибка загрузки изображения в Imagick: " . $originalPath . "\n");
                    continue;
                }

                $image->stripImage();
                $image->setImageCompressionQuality(70);
                $image->setImageDepth(8);
                $image->quantizeImage(256, Imagick::COLORSPACE_RGB, 0, false, false);

                if ($fileExtension == 'exr' || $fileExtension == 'hdr' || $fileExtension == 'png') {
                    $compressedPath = preg_replace('/\.(exr|hdr|png)$/i', '.jpg', $compressedPath);
                    $dbCompressedPath = preg_replace('/\.(exr|hdr|png)$/i', '.jpg', $dbCompressedPath);
                    $image->setImageFormat("jpg");
                    fwrite($log, "Изображение будет сохранено как JPG: " . $compressedPath . "\n");
                }

                if ($image->writeImage($compressedPath)) {
                    fwrite($log, "Сжатое изображение сохранено: " . $compressedPath . "\n");
                } else {
                    fwrite($log, "Ошибка сохранения сжатого изображения: " . $compressedPath . "\n");
                }

                clearstatcache();
                $compressedSize = filesize($compressedPath) / (1024 * 1024);
                fwrite($log, "Размер после сжатия: " . round($compressedSize, 2) . " MB\n");

                if ($compressedSize > 5) {
                    $image->setImageCompressionQuality(50);
                    $image->writeImage($compressedPath);
                    clearstatcache();
                    $compressedSize = filesize($compressedPath) / (1024 * 1024);
                    fwrite($log, "Размер после доп. сжатия: " . round($compressedSize, 2) . " MB\n");
                }
            } catch (ImagickException $e) {
                fwrite($log, "Ошибка обработки изображения: " . $e->getMessage() . "\n");
                continue;
            }
        } else {
            $compressedPath = $originalPath;
            $dbCompressedPath = $dbOriginalPath;
        }

        R::exec("INSERT INTO $tableName (path, id_project, name, format, size, preview,content_types) VALUES (?, ?, ?, ?, ?, ?,?)", [
            $dbOriginalPath, $projectId, $Name, $fileExtension, filesize($originalPath), $dbCompressedPath, 'image'
        ]);
        $lastInsertId = R::getInsertID();
        foreach ($tagNames as $tagName) {
            $tagName = strval($tagName);
            $existingTag = R::findOne('hashtags', 'name = ?', [$tagName]);
            if (!$existingTag) {
                $existingTag = R::dispense('hashtags');
                $existingTag->name = $tagName;
                $tagId = R::store($existingTag);
            } else {
                $tagId = $existingTag->id;
            }
            
            $linkTagToAudio = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsertId]);
            if (!$linkTagToAudio) {
                $linkTagToAudio = R::dispense($namesTable);
                $linkTagToAudio->hashtagfromheshtag = $tagId;
                $linkTagToAudio->typeid = $lastInsertId;
                R::store($linkTagToAudio);
            }
        }
        fwrite($log, "Файл записан в БД: " . $dbOriginalPath . ", превью: " . $dbCompressedPath . "\n");
    }

    fclose($log);
}


function insertFilesTexture($tableName, $files, $projectId, $tagNames, $namesTable, $url) {
    for ($key = 0; $key < count($files['name']); $key++) {
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $tmpName = $files['tmp_name'][$key]; 
        $zipFilePath = 'projects/'.$projectId. '/' . $Name;
        $extractPath = 'projects/'.$projectId. '/' . pathinfo($Name, PATHINFO_FILENAME);
        $FinalextractPath = 'projects/'.$projectId. '/' . pathinfo($Name, PATHINFO_FILENAME);
        extractZipArchive($tmpName, $extractPath);
        $finalPath = strval("three.js-dev/examples/".$url."/".$Name); 
        move_uploaded_file($tmpName, $url."/".$Name); 
        $fileExtension = pathinfo($Name, PATHINFO_EXTENSION);
        R::exec("INSERT INTO $tableName (path, id_project, name, format, size,content_types) VALUES (?, ?,?,?,?,?)", [$FinalextractPath, $projectId, $Name, $fileExtension, $size,'texture']);
        $lastInsert = R::getInsertID();
        foreach ($tagNames as $tagName) {
            $tagName = strval($tagName);
            $existingTag = R::findOne('hashtags', 'name = ?', [$tagName]);
            if (!$existingTag) {
                $existingTag = R::dispense('hashtags');
                $existingTag->name = $tagName;
                $tagId = R::store($existingTag);
            } else {
                $tagId = $existingTag->id;
            }
            $linkTagToAudio = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$linkTagToAudio) {
                $linkTagToAudio = R::dispense($namesTable);
                $linkTagToAudio->hashtagfromheshtag = $tagId;
                $linkTagToAudio->typeid = $lastInsert;
                R::store($linkTagToAudio);
            }
        }
    }
}

function insertFiles3d($tableName, $files, $projectId, $tagNames, $namesTable,$url,$img) {
    foreach ($files['name'] as $key => $name) {
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $imgmodels=$img['tmp_name'][$key];
        $imgname=$img['name'][$key];
        $tmpName = $files['tmp_name'][$key]; 
        $finalPath = strval("three.js-dev/examples/".$url."/".$name); 
        move_uploaded_file($tmpName, $url."/".$name); 
        $finalPath1 = strval("three.js-dev/examples/".$url."/".$imgname); 
        move_uploaded_file($imgmodels, $url."/".$imgname); 
        $fileExtension = pathinfo($Name, PATHINFO_EXTENSION);
        R::exec("INSERT INTO $tableName (path, id_project, name, format,size,preview,content_types) VALUES (?, ?,?,?,?,?,?)", [$finalPath, $projectId,$Name,$fileExtension,$size,$finalPath1,'model']);
        $lastInsert = R::getInsertID();
        foreach ($tagNames as $tagName) {
            $tagName = strval($tagName);
            $existingTag = R::findOne('hashtags', 'name = ?', [$tagName]);
            if (!$existingTag) {
                $existingTag = R::dispense('hashtags');
                $existingTag->name = $tagName;
                $tagId = R::store($existingTag);
            } else {
                $tagId = $existingTag->id;
            }
            $linkTagToAudio = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$linkTagToAudio) {
                $linkTagToAudio = R::dispense($namesTable);
                $linkTagToAudio->hashtagfromheshtag = $tagId;
                $linkTagToAudio->typeid = $lastInsert;
                R::store($linkTagToAudio);
            }
        }
    }
  
}


function insertMaterial($tableName, $textures, $projectId, $url, $sphereImages, $sphereNames, $namesTable, $tagNames) {
    $baseDir = "projects/$projectId/";
    if (!file_exists($baseDir)) {
        mkdir($baseDir, 0777, true);
    }

    $groupedTextures = [];
    foreach ($textures['tmp_name'] as $key => $tmpName) {
        if (preg_match('/row(\d+)_input\d+/', $key, $matches)) {
            $rowIndex = $matches[1];
            $groupedTextures[$rowIndex][$key] = $tmpName;
        }
    }

    foreach ($groupedTextures as $rowIndex => $files) {
        $zip = new ZipArchive();
        $zipFileName = "textures_group_" . uniqid() . ".zip";
        $fullPath = $baseDir . $zipFileName;

        if ($zip->open($fullPath, ZipArchive::CREATE) !== TRUE) {
            echo "Не удалось создать архив: $fullPath\n";
            continue;
        }

        foreach ($files as $originalKey => $tmpName) {
            $originalName = $textures['name'][$originalKey] ?? basename($tmpName);
            $zip->addFile($tmpName, $originalName);
        }
        $zip->close();
        $archiveSize = filesize($fullPath);

        $img = $sphereImages['tmp_name'][$rowIndex] ?? null;
        $imgName = $sphereImages['name'][$rowIndex] ?? null;
        $finalPath = "three.js-dev/examples/$url/$imgName";
        if ($img && $imgName) {
            move_uploaded_file($img, "$url/$imgName");
        }

        $namesphere = $sphereNames[$rowIndex] ?? "Unnamed";
        R::exec("INSERT INTO $tableName (path, id_project, name, preview, size,content_types) VALUES (?, ?, ?, ?, ?,?)", [$fullPath, $projectId, $namesphere, $finalPath, $archiveSize,'material']);
        $lastInsert = R::getInsertID();

        foreach ($tagNames as $tagName) {
            $tagName = strval($tagName);
            $existingTag = R::findOne('hashtags', 'name = ?', [$tagName]);
            if (!$existingTag) {
                $existingTag = R::dispense('hashtags');
                $existingTag->name = $tagName;
                $tagId = R::store($existingTag);
            } else {
                $tagId = $existingTag->id;
            }
            $linkTagToAudio = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$linkTagToAudio) {
                $linkTagToAudio = R::dispense($namesTable);
                $linkTagToAudio->hashtagfromheshtag = $tagId;
                $linkTagToAudio->typeid = $lastInsert;
                R::store($linkTagToAudio);
            }
        }
    }
}

function insertFiles($tableName, $files, $projectId, $tagNames, $namesTable,$url,$content_type) {

    foreach ($files['name'] as $key => $name) {
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $tmpName = $files['tmp_name'][$key]; 
        $finalPath = strval("three.js-dev/examples/".$url."/".$name); 
        move_uploaded_file($tmpName, $url."/".$name); 
        $fileExtension = pathinfo($Name, PATHINFO_EXTENSION);
        R::exec("INSERT INTO $tableName (path, id_project, name, format,size,content_types) VALUES (?, ?,?,?,?,?)", [$finalPath, $projectId,$Name,$fileExtension,$size,$content_type]);
        $lastInsertId = R::getInsertID();
        foreach ($tagNames as $tagName) {
            $tagName = strval($tagName);
            $existingTag = R::findOne('hashtags', 'name = ?', [$tagName]);
            if (!$existingTag) {
                $existingTag = R::dispense('hashtags');
                $existingTag->name = $tagName;
                $tagId = R::store($existingTag);
            } else {
                $tagId = $existingTag->id;
            }
            $linkTagToAudio = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsertId]);
            if (!$linkTagToAudio) {
                $linkTagToAudio = R::dispense($namesTable);
                $linkTagToAudio->hashtagfromheshtag = $tagId;
                $linkTagToAudio->typeid = $lastInsertId;
                R::store($linkTagToAudio);
            }
        }
    }
}

function insertProject($table, $title, $description, $previewFile, $tags, $HashTable, $projectId,$name,$url,$sizefromfiles,$archiveFile,$archiveFileName,$iduser) {
    $totalSize = 0; 
    foreach ($sizefromfiles as $size) {
        $totalSize += $size;
    }
    $baseUrl = strval("three.js-dev/examples/".$url."/".$name); 
    $baseUrl2 = strval("three.js-dev/examples/".$url."/".$archiveFileName);
    move_uploaded_file($previewFile, $url."/".$name);
    if ($archiveFile !== null && $archiveFileName !== null) {
        move_uploaded_file($archiveFile, $url . "/" . $archiveFileName);
    }
    foreach ($tags as $tagName) {
        $tagName = strval($tagName);
        $existingTag = R::findOne('hashtags', 'name = ?', [$tagName]);

        if (!$existingTag) {
            $newTag = R::dispense('hashtags');
            $newTag->name = $tagName;
            $tagId = R::store($newTag);
            
        } else {
        
            $tagId = $existingTag->id;
        
        }
        R::exec("INSERT INTO $HashTable (hashtagfromheshtag , typeid) VALUES (?, ?)", [$tagId, $projectId]);
    }
    if (!empty($archiveFileName)) {

        R::exec("UPDATE $table SET name = ?, User_id=?, preview_url=?, size=?, archive_url=? WHERE id = ?", [$title, $iduser, $baseUrl, $totalSize, $baseUrl2, $projectId]);
    } else {

        R::exec("UPDATE $table SET name = ?, User_id=?, preview_url=?, size=?, archive_url=NULL WHERE id = ?", [$title, $iduser, $baseUrl, $totalSize, $projectId]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titleProject = $_POST['TitleProject'];
    $descriptionProject = $_POST['DescriptionProject'];
    $userId = $_POST['userId'];
    $project = R::dispense('projects');
    $project->description_project = $descriptionProject;
    $project->format = isset($_FILES['archiveFile']['name'][0]) && !empty($_FILES['archiveFile']['name'][0]) ? pathinfo($_FILES['archiveFile']['name'][0], PATHINFO_EXTENSION) : null;
    $projectId = R::store($project);
    $sphere =$_FILES['sphereImages'];
    $nameMaterial = $_POST['NameMaterial'];
    $previewFile =$_FILES['FileProject']['tmp_name'][0] ;
    $previewFilename =$_FILES['FileProject']['name'][0] ;
    $sizeProject = isset($_FILES['archiveFile']['size']) ? $_FILES['archiveFile']['size'] : 0;
    $archiveFile = isset($_FILES['archiveFile']['tmp_name'][0]) ? $_FILES['archiveFile']['tmp_name'][0] : 0;
    $archiveFileName = isset($_FILES['archiveFile']['name'][0]) ? $_FILES['archiveFile']['name'][0] : 0;
    $fileimageforMaterial =$_FILES['textures'];
    $models3d=$_FILES['screenshots'];
    $tags = json_decode($_POST['tags']);
    $dirPathproject = "projects/".$projectId;
    $fileFormats = [
        'modelFile','models' ,'imageFile','FileProject',
        'audioFile', 'videoFile', 'textureFile', 'documentFile', 'tags','textures'
    ];
    
    if (!file_exists($dirPathproject )) {
       
        if (mkdir($dirPathproject , 0777, true)) {
        
            insertProject('projects',    $titleProject ,  $descriptionProject, $previewFile, $tags,"hashtagtoproject" ,$projectId, $previewFilename,$dirPathproject,$sizeProject,$archiveFile,$archiveFileName, $userId);
            foreach ($fileFormats as $format) {
        
                $fileNames =$_FILES[$format];
                if (!empty($fileNames)) {
                
                    switch ($format) {
                        
                        case 'videoFile':
                            if ($fileNames['name'][0] != "") insertFiles('models', $_FILES['videoFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'video');
                            break;
                        case 'imageFile':
                            if ($fileNames['name'][0]  != "") insertFilesimage('models', $_FILES['imageFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject);
                            break;
                        case 'documentFile':
                            if ($fileNames['name'][0]  != "") insertFiles('models',$_FILES['documentFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'document');
                            break;
                        case 'textureFile':
                            if ($fileNames['name'][0]  != "") insertFilesTexture('models', $_FILES['textureFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject);
                            break;
                        case 'models':
                            if ($fileNames['name'][0]  != "") insertFiles3d('models', $_FILES['models'], $projectId, $tags,'hashtagtomodels',$dirPathproject,$models3d);
                            break;
                        case 'textures':
                            if ($fileNames['name']['row0_input0']  != "")  insertMaterial('models', $_FILES['textures'],$projectId,$dirPathproject,$sphere,$nameMaterial,"hashtagtomodels",$tags);
                            break;
                        case 'audioFile':
                            if ($fileNames['name'][0]  != "") insertFiles('models', $_FILES['audioFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'audio');
                            break;

                    }
                }

                $project = R::load('projects', $projectId);
                $folderPath = $_SERVER['DOCUMENT_ROOT']."/examples/projects/" . $projectId;
                    
                if (is_dir($folderPath)) {
                    $newSize = getFolderSize($folderPath);
                    $project->size = $newSize;
                    R::store($project);
                }

            }

            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
   
        }

        header('Content-Type: application/json');

        $response = array(
            'success' => true,
            'message' => 'Данные успешно обработаны.'
        );
        echo json_encode($response);
        exit; 
    }
}
?>
