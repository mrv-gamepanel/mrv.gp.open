<?php
header_remove("X-Powered-By");
$img_src = 'https://'.$_SERVER['HTTP_HOST'].'/assets/img/mrv-logo.png';
?>
<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Links -->
	<link rel="canonical" 					href="https://<?php echo $_SERVER['HTTP_HOST']; ?>" />
	<link rel='shortlink' 					href='https://<?php echo $_SERVER['HTTP_HOST']; ?>' />

	<!-- Other -->
	<meta itemprop="name" 					content="MRV Hosting" />
	<meta itemprop="url" 					content="https://<?php echo $_SERVER['HTTP_HOST']; ?>" />
	<meta itemprop="description" 			content="MRV Hosting is best Hosting solution for you." />
	<meta itemprop="thumbnailUrl" 			content="<?php echo $img_src; ?>" />
	<meta itemprop="image" 					content="<?php echo $img_src; ?>" />
	<link rel="image_src" 					href="<?php echo $img_src; ?>" />
	<meta name="author" 					content="MRV Team" />
	<meta name="keywords" 					content="MRV Hosting" />
	<meta name="abstract" 					content="MRV Hosting" />
	<meta name="distribution" 				content="global" />
	<meta name="googlebot" 					content="index, follow" />
	<meta name="audience" 					content="all" />

    <!-- Bootstrap 4 -->
    <link rel="stylesheet" type="text/css" href="/assets/bootstrap/css/bootstrap.css?v=1">
    <!-- FontAwesome 4.7.0 -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- Fonts -->
	<link href="//fonts.googleapis.com/css?family=Ubuntu:300,400,500,700&display=swap" rel="stylesheet"> 
    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?<?php echo date('d_m'); ?>">
    <link rel="stylesheet" type="text/css" href="/assets/css/header.css?<?php echo date('d_m'); ?>">
	<link rel="stylesheet" type="text/css" href="/assets/css/gp.css?<?php echo date('d_m'); ?>">
	<!-- Alert -->
	<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css">