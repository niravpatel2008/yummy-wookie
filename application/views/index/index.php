<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?=public_path()?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?=public_path()?>css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?=public_path()?>css/AdminLTE.css" rel="stylesheet" type="text/css" />

		<link href="<?=public_path()?>css/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
	<body class="login-page">
    <div class="login-box">
      <div class="login-box-body clearfix">
        <p class="login-box-msg">Login</p>
        <form action="<?=base_url()?>index" method="post">
          <?php
                        if(@$error_msg['invalid_login'] != ''){
                    ?>
                        <div class="box box-solid box-danger">
                            <div class="box-header">
                                <h3 class="box-title"><?=$error_msg['invalid_login']?></h3>
                                <div class="box-tools pull-right">
                                    <button data-widget="remove" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    ?>
                    <div class="form-group <?=(@$error_msg['userid'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['userid'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['userid']?></label>
                        <?php
                            }
                        ?>
                        <input type="text" name="userid" class="form-control allow-enter" placeholder="Email" value='<?php if(isset($_COOKIE['uname'])) echo $_COOKIE['uname']; ?>'/>
                    </div>
                    <div class="form-group <?=(@$error_msg['password'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['password'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['password']?></label>
                        <?php
                            }
                        ?>
                        <input type="password" name="password" class="form-control allow-enter" placeholder="Password" value="<?php if(isset($_COOKIE['password'])) echo $_COOKIE['password']; ?>"/>
                    </div>
					<div class="row">
					<div class="col-xs-8">    
					  <div class="icheck">
						<label>
						  <input type="checkbox" id='chkRemember' name='remember_me'> Remember Me
						</label>
					  </div>                        
					</div><!-- /.col -->
					<div class="col-xs-4">
					  <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
					</div><!-- /.col -->
				  </div>
                </div>
        </form>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.0.2 -->
		<script src="<?=public_path()?>js/jquery.2.0.2.min.js"></script>
		<!-- Bootstrap -->
		<script src="<?=public_path()?>js/bootstrap.min.js" type="text/javascript"></script>
		<!-- AdminLTE App -->
		<script src="<?=public_path()?>js/AdminLTE/app.js" type="text/javascript"></script>
		<script src="<?=public_path()?>js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
  </body>
</html>
