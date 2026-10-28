<?php 
require_once '../classes/JwksExporter.php';
header('Content-Type: application/json');
echo json_encode(JwksExporter::export(),JSON_PRETTY_PRINT);