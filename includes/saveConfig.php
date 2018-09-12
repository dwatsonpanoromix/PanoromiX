<?php

$delimiter = "\t";
$workFolder = $_POST['workFolder'];
$externalApp = $_POST['externalApp'];
$configData = stripcslashes($_POST['pconfigData']);
$relative_path = "../{$workFolder}config.txt";
$configData = json_decode($configData, true);

$header = [
    "rootId", "rootX", "rootY", "timepoint",
    "charge", "gravity", "borderW", "linkWidth",
    "linkColor", "maxScore", "minScore", "radius",
    "fontColorNode", "fontColorCenter", "saved", "key",
    "colorLabel", "sizeLabel", "typeLabel", "tpLabels",
    "typeColors", "fillColors", "discreteFillColors", "bgFlg", "background"
];

$fp = fopen($relative_path, "w");

if ( ! $fp)
{
    return false;
}

fputcsv($fp, $header, $delimiter);

foreach ($configData as $row)
{
    fputcsv($fp, $row, $delimiter);
}

fclose($fp);
chmod($relative_path, 0777);

return true;