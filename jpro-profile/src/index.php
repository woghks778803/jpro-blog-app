<?php
include_once "vendor/scssphp/scssphp/scss.inc.php";
use ScssPhp\ScssPhp\Compiler;

$compiler = new Compiler();
$compiler->setImportPaths('scss/');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>JPro - Profile</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.png" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.3/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
        <!-- slider CSS -->
        <link rel="stylesheet" href="owlcarousel/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="owlcarousel/assets/owl.theme.default.min.css">

        <link href="css/styles.css" rel="stylesheet" />
        <!-- <link href="scss/styles.scss" rel="stylesheet" /> -->
        <style>
            <?php echo $compiler->compileString('@import "styles.scss";')->getCss(); ?>
        </style>
        
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="#page-top">JPro - Profile</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ms-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                        <li class="nav-item"><a class="nav-link" href="#introduce">Introduce</a></li>
                        <li class="nav-item"><a class="nav-link" href="#skills">Skills</a></li>
                        <li class="nav-item"><a class="nav-link" href="#experience">Experience</a></li>
                        <li class="nav-item"><a class="nav-link" href="#portfolio">Project</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Masthead-->
        <?php include "src/header.html"; ?>

        <!-- About -->
        <?php include "src/section/about.html"; ?>
        <!-- Skills -->
        <?php include "src/section/skills.html"; ?>
        <!-- Experience -->
        <?php include "src/section/experience.html"; ?>
        <!-- Portfolio Grid -->
        <?php include "src/section/portfolio.html"; ?>
        <!-- Contact -->
        <?php include "src/section/contact.html"; ?>

        <!-- Footer-->
        <?php include "src/footer.html"; ?>

        <!-- Portfolio Modals-->
        <?php include "src/modal/portfolio.html"; ?>

        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <!-- <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->

        <!-- Plugin JavaScript -->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
        
        <!-- Contact Form JavaScript -->
        <script src="js/jqBootstrapValidation.js"></script>
        <script src="js/contact_me.js"></script>
        
        <!-- slider JavaScript -->
        <script src="owlcarousel/owl.carousel.min.js"></script>
        
        <!-- chart Form JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.min.js"></script>
        
        <!-- ionicons JavaScript -->
        <script src="https://unpkg.com/ionicons@4.5.9-1/dist/ionicons.js"></script>
        
        <!-- list JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>

        <!-- main JavaScript -->
        <script src="js/index.js"></script>
        
        <!-- 상단으로 이동하기 버튼 -->
        <a href="#" class="btn_gotop">
            <ion-icon name="arrow-round-up"></ion-icon>
        </a>

    </body>
</html>
