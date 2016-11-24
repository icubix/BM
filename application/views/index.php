<!DOCTYPE html>
<html lang="en">
<head>
<title>Bail Maker <?php echo $page_title; ?></title>
<meta charset="utf-8">

<?php include 'header.php';?>
<!--[if lt IE 7]>
<link rel="stylesheet" href="css/ie6.css" type="text/css" media="all">
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/html5.js"></script>
<script type="text/javascript" src="js/IE9.js"></script>
<![endif]-->
</head>
<body>
<!-- START PAGE SOURCE -->

<?php include 'headermenu.php';?>
 <section class="">
      <?php include 'sidemenu.php';?>
        
      <?php include 'app/'.$page_name.'.php';?>
    </section>
<?php //include 'footer.php';?>
<?php include 'includes.php';?>
</body>

</html>
