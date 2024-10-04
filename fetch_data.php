<?php
require 'CRUD.php';

header('Content-Type: application/json');

$crud = new CRUD();
$data = $crud->fetchAllProducts();

echo json_encode($data);
