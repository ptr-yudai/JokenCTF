<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// ログイン状態を確認
if (!$ctf->auth->check_login()) {
    // ログインしていなければ移動
    header("Location: /login/login.php");
}

// 問題の取得を試行
if (!$ctf->prob->get_problem()) {
    header("Location: /problem/problem.php");
}

?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<title><?php print($ctf->h($ctf->prob->prob_title)); ?> - Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/../global/header.php'); ?>
	<!-- 問題一覧 -->
	<div class="block">
	    <h2><?php print($ctf->h($ctf->prob->prob_title)); ?></h2>
	    <div class="border-blue">
		<?php print($ctf->prob->prob_problem); ?>
	    </div>
	</div>
    </body>
</html>
