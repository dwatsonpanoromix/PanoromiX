<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// Function to check correct file type, rename and place file in correct location, output report to the user
function upload($fileArray) {

    clearHistory();
    //prepare a workfolder
    $id = mt_rand();

    global $workFolder;
    global $html;

    $workFolder = "workspaces/".$id."/";
    $uploadOk = 1;
    $fileCount = 0;
    $result = 0;
    global $fileArray;

    if ($_FILES["modules"]["name"]) {
        $modules_file = $workFolder . basename($_FILES["modules"]["name"]);
    } else $uploadOk = 0;
    if ($_FILES["interactions"]["name"]) {
        $interactions_file = $workFolder . basename($_FILES["interactions"]["name"]);
    }
    if ($_FILES["config"]["name"]) {
        $config_file = $workFolder . basename($_FILES["config"]["name"]);
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $html .= '<div class="alert alert-danger" role="alert">We were unable to process your data. Please try again below.</div>';
        // if everything is ok, try to upload file
    } else {
        exec("mkdir " . $workFolder);
        if (move_uploaded_file($_FILES["modules"]["tmp_name"], $modules_file)) {
            $html .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Nodes file upload: Successful [' . basename($_FILES["modules"]["name"]) . ']</div>';
            rename($modules_file, $workFolder . "tempnodes.txt");
            $fileArray[0] = $workFolder . "tempnodes.txt";
            $fileCount++;
        } else {
            $html .= '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your nodes file. This may be temporary, please try again.</div>';
            $result = 0;
        }
        if ($_FILES["interactions"]["name"]) {
            if (move_uploaded_file($_FILES["interactions"]["tmp_name"], $interactions_file)) {
                $html .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Interactions file upload: Successful [' . basename($_FILES["interactions"]["name"]) . ']</div>';
                rename($interactions_file, $workFolder . "templinks.txt");
                $fileArray[1] = $workFolder . "templinks.txt";
                $fileCount++;
            } else {
                $html .= '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your nodes file. This may be temporary, please try again.</div>';
                $result = 0;
            }
        } else {
            //no links file uploaded, create temporary one
            //callR($fileArray);
            $fp = fopen($workFolder . 'templinks.txt', "w");
            if ($fp) {
                $fileArray[1] = $workFolder . "templinks.txt";
                $padArray = array("sourceId", "targetId");
                fputcsv($fp, $padArray, "\t");
            }
            fclose($fp);
        }
        if ($_FILES["config"]["name"]) {
            if (move_uploaded_file($_FILES["config"]["tmp_name"], $config_file)) {
                $html .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Config file upload: Successful [' . basename($_FILES["config"]["name"]) . ']</div>';
                rename($config_file, $workFolder . "tempconfig.txt");
                $fileArray[2] = $workFolder . "tempconfig.txt";
                $fileCount++;
            } else {
                $html .= '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your config file. Proceeding with default layout.</div>';
            }
        }
        if ($fileCount > 0) {
            $result = 1;
        }
    }

    $normalizeResult = normalize($fileArray);
    if ($normalizeResult == 1) {
        $result = 1;
    } else {
        $result = 0;
        exec("rm " . $workFolder . "tempnodes.txt");
        exec("rm " . $workFolder . "templinks.txt");
        exec("rm " . $workFolder . "tempconfig.txt");
    }
    return $result;
}

function checkImages() {
    global $count;
    global $workFolder;
    global $html;
    $valid_formats = array("jpg");
    $max_file_size = 1024*50000; //50 MB
    $iconPath = $workFolder; // Upload directory

    // Loop $_FILES to execute all files
    foreach ($_FILES['files']['name'] as $f => $name) {
        if ($_FILES['files']['error'][$f] == 4) {
            continue; // Skip file if any error found
        }
        if ($_FILES['files']['error'][$f] == 0) {
            if ($_FILES['files']['size'][$f] > $max_file_size) {
                $html .= '<div class="alert alert-warning" role="alert">Sorry, there was an error uploading custom image: ' . $name . ' file size is too large!
				<br />You may try again or continue to view visualization.</div>';
                continue; // Skip large files
            }
            else { // No error found! Move uploaded files
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $iconPath.$name))
                    $html .= '<div class="alert alert-success" role="alert">Custom image file upload: Successful [' . $name . ']</div>';
                $count++; // Number of successfully uploaded files
            }
        }
    }
}

function callR($fileArray) {
    global $delimiter;
    global $fileArray;
    global $html;
    //global $workFolder;
    if (($handle = fopen($fileArray[0], "r")) !== FALSE) {
        if (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
            if ((count($data) < 3) || !($_FILES["interactions"]["name"])) {
                //run R script
                //echo "<p>R script called.</p>";
                //shell_exec('/opt/nasapps/development/R/3.2.0/bin/R CMD BATCH r/pathway_module.R');
                //$fileArray[1] = $workFolder . "templinks.txt";
                $html .= '<div class="alert alert-danger" role="alert">Sorry, there was an error uploading your interactions file.</div>';
            }
        }
    }
}

function normalize($fileArray) {
    global $html;
    $result = 1;
    $lines = file($fileArray[0]);
    $line = $lines[0];
    switch (TRUE) {
        case substr($line, -2) === "\r\n" :
            $html .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Reading Nodes file: File format OK</div>';
            break;
        case substr($line, -1) === "\n" :
            $html .= '<div class="alert alert-warning" role="alert">Reading Nodes file: Please Try Again. This is a Unix formatted file.</div>';
            $result = 0;
            break;
        default :
            // Excel Mac?
            $html .= '<div class="alert alert-danger" role="alert">Reading Nodes file: File format error: From Excel 2011 Mac, please save as "Windows formatted text" and try again.</div>';
            $result = 0;
            break;
    }
    return $result;
}

// Function to read the uploaded data files, remove invalid interactions and write the new data files to the work folder
function prepData($fileArray) {
    global $count;
    global $delimiter;
    global $workFolder;
    global $timePoints;
    global $html;
    $rootLabels = array("root", "icon");
    $padArray = array("false", "none");
    $dataResult = 1;

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
                        $html .= '<div class="alert alert-success" role="alert"><p><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> It looks like your data includes ' . $timePoints . ' time-point values.</p>
                            <p>We automatically found:</p>
                            <p>Types: ' . $typeNum . ' | Sizes: ' . $sizeNum . ' | Colors: ' . $colorNum . ' | Groups: ' . $groupNum . '</p><p>You may proceed with these values, or try again.</p><p><a class="btn btn-xs btn-warning" href="index.html#getStarted" role="button">Try Again</a></p></div>';
                    } else {
                        if ($_POST['timePointNum'] == $typeNum && $_POST['timePointNum'] == $sizeNum && $_POST['timePointNum'] == $colorNum && $_POST['timePointNum'] == $groupNum) {
                            $html .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> We found ' . $_POST['timePointNum'] . ' time-point values as indicated.</div>';
                        } else {
                            $html .= '<div class="alert alert-warning" role="alert"><p><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> You indicated your data includes ' . $_POST['timePointNum'] . ' time-point values.</p>
                            <p>We automatically found:</p>
                            <p>Types: ' . $typeNum . ' | Sizes: ' . $sizeNum . ' | Colors: ' . $colorNum . ' | Groups: ' . $groupNum . '</p><p>You may try again or proceed, but this may affect your expected results.</p><p><a class="btn btn-xs btn-warning" href="index.html#getStarted" role="button">Try Again</a></p></div>';
                        }
                    }
                } else if ( $timePoints > 1) {
                    $html .= '<div class="alert alert-success" role="alert"><p><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> It looks like your data includes ' . $timePoints . ' time-point values.</p>
                            <p>We automatically found:</p>
                            <p>Types: ' . $typeNum . ' | Sizes: ' . $sizeNum . ' | Colors: ' . $colorNum . ' | Groups: ' . $groupNum . '</p></div>';
                }
            } else {
                for ($i = 0; $i < count($headerArrayTypes); $i++) {
                    if ($headerArrayTypes[$i]['TYPE'] == gettype($data[$i]) || ($headerArrayTypes[$i]['TYPE'] == "integer" && is_numeric($data[$i]))) {
                        $data = str_replace('/', '-', $data);
                        //$data = str_replace('.', '-', $data);
                        $data = str_replace(',', '-', $data);
                        $data = str_replace('+', '-', $data);
                        array_push($data, $timePoints);
                        fputcsv($nodeFile, array_merge($padArray, $data), $delimiter);
                    } else {
                        $html .= '<div class="alert alert-danger" role="alert">We\'re sorry, we found a data type mismatch in data column "' . $headerArrayTypes[$i]['LABEL'] . '" on row ' . $row . '. Please correct your data and try again.</div>';
                        $dataResult = 0;
                    }
                }

            }
            //collect nodes and groups
            $nodes[$row - 1] = $data[0];
            $groups[$row - 1] = $data[2];
            $row++;
        }
        //read uploaded icon file names
        if ($count == 0) {
            $icons[0] = "no_image";
        }
        for ($i = 0; $i < $count; $i++) {
            $icons[$i] = basename($_FILES['files']['name'][$i], ".jpg");
        }
        $rootNodes = array_keys(array_flip($groups)); // Identify root nodes
        $nodeList = array_merge($nodes, $rootNodes); // final node list
        $num = count($rootNodes);

        //write rootNodes to nodeList
        $rNodes = array();
        for ($i = 1; $i < $num; $i++) {
            $rNodes[0] = "true";

            //check if nodes have custom icon files and assign them, else make transparent
            if (in_array($rootNodes[$i], $icons)) {
                $rNodes[1] = $rootNodes[$i] . ".jpg";
            } else {
                $rNodes[1] = "transparent.gif";
            }
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
        $html .= '<div class="alert alert-danger" role="alert">We\'re sorry, an unknown data processing error has occurred. Please try again.</div>';
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
        $num = count($nodes);
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
        $html .= '<div class="alert alert-danger" role="alert">A data processing error occurred.</div>';
        $dataResult = 0;
    }
    //read config file and validate
    $configError = 0;
    if (count($fileArray) > 2) {
        if (($handle = fopen($fileArray[2], "r")) !== FALSE) {
            $configFile = fopen($workFolder . 'config.txt', 'w'); //open new interactions file
            $row = 1;
            while (($data = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                $configLabels = array("rootId", "rootX", "rootY", "timepoint", "charge", "gravity", "linkWidth", "linkColor", "maxScore", "minScore", "radius", "fontColorNode", "fontColorCenter", "saved", "key", "colorLabel", "sizeLabel", "typeLabel", "tpLabels");
                if ($row == 1) {
                    fputcsv($configFile, $configLabels, $delimiter);
                } //write labels
                //Validate root nodes in config
                elseif (in_array($data[0], $rootNodes)) {
                    $data[13] = "no";
                    $data[14] = "";
                    fputcsv($configFile, $data, $delimiter);
                } else {
                    // node does not exist in Root Node list
                    $configError = 1;
                }
                $row++;
            }
            $stat = fstat($configFile);
            ftruncate($configFile, $stat['size'] - 1);

            fclose($configFile); //Write and close clean config file
            fclose($handle);
            if ($configError == 1) {
                $html .= '<div class="alert alert-danger" role="alert">Reading Config File: An error was detected: Your Node IDs do not match. Proceeding with default configuration.</div>';
                exec("rm " . $workFolder . "config.txt");
            } else {
                $html .= '<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Reading Config file: File format OK</div>';
            }
        } else {
            $html .= '<div class="alert alert-danger" role="alert">An error was detected in your configuration file: Your Node IDs do not match. Proceeding with default config.</div>';
        }
    }
    if ($dataResult == 1) {
        //remove temp files
        exec("rm " . $workFolder . "tempnodes.txt");
        exec("rm " . $workFolder . "templinks.txt");
        exec("rm " . $workFolder . "sortNodes.txt");
    }
    return $dataResult;
}

function rmdir_recursive($dir) {
    foreach(scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) rmdir_recursive("$dir/$file");
        else unlink("$dir/$file");
    }
    rmdir($dir);
}

function clearHistory() {
    $workFolder = "workspaces/";

    if ($handle = opendir($workFolder)) {
        $batchFolders = array();
        $dir = new DirectoryIterator($workFolder);

        foreach ($dir as $fileinfo) {
            if ($fileinfo->getFilename() == "." || $fileinfo->getFilename() == "..")
                continue;

            $batchFolders[$fileinfo->getMTime()] = $fileinfo->getFilename();
        }
        asort($batchFolders);

        foreach ($batchFolders as $key => $folder) {
            $age = round(((time() - $key)/60/60/24), 1);
            if ($age >= 13) {
                $fullPath = $workFolder . $folder;
                rmdir_recursive($fullPath);
            }
        }
    }
}