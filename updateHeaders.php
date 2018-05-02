<?php

function getHeaders(&$headerArray, &$required, &$newHeaders, $requiredLabels) {
    foreach($requiredLabels as $reqKey => $label){
        $key = array_search($label, $headerArray);
        $required[$key] = $reqKey;
        array_push($newHeaders, $headerArray[$key]);
        unset($headerArray[$key]);
    }
}

function sortData(&$data, $required) {
    $newData = array();
    foreach($required as $position => $labelIndex) {
        $newData[$labelIndex] = $data[$position];
        unset($data[$position]);
    }
    $newData = array_merge($newData, $data);
    return $newData;
}

function writeFile($workFolder, $readFile, $writeFile, $newHeaders, $delimiter, $required) {
    rename($workFolder . $writeFile, $workFolder . $readFile);

    $handle = fopen($workFolder . $readFile, 'r') or die('cannot read file');
    $writeFile = fopen($workFolder . $writeFile, 'w') or die('cannot read file');

    $rowCount = 1;
    while (false != ( $row = fgetcsv($handle, 0, $delimiter)) ) {
        if ($rowCount == 1) {
            fputcsv ($writeFile, $newHeaders, $delimiter);
            $rowCount++;
        } else {
            $row = sortData($row, $required);
            // BEGIN data typecheck/typecast testing
            foreach($row as $key => $data) {
                //echo $data . " before cast: " . gettype($data) . "\n";
                $data = (string)$data;
                //echo $data . " after cast: " . gettype($data) . "\n";
                if (is_numeric($data)) {
                    $data = round($data, 2);
                    //echo $data . " after round: " . gettype($data) . "\n";
                }
            }
            // END data typecheck/typecast testing
            fputcsv ($writeFile, $row, $delimiter);
            $rowCount++;
        }
    }

    fclose($writeFile);
    fclose($handle);
}

$delimiter = "\t";
$newHeaders = array();
$required = array();
$headers = json_decode(stripslashes($_POST['data']));
$requiredLabels = array("id", "name", "group");
$workFolder = json_decode($_POST['workFolder']);

getHeaders($headers, $required, $newHeaders, $requiredLabels);
$newHeaders = array_merge($newHeaders, $headers);

writeFile($workFolder, "custom-nodes.txt", "tempnodes.txt", $newHeaders, $delimiter, $required);

if ($_POST['links']) {
    $newHeaders = array();
    $required = array();
    $headers = json_decode(stripslashes($_POST['links']));
    $requiredLabels = array("sourceId", "targetId");

    getHeaders($headers, $required, $newHeaders, $requiredLabels);
    $newHeaders = array_merge($newHeaders, $headers);

    writeFile($workFolder, "custom-links.txt", "templinks.txt", $newHeaders, $delimiter, $required);
}

?>