<?php
require_once __DIR__."/rb.php";
R::setup('mysql:host=127.0.0.1;dbname=Base_material;charset=utf8mb4', 'root', '');

if (!R::testConnection()) {
    exit('Нет соединения с базой данных');
}
session_start();
?>