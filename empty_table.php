<?php
require 'CRUD.php';

$crud = new CRUD();
$result = $crud->emptyTable();

echo json_encode(['success' => $result]);
