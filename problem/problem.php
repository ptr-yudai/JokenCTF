<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// ログイン状態を確認
if (!$ctf->auth->check_login()) {
    // ログインしていなければ移動
    header("Location: /login/login.php");
}

?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<title>問題一覧 - Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/../global/header.php'); ?>
	<!-- 問題一覧 -->
	<div class="block">
	    <h2>問題一覧</h2>
	    <div class="border-blue">
		<p>問題のカテゴリをクリックして選択してください。</p>
		<div class="accordion">
		    <!-- BINARY -->
		    <label for="binary">Binary (<?php print($ctf->prob->count_available_problems('binary')); ?>)</label>
		    <input id="binary" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('binary'); ?>
		    </div>
		    <!-- CRYPTOGRAPHY -->
		    <label for="cryptography">Cryptography (<?php print($ctf->prob->count_available_problems('cryptography')); ?>)</label>
		    <input id="cryptography" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('cryptography'); ?>
		    </div>
		    <!-- FORENSICS -->
		    <label for="forensics">Forensics (<?php print($ctf->prob->count_available_problems('forensics')); ?>)</label>
		    <input id="forensics" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('forensics'); ?>
		    </div>
		    <!-- MISCELLANEOUS -->
		    <label for="miscellaneous">Miscellaneous (<?php print($ctf->prob->count_available_problems('miscellaneous')); ?>)</label>
		    <input id="miscellaneous" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('miscellaneous'); ?>
		    </div>
		    <!-- NETWORK -->
		    <label for="network">Network (<?php print($ctf->prob->count_available_problems('network')); ?>)</label>
		    <input id="network" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('network'); ?>
		    </div>
		    <!-- WEB -->
		    <label for="web">Web (<?php print($ctf->prob->count_available_problems('web')); ?>)</label>
		    <input id="web" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('web'); ?>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>
