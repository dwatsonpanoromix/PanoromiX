<?php
/**
 * Die & Dump Data.
 *
 * @param $data
 */
function dd($data)
{
    echo '<pre>';
    die(var_dump($data));
    echo '</pre>';
}

// Un-escape the string values in the JSON array
$configData = stripcslashes($_POST['pconfigData']);
$workFolder = $_POST['workFolder'];
$externalApp = $_POST['externalApp'];

if ($externalApp !== "") {
    $relative_path = "{$_SERVER['DOCUMENT_ROOT']}/panoromics/{$workFolder}nodes.txt";
} else {
    $relative_path = '../' . $workFolder . "config.txt";
}

//dd($relative_path);
//dd($_SERVER['DOCUMENT_ROOT']);

// Decode the JSON array
$configData = json_decode($configData,TRUE);

$header = array("rootId", "rootX", "rootY", "timepoint", "charge", "gravity", "borderW", "linkWidth", "linkColor", "maxScore", "minScore", "radius", "fontColorNode", "fontColorCenter", "saved", "key", "colorLabel", "sizeLabel", "typeLabel", "tpLabels", "typeColors", "fillColors", "discreteFillColors", "bgFlg", "background");
$delimiter = "\t";

$fp = fopen( $relative_path, "w");
fputcsv($fp, $header, $delimiter);
foreach($configData as $row){
    fputcsv($fp, $row, $delimiter);
}
fclose($fp);