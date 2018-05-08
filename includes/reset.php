<?php

$reset = $_POST['reset'];
$workFolder = $_POST['workFolder'];
$config_file = "../{$workFolder}config.txt";

if ($reset == 1)
{
    shell_exec("rm " . $config_file);
    echo true;
}

echo false;