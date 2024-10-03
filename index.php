<?php
require 'CRUD.php';
require 'vendor/autoload.php';
require 'src/FileHandler.php';

$crud = new CRUD();
$crud->createTable();

$fileHandler = new FileHandler();
$url = "https://www.alko.fi/INTERSHOP/static/WFS/Alko-OnlineShop-Site/-/Alko-OnlineShop/fi_FI/Alkon%20Hinnasto%20Tekstitiedostona/alkon-hinnasto-tekstitiedostona.xlsx";

$savePath = 'alkon_hinnasto.xlsx';

// Call the processExcelFile method
$fileHandler->processExcelFile($url, $savePath);
