<?php
//ini_set('display_errors', 1);
require(dirname(__FILE__).'/module/init.php');
$ctf = new CTF();
?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/global/head.php'); ?>
	<title>Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/global/header.php'); ?>
	<!-- STOMACH -->
	<div class="block">
	    <h2>ようこそ</h2>
	    <div class="border-blue">
		<p>Joken CTFへようこそ。</p>
	    </div>
	</div>
    </body>
</html>
