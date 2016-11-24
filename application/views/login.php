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
            <div class="sign-in">
                <h3>Sign in</h3>
                <div class="sign-in-form">
                    <!-- <form method="" action=""> -->
                        <?php echo form_open('auth/validate_credentials');?>
                        <label>User Name:</label>
                        <!-- <input class="user" type="text" name="" value=""> -->
                        <?php echo form_input(array('id'=>'email', 'name' => 'email', 'class'=>'user',
                            'placeholder'=>'Email', 'required'=>'required', 'autofocus'=>'autofocus'  ));?>

                        <div class="clearfix"></div>
                        <label>Password:</label>
                        <!-- <input class="password" type="text" name="" value=""> -->
                         <?php echo form_password(array( 'id'=>'password', 'name' => 'password',
                            'class'=>'password', 'placeholder'=>'Password', 'required'=>'required')); ?>

                        <div class="clearfix"></div>
                       <?php echo form_submit(array('name'=>'signin', 'type'=>'submit', 'value'=>'Sign in',
                        'class'=>'submit-button')); ?>

                        <div class="remember">
                            <span>
                                <?php echo form_input(array('id'=>'remember-me', 'name'=>'remember-me',
                                'type'=>'checkbox', 'value'=>'remember-me', 'class' => 'box')); ?>Remember me
                            </span>
                            <a href="#">Forget password?</a>
                            <div class="clearfix"></div>
                            <a href=<?php echo site_url("auth"); ?> class=" " > Create New Account </a>
                          
                            <?php echo form_close(); ?>
                        </div>
                    <!-- </form> -->
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="template/js/bootstrap.min.js"></script>
</body>

</html>
