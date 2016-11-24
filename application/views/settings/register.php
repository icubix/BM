<!DOCTYPE HTML>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link href="template/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="template/css/style.css" />
    <title>Bail Sign In Form</title>
</head>

<body>
    <header>
        <a href="#"><img src="template/images/logo.png" alt=""></a>
    </header>
    <section>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <h1 class="text-center login-title">Register with Bail Bond Bookie</h1>
                <div class="account-wall">
                   <!--  <img class="profile-img" src="<?php echo base_url(); ?>images/photo.png" alt=""> -->
                    <?php $attributes = array('class' => 'form-signin');
                        echo form_open('auth/register_credentials', $attributes); ?>
                        
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                        <?php echo form_input(array('id'=>'email', 'name' => 'email', 'class'=>'form-control',
                            'placeholder'=>'Email', 'required'=>'required', 'autofocus'=>'autofocus')); ?>
                            
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <?php echo form_password(array( 'id'=>'password', 'name' => 'password',
                            'class'=>'form-control', 'placeholder'=>'Password', 'required'=>'required')); ?>
                            
                    </div>
                    <br>
                    <?php echo form_submit(array('type'=>'submit', 'value'=>'Register',
                        'class'=>'btn btn-lg btn-primary btn-block')); ?>
                        
                    <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                    <?php echo form_close(); ?>
                    
                </div>
            </div>
        </div>
    </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="template/js/bootstrap.min.js"></script>
</body>

</html>
