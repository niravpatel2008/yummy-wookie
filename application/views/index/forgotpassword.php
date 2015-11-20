<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?=public_path()?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?=public_path()?>css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?=public_path()?>css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-black">
        <div class="form-box" id="login-box">
            <div class="header">Forgot Password</div>
            <form action="" method="post">
                <div class="body bg-gray">
                    <div id="flash_msg">
                        <?php
                            if (@$flash_msg['flash_type'] == "success") {
                                echo success_msg_box($flash_msg['flash_msg']);
                            }

                            if (@$flash_msg['flash_type'] == "error") {
                                echo error_msg_box($flash_msg['flash_msg']);
                            }
                        ?>
                    </div>
                    <div class="form-group <?=(@$error_msg['email'] != '')?'has-error':'' ?>">
                        <?php
                            if(@$error_msg['email'] != ''){
                        ?>
                            <label for="inputError" class="control-label"><i class="fa fa-times-circle-o"></i><?=$error_msg['email']?></label>
                        <?php
                            }
                        ?>
                        <input type="text" name="email" class="form-control allow-enter" placeholder="Email"/>
                    </div>

                </div>
                <div class="footer">
                    <button type="submit" class="btn bg-olive btn-block sumitbtn">Submit</button>
                </div>
            </form>
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?=public_path()?>js/bootstrap.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="<?=public_path()?>js/AdminLTE/app.js" type="text/javascript"></script>
    </body>
</html>
