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
	    <h2><?php print($ctf->h($ctf->prob->prob_title)." - ".$ctf->prob->prob_score."[pt]"); ?></h2>
	    <div class="border-blue">
		<?php print($ctf->prob->prob_problem); ?>
		<?php if ($ctf->prob->prob_note !== '') { ?>
		    <div class="border-white">
			<p><?php print("備考：<br>".$ctf->prob->prob_note); ?></p>
		    </div>
		<?php } ?>
		<div class="border-white">
		    <?php if ($ctf->prob->prob_solved === 0) { ?>
			<p>この問題はまだ誰にも解かれていません。</p>
		    <?php } else { ?>
			<p>この問題は<?php print($ctf->h("")); ?>が最後に解きました。</p>
		    <?php } ?>
		</div>
	    </div>
	</div>
	<div class="block">
	    <h2>送信</h2>
	    <div class="border-blue">
		<p>この問題のフラグは以下のフォームから送信してください。</p>
		<form method="post">
		    <input type="text" name="flag" placeholder="フラグ" required>
		    <input type="submit" value="送信">
		</form>
	    </div>
	</div>
    </body>
</html>
