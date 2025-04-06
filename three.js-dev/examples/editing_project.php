<?php
require_once __DIR__ . '/../rb/db.php';

R::ext('xdispense', function ($type) {
    return R::getRedBean()->dispense($type);
});

R::ext('type', function ($type) {
    return $type === 'hash_tags' ? 'hash_tags' : $type;
});


function insertProject($table, $title, $description, $previewFile, $tags, $HashTable, $projectId, $name, $url, $sizefromfiles, $archive, $archiveName) {
    $project = R::load('projects', $projectId);
    $previewUrl = $project->preview_url;
    $archiveUrl = $project->archive_url;
    $substring_to_remove = "three.js-dev/examples/";
    $result = str_replace($substring_to_remove, "", $previewUrl);
    $result2 = str_replace($substring_to_remove, "", $archiveUrl);
    if (!empty($previewFile) && !empty($previewUrl)) {
        unlink($result);
    }
    if (!empty($archive) && !empty($archiveUrl)) {
        unlink($result2); 
    }
    $newPreviewUrl = "three.js-dev/examples/" . $url . "/" . $name;
    $newPreviewUrl2 = "three.js-dev/examples/" . $url;
    $newArchiveUrl = "three.js-dev/examples/" . $url . "/" . $archiveName;
    move_uploaded_file($previewFile, $url . "/" . $name);
    move_uploaded_file($archive, $url . "/" . $archiveName);
    if (!empty($tags) && $tags[0] !== '') {
        R::hunt('hashtagtoproject', 'typeid = ?', [$projectId]); 
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

            R::exec("INSERT INTO $HashTable (hashtagfromheshtag, typeid) VALUES (?, ?)", [$tagId, $projectId]);

            if (!empty($tags) && $tags[0] !== '') {
                $contentTables = [
                    'models' => 'hashtagtomodels',
                ];
                R::hunt($HashTable, 'typeid = ?', [$projectId]);
                foreach ($contentTables as $contentTable => $hashtagTable) {
                    $contentItems = R::findAll($contentTable, 'id_project = ?', [$projectId]);

                    foreach ($contentItems as $item) {
                        R::hunt($hashtagTable, 'typeid = ?', [$item->id]); 
                    }
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

                    R::exec("INSERT INTO $HashTable (hashtagfromheshtag, typeid) VALUES (?, ?)", [$tagId, $projectId]);

                    foreach ($contentTables as $contentTable => $hashtagTable) {
                        $contentItems = R::findAll($contentTable, 'id_project = ?', [$projectId]);

                        foreach ($contentItems as $item) {
                            R::exec("INSERT INTO $hashtagTable (hashtagfromheshtag, typeid) VALUES (?, ?)", [$tagId, $item->id]);
                        }
                    }
                }

            }
        }
    }

    $folderSize = 0;  
    
    if (!empty($url) && is_dir($url)) {  
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($url, FilesystemIterator::SKIP_DOTS)) as $file) {  
            $folderSize += $file->getSize();  
        }  
    }  
    $updateData = [];
    if (isset($title) && $title !== "") { 
        $updateData['name'] = $title;
    }
    if (isset($description) && $description !== "") {
        $updateData['description_project'] = $description;
    }
    if (!empty($previewFile)) {
        $updateData['preview_url'] = $newPreviewUrl;
    }
    if ($sizefromfiles > 0) {
        $updateData['size'] = $folderSize;
    }
    if (!empty($archive)) {
        $updateData['archive_url'] = $newArchiveUrl;
    }

    if (!empty($updateData)) {
        $updateQuery = "UPDATE $table SET ";
        foreach ($updateData as $column => $value) {
            $updateQuery .= "$column = ?, ";
        }
        $updateQuery = rtrim($updateQuery, ", ");
        $updateQuery .= " WHERE id = ?";

        $params = array_values($updateData);
        $params[] = $projectId;

        R::exec($updateQuery, $params);
    } else {
        echo "Нет данных для обновления.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $titleProject = $_POST['TitleProject'];
    $descriptionProject = $_POST['DescriptionProject'];
    $previewFilename = $_FILES['FilePrewiew']['name'] ?? null;
    $previewFile = $_FILES['FilePrewiew']['tmp_name'] ?? null;
    $archiveFileName = $_FILES['FileProject']['name'] ?? null;
    $archiveFile = $_FILES['FileProject']['tmp_name'] ?? null;
    $tags = json_decode($_POST['tags']);
    $dirPathproject = "projects/" . $id;
    insertProject(
        'projects', 
        $titleProject, 
        $descriptionProject, 
        $previewFile, 
        $tags,
        "hashtagtoproject", 
        $id, 
        $previewFilename, 
        $dirPathproject, 
        1, 
        $archiveFile, 
        $archiveFileName
    );
}
?>
