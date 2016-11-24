<!DOCTYPE html>
<html lang="en">
<head>
<title>Bail Maker <?php echo $page_title; ?></title>
<meta charset="utf-8">

<?php $this->load->view('header');?>
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

<?php $this->load->view('headermenu');?>
 <section class="">
      <?php $this->load->view('sidemenu');?>
        
      <!-- <?php include 'app/'.$page_name.'.php';?> -->
      <?php $this->load->view($page_name); ?>

    </section>
<?php //include 'footer.php';?>
<?php $this->load->view('includes');?>
</body>

</html>
