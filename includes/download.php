<?php
error_reporting(E_ALL);
if (isset($_GET['workFolder'])) {
    $workFolder = $_GET['workFolder'];

    $file = "../" . $workFolder . "config.txt";

    // Quick check to verify that the file exists
    if( !file_exists($file) ) die("File not found" . $file);
        // Force the download
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        header('Content-Type: application/octet-stream;');
        readfile($file);

}