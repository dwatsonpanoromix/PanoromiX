<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include_once("includes/importFunctions.php");

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
                    <form enctype="multipart/form-data" method="get" action="panoromics.php">
                        <?php
                        $fileArray = explode(",", $_POST['fileArray']);
                        $delimiter = "\t";
                        $workfolder = $_POST['workFolder'];
                        $timePoints;
                        $timePointLabels = $_POST['timePointLabels'];

                        // Check images and pre-process data

                        //check if pre-processing needs to be done
                        //callR($fileArray);
                        //checkImages();
                        $dataResult = prepData($fileArray, $workfolder);

                        ?>
                        <input type="hidden" id="workFolder" name="workFolder" value="<?php echo $workfolder; ?>">
                        <input type="hidden" id="timePointLabels" name="timePointLabels" value="<?php echo $timePointLabels; ?>">
                        <input type="hidden" id="timePoints" name="timePoints" value="<?php echo $timePoints; ?>">
                </div><!--//speech-bubble-->
                <div class="btn-container  text-center">
                    <?php if ($dataResult == 1) {
                        echo '<button class="btn btn-cta-primary" id="visualize" name="visualize" type="submit" value="Submit">View Visualization</button>';
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

<!-- ******FOOTER****** -->
<footer class="footer">
    <div class="container text-center">
        <small class="copyright"><img class="gap-left gap-right" src="assets/images/fnlcr-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/nci-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/footer-logo.png" alt="" /><img src="assets/images/footer-logo2.png" alt="" /></small>
    </div><!--//container-->
</footer><!--//footer-->
</body>
</html>

