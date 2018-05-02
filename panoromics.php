<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require 'includes/functions.php';

if (isset($_GET["workFolder"])) {
    $workFolder = $_GET["workFolder"];
    $timePoints = $_GET["timePoints"];
    if ($_GET["timePointLabels"] == "" || $_GET["timePointLabels"] == null) {
        for ($i = 0; $i < $timePoints; $i++) {
            $labelArray[$i] = "Time-Point " . ($i + 1);
        }
        $timePointLabels = implode(",", $labelArray);
    } else {
        $labelArray = explode(",", $_GET["timePointLabels"]);
        if (count($labelArray) < $timePoints) {
            $diff = $timePoints - count($labelArray);
            for ($i = 1; $i <= $diff; $i++) {
                array_push($labelArray, "Time-Point " . ($timePoints - ($diff - $i)));
            }
            $timePointLabels = implode(",", $labelArray);
        } else {
            $timePointLabels = $_GET["timePointLabels"];
        }
    }
} else {
    header( 'Location: index.php' ) ;
}
if (isset($_GET["auth"])) {
    $authKey = $_GET["auth"];
}
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
<head>
    <title>Panoromics</title>
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
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.css">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/plugins/prism/prism.css">
    <link rel="stylesheet" href="assets/plugins/fileinput/css/fileinput.css" media="all" type="text/css" />
    <link rel="stylesheet" href="assets/css/datGuiStyles.css" media="all" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.css">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">

    <!-- Javascript -->
    <script type="text/javascript" src="assets/plugins/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/plugins/prism/prism.js"></script>
    <script src="assets/plugins/fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/js/saveSvgAsPng.js"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery.longpress.js"></script>

    <!-- Visualization Specific Scripts and Styles-->
    <script type="text/javascript" src="assets/js/d3.v3.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="assets/js/dat.gui.js"></script>
    <script src="assets/js/jquery.json.js"></script>
    <script type="text/javascript" src="assets/js/moe.js"></script>
    <link type="text/css" rel="stylesheet" href="assets/css/dat-gui-light.css">
    <link type="text/css" rel="stylesheet" href="assets/css/vizStyles.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body onload='main()'>
<?php include_once("includes/analyticstracking.php") ?>
<!-- ******HEADER****** -->
<header id="header" class="header">
    <div class="container">
        <h1 class="logo pull-left">
            <a href="index.php">
                <span class="logo-title"><img src="assets/images/MOE-logo1.png" height="28" width="28" alt="..."> Panoromics</span>
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
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" id="search" class="ui-autocomplete-input form-control" placeholder="Search">
                    </div>
                    <button type="button" id="searchButton" class="btn btn-default">Search</button>
                </form>
            </div><!--//navabr-collapse-->
        </nav><!--//main-nav-->
    </div>
</header><!--//header-->
        <div class="container-fluid">
            <div class="row">
                <div id="gui-container"></div>
                <div id="graph-labels"></div>
                <div class="col-md-12 main moe-main">
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="reset" data-tooltip="tooltip" title="Reset Zoom"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="zoomout" data-tooltip="tooltip" title="Zoom Out"><span class="glyphicon glyphicon-zoom-out" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group section" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="zoomin" data-tooltip="tooltip" title="Zoom In"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="stopAnimateDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="stopAnimate" data-tooltip="tooltip" title="Stop Animation"><span class="glyphicon glyphicon-stop" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group section" role="group">
                            <span id="animateDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="animate" data-tooltip="tooltip" title="Play/Pause Animation"><span class="glyphicon glyphicon-play" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="toggleLegend" data-tooltip="tooltip" title="Toggle Legend"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="invertBackground" data-tooltip="tooltip" title="Invert Background Color"><span class="glyphicon glyphicon-adjust" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="showBackground" data-tooltip="tooltip" title="Show/Hide Background"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="connectGroup" data-tooltip="tooltip" title="Show Center Connections"><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="hideimgDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="hideimg" data-tooltip="tooltip" title="Hide Center Image"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="profileDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="profile" data-tooltip="tooltip" title="Show Profile"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group section" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="discrete" data-tooltip="tooltip" title="Discrete Color"><span class="glyphicon glyphicon-tint" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <a type="button" class="btn btn-cta-primary btn-block toolbar" id="save_as_png" data-tooltip="tooltip" title="Export as Image"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span></a>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="downloadDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="download" data-tooltip="tooltip" title="Save and Download"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="resetConfigDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="reset_config" data-toggle="modal" data-tooltip="tooltip" data-target="#resetLayout" title="Reset Layout"><span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group section" role="group">
                            <span id="saveConfigDisable" data-tooltip="tooltip" title=""><a type="button" class="btn btn-cta-primary btn-block toolbar" id="save_config" data-toggle="modal" data-tooltip="tooltip" data-target="#saveLayout" title="Save Layout"><span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="editableLinkDisable" data-tooltip="tooltip" title=""><a href="#" type="button" class="btn btn-cta-primary btn-block toolbar" id="editableLink" data-toggle="modal" data-target="#shareUnlocked" data-tooltip="tooltip" title="Share Editable Project"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group" role="group">
                            <span id="lockedLinkDisable" data-tooltip="tooltip" title=""><a href="#" type="button" class="btn btn-cta-primary btn-block toolbar" id="lockedLink" data-toggle="modal" data-target="#shareLocked" data-tooltip="tooltip" title="Share Locked Project"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <span class="glyphicon glyphicon-lock" aria-hidden="true"></span></a></span>
                        </div>
                        <div class="btn-group" role="group">
                            <a href="#" type="button" class="btn btn-cta-primary btn-block toolbar" id="settingsButton" data-toggle="modal" data-target="#settings" data-tooltip="tooltip" title="Graph Options"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>
                        </div>
                    </div>

                    <div class="chart" id='ex1' style="border: solid 1px #aaa;"></div>
                </div>
            </div>
        </div>

        <!-- ########### The Export Section ####### -->
        <form id="svgform" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" id="workFolder" value="<?php echo $workFolder; ?>">
            <input type="hidden" id="timePointLabels" name="timePointLabels" value="<?php echo $timePointLabels; ?>">
            <input type="hidden" id="authKey" name="authKey" value="<?php echo $authKey; ?>">
        </form>

            <div class="messagepop pop"></div>
            <!-- Modal -->
            <div class="modal fade" id="saveLayout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Save Layout</h4>
                        </div>
                        <div class="modal-body">
                            <div id="secret"></div>
                        </div>
                        <div class="modal-footer">
                            <button id="saveLayoutOK" type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="resetLayout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Reset Layout</h4>
                        </div>
                        <div class="modal-body">
                            <p>Your layout has been successfully reset.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="shareUnlocked" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog share" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Share Editable Project</h4>
                        </div>
                        <div class="modal-body">
                            <p>Please copy the link below if you would like to grant access to your project. Please note that visitors to this link will be able to edit, download and save your layout, possibly overwriting your own changes.</p>
                            <p>Share Url:</p>
                            <p id="unlockedURL"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="shareLocked" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog share" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Share Locked Project</h4>
                        </div>
                        <div class="modal-body">
                            <p>Please copy the link below if you would like to grant restricted access to your project. Visitors to this link will not be able to edit, download or save your layout.</p>
                            <p>Share Url:</p>
                            <p id="lockedURL"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="settings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Project Settings</h4>
                        </div>
                        <div class="modal-body">
                            <form>
                                <h4>Graph Labels</h4>
                                <div class="form-inline">
                                    <label for="colorLabel">Color Label:&nbsp;&nbsp;</label>
                                    <input id="colorLabel" name="colorLabel" class="form-control" value="Color:" placeholder="Enter Label for Color">
                                    <p class="help-block">What does color represent in your graph?</p>
                                </div>
                                <div class="form-inline">
                                    <label for="sizeLabel">Size Label:&nbsp;&nbsp;</label>
                                    <input id="sizeLabel" name="sizeLabel" class="form-control" value="Size:" placeholder="Enter Label for Size">
                                    <p class="help-block">What does size represent in your graph?</p>
                                </div>
                                <div class="form-inline">
                                    <label for="typeLabel">Type Label:&nbsp;&nbsp;</label>
                                    <input id="typeLabel" name="typeLabel" class="form-control" value="Type:" placeholder="Enter Label for Type">
                                    <p class="help-block">What does type represent in your graph?</p>
                                </div>
                                <h4>Time-Point Labels</h4>
                                <?php
                                for ($i = 1; $i <= $timePoints; $i++) {
                                    echo ' <div class="form-inline" style="margin-bottom: 10px;">
                                                <label for="timepoint' . $i . '">Time-Point ' . $i . ' Label:&nbsp;&nbsp;</label>
                                                <input id="timepoint' . $i . '" name="timepoint' . $i . '" class="form-control" placeholder="Enter label name">
                                            </div>';
                                }
                                ?>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="updateLabelsButton" data-dismiss="modal">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
<!-- ******FOOTER****** -->
<footer class="footer navbar-fixed-bottom">
    <div class="container text-center">
        <small class="copyright"><img class="gap-left gap-right" src="assets/images/fnlcr-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/nci-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/footer-logo.png" alt="" /><img src="assets/images/footer-logo2.png" alt="" /></small>
    </div><!--//container-->
</footer><!--//footer-->

</body>
</html>

