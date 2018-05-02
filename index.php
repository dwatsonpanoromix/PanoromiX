<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
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
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->    
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/plugins/prism/prism.css">
    <link rel="stylesheet" href="assets/plugins/fileinput/css/fileinput.css" media="all" type="text/css" />
    <!-- Theme CSS -->  
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
    <!-- Page CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/homeStyles.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head> 

<body data-spy="scroll">
<?php include_once("includes/analyticstracking.php") ?>
    <!-- ******HEADER****** --> 
    <header id="header" class="header">  
        <div class="container">            
            <h1 class="logo pull-left">
                <a class="scrollto" href="#promo">
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
                        <li class="nav-item"><a class="scrollto" href="#about">About</a></li>
                        <li class="nav-item"><a class="scrollto" href="#features">Features</a></li>
                        <li class="nav-item"><a class="scrollto" href="#tutorial">Tutorial</a></li>
                        <li class="nav-item"><a class="scrollto" href="#examples">Examples</a></li>
                        <li class="nav-item"><a class="scrollto" href="#getStarted">Get Started</a></li>
                        <li class="nav-item last"><a class="scrollto" href="#contact">Contact</a></li>
                    </ul><!--//nav-->
                </div><!--//navabr-collapse-->
            </nav><!--//main-nav-->
        </div>
    </header><!--//header-->
    
    <!-- ******PROMO****** -->
    <section id="promo" class="promo section offset-header">
        <div class="container text-center">
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="assets/images/panoromics-viz.jpg" alt="...">
                    </div>
                    <div class="item">
                        <img src="assets/images/panoromics-viz2.jpg" alt="...">
                    </div>
                    <div class="item">
                        <img src="assets/images/panoromics-viz3.jpg" alt="...">
                    </div>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
            <br />
            <p class="intro">Panoromics - A fully interactive visualization tool offering automated modular network construction</p>
            <div class="btns">
                <a class="btn btn-cta-secondary scrollto" href="#examples">Examples</a>
                <a class="btn btn-cta-primary scrollto" href="#getStarted">Get Started</a>
            </div>
            <ul class="meta list-inline">
                <li><a href="#" target="_blank">Full Documentation</a></li>
                <li>Created by: <a href="#" target="_blank">Dr. Ruoting Yang, Daniel Watson</a> - ABCC, NCI, Leidos Biomedical Research, Inc.</li>
            </ul><!--//meta-->
        </div><!--//container-->
    </section><!--//promo-->
    
    <!-- ******ABOUT****** --> 
    <section id="about" class="about section">
        <div class="container">
            <h2 class="title text-center">What is Panoromics?</h2>
            <p class="intro text-center">Panoromics is a novel data-driven web application for network visualization. It enables users to define a modularized, multi-layered network, and display it as an interactive figure seamlessly across most web browsers, by uploading their data in a text file. The user can customize many attributes of the network, and share interactive results easily via email. With minimal requirements on software installation and programming knowledge, Panoromics allows users to easily design, explore and share informative, interactive networks.</p>
            <div class="row">
                <div class="item col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-holder">
                        <i class="fa fa-keyboard-o"></i>
                    </div>
                    <div class="content">
                        <h3 class="sub-title">Designed for developers</h3>
                        <p>With custom configuration file upload, advanced users and developers can create and upload their own configuration files to create custom layouts and more!</p>
                    </div><!--//content-->
                </div><!--//item-->
                <div class="item col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-holder">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    <div class="content">
                        <h3 class="sub-title">Time saver</h3>
                        <p>With no need for software download or installation, or requisite programming knowledge, users can get up and running in minutes.</p>
                    </div><!--//content-->
                </div><!--//item-->
                <div class="item col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-holder">
                        <i class="fa fa-crosshairs"></i>
                    </div>
                    <div class="content">
                        <h3 class="sub-title">UX-centred</h3>
                        <p>Our software is easy to use. Designed for quick and hassle-free network visualization, there are no complicated options or menus to navigate. Simply select your data, upload and visualize.</p>
                    </div><!--//content-->
                </div><!--//item-->           
                <div class="clearfix visible-md"></div>    
                <div class="item col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-holder">
                        <i class="fa fa-tablet"></i>
                    </div>
                    <div class="content">
                        <h3 class="sub-title">Mobile-friendly</h3>
                        <p>The Panoromics application is compatible and will operate smoothly with most mobile and touch-enabled devices!</p>
                    </div><!--//content-->
                </div><!--//item-->                
                <div class="item col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-holder">
                        <i class="fa fa-code"></i>
                    </div>
                    <div class="content">
                        <h3 class="sub-title">Easy to customize</h3>
                        <p>Change your network visualization settings live! Edit colors, sizes, labels, links, icons, graph and module positions and much more!</p>
                    </div><!--//content-->
                </div><!--//item-->
                <div class="item col-md-4 col-sm-6 col-xs-12">
                    <div class="icon-holder">
                        <i class="fa fa-coffee"></i>
                    </div>
                    <div class="content">
                        <h3 class="sub-title">Example files included</h3>
                        <p>We provide data templates and sample data for all our upload files, simply cut and paste your data into our template and you're ready to go!</p>
                    </div><!--//content-->
                </div><!--//item-->               
            </div><!--//row-->            
        </div><!--//container-->
    </section><!--//about-->
    
    <!-- ******FEATURES****** --> 
    <section id="features" class="features section">
        <div class="container text-center">
            <h2 class="title">Features</h2>
            <ul class="feature-list list-unstyled">
                <li><i class="fa fa-check"></i> Automated modular network construction with nodes and links generated from your uploaded data.</li>
                <li><i class="fa fa-check"></i> Visualize changes in your data relationships, groups and types through multiple time-points included with your data.</li>
                <li><i class="fa fa-check"></i> Customize your display with graphics and icons you upload to represent your groups!</li>
                <li><i class="fa fa-check"></i> Display multiple relationships between groups, modules and individual network nodes.</li>
                <li><i class="fa fa-check"></i> Customizable color coding, shapes and sizes for network node types.</li>
            </ul>
        </div><!--//container-->
    </section><!--//features-->
    
    <!-- ******DOCS****** --> 
    <section id="tutorial" class="docs section">
        <div class="container">
            <div class="docs-inner">
            <h2 class="title text-center">How does it work?</h2>
            <div class="block">
                <h3 class="sub-title text-center">Prepare Your Data</h3>
                <p>The Panoromics application accepts the following data files which it processes in order to create the network visualization:</p>
                <ul>
                    <li>A required nodes file containing information about individual points (nodes) of interest that will be plotted as part of your network visualization.</li>
                    <li>An optional links file which contains information about links or relationships between each of these points (nodes).</li>
                    <li>An optional configuration file you may have downloaded from a previous Panoromics project, or one you may have created yourself.</li>
                    <li>An optional time-point configuration file which you may have created to display an animated legend for your visualization</li>
                    <li>Optional set of images to be displayed as icons, background image, or dynamic legend images for your visualization.</li>
                </ul>
                <p><a href="assets/data/panoromics-nodes-template.txt" target="_blank" download>Download</a> an example nodes file template here.</p>
                <p><a href="assets/data/panoromics-links-template.txt" target="_blank" download>Download</a> an example links file template here.</p>
                <p>Below are the details of the data preparation process for Panoromics.</p>
                <ul class="list-unstyled">
                    <li><strong>Data File Type:</strong> Tab Delimited Text File (.txt)</li>
                    <li><strong>Custom Icon File Type:</strong> .jpg image files</li>
                </ul>
            </div>
            <div class="block">
                <h3 class="sub-title text-center">Full Documentation</h3>
                <p>If your documentation is very long you can host the full docs page (including FAQ etc) on GitHub and provide a Call to Action button below to direct users there.</p>
                <p class="text-center">
                    <a class="btn btn-cta-primary" href="#">Download Docs</a>
                </p>
            </div><!--//block-->
            
            </div><!--//docs-inner-->         
        </div><!--//container-->
    </section><!--//docs-->

    <!-- ******EXAMPLES****** -->
    <section id="examples" class="features section">
        <div class="container text-center">
            <h2 class="title">Example 1: Multi-tissue drug response in different conditions</h2>
            <p>This example shows the immunophenotypic progression in healthy human bone marrow.
                The tree plot displays many blood cells in healthy human bone marrow. The colored lines encircling
                sets of nodes define the cells, while the blue-red color scheme of the node indicate the low-high
                CD marker expression in the cells. Similarly, the different stimulator-responser combination can be
                achieved. Panoromics can illustrate how the cells respond when the conditions change.<br />
                Click the 'View Demo' button below to launch an interactive view.
            </p>
            <p><img src="assets/images/spade-tree.png" height="300" alt="Spade Analysis Tree"></p>
            <p><img src="assets/images/panoromics-viz.jpg" height="400" alt="Spade Analysis Tree"></p>
            <div class="btns">
                <a class="btn btn-cta-secondary" href="https://bioinfo-abcc.ncifcrf.gov/panoromics/panoromics.php?workFolder=examples%2Fspade_analysis%2F&timePointLabels=&timePoints=6&visualize=Submit" target="_blank">View Demo</a>
            </div>
            <p>&nbsp;</p>
            <hr />
            <p>&nbsp;</p>
            <h2 class="title">Example 2: Comparison of different drug effects in terms of gene expression: Prion Disease</h2>
            <p>The expression mosaics for two drugs, ATRA and DMSO, stimulated time course gene expressions capture spatial patterns in terms of modules as the system responds to the drugs through the time series.  These images are a graphical representation of dynamic expression changes in clusters in the orginal paper. They show how the drug effects converge while two routes involving different pathways have been taken in the time course.
                Red/Blue denote extreme positive/negative log expression fold change ratios.
                Panoromics can illustrate the drug effect in a modular network using two color sets.
                The different drug responses start and spread from different areas of the network, and finally
                converge to the same area.<br />The prion disease is a simpler example on one system response.<br />
                Click the 'View Demo' button below to launch an interactive view.
            </p>
            <p><img src="assets/images/panoromics-viz2.jpg" height="400" alt="Prion Disease"></p>
            <div class="btns">
                <a class="btn btn-cta-secondary" href="https://bioinfo-abcc.ncifcrf.gov/panoromics/panoromics.php?workFolder=examples%2Fprion_mouse%2F&timePointLabels=&timePoints=6&visualize=Submit" target="_blank">View Demo</a>
            </div>
            <p>&nbsp;</p>
            <hr />
            <p>&nbsp;</p>
            <h2 class="title">Example 3: Panoromics Feature Demonstration</h2>
            <p>This interactive demonstration gives the user an opportunity to experience a full feature demonstration of Panoromics including the use of all our latest template files offered on the website. It illustrates the use of Panoromics custom icon images, custom link colors and more! Feel free to customize, save, export and share this interactive demo to get a full-feature experience of our software.</p>
            <p><img src="assets/images/panoromics-viz3.jpg" height="400" alt="Panoromics Demonstration"></p>
            <div class="btns">
                <a class="btn btn-cta-secondary" href="https://bioinfo-abcc.ncifcrf.gov/panoromics/panoromics.php?workFolder=examples%2Fdemonstration%2F&timePointLabels=&timePoints=3&visualize=Submit" target="_blank">View Demo</a>
            </div>
        </div><!--//container-->
    </section><!--//features-->

    <!-- ******Get Started****** -->
    <section id="getStarted" class="license section">
        <div class="container">
            <div class="license-inner">
                <h2 class="title text-center" style="margin-bottom: 30px;">Get Started: Create a Project</h2>
                <div class="cta-container" style="margin-top: 30px;">
                    <form name="upload" enctype="multipart/form-data" method="post" action="upload.php" onsubmit="return validateForm()">
                        <div class="speech-bubble">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <div class="checkbox">
                                                <label data-toggle="collapse" data-target="#collapseOne">
                                                    <input type="checkbox" id="uploadMain" name="uploadMain"/> Step 1: Ready to upload your project files? (Required) <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
                                                </label>
                                            </div>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="import" name="import"> Are you uploading export files from another application?<br />(e.g. Cytoscape, IPA, etc.)
                                                    </label>
                                                </div>
                                                <label class="control-label">Select your nodes file here.</label>
                                                <input data-show-upload="false" id="modules" name="modules" type="file" class="file-loading" accept="text/plain">
                                                <p class="help-block">(Required) <span class="glyphicon glyphicon-flag" aria-hidden="true"></span> Tab delimited format (.txt)<br />
                                                    <a href="assets/data/panoromics-nodes-template.txt" target="_blank" download>Download</a> an example nodes file here.</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Select your links file here.</label>
                                                <input data-show-upload="false" id="interactions" name="interactions" type="file" class="file-loading" accept="text/plain">
                                                <p class="help-block">(Optional) <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Tab delimited format (.txt)<br />
                                                    <a href="assets/data/panoromics-links-template.txt" target="_blank" download>Download</a> an example links file here.</p><div id="error2"></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">If you have custom icons for groups, background, or time-point images, select them all here.</label>
                                                <input data-show-upload="false" id="file" name="files[]" type="file" accept="image/*" class="file-loading" multiple=true>
                                                <p class="help-block">(Optional) <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Image files less than 50MB each (.jpg only).<br />Names must match data groups or time-points.</p><div id="error3"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <div class="checkbox">
                                                <label data-toggle="collapse" data-target="#collapseTwo">
                                                    <input type="checkbox" id="uploadConfig" name="uploadConfig"/> Step 2: Did you already save or create a configuration for a Panoromics project? (Optional) <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                                </label>
                                            </div>
                                        </h4>

                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label class="control-label">Select Config File:</label>
                                                <input data-show-upload="false" id="config" name="config" type="file" class="file" accept="text/plain">
                                                <p class="help-block">Must be tab delimited format (.txt)<br />
                                                    <a href="assets/data/config-template.txt" target="_blank" download>Download</a> an example config file here.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <div class="checkbox">
                                                <label data-toggle="collapse" data-target="#collapseThree">
                                                    <input type="checkbox" id="timePoint" name="timePoint"/> Step 3: Does your data contain time points? (Optional) <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                                </label>
                                            </div>
                                        </h4>

                                    </div>
                                    <div id="collapseThree" class="panel-collapse collapse in">
                                        <div class="panel-body">
                                            <div class="form-group form-inline">
                                                <label for="timePointNum" class="control-label">How many? </label>
                                                <input type="text" class="form-control inputNumber" id="timePointNum" name="timePointNum" placeholder="Enter number">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="notSure" name="notSure"> I'm not sure
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="timePointLabels">Enter a label for each time-point if known:</label>
                                                <input type="text" class="form-control" id="timePointLabels" name="timePointLabels" placeholder="Enter labels (comma separated)">
                                                <p class="help-block">e.g. 2hrs,4hrs,6hrs (try to avoid special characters)</p>
                                                <p><label>-- OR --</label></p>
                                                <label class="control-label">Upload Time-point Configuration File:</label>
                                                <input data-show-upload="false" id="tpConfig" name="tpConfig" type="file" class="file" accept="text/plain">
                                                <p class="help-block">Must be tab delimited format (.txt)<br />
                                                    <a href="#" target="_blank" download>Download</a> an example time-point config file here.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-container  text-center">
                            <div id="error"></div>
                            <input type="hidden" id="status" name="status" value="set" />
                            <button type="submit" class="btn btn-cta-primary">Upload</button>
                        </div><!--//btn-container-->
                    </form>
                </div><!--//cta-container-->
            </div><!--//license-inner-->
        </div><!--//container-->
    </section><!--//how-->
    
    <!-- ******CONTACT****** --> 
    <section id="contact" class="contact section has-pattern">
        <div class="container">
            <div class="contact-inner">
                <h2 class="title  text-center">Contact</h2>
                <p class="intro  text-center">Get in touch with the team if you have any questions.</p>
                <div class="author-message">                      
                    <div class="profile">
                        <img class="img-responsive" src="assets/images/MOE-logo1.png" alt="" />
                    </div><!--//profile-->
                    <div class="speech-bubble">
                        <h3 class="sub-title">Like the Panoromics app?</h3>
                        <div class="source">
                            <p></p><span class="name"><a href="" target="_blank">Daniel Watson</a></span>
                            <br />
                            <span class="title">Visualization Development & UX/UI Design</span><p>
                            <p></p><span class="name"><a href="" target="_blank">Dr. Ruoting Yang</a></span>
                            <br />
                            <span class="title">Algorithm & Visualization Development</span></p>
                        </div><!--//source-->
                    </div><!--//speech-bubble-->                        
                </div><!--//author-message-->
            </div><!--//contact-inner-->
        </div><!--//container-->
    </section><!--//contact-->
      
    <!-- ******FOOTER****** --> 
    <footer class="footer">
        <div class="container text-center">
            <small class="copyright"><img class="gap-left gap-right" src="assets/images/fnlcr-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/nci-logo.png" alt="" /><img class="gap-left gap-right" src="assets/images/footer-logo.png" alt="" /><img src="assets/images/footer-logo2.png" alt="" /></small>
        </div><!--//container-->
    </footer><!--//footer-->
     
    <!-- Javascript -->          
    <script type="text/javascript" src="assets/plugins/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>    
    <script type="text/javascript" src="assets/plugins/jquery.easing.1.3.js"></script>   
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-scrollTo/jquery.scrollTo.min.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="assets/plugins/prism/prism.js"></script>
    <script src="assets/plugins/fileinput/js/fileinput.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/js/main.js"></script>

    <script>
        $(document).on('ready', function() {
            $("#modules").fileinput({
                previewFileType: "text",
                allowedFileExtensions: ["txt"],
                previewClass: "bg-info"
            });
            $("#interactions").fileinput({
                previewFileType: "text",
                allowedFileExtensions: ["txt"],
                previewClass: "bg-info"
            });
            $("#file").fileinput({
                previewFileType: "image",
                browseClass: "btn btn-success",
                browseLabel: "Pick Images",
                browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
                removeClass: "btn btn-danger",
                removeLabel: "Delete",
                removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> "
            });
        });
        function validateForm()
        {
            if (document.forms["upload"]["modules"].value == null || document.forms["upload"]["modules"].value == "")
            {
                document.getElementById("error").innerHTML =
                        "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>Please select a node file.</div>";
                var nodeFile = false;
            } else {
                var ext = $('#modules').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['txt', 'csv']) == -1) {
                    document.getElementById("error").innerHTML =
                            "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>Incorrect format. Please select a valid node file.</div>";
                    var nodeFile = false;
                }
            }
            if (document.forms["upload"]["interactions"].value == null || document.forms["upload"]["interactions"].value == "")
            {
                var linksFile = true;
            } else {
                var ext = $('#interactions').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['txt']) == -1) {
                    document.getElementById("error2").innerHTML =
                            "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>Incorrect format. Please select a valid links file.</div>";
                    var linksFile = false;
                } else linksFile = true;
            }
            if (document.forms["upload"]["file"].value == null || document.forms["upload"]["file"].value == "")
            {
                var images = true;
            } else {
                var ext = $('#file').val().split('.').pop().toLowerCase();
                if ($.inArray(ext, ['jpg']) == -1) {
                    document.getElementById("error3").innerHTML =
                            "<div class=\"alert alert-dismissible alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>Incorrect format. Please select a valid .jpg file.</div>";
                    var images = false;
                } else images = true;
            }
            if ( nodeFile == false || linksFile == false || images == false ) {
                return false;
            }
            else
                return true;
        }
        $('.collapse').collapse()
    </script>
</body>
</html> 

