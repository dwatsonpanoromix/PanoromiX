<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'on');
require 'includes/functions.php';

if (!isset($_FILES["modules"]["name"])) {
    header( 'Location: index.php' ) ;
}

?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
<head>
    <title>PanoromiX</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/plugins/prism/prism.css">
    <link rel="stylesheet" href="assets/plugins/fileinput/css/fileinput.css" media="all" type="text/css" />
    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
    <!-- Page CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/uploadStyles.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Javascript -->
    <script type="text/javascript" src="assets/plugins/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>
    <script type="text/javascript" src="assets/plugins/prism/prism.js"></script>
    <script src="assets/plugins/fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>
</head>

<body data-spy="scroll">
<?php include_once("includes/analyticstracking.php") ?>
<!-- ******HEADER****** -->
<header id="header" class="header">
    <div class="container">
        <h1 class="logo pull-left">
            <a href="index.php">
                <span class="logo-title"><img src="assets/images/MOE-logo1.png" height="28" width="28" alt="..."> PanoromiX</span>
            </a>
        </h1><!--//logo-->
        <nav id="main-nav" class="main-nav navbar-right" role="navigation">
            <div class="navbar-header">
                <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button><!--//nav-toggle-->
            </div><!--//navbar-header-->
            <div class="navbar-collapse collapse" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active nav-item sr-only"><a class="scrollto" href="#promo">Home</a></li>
                </ul><!--//nav-->
            </div><!--//navabr-collapse-->
        </nav><!--//main-nav-->
    </div>
</header><!--//header-->

<!-- ******Get Started****** -->
<section id="getStarted" class="license section">
    <div class="container">
        <div class="license-inner">
            <h2 class="title text-center">File Upload Report</h2>
            <!--<div class="info">
                <p>This container will contain an upload report for the user as well as a button linking to the visualization.</p>
            </div><!--//info-->
            <div class="cta-container">
                <div class="speech-bubble">
                    <form id="mainform" enctype="multipart/form-data" method="<?php if (isset($_POST['import'])) { echo "post"; } else echo "get"; ?>" action="<?php if (isset($_POST['import'])) { echo "uploadImport.php"; } else echo "panoromics.php"; ?>">
                        <?php
                            $count = 0;
                            $fileArray = array();
                            $delimiter = "\t";
                            $workfolder;
                            $timePoints;
                            $timePointLabels = $_POST['timePointLabels'];
                            // Check files are valid
var_dump($fileArray);
                            // 
                            $result = upload($fileArray);

                            // If import files, rewrite header then continue
                            if (isset($_POST['import'])) {
                                $dataResult = 2;
                                echo '<div class="alert alert-success" role="alert"><div id="message"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> You indicated you were importing custom files or files from another application. Please click the button below to configure your imported files.</div></div>';
                            } else {
                                // If valid files uploaded, check images and pre-process data
                                if ($result == 1) {
                                    //check if pre-processing needs to be done
                                    //callR($fileArray);
                                    checkImages();
                                    $dataResult = prepData($fileArray);
                                } else $dataResult = 0;
                            }
                        ?>
                        <?php if (isset($_POST['import'])) { echo '<input type="hidden" id="fileArray" name="fileArray" value="' . implode(",", $fileArray) . '">'; }?>
                        <input type="hidden" id="workFolder" name="workFolder" value="<?php echo $workFolder; ?>">
                        <input type="hidden" id="timePointLabels" name="timePointLabels" value="<?php echo $timePointLabels; ?>">
                        <input type="hidden" id="timePoints" name="timePoints" value="<?php if (isset($timePoints)) { echo $timePoints; } else echo ""; ?>">
                </div><!--//speech-bubble-->
                <div class="btn-container  text-center">
                    <?php if ($dataResult == 1) {
                            echo '<button class="btn btn-cta-primary" id="visualize" name="visualize" type="submit" value="Submit">View Visualization</button>';
                        } else if ($dataResult == 2) {
                            echo '<div id="configButton"><a class="btn btn-cta-primary" data-toggle="modal" data-target="#nodeHeaders">Configure Import</a></div>';
                        } else {
                            echo '<a class="btn btn-cta-secondary" href="index.php#getStarted">Try Again</a>';
                        }
                    ?>
                    </form>

                </div><!--//btn-container-->
                </div><!--//btn-container-->
            </div><!--//cta-container-->
        </div><!--//license-inner-->
    </div><!--//container-->
</section><!--//how-->

<!-- Modal -->
<div class="modal fade" id="nodeHeaders" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog share" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Data Label Assignment</h4>
            </div>
            <div class="modal-body">
                <p>Before we can visualize your files in PanoromiX, we will need to assign your data columns to the ones required by our application.</p>
                <p>Nodes File</p>
                <form id ="modalForm" enctype="multipart/form-data" method="post">
                    <?php
                        echo "<table width=100% class='table table-hover table-sm table-bordered'>\n\n";
                        echo "<thead><tr class=\"bg-info\"><td>Original Field Name</td><td>PanoromiX Field Assignment</td><td>Data Sample</td></tr></thead>";
                        echo "<tbody class=\"table-striped>\"";
                            $f = fopen($workFolder . 'tempnodes.txt', "r");
                                $line1 = fgetcsv($f, 0, $delimiter);
                                $line2 = fgetcsv($f, 0, $delimiter);
                            fclose($f);

                            foreach ($line1 as $index => $cell) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($cell) . "<span style=\"float: right; margin-top: 10px;\" class=\"glyphicon glyphicon-arrow-right\" aria-hidden=\"true\"></td>";
                                echo '<td><select class="form-control" id="sel_node_' . $index . '">
                                      <option value="">-Select Field-</option>
                                      <option value="id">id (text or number)</option>
                                      <option value="name">name (text or number)</option>
                                      <option value="group">group (text or number)</option>
                                      <option value="type">type (text or number)</option>
                                      <option value="color">color (number only)</option>
                                      <option value="size">size (number only)</option>
                                      <option value="shape">shape (number only)</option>
                                    </select></td>';
                                echo "<td>" . $line2[$index] . "</td>";
                                echo "</tr>\n";
                            }
                        echo "</tbody>";
                        echo "\n</table>";

                        if ($_FILES["interactions"]["name"]) {
                            echo "<p>Links File</p>";
                            echo "<table width=100% class='table table-hover table-sm table-bordered'>\n\n";
                            echo "<thead><tr class=\"bg-info\"><td>Original Field Name</td><td>PanoromiX Field Assignment</td><td>Data Sample</td></tr></thead>";
                            echo "<tbody class=\"table-striped>\"";
                            $f = fopen($workFolder . 'templinks.txt', "r");
                            $line1 = fgetcsv($f, 0, $delimiter);
                            $line2 = fgetcsv($f, 0, $delimiter);
                            fclose($f);

                            foreach ($line1 as $index => $cell) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($cell) . "<span style=\"float: right; margin-top: 10px;\" class=\"glyphicon glyphicon-arrow-right\" aria-hidden=\"true\"></td>";
                                echo '<td><select class="form-control" id="sel_link_' . $index . '">
                                          <option value="">-Select Field-</option>
                                          <option value="sourceId">sourceId</option>
                                          <option value="targetId">targetId</option>
                                          <option value="marker_start">marker_start</option>
                                          <option value="marker_end">marker_end</option>
                                          <option value="linkColor">linkColor</option>
                                        </select></td>';
                                echo "<td>" . $line2[$index] . "</td>";
                                echo "</tr>\n";
                            }
                            echo "</tbody>";
                            echo "\n</table>";
                        }

                    ?>


                    </div>
                    <div class="modal-footer">
                        <button id="saveButton" class="btn btn-cta-primary" data-dismiss="modal">Save and Import</button>
                        <button type="button" class="btn btn-default active" data-dismiss="modal">Cancel</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- ******FOOTER****** -->
<footer class="footer">
    <div class="container text-center">
        <small class="copyright"><img class="gap-left gap-right" src="assets/images/fnlcr-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/nci-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/footer-logo.png" alt="" /><img src="assets/images/footer-logo2.png" alt="" /></small>
    </div><!--//container-->
</footer><!--//footer-->
</body>
<script type="text/javascript">
    $(function(){
        $('select[id^=sel_]').change(function()
        {
            // List of ids that are selected in all select elements
            var selected = new Array();

            // Get a list of the ids that are selected
            $('[id^=sel_] option:selected').each(function()
            {
                selected.push($(this).val());
            });

            // Walk through every select option and enable if not
            // in the list and not already selected
            $('[id^=sel_] option').each(function()
            {
                if (!$(this).is(':selected') && $(this).val() != '')
                {
                    var shouldDisable = false;
                    for (var i = 0; i < selected.length; i++)
                    {
                        if (selected[i] == $(this).val())
                            shouldDisable = true;
                    }

                    $(this).css('text-decoration', '');
                    $(this).removeAttr('disabled', 'disabled');
                    if (shouldDisable)
                    {
                        $(this).css('text-decoration', 'line-through');
                        $(this).attr('disabled', 'disabled');
                    }
                }
            });
        });
    });
    $(function(){
        $('#saveButton').on('click', function(e){
            e.preventDefault();

            // List of fields that are selected in all select elements
            var fields = new Array();
            var links = new Array();

            // Get a list of the node fields that are selected
            $('[id^=sel_node_]').each(function()
            {
                fields.push($(this).val());
            });

            // Get a list of the node fields that are selected
            $('[id^=sel_link_]').each(function()
            {
                links.push($(this).val());
            });

            var workFolder = JSON.stringify(document.getElementById("workFolder").value);
            var jsonNodeString = JSON.stringify(fields);
            var jsonLinkString = JSON.stringify(links);
            console.log(jsonNodeString);
            console.log(jsonLinkString);
            console.log(workFolder);

            $.ajax({
                type: "POST",
                url: "updateHeaders.php",
                data: {data : jsonNodeString, <?php if ($_FILES["interactions"]["name"]) { echo "links : jsonLinkString, "; } ?> workFolder : workFolder},
                cache: false,
                success: function(data) {
                    $( "div#message" ).html( '<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Thank you for assigning your data fields. Please click the button below to complete your import.');
                    $( "div#configButton" ).html( '<button class="btn btn-cta-primary" id="import" name="import" type="submit" value="Submit">Import</button>');
                    $( "#import" ).click(function() {
                        $( "#mainform" ).submit();
                    });
                    //alert(data); // Alert debugging echo from updateHeaders.php
                }
            });
        });
    });
</script>
</html>

