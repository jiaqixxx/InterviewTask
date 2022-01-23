<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../vendor/autoload.php';

use InterviewTask\Services\CsvImportSvc;

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'upload_csv') {
        if (!$file = $_FILES['file']) {
            echo json_encode([
                'success' => false,
                'message' => 'File Missing'
            ]);
            die;
        }
        $csvImportSvc = new CsvImportSvc($file);
        echo json_encode([
            'success' => true,
            'data' => $csvImportSvc->getCollection()
        ]);
        die;   
    }
}