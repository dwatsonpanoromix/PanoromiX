<?php

$workFolder = $_POST['workFolder'];
$config_file = "../{$workFolder}config.txt";
shell_exec("rm " . $config_file);
echo true;
