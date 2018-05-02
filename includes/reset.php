<?php

$reset = $_POST['reset'];
$workFolder = $_POST['workFolder'];
$config_file = '../' . $workFolder . "config.txt";

if ($reset == 1) {
    exec("rm " . $config_file);
}