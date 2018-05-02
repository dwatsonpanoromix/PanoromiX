<?php

// Function to read the uploaded data files, remove invalid interactions and write the new data files to the work folder
function prepData($fileArray, $workfolder) {
    global $delimiter;
    $workFolder = $workfolder;
    global $timePoints;
    $rootLabels = array("root", "icon");
    $padArray = array("false", "none");
    $dataResult = 1;
    $orphanCount = 0;

    //---------------- SORT BY GROUP ---------------//

    $handle = fopen($fileArray[0], 'r') or die('cannot read file');
    $sortGroups = array();
    $rows = array();
    $rowCount = 1;

    //build array of groups, and array of rows
    while (false != ( $row = fgetcsv($handle, 0, $delimiter)) ) {
        if ($rowCount == 1) {
            $header = $row;
            $rowCount++;
        } else {
            //extract group from the third column
            $sortGroups[] = $row[2];
            $rows[] = $row;
            $rowCount++;
        }
    }

    fclose($handle);

    //sort array of rows by group using the array of groups
    //array_multisort($sortGroups, $rows);

    $sortNodesFile = fopen($workFolder . 'sortNodes.txt', 'w'); //open new nodes file
    fputcsv ($sortNodesFile, $header, $delimiter);
    foreach($rows as $row) {
        fputcsv($sortNodesFile, $row, $delimiter);
    }
    fclose($sortNodesFile);


    //------------------ END SORTING -------------//

    //read node IDs into an array
    if (($handle = fopen($workFolder . 'sortNodes.txt', "r")) !== FALSE) {
        $nodeFile = fopen($workFolder . 'nodes.txt', 'w'); //open new nodes file
        $row = 1;
        while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            if ($row == 1) {
                if ($data[0] !== "id" &&  $data[1] !== "name" && $data[2] !== "group") {
                    echo '<div class="alert alert-danger" role="alert">We\'re sorry, we found a data mismatch in your header column. Please correct your data and try again.</div>';
                    $dataResult = 0;
                }
                $data =  array_map('strtolower', $data);

                // set header data types
                for ($i = 0; $i < count($data); $i++){
                    $headerArrayTypes[$i]['LABEL'] = $data[$i];
                    if (strpos($data[$i], "size") === false && strpos($data[$i], "colo") === false) {
                        $headerArrayTypes[$i]['TYPE'] = "string";
                    } else {
                        $headerArrayTypes[$i]['TYPE'] = "integer";
                    }
                }

                //calculate number of time-points (types)
                $typeNum = substr_count ( implode(",", $data ), "type" );
                $sizeNum = substr_count ( implode(",", $data ), "size" );
                $colorNum = substr_count ( implode(",", $data ), "colo" );
                $groupNum = substr_count ( implode(",", $data ), "group" );
                $timePoints = max($typeNum, $sizeNum, $colorNum, $groupNum);

                array_push($data, "timepoints");

                fputcsv($nodeFile, array_merge($rootLabels, $data), $delimiter); //write labels
                if (isset($_POST['timePoint'])) {
                    if (isset($_POST['notSure'])) {
                        echo '<div class="alert alert-success" role="alert"><p><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> It looks like your data includes ' . $timePoints . ' time-point values.</p>
                            <p>We automatically found:</p>
                            <p>Types: ' . $typeNum . ' | Sizes: ' . $sizeNum . ' | Colors: ' . $colorNum . ' | Groups: ' . $groupNum . '</p><p>You may proceed with these values, or try again.</p><p><a class="btn btn-xs btn-warning" href="index.php#getStarted" role="button">Try Again</a></p></div>';
                    } else {
                        if ($_POST['timePointNum'] == $typeNum && $_POST['timePointNum'] == $sizeNum && $_POST['timePointNum'] == $colorNum && $_POST['timePointNum'] == $groupNum) {
                            echo '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> We found ' . $_POST['timePointNum'] . ' time-point values as indicated.</div>';
                        } else {
                            echo '<div class="alert alert-warning" role="alert"><p><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> You indicated your data includes ' . $_POST['timePointNum'] . ' time-point values.</p>
                            <p>We automatically found:</p>
                            <p>Types: ' . $typeNum . ' | Sizes: ' . $sizeNum . ' | Colors: ' . $colorNum . ' | Groups: ' . $groupNum . '</p><p>You may try again or proceed, but this may affect your expected results.</p><p><a class="btn btn-xs btn-warning" href="index.php#getStarted" role="button">Try Again</a></p></div>';
                        }
                    }
                } else if ( $timePoints > 1) {
                    echo '<div class="alert alert-success" role="alert"><p><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> It looks like your data includes ' . $timePoints . ' time-point values.</p>
                            <p>We automatically found:</p>
                            <p>Types: ' . $typeNum . ' | Sizes: ' . $sizeNum . ' | Colors: ' . $colorNum . ' | Groups: ' . $groupNum . '</p></div>';
                }
            } else {
                for ($i = 0; $i < count($headerArrayTypes); $i++) {
                    if ($headerArrayTypes[$i]['TYPE'] == gettype($data[$i]) || ($headerArrayTypes[$i]['TYPE'] == "integer" && is_numeric($data[$i]))) {
                        $data = str_replace('/', '-', $data);
                        //$data = str_replace('.', '-', $data); // Causes an issue with continuous color when enabled
                        $data = str_replace(',', '-', $data);
                        $data = str_replace('+', '-', $data);
                    } else {
                        echo '<div class="alert alert-danger" role="alert">We\'re sorry, we found a data type mismatch in data column "' . $headerArrayTypes[$i]['LABEL'] . '" on row ' . $row . '. Please correct your data and try again.</div>';
                        $dataResult = 0;
                    }
                }
                array_push($data, $timePoints);
                fputcsv($nodeFile, array_merge($padArray, $data), $delimiter);
            }
            //collect nodes and groups
            $nodes[$row - 1] = $data[0];
            if ($data[2] !== "NaN") {
                $groups[$row - 1] = $data[2];
            } else {
                $orphanCount++;
            }
            $row++;
        }
        //read uploaded icon file names
        /*if ($count == 0) {
            $icons[0] = "no_image";
        }
        for ($i = 0; $i < $count; $i++) {
            $icons[$i] = basename($_FILES['files']['name'][$i], ".jpg");
        }*/
        $rootNodes = array_keys(array_flip($groups)); // Identify root nodes
        $nodeList = array_merge($nodes, $rootNodes); // final node list
        $num = count($rootNodes);

        //write rootNodes to nodeList
        $rNodes = array();
        for ($i = 1; $i < $num; $i++) {
            $rNodes[0] = "true";

            //check if nodes have custom icon files and assign them, else make transparent
            /*if (in_array($rootNodes[$i], $icons)) {
                $rNodes[1] = $rootNodes[$i] . ".jpg";
            } else {
                $rNodes[1] = "transparent.gif";
            }*/
            $rNodes[1] = "transparent.gif";
            $rNodes[2] = $rootNodes[$i];
            $rNodes[3] = $rootNodes[$i];
            $rNodes[4] = $i;
            $rNodes[5] = $i;
            fputcsv($nodeFile, $rNodes, $delimiter);
        }
        $stat = fstat($nodeFile);
        ftruncate($nodeFile, $stat['size'] - 1);

        fclose($nodeFile); //Write and close new nodes file
        fclose($handle);
    } else {
        echo '<div class="alert alert-danger" role="alert">We\'re sorry, an unknown data processing error has occurred. Please try again.</div>';
        $dataResult = 0;
    }
    //read interactions and remove invalid links
    if (($handle = fopen($fileArray[1], "r")) !== FALSE) {
        $linksFile = fopen($workFolder . 'links.txt', 'w'); //open new interactions file
        $row = 1;
        while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            //$linkLabels = array("sourceId", "targetId");
            if ($row == 1) {

                // Calculate number of link time-points
                $linksNum = substr_count ( implode(",", $data ), "source" );
                fputcsv($linksFile, $data, $delimiter);
            } //write labels
            //Remove invalid interactions
            elseif ((in_array($data[0], $nodeList) && in_array($data[1], $nodeList)) || (in_array($data[0], $rootNodes) && in_array($data[1], $rootNodes))) {
                fputcsv($linksFile, $data, $delimiter);
            }
            $row++;
        }
        //link nodes to groups
        $num = (count($nodes)) - $orphanCount;
        $newLinks = array();
        for ($i = 1; $i < $num; $i++) {
            $n = 0;
            for ($j = 0; $j < $linksNum; $j++) {
                $newLinks[$n] = $nodes[$i];
                $newLinks[$n+1] = $groups[$i];
                $n += 2;
            }
            fputcsv($linksFile, $newLinks, $delimiter);
        }
        $stat = fstat($linksFile);
        ftruncate($linksFile, $stat['size'] - 1);

        fclose($linksFile); //Write and close clean interactions file
        fclose($handle);
    } else {
        echo '<div class="alert alert-danger" role="alert">A data processing error occurred.</div>';
        $dataResult = 0;
    }

    if ($dataResult == 1) {
        //remove temp files
        exec("rm " . $workFolder . "tempnodes.txt");
        exec("rm " . $workFolder . "templinks.txt");
        exec("rm " . $workFolder . "sortNodes.txt");
        echo '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Import Complete.</div>';
    }
    return $dataResult;
}