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

function insertFilesTexture($tableName, $files, $projectId, $tagNames, $namesTable, $url,$type) {
    for ($key = 0; $key < count($files['name']); $key++) {
        $projectTags = R::findAll('hashtagtoproject', 'typeid = ?', [$projectId]);
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
        R::exec("INSERT INTO $tableName (path, id_project, name, format, size,content_types) VALUES (?, ?,?,?,?,?)", [$FinalextractPath, $projectId, $Name, $fileExtension, $size,$type]);
        $lastInsert = R::getInsertID();
        $allTags = [];
        
        foreach ($projectTags as $projectTag) {
            $allTags[$projectTag->hashtagfromheshtag] = true;
        }
     
        if (!empty($tagNames)) {
            foreach ($tagNames as $tag) {
                $existingTag = R::findOne('hashtags', 'name = ?', [$tag]);
                if ($existingTag) {
                    $tagId = $existingTag->id;
                } else {
                    $newTag = R::dispense('hashtags');
                    $newTag->name = $tag;
                    $tagId = R::store($newTag);
                }
                 $allTags[$tagId] = true;
            }
        }
        foreach (array_keys($allTags) as $tagId) {
             $existingLink = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
             if (!$existingLink) {
                 $link = R::dispense($namesTable);
                 $link->hashtagfromheshtag = $tagId;
                 $link->typeid = $lastInsert;
                 R::store($link);
             }
        }
    }
}

function insertFiles3d($tableName, $files, $projectId, $tagNames, $namesTable,$url,$img,$type) {
    $projectTags = R::findAll('hashtagtoproject', 'typeid = ?', [$projectId]);
    foreach ($files['name'] as $key => $name) {
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $imgmodels=$img['tmp_name'][$key];
        $imgname=$img['name'][$key];
        $rand = mt_rand(1000, 9999);
        $uniqueFileName = $rand . '_' . $Name;
        $imguniqueFileName = $rand . '_' . $imgname;
        $tmpName = $files['tmp_name'][$key]; 
        $finalPath = strval("three.js-dev/examples/".$url."/".$uniqueFileName); 
        move_uploaded_file($tmpName, $url."/".$uniqueFileName); 
        $finalPath1 = strval("three.js-dev/examples/".$url."/".$imguniqueFileName); 
        move_uploaded_file($imgmodels, $url."/".$imguniqueFileName); 
        $fileExtension = pathinfo($Name, PATHINFO_EXTENSION);
        R::exec("INSERT INTO $tableName (path, id_project, name, format,size,preview,content_types) VALUES (?, ?,?,?,?,?,?)", [$finalPath, $projectId,$Name,$fileExtension,$size,$finalPath1,$type]);
        $lastInsert = R::getInsertID();
        $allTags = [];
        foreach ($projectTags as $projectTag) {
            $allTags[$projectTag->hashtagfromheshtag] = true;
        }

        if (!empty($tagNames)) {
            foreach ($tagNames as $tag) {
                $existingTag = R::findOne('hashtags', 'name = ?', [$tag]);
                
                if ($existingTag) {
                    $tagId = $existingTag->id;
                } else {
                    $newTag = R::dispense('hashtags');
                    $newTag->name = $tag;
                    $tagId = R::store($newTag);
                }

                $allTags[$tagId] = true;
            }
        }

        foreach (array_keys($allTags) as $tagId) {
            $existingLink = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$existingLink) {
                $link = R::dispense($namesTable);
                $link->hashtagfromheshtag = $tagId;
                $link->typeid = $lastInsert;
                R::store($link);
            }
        }
    }
}



function insertMaterial($tableName, $textures, $projectId, $url, $sphereImages, $sphereNames, $namesTable, $tagNames,$type) {
    $baseDir = "projects/$projectId/";
    if (!file_exists($baseDir)) {
        mkdir($baseDir, 0777, true);
    }
    $projectTags = R::findAll('hashtagtoproject', 'typeid = ?', [$projectId]);
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
        $rand = mt_rand(1000, 9999);
        $uniqueImgName = $rand . '_' . $imgName;
        $finalPath = "three.js-dev/examples/$url/$uniqueImgName";
        
        if ($img && $imgName) {
            move_uploaded_file($img, "$url/$uniqueImgName");
        }

        $namesphere = $sphereNames[$rowIndex] ?? "Unnamed";
        R::exec("INSERT INTO $tableName (path, id_project, name, preview, size,content_types) VALUES (?, ?, ?, ?, ?,?)", [$fullPath, $projectId, $namesphere, $finalPath, $archiveSize,$type]);
        $lastInsert = R::getInsertID();
        $allTags = [];
        foreach ($projectTags as $projectTag) {
            $allTags[$projectTag->hashtagfromheshtag] = true;
        }
    
        if (!empty($tagNames)) {
            foreach ($tagNames as $tag) {
                $existingTag = R::findOne('hashtags', 'name = ?', [$tag]);
                if ($existingTag) {
                    $tagId = $existingTag->id;
                } else {
                    $newTag = R::dispense('hashtags');
                    $newTag->name = $tag;
                    $tagId = R::store($newTag);
                }
                $allTags[$tagId] = true;
            }
        }
        foreach (array_keys($allTags) as $tagId) {
            $existingLink = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$existingLink) {
                $link = R::dispense($namesTable);
                $link->hashtagfromheshtag = $tagId;
                $link->typeid = $lastInsert;
                R::store($link);
            }
        }
    }
}

function insertFilesImage($tableName, $files, $projectId, $tagNames, $namesTable, $url, $type) {
    $projectTags = R::findAll('hashtagtoproject', 'typeid = ?', [$projectId]);
    foreach ($files['name'] as $key => $name) {
        $rand = mt_rand(1000, 9999);
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $tmpName = $files['tmp_name'][$key];
        $fileExtension = strtolower(pathinfo($Name, PATHINFO_EXTENSION));
        $uniqueFileName = $rand . '_' . $Name;
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "//examples/" . $url;
        $originalPath = $uploadDir . "/" . $uniqueFileName;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        move_uploaded_file($tmpName, $originalPath);
        $compressedPath = null;
        if (in_array($fileExtension, ['hdr', 'png', 'exr', 'jpg'])) {
            try {
                $image = new Imagick($originalPath);
                $image->stripImage();
                $image->setImageCompressionQuality(70);
                $image->setImageDepth(8);
                $image->quantizeImage(256, Imagick::COLORSPACE_RGB, 0, false, false);
                if (in_array($fileExtension, ['hdr', 'png', 'exr'])) {
                    $compressedFileName = preg_replace('/\.(hdr|png|exr)$/i', '.jpg', $uniqueFileName);
                    $image->setImageFormat("jpg");
                } else {
                    $compressedFileName = $uniqueFileName;
                }
                $compressedPath = $uploadDir . "/" . $compressedFileName;
                $image->writeImage($compressedPath);
                clearstatcache();
                if (filesize($compressedPath) > 5 * 1024 * 1024) {
                    $image->setImageCompressionQuality(50);
                    $image->writeImage($compressedPath);
                }
            } catch (ImagickException $e) {
                $compressedPath = null;
            }
        }

        $dbOriginalPath = "three.js-dev/examples/" . $url . "/" . $uniqueFileName;
        $dbCompressedPath = $compressedPath 
            ? "three.js-dev/examples/" . $url . "/" . basename($compressedPath) 
            : $dbOriginalPath;

        R::exec("INSERT INTO $tableName (path, id_project, name, format, size, preview, content_types) VALUES (?, ?, ?, ?, ?, ?, ?)", 
            [$dbOriginalPath, $projectId, $Name, $fileExtension, $size, $dbCompressedPath, $type]
        );

        $lastInsert = R::getInsertID();
        $allTags = [];

        foreach ($projectTags as $projectTag) {
            $allTags[$projectTag->hashtagfromheshtag] = true;
        }

        if (!empty($tagNames)) {
            foreach ($tagNames as $tag) {
                $existingTag = R::findOne('hashtags', 'name = ?', [$tag]);

                if ($existingTag) {
                    $tagId = $existingTag->id;
                } else {
                    $newTag = R::dispense('hashtags');
                    $newTag->name = $tag;
                    $tagId = R::store($newTag);
                }
                $allTags[$tagId] = true;
            }
        }

        foreach (array_keys($allTags) as $tagId) {
            $existingLink = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$existingLink) {
                $link = R::dispense($namesTable);
                $link->hashtagfromheshtag = $tagId;
                $link->typeid = $lastInsert;
                R::store($link);
            }
        }
    }
}



function insertFiles($tableName, $files, $projectId, $tagNames, $namesTable, $url,$type) {
    $projectTags = R::findAll('hashtagtoproject', 'typeid = ?', [$projectId]);
    foreach ($files['name'] as $key => $name) {
        $rand = mt_rand(1000, 9999);
        $Name = $files['name'][$key];
        $size = $files['size'][$key];
        $tmpName = $files['tmp_name'][$key];
        $uniqueFileName = $rand . '_' . $Name;
        $finalPath = "three.js-dev/examples/" . $url . "/" . $uniqueFileName;
        move_uploaded_file($tmpName, $url . "/" . $uniqueFileName);
        $fileExtension = pathinfo($Name, PATHINFO_EXTENSION);
        R::exec("INSERT INTO $tableName (path, id_project, name, format, size, content_types) VALUES (?, ?, ?, ?, ?,?)", [$finalPath, $projectId, $Name, $fileExtension, $size,$type]);
        $lastInsert = R::getInsertID();
        $allTags = [];

        foreach ($projectTags as $projectTag) {
            $allTags[$projectTag->hashtagfromheshtag] = true;
        }
    
        if (!empty($tagNames)) {
            foreach ($tagNames as $tag) {

                $existingTag = R::findOne('hashtags', 'name = ?', [$tag]);
                
                if ($existingTag) {
                    $tagId = $existingTag->id;
                } else {
                    $newTag = R::dispense('hashtags');
                    $newTag->name = $tag;
                    $tagId = R::store($newTag);
                }
    
                $allTags[$tagId] = true;
            }
        }
    
        foreach (array_keys($allTags) as $tagId) {
            $existingLink = R::findOne($namesTable, 'hashtagfromheshtag = ? AND typeid = ?', [$tagId, $lastInsert]);
            if (!$existingLink) {
                $link = R::dispense($namesTable);
                $link->hashtagfromheshtag = $tagId;
                $link->typeid = $lastInsert;
                R::store($link);
            }
        }
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['id'];
    $sphere =$_FILES['sphereImages'];
    $nameMaterial = $_POST['NameMaterial'];
    $fileimageforMaterial =$_FILES['textures'];
    $models3d=$_FILES['screenshots'];
    $tags = json_decode($_POST['tags']);
    $dirPathproject = "projects/".$projectId;
    $fileFormats = [
        'modelFile','models' ,'imageFile','FileProject',
        'audioFile', 'videoFile', 'textureFile', 'documentFile', 'tags','textures'
    ];

    foreach ($fileFormats as $format) {
           $fileNames =$_FILES[$format];
        if (!empty($fileNames)) {
    
            switch ($format) {
                
                case 'videoFile':
                    if ($fileNames['name'][0] != "") insertFiles('models', $_FILES['videoFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'video');
                    break;
                case 'imageFile':
                    if ($fileNames['name'][0]  != "") insertFilesImage('models', $_FILES['imageFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'image');
                    break;
                case 'documentFile':
                    if ($fileNames['name'][0]  != "") insertFiles('models',$_FILES['documentFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'document');
                    break;
                case 'textureFile':
                    if ($fileNames['name'][0]  != "") insertFilesTexture('models', $_FILES['textureFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'texture');
                    break;
                case 'models':
                    if ($fileNames['name'][0]  != "") insertFiles3d('models', $_FILES['models'], $projectId, $tags,'hashtagtomodels',$dirPathproject,$models3d,'model');
                    break;
                case 'textures':
                    if ($fileNames['name']['row0_input0']  != "")  insertMaterial('models', $_FILES['textures'],$projectId,$dirPathproject,$sphere,$nameMaterial,"hashtagtomodels",$tags,'material');
                     break;
                case 'audioFile':
                    if ($fileNames['name'][0]  != "") insertFiles('models', $_FILES['audioFile'], $projectId, $tags,'hashtagtomodels',$dirPathproject,'audio');
                    break;

                
            }
            $project = R::load('projects', $projectId);
            $folderPath = $_SERVER['DOCUMENT_ROOT']."/examples/projects/" . $projectId;
            if (is_dir($folderPath)) {
                $newSize = getFolderSize($folderPath);
                $project->size = $newSize;
                R::store($project);
            }
        }
    }

    header('Content-Type: application/json');

    $response = array(
        'success' => true,
        'message' => 'Данные успешно обработаны.'
    );

    echo json_encode($response);
    exit; 
}
?>
