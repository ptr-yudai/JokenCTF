<?php
//ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// ログイン試行
$ctf->auth->login();
// サインアップ試行
$ctf->auth->signup();

// ログイン状態を確認
if ($ctf->auth->check_login()) {
    // ログイン済みであれば移動
    header("Location: /login/myinfo.php");
}

?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<title>ログイン - Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/../global/header.php'); ?>
	<!-- ログインフォーム -->
	<div class="block">
	    <h2>ログイン</h2>
	    <div class="border-blue">
		<p>既にアカウントを持っている場合は以下のフォームからログインしてください。</p>
		<form method="post">
		    <input type="hidden" name="type" value="login">
		    <input type="text" name="username" placeholder="ユーザー名" required><br>
		    <input type="password" name="password" placeholder="パスワード" required><br>
		    <?php
		    if ($ctf->auth->error_type === 'login') {
			if ($ctf->auth->error_flag) {
			    print("<p class=\"success\">".$ctf->auth->error_msg."</p>");
			} else {
			    print("<p class=\"warning\">".$ctf->auth->error_msg."</p>");
			}
		    }
		    ?>
		    <input type="submit" value="ログイン">
		</form>
	    </div>
	</div>
	<!-- 登録フォーム -->
	<div class="block">
	    <h2>新規登録</h2>
	    <div class="border-blue">
		<p>アカウントを持っていない場合は以下のフォームから登録してください。</p>
		<form method="post">
		    <input type="hidden" name="type" value="signup">
		    <input type="text" name="username" placeholder="ユーザー名"><br>
		    <input type="password" name="password" placeholder="パスワード"><br>
		    <input type="password" name="password_confirm" placeholder="パスワード(確認)"><br>
		    <?php
		    if ($ctf->auth->error_type === 'signup') {
			if ($ctf->auth->error_flag) {
			    print("<p class=\"success\">".$ctf->auth->error_msg."</p>");
			} else {
			    print("<p class=\"warning\">".$ctf->auth->error_msg."</p>");
			}
		    }
		    ?>
		    <input type="submit" value="登録">
		</form>
	    </div>
	</div>
    </body>
</html>
