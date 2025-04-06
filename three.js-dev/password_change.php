<?php
session_start();
require_once __DIR__."/rb/rb.php";

R::setup('mysql:host=127.0.0.1;dbname=Base_material;charset=utf8mb4', 'root', '');

$url = $_SERVER['REQUEST_URI'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $password = $_POST["password"];
        $password2 = $_POST["password2"];
        if ($password == $password2) {
            $token = $_GET['token']; 
            if (preg_match('/-(\d+)-/', $token, $matches)) {
                $userId = $matches[1];
                $user = R::findOne('users', 'id = ?', [$userId]);
                if ($user) {
                    $user->password = password_hash($password, PASSWORD_DEFAULT);
                    $user->recovery_token = null;
                    R::store($user);
                    header("Location: login.php");
                    exit;
                } else {
                    echo '<script>alert("Пользователь не найден.");</script>';
                }
            } else {
                echo '<script>alert("Неверный формат токена.");</script>';
            }
        } else {
            echo '<script>alert("Пароли не совпадают.");</script>';
        }
    } catch (Exception $e) {
        die('Ошибка при выполнении запроса: ' . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="examples/css/style_login.css" rel="stylesheet">
    <title>Восстановление</title>
</head>
<body>
<div class="container" >
  <div class="d-flex md-12">
    <div class="row justify-content-center src_svg">
    <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" version="1.0" width="100%" height="auto" viewBox="0 0 694.34644 341.18503" id="svg2041">
    <defs>
          <linearGradient id="linearGradient2">
            <stop style="stop-color:#006fcb;stop-opacity:1;" offset="0"/>
            <stop style="stop-color:#006fcb;stop-opacity:0;" offset="1"/>
          </linearGradient>
          <linearGradient id="swatch1">
            <stop style="stop-color:#006fcb;stop-opacity:1;" offset="0"/>
          </linearGradient>
        </defs>
        <g transform="translate(-8.479224,-404.6258)" id="layer1" style="fill:#ffffff;fill-opacity:0;stroke:url(#linearGradient1)">
    <g transform="translate(7.081015,7.088444)" id="g2058" style="fill:#ffffff;fill-opacity:0;stroke:url(#linearGradient12)">
      <path d="m 218.56621,618.42453 h 43.16799 v 14.10478 h -20.53539 v 99.10464 h -22.6326 V 618.42453" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient3)" id="path26"/>
      <path d="m 287.32698,689.13401 3.8788,-56.6047 h 1.29915 l 3.8788,56.6047 z m -25.06378,42.49994 h 22.1593 l 2.09721,-30.06546 h 10.67136 l 2.10642,30.06546 h 22.15008 L 309.64411,618.42453 H 274.06667 L 262.2632,731.63395" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient4)" id="path28"/>
      <path d="m 353.86075,692.01065 v 21.34274 c 0,2.87664 2.22706,3.71182 3.52613,3.71182 2.13434,0 3.61902,-1.7631 3.61902,-3.71182 v -26.35364 c 0,-3.52621 -0.64957,-7.42357 -9.09387,-7.42357 h -9.18669 V 667.1417 h 9.55784 c 6.40285,0 8.72272,-1.48476 8.72272,-8.4443 v -22.45634 c 0,-1.94865 -1.48468,-3.71175 -3.61902,-3.71175 -1.29907,0 -3.52613,0.74238 -3.52613,3.71175 v 18.46617 h -22.64189 v -16.05349 c 0,-7.88754 0.92792,-20.22921 16.7958,-20.22921 h 18.74452 c 15.86788,0 16.88861,12.34167 16.88861,20.22921 v 18.09494 c 0,12.34167 -8.62992,15.68227 -15.40392,15.40392 v 1.11353 c 15.2183,-0.37115 15.40392,11.32094 15.40392,15.31112 v 22.82749 c 0,7.98035 -1.02073,20.22921 -16.88861,20.22921 h -18.74452 c -15.86788,0 -16.7958,-12.24886 -16.7958,-20.22921 v -19.39409 h 22.64189" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient5)" id="path30"/>
      <path d="m 398.49494,618.42453 h 52.42892 v 113.20942 h -22.64188 v -99.10464 h -7.14516 v 99.10464 H 398.49494 V 618.42453" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient6)" id="path32"/>
      <path d="m 488.41289,680.68971 v -48.1604 h 2.96945 c 2.4126,0 4.17571,2.04145 4.17571,5.66047 v 36.83946 c 0,3.61902 -1.76311,5.66047 -4.17571,5.66047 z m -22.64187,50.94424 h 22.64187 v -38.50977 h 12.99124 c 15.77508,0 16.7958,-12.24886 16.7958,-20.2292 v -34.24124 c 0,-7.88754 -1.02072,-20.22921 -16.7958,-20.22921 h -35.63311 v 113.20942" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient7)" id="path34"/>
      <path d="m 562.83413,713.35339 c 0,1.94872 -1.48469,3.71182 -3.61895,3.71182 -1.29914,0 -3.52621,-0.83518 -3.52621,-3.71182 v -77.11233 c 0,-2.96937 2.22707,-3.71175 3.52621,-3.71175 2.13426,0 3.61895,1.7631 3.61895,3.71175 z m -29.78704,-1.94865 c 0,7.98035 1.02072,20.22921 16.7958,20.22921 h 18.83732 c 15.77507,0 16.7958,-12.24886 16.7958,-20.22921 v -72.751 c 0,-7.88754 -1.02073,-20.22921 -16.7958,-20.22921 h -18.83732 c -15.77508,0 -16.7958,12.34167 -16.7958,20.22921 v 72.751" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient8)" id="path36"/>
      <path d="m 659.15494,618.42453 h 29.50869 v 113.20942 h -22.64188 v -70.9879 h -0.83519 l -11.6921,70.9879 h -18.09494 l -11.59929,-70.9879 h -0.83518 v 70.9879 H 600.32316 V 618.42453 h 29.41589 l 14.75435,79.61775 14.66154,-79.61775" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient9)" id="path38"/>
      <path d="m 189.22457,463.6431 c -6.93178,-30.3438 -24.3029,-55.02714 -26.69702,-59.01731 -3.78607,5.66047 -17.64027,27.46717 -24.68341,51.59381 -7.69264,27.00321 -8.82474,50.94424 -6.17083,74.42123 2.63541,23.5698 12.59224,47.78925 12.59224,47.78925 5.28932,12.62002 13.21398,26.26083 18.37337,32.94203 7.56271,-9.83618 24.94319,-39.15926 30.36237,-77.39068 3.01584,-21.34274 3.1457,-39.99445 -3.77672,-70.33833 z m -26.69702,140.11989 c -3.40556,-6.40285 -8.69488,-18.5589 -9.19596,-37.48903 -0.12993,-18.18776 7.173,-33.87002 9.30733,-37.11781 1.90224,3.24779 8.19372,16.8886 8.94538,35.54031 0.51037,18.18776 -5.53982,32.57087 -9.05675,39.06653 z m 24.68334,-88.61888 c -0.25979,11.59936 -1.64246,23.84822 -3.40557,31.17899 0.63101,-12.62002 -0.88151,-30.34381 -3.77671,-44.26297 -2.8952,-13.82643 -11.089,-37.02507 -17.63099,-47.69644 -6.0409,10.20741 -13.48304,30.251 -17.38041,47.60364 -3.91592,17.35256 -4.02729,38.41695 -4.02729,44.727 -1.03929,-5.28932 -3.63758,-24.31219 -2.90448,-43.33513 0.61245,-15.68226 4.28708,-31.92129 6.30076,-39.34487 7.68336,-24.77614 16.37824,-40.64402 18.01142,-43.14951 1.63318,2.50549 12.58297,22.08512 18.262,42.59274 5.65119,20.50763 6.79256,40.18006 6.55127,51.68655" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient10)" id="path40"/>
      <path d="m 174.28461,618.42453 h -52.69806 v 35.35471 c 0.0464,0 0.0835,-0.0927 0.12993,-0.0927 12.41591,-12.43447 32.55231,-12.43447 44.9775,0 12.41591,12.34167 12.41591,32.47807 0,44.91254 -0.0556,0.0928 -0.11129,0.0928 -0.16698,0.18562 0,0 0,0 0,0 -0.065,0 -0.12993,0.0928 -0.18561,0.18561 -12.38807,12.24886 -28.57142,18.37329 -44.75484,18.37329 -16.2576,0 -32.515186,-6.12443 -44.912536,-18.5589 -21.86246,-21.80678 -24.45139,-55.67679 -7.78552,-80.36013 2.24562,-3.3406 4.83463,-6.49566 7.78552,-9.46503 12.39735,-12.43447 28.654936,-18.55898 44.912536,-18.55898 v -84.99979 c -62.469276,0 -113.1073257,50.57301 -113.1073257,113.0238 0,62.45079 50.6380497,113.11661 113.1073257,113.11661 32.57094,0 61.91258,-13.82643 82.55006,-35.81874 v -77.29787 h -29.852" style="fill:#ffffff;fill-opacity:0;fill-rule:nonzero;stroke:url(#linearGradient11)" id="path42"/>
    </g>
  </g>
</svg>
</div>
    <div class="container rounded-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <H1 style="color:white;">Восстановление:</H1>
                <form action="password_change.php?token=<?php echo $_GET['token']; ?>" method="post">
                    <div class="form-group text-center">
                        <label for="password">Пароль:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" required>
                    </div>
                    <div class="form-group text-center">
                        <label for="password">Повторите пароль:</label>
                        <input type="password" class="form-control" name="password2" id="password" placeholder="Введите пароль" required>
                          
                    </div>
                    <button name="change_password" class="btn btn-primary logout_btn " >Изменить</button>
                   <div class="col-md-12 text-center">
                    <a href="login.php" class="btn btn-link  text-center ">Забыли пароль?</a>
                    <a href="index.php" class="btn btn-link  text-center ">Регистрация</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
