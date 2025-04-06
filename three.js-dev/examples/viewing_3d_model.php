<?PHP
require_once __DIR__ . '/../rb/db.php';
require_once 'php_date.php';
if ($_SESSION['logged_user']->role == "Администратор") {
  $isAdmin = true;
  $users = R::findAll('users');

} else {
  $isAdmin = false;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_SESSION['logged_user'])) {
    $userId = $_SESSION['logged_user']['id'];

  } else {
    header("Location: login.php");
    exit();
  }

} if (!$_SESSION['logged_user']) {
  header("Location:http://three.js-dev2/login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">


<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


<link type="text/css" rel="stylesheet" href="main.css">
<link rel="stylesheet" href="css/style.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/compressorjs@1.1.4/dist/compressor.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="jsm/playerjs_for_v_and_a.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<link rel="stylesheet" href="css/style_for_3d.css">
<script>window.onload = function() {
    window.scrollTo(0, document.body.scrollHeight);
};</script>



<title>3д просмотрщик</title>

<style>
.list-group-item-model span {
    
    display: flex;
    flex-wrap: wrap; 
    gap: 5px; 
}

.tag-badge {
    background-color: #f0f0f0;
    padding: 5px 10px;
    border-radius: 5px;
    white-space: nowrap; 
}

.viewer-container {
width: 100%;
height: 100% ;
background: radial-gradient(circle at 50% 120%, rgba(255, 255, 255, 0.05), rgba(0, 0, 0, 0.3));
border-radius: 12px;
position: relative;
overflow: hidden;
}

.glow-platform {
position: absolute;
bottom: 20px;
left: 50%;
width: 50%;
height: 60px;
background: linear-gradient(to top, rgba(0, 100, 255, 0.8), rgba(0, 0, 0, 0)); 
transform: translateX(-50%) perspective(10px) rotateX(5deg);
border-radius: 50%;
border: 4px solid #dcdcdc;
}

.glow-cylinder {
position: absolute;
bottom: 5%;
left: 50%;
width: 50%;
height: 200px;
background: linear-gradient(to top, rgba(0, 100, 255, 0.8), rgba(0, 100, 255, 0.5) 30%, rgba(0, 0, 0, 0)); 
transform: translateX(-50%);
filter: blur(20px);
opacity: 1;
}


.glow-cylinder::after {
content: "";
position: absolute;
width: 100%;
height: 100%;
background: linear-gradient(to top, rgba(0, 100, 255, 0), rgba(0, 100, 255, 0.5) 50%, rgba(0, 100, 255, 0.2));
filter: blur(20px);
opacity: 0.6;
animation: glowAnimation 1s infinite alternate;
}


@keyframes glowAnimation {
0% {
opacity: 0.1;
transform: translateY(0);
}
100% {
opacity: 1;
transform: translateY(-15px);
}
}

</style>
</head>
<body>
<div class=" menu5" id="menu7">
<div class="profile-card">
<? include 'card_users.php';?>
</div>
</div>
</div>
</div>
<? include 'navigation_bar.php';?>
<hr>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && isset($_POST['model_path'])) {
    $modelPath = $_POST['model_path'];
    $id3dmodels = $_POST['id'];
    $size=$_POST['filesize'];
    $format=$_POST['fileformat'];
    $filedate=$_POST['filedate'];
    $fileproject=$_POST['fileproject'];
    $filename=$_POST['filename'];
    setlocale(LC_TIME, 'ru_RU.UTF-8');
    $timestamp = strtotime($filedate);
    $megabytes = round($size / 1024 / 1024, 2);
    $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
 
?>
<hr>
<p class="text-justify category-p">Модель </p>
<hr>
<div class="d-flex flex-wrap model-wrapper " >
    <div class="card model-info-card">
        <div class="card-header text-white bg-primary">
            <h5 class="mb-0"><i class="bi bi-box"></i> Детали 3D модели</h5>
        </div>
        <ul class="list-group listmodel ">
            <li class="list-group-item-model d-flex justify-content-start align-items-center ">
                <i class="bi bi-file-earmark-text"></i>  <span>Имя файла:</span>
                 <span><?php echo $filenameWithoutExtension; ?></span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-diagram-3"></i>  <span>Связан с проектом №:</span>
                 <span><?php echo $fileproject;?></span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-calendar-event"></i>  <span>Дата:</span>
                 <span><?php echo translateEnglishToRussianMonths($filedate); ?></span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-hdd"></i>  <span>Размер:</span>
                 <span><?php echo strval($megabytes) . " MB"; ?></span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi-file-earmark-zip"></i>  <span>Формат файла:</span>
                 <span><?php echo strval($format); ?></span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-boxes"></i>  <span>Количество объектов:</span>
                 <span id="subObjectCountItem">0</span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-triangle"></i>  <span>Количество треугольников:</span>
                 <span id="triangleCountItem">0</span>
            </li>

            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi-record"></i>  <span>Количество точек:</span>
                 <span id="ngonCountItem">0</span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-camera"></i>  <span>Количество камер:</span>
                 <span id="cameraCountItem">0</span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-skip-forward-btn"></i>  <span>Анимация:</span>
                 <span id="animationCountItem">0</span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-lightbulb"></i>  <span>Количество источников света:</span>
                 <span id="lightCountItem">0</span>
            </li>
            <li class="list-group-item-model d-flex justify-content-start align-items-center">
                <i class="bi bi-tags"></i>  <span>Теги:</span>
                 <span>
<?php
$modelstag = R::load('models', $id3dmodels);
if ($modelstag->id) {
    $hashTagConnections = R::findAll('hashtagtomodels', 'typeid = ?', [$id3dmodels]);
    $hashTags = [];
    
    if (!empty($hashTagConnections)) {
        echo '<form action="search_result.php" method="POST">
            <input type="hidden" name="tag_type" value="models">
            <input type="hidden" id="secondClassInput15" name="secondClass" value="fa-cube">';

        foreach ($hashTagConnections as $connection) {
            $hashTag = R::load('hashtags', $connection->hashtagfromheshtag);
            if ($hashTag->id) {

                $hashTags[] = '<button type="submit" class="btn btn-primary" name="tagInput" value="#' . htmlspecialchars($hashTag->name) . '">' . htmlspecialchars($hashTag->name) . '</button>';
            }
        }
        echo implode(' ', $hashTags);
        echo '</form>';
    } else {
        echo '<span class="tag">Нет тегов</span>';
    }
} else {
    echo 'Модель не найдена';
}
?>


                </span>
            </li>
        </ul>

    </div>

    <div id="d-model-container" class="card model-view-card">
    <div class="viewer-container">
        <div class="glow-platform shadow"></div>
        <div class="glow-cylinder"></div>
    </div>
    </div>
   
</div>
<div id="d-model-control"></div>
</div>
  
  
<script type="importmap">
    {
        "imports": {
        "three": "../build/three.module.js",
        "three/addons/": "./jsm/"
        }
    }
</script>
    <script type="module">
    import * as THREE from 'three';
    import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
    import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
    import { FBXLoader } from 'three/addons/loaders/FBXLoader.js';
    import { OBJLoader } from 'three/addons/loaders/OBJLoader.js';
    import { STLLoader } from 'three/addons/loaders/STLLoader.js';
    let model = null;
    let isRotating = false;
    let modelGroup = new THREE.Group(); 
    const container = document.getElementById('d-model-container');
    const container_control = document.getElementById('d-model-control');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, container.offsetWidth / container.offsetHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(container.offsetWidth, container.offsetHeight);
    container.appendChild(renderer.domElement);
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
    scene.add(ambientLight);
    const light1 = new THREE.DirectionalLight(0xffffff, 1);
    light1.position.set(1, 1, 1);
    const light2 = new THREE.DirectionalLight(0xffffff, 1);
    light2.position.set(10, 10, 10);
    scene.add(light2);
    scene.add(light1);
    camera.position.set(0, 0, 3);
    const controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.dampingFactor = 0.25;
    controls.screenSpacePanning = false;
    controls.minDistance = 1;
    controls.maxDistance = 50;
    controls.maxPolarAngle = Math.PI / 2;
    let modelPath = '<?php echo $modelPath; ?>';
    let loader;
    if (modelPath.endsWith('.fbx')) {
        const emptyTexture = new THREE.Texture();
        emptyTexture.needsUpdate = true;
        const loadingManager = new THREE.LoadingManager();
        loadingManager.setURLModifier((url) => {
            if (url.endsWith('.png') || url.endsWith('.jpg') || url.endsWith('.jpeg') || url.endsWith('.tif') || url.endsWith('.dds')) {
                return ''; 
            }
            return url;
        });
        loader = new FBXLoader(loadingManager);
    } else if (modelPath.endsWith('.obj')) {
        loader = new OBJLoader();
    } else if (modelPath.endsWith('.stl')) {
        loader = new STLLoader();
    } 
    else if (modelPath.endsWith('.zip')) {
        console.log(modelPath);
        const response = await fetch(modelPath);
        const zipData = await response.arrayBuffer();
        const zip = await JSZip.loadAsync(zipData);
        const fileUrls = {};
        await Promise.all(Object.keys(zip.files).map(async (filename) => {
            const file = zip.files[filename];
            if (!file.dir) {
                const blob = await file.async('blob');
                fileUrls[filename] = URL.createObjectURL(blob);
            }
        }));
        const gltfFileName = Object.keys(fileUrls).find(name => name.endsWith('.gltf'));
        const loadingManager = new THREE.LoadingManager();
        loadingManager.setURLModifier((path) => {
            const parsedUrl = new URL(path);
            const relativeUrl = parsedUrl.pathname.replace(parsedUrl.origin + '/', "");
            return fileUrls[relativeUrl] || path;
        });
        modelPath = fileUrls[gltfFileName];
        console.log(modelPath);
        loader = new GLTFLoader(loadingManager);
    }
    else {
        loader = new GLTFLoader();
    }
    const originalConsoleWarn = console.warn;
    console.warn = function () {};
    let mixer;
    loader.load(modelPath, function(loadedModel) {
    let model;
        if (modelPath.endsWith('.stl')) {
            const material = new THREE.MeshStandardMaterial({ color: 0x808080 , roughness: 0.75, side: THREE.DoubleSide });
            model = new THREE.Mesh(loadedModel, material);
    } else if (modelPath.endsWith('.glb') || modelPath.startsWith('blob:')  ) {
            model = loadedModel.scene || loadedModel;
        if (loadedModel.animations && loadedModel.animations.length > 0) {
            mixer = new THREE.AnimationMixer(model);
            loadedModel.animations.forEach((clip) => {
                mixer.clipAction(clip).play();
            });
        }
    } else {
            model = loadedModel.scene || loadedModel;
        if (loadedModel.animations && loadedModel.animations.length > 0) {
            mixer = new THREE.AnimationMixer(model);
            loadedModel.animations.forEach((clip) => {
                mixer.clipAction(clip).play();
            });
        }
        const material = new THREE.MeshStandardMaterial({ color: new THREE.Color("rgb(253, 5, 5)"), roughness: 0.75, side: THREE.DoubleSide });
        model.traverse((child) => {
            if (child.isMesh) {
                child.material = material;
            }
        });
    }
    const boundingBox = new THREE.Box3().setFromObject(model);
    const size = new THREE.Vector3();
    boundingBox.getSize(size);
    const center = new THREE.Vector3();
    boundingBox.getCenter(center);
    const maxDim = Math.max(size.x, size.y, size.z);
    const desiredSize = 2; 
    const scale = desiredSize / maxDim;
    model.scale.set(scale, scale, scale);
    const moveSpeed = 0.2;
    function moveModel(direction) {
    switch (direction) {
        case 'forward':
            modelGroup.position.z -= moveSpeed;
            break;
        case 'backward':
            modelGroup.position.z += moveSpeed;
            break;
        case 'left':
            modelGroup.position.x -= moveSpeed;
            break;
        case 'right':
            modelGroup.position.x += moveSpeed;
            break;
        case 'up':
            modelGroup.position.y += moveSpeed;
            break;
        case 'down':
            modelGroup.position.y -= moveSpeed;
            break;
        case '[':
            changeLightIntensity(-0.1); 
            break;
        case ']':
            changeLightIntensity(0.1);
            break;
    }
}

const originalMaterials = new Map();

function changeMaterial(type) {
    modelGroup.traverse((child) => {
        if (child.isMesh) {
            if (originalMaterials.has(child)) {
                child.material = originalMaterials.get(child);
                originalMaterials.delete(child);
            } else {
                originalMaterials.set(child, child.material);

                switch (type) {
                    case 'basic':
                        child.material = new THREE.MeshBasicMaterial({ color: new THREE.Color(Math.random(), Math.random(), Math.random()) });
                        break;
                    case 'lambert':
                        child.material = new THREE.MeshLambertMaterial({ 
                            color: new THREE.Color(Math.random(), Math.random(), Math.random()),
                            emissive: 0x111111,  
                         });
                        break;
                    case 'phong':
                        child.material = new THREE.MeshPhongMaterial({ 
                            color: 0xff0000,   
                            shininess: 100,    
                            specular: 0xffffff
                        });
                        break;
                    case 'metal':
                        child.material = new THREE.MeshStandardMaterial({ 
                            color: new THREE.Color(Math.random(), Math.random(), Math.random()),
                            metalness: 1,
                            roughness: 0.3 
                        });
                        break;
                    case 'transparent':
                        child.material = new THREE.MeshPhysicalMaterial({
                            color: child.material.color,
                            transmission: 1,
                            transparent: true,
                            opacity: 0.3,
                            roughness: 0.05,
                            metalness: 0.1,
                            depthWrite: false,
                            ior: 1.5,
                        });
                        break;
                    case 'wireframe':
                        child.material = new THREE.MeshBasicMaterial({ color: child.material.color, wireframe: true });
                        break;
                }
            }
        }
    });
}
function changeLightIntensity(amount) {
    ambientLight.intensity = Math.max(0, ambientLight.intensity + amount);
    light1.intensity = Math.max(0, light1.intensity + amount);
}
function scaleModel(scaleFactor) {
    modelGroup.scale.multiplyScalar(scaleFactor);
}

document.addEventListener('keydown', (event) => {
    switch (event.key) {
        case 'ArrowUp':
            moveModel('forward');
            break;
        case 'ArrowDown':
            moveModel('backward');
            break;
        case 'ArrowLeft':
            moveModel('left');
            break;
        case 'ArrowRight':
            moveModel('right');
            break;
        case 'PageUp':
            moveModel('up');
            break;
        case 'PageDown':
            moveModel('down');
            break;
        case '+':
            scaleModel(1.1);
            break;
        case '-':
            scaleModel(0.9);
            break;
    }
});

const controlsContainer = document.createElement('div');
controlsContainer.style.bottom = '10px';
controlsContainer.style.left = '50%';
controlsContainer.style.display = 'flex';
controlsContainer.style.flexWrap = 'wrap';
controlsContainer.style.height = '0%';
controlsContainer.style.gap = '10px';
controlsContainer.style.zIndex = '10'; 
controlsContainer.style.background = 'rgba(0, 0, 0, 0.5)';
controlsContainer.style.padding = '10px';
controlsContainer.style.borderRadius = '10px';
controlsContainer.style.width = '100%';
controlsContainer.style.justifyContent = 'center';
const lightButtons = [
    { label: '☀️+', action: 'increaseLight' },
    { label: '☀️-', action: 'decreaseLight' },
];
lightButtons.forEach(({ label, action }) => {
    const btn = document.createElement('button');
    btn.textContent = label;
    btn.style.padding = '10px 15px';
    btn.style.fontSize = '20px';
    btn.style.cursor = 'pointer';
    btn.style.background = '#ffffff';
    btn.style.border = 'none';
    btn.style.borderRadius = '5px';
    btn.style.opacity = '0.8';
    btn.onclick = () => {
        if (action === 'increaseLight') {
            changeLightIntensity(0.1);
        } else if (action === 'decreaseLight') {
            changeLightIntensity(-0.1);
        }
    };
    controlsContainer.appendChild(btn);
});
const buttons = [
    { label: '⇑', action: 'forward' },
    { label: '⇓', action: 'backward' },
    { label: '⬅', action: 'left' },
    { label: '➡', action: 'right' },
    { label: '⬆', action: 'up' },
    { label: '⬇', action: 'down' },
    { label: '+', action: 'scaleUp' },
    { label: '-', action: 'scaleDown' },
    
];
buttons.forEach(({ label, action }) => {
    const btn = document.createElement('button');
    btn.textContent = label;
    btn.style.padding = '10px 15px';
    btn.style.fontSize = '20px';
    btn.style.cursor = 'pointer';
    btn.style.background = '#ffffff';
    btn.style.border = 'none';
    btn.style.borderRadius = '5px';
    btn.style.opacity = '0.8';
    btn.onclick = () => {
        if (action === 'scaleUp') {
            scaleModel(1.1);
        } else if (action === 'scaleDown') {
            scaleModel(0.9);
        } else {
            moveModel(action);
        }
    };
    controlsContainer.appendChild(btn);
});
container_control.appendChild(controlsContainer);
model.position.sub(center.clone().multiplyScalar(scale));
let subObjectCount = 0;
let cameraCount = 0;
let totalTriangles = 0;
let totalfaces  = 0;
let totalVertices = new Set(); 
let lightCount = 0;
let hasAnimation = false;
if (modelPath.endsWith('.stl')){
    hasAnimation = false;
}
else{
     hasAnimation = loadedModel.animations.length > 0;
}
const countPolygons = (mesh) => {
    const geometry = mesh.geometry;
    if (!geometry) return; 
    if (geometry.index) {
        totalTriangles += geometry.index.count / 3;
        totalfaces  += geometry.index.count / 2; 
        for (let i = 0; i < geometry.index.count; i++) {
            const vertexIndex = geometry.index.array[i];
            const vertex = geometry.attributes.position.array.slice(vertexIndex * 3, (vertexIndex + 1) * 3);
            totalVertices.add(vertex.join(',')); 
        }
    } else if (geometry.attributes && geometry.attributes.position) {
        totalTriangles += geometry.attributes.position.count / 3;
        totalfaces  += totalTriangles * 1.5; 
        for (let i = 0; i < geometry.attributes.position.count; i++) {
            const vertex = geometry.attributes.position.array.slice(i * 3, (i + 1) * 3);
            totalVertices.add(vertex.join(',')); 
        }
    }
};
model.traverse((child) => {
    if (child.isMesh) {
        countPolygons(child);
        subObjectCount++;
    }
    if (child.isCamera) cameraCount++;
    if (child.isLight) lightCount++;
});

document.getElementById('triangleCountItem').textContent = `${totalTriangles}`;
document.getElementById('ngonCountItem').textContent = `${totalVertices.size}`; 
document.getElementById('cameraCountItem').textContent = ` ${cameraCount}`;
document.getElementById('lightCountItem').textContent = ` ${lightCount}`;
document.getElementById('subObjectCountItem').textContent = ` ${subObjectCount}`;
document.getElementById('animationCountItem').textContent = hasAnimation ? 'Да' : 'Нет';
const aspect = container.offsetWidth / container.offsetHeight;
camera.aspect = aspect;
camera.updateProjectionMatrix();
window.model = model;
modelGroup.add(model);
scene.add(modelGroup);
const rotateBtn = document.createElement('button');
rotateBtn.textContent = '⟳';
rotateBtn.style.padding = '10px 15px';
rotateBtn.style.fontSize = '16px';
rotateBtn.style.cursor = 'pointer';
rotateBtn.style.background = '#ffffff';
rotateBtn.style.border = 'none';
rotateBtn.style.borderRadius = '5px';
rotateBtn.style.opacity = '0.8';

rotateBtn.onclick = () => {
    isRotating = !isRotating;
};

controlsContainer.appendChild(rotateBtn);
let secondWireframeMesh = null;
let isSecondWireframeActive = false;

function toggleSecondWireframe() {
    if (!secondWireframeMesh) {
        model.traverse((child) => {
            if (child.isMesh) {
                const wireframeGeometry = new THREE.WireframeGeometry(child.geometry);
                const wireframeMaterial = new THREE.LineBasicMaterial({ color: 0xffffff, linewidth: 1 });
                const wireframe = new THREE.LineSegments(wireframeGeometry, wireframeMaterial);
               
                child.add(wireframe);
            }
        });
        secondWireframeMesh = model;
    }
    isSecondWireframeActive = !isSecondWireframeActive;
    model.traverse((child) => {
        if (child.isMesh) {
            child.children.forEach(wf => wf.visible = isSecondWireframeActive);
        }
    });
}

const secondWireframeBtn = document.createElement('button');
secondWireframeBtn.textContent = '⬛';
secondWireframeBtn.style.padding = '10px 15px';
secondWireframeBtn.style.fontSize = '16px';
secondWireframeBtn.style.cursor = 'pointer';
secondWireframeBtn.style.background = '#ffffff';
secondWireframeBtn.style.border = 'none';
secondWireframeBtn.style.borderRadius = '5px';
secondWireframeBtn.style.opacity = '0.8';

secondWireframeBtn.onclick = toggleSecondWireframe;
const materialButtons = [
    { label: '🎨', action: () => changeMaterial('basic') },
    { label: '🧻', action: () => changeMaterial('lambert') },
    { label: '💎', action: () => changeMaterial('phong') },
    { label: '🔩', action: () => changeMaterial('metal') },
    { label: '🧊', action: () => changeMaterial('transparent') },
    { label: '🌐', action: () => changeMaterial('wireframe') },
];

materialButtons.forEach(({ label, action }) => {
    const btn = document.createElement('button');
    btn.textContent = label;
    btn.style.padding = '10px 15px';
    btn.style.fontSize = '20px';
    btn.style.cursor = 'pointer';
    btn.style.background = '#ffffff';
    btn.style.border = 'none';
    btn.style.borderRadius = '5px';
    btn.style.opacity = '0.8';
    btn.onclick = action;
    controlsContainer.appendChild(btn);
});
controlsContainer.appendChild(secondWireframeBtn);


const wireframeBtn = document.createElement('button');
wireframeBtn.textContent = '⬜';
wireframeBtn.style.padding = '10px 15px';
wireframeBtn.style.fontSize = '16px';
wireframeBtn.style.cursor = 'pointer';
wireframeBtn.style.background = '#ffffff';
wireframeBtn.style.border = 'none';
wireframeBtn.style.borderRadius = '5px';
wireframeBtn.style.opacity = '0.8';

let isWireframe = false; 


const pauseButton = document.createElement('button');
pauseButton.textContent = '▶️';
pauseButton.style.padding = '10px 15px';
pauseButton.style.fontSize = '20px';
pauseButton.style.cursor = 'pointer';
pauseButton.style.background = '#ffffff';
pauseButton.style.border = 'none';
pauseButton.style.borderRadius = '5px';
pauseButton.style.opacity = '0.8';
pauseButton.onclick = () => {
    togglePause();
    pauseButton.textContent = isPaused ? '▶️ ' : '⏸ ';
};
function rotateModel(axis) {
    if (model) {
        model.rotation[axis] += THREE.MathUtils.degToRad(90);
    }
}

const rotateXButton = document.createElement('button');
rotateXButton.textContent = '↕️ Повернуть по X';
rotateXButton.style.padding = '10px 15px';
rotateXButton.style.fontSize = '20px';
rotateXButton.style.cursor = 'pointer';
rotateXButton.style.background = '#ffffff';
rotateXButton.style.border = 'none';
rotateXButton.style.borderRadius = '5px';
rotateXButton.style.opacity = '0.8';
rotateXButton.onclick = () => rotateModel('x', 90);

const rotateYButton = document.createElement('button');
rotateYButton.textContent = '↔️ Повернуть по Y';
rotateYButton.style.padding = '10px 15px';
rotateYButton.style.fontSize = '20px';
rotateYButton.style.cursor = 'pointer';
rotateYButton.style.background = '#ffffff';
rotateYButton.style.border = 'none';
rotateYButton.style.borderRadius = '5px';
rotateYButton.style.opacity = '0.8';
rotateYButton.onclick = () => rotateModel('y', 90);

wireframeBtn.onclick = () => {
    isWireframe = !isWireframe;
    model.traverse((child) => {
        if (child.isMesh) {
            child.material.wireframe = isWireframe;
        }
    });
};

controlsContainer.appendChild(wireframeBtn);
controlsContainer.appendChild(pauseButton);
controlsContainer.appendChild(rotateXButton);
controlsContainer.appendChild(rotateYButton);

    }, undefined, function(error) {
        console.error('Ошибка загрузки модели:', error);
    });
    let isPaused = true;



    function togglePause() {
    if (mixer) {
        if (isPaused) {
            mixer.timeScale = 1; 
        } else {
            mixer.timeScale = 0; 
        }
        isPaused = !isPaused;
    }
}
function animate() {
    requestAnimationFrame(animate);
    if (!isPaused) {
        if (mixer) {
            mixer.update(0.01);
        }
        controls.update();
    }
    if (isRotating && modelGroup.children.length > 0) {
        modelGroup.rotation.y += 0.01;
    }
    renderer.render(scene, camera);
}

    animate();
    
    </script>

    
<?php
} else {
    header("Location: login.php");
}
?>
</body>
<footer>
<img src="Gazprom-Logo-rus.svg.png" alt="Газпром" class="logo">
</footer>
</html>
