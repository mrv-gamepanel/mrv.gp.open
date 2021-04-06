<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="all,follow">
<!-- Bootstrap CSS-->
<link rel="stylesheet" href="/admin/vendor/bootstrap/css/bootstrap.min.css">
<!-- Font Awesome CSS-->
<link rel="stylesheet" href="/admin/vendor/font-awesome/css/font-awesome.min.css">
<!-- Custom Font Icons CSS-->
<link rel="stylesheet" href="/admin/css/font.css">
<!-- Google fonts - Muli-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
<!-- theme stylesheet-->
<link rel="stylesheet" href="/admin/css/style.default.css" id="theme-stylesheet">
<!-- Custom stylesheet - for your changes-->
<link rel="stylesheet" href="/admin/css/custom.css">
<link rel="stylesheet" href="/admin/css/bl_style.css?<?php echo time(); ?>">
<!-- Select2 -->
<link href="//cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
<style type="text/css" media="screen">
	.selection {
		width: 100%;
	}
	.select2-container--default .select2-selection--single {
	    background-color: transparent;
	    height: calc(2.4rem + 2px);
	    border: 1px solid #444951;
	    background: transparent;
	    border-radius: 0;
	    color: #979a9f;
	    padding: 0.45rem 0.75rem;
	}
	.select2-container--default .select2-selection--single .select2-selection__rendered {
	    color: #979a9f;
	    line-height: 28px;
	}
	.select2-container--default .select2-selection--single .select2-selection__arrow {
	    height: 40px;
	    position: absolute;
	    top: 1px;
	    right: 5px;
	    width: 25px;
	}
</style>
<!-- Alert -->
<link rel="stylesheet" type="text/css" href="/assets/plugins/jquery-toast-plugin/dist/jquery.toast.min.css?<?php echo date('d_m_y'); ?>">

<!--[ALERTS]-->
<div id="msg_alert"><?php echo $Alert->PrintAlert(); ?></div>
<script type="text/javascript">
	setTimeout(function() {
		document.getElementById('msg_alert').innerHTML = '<?php echo $Alert->RemoveAlert(); ?>';
	}, 5000);
</script>