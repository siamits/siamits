<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?php echo Theme::get('title'); ?></title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo URL::to('public/themes/adminlte2');?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo URL::to('public/themes/adminlte2');?>/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo URL::to('public/themes/adminlte2');?>/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
    <?php echo Theme::asset()->styles(); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <?php echo Theme::asset()->scripts(); ?>
</head>

<body class="skin-blue sidebar-mini">
    <div class="wrapper">

<?php   $param = array(
            'user' => $user,
        );
?>

        <?php echo Theme::partial('header', $param); ?>
        
        <!-- Left side column. contains the logo and sidebar -->
        <?php echo Theme::partial('sidebar', $param); ?>

        <!-- Content Wrapper. Contains page content -->
        <?php echo Theme::content(); ?>
        
        <!-- /.content-wrapper -->
        <?php echo Theme::partial('footer'); ?>

        <!-- Control Sidebar -->
        <?php echo Theme::partial('controlsidebar'); ?>
        
    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.1.3 -->
    <script src="<?php echo URL::to('public/themes/adminlte2');?>/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo URL::to('public/themes/adminlte2');?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- Slimscroll -->
    <script src="<?php echo URL::to('public/themes/adminlte2');?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- FastClick -->
    <script src="<?php echo URL::to('public/themes/adminlte2');?>/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo URL::to('public/themes/adminlte2');?>/dist/js/app.min.js" type="text/javascript"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo URL::to('public/themes/adminlte2');?>/dist/js/demo.js" type="text/javascript"></script>

    <?php echo Theme::asset()->container('footer')->scripts();?>
    <?php echo Theme::asset()->container('inline_script')->scripts();?>
</body>

</html>