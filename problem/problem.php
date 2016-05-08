<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// ログイン状態を確認
if (!$ctf->auth->check_login()) {
    // ログイン済みであれば移動
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
		<p>現在以下の問題が公開されいます。</p>
		<div class="accordion">
		    <!-- NETWORK -->
		    <label for="network">Network</label>
		    <input id="network" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('network'); ?>
		    </div>
		    <!-- MISCELLANEOUS -->
		    <label for="miscellaneous">Miscellaneous</label>
		    <input id="miscellaneous" type="checkbox">
		    <div class="content">
			<?php $ctf->prob->display_problems('miscellaneous'); ?>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>
