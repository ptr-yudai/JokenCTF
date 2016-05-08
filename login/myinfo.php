<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// ログアウト試行
$ctf->auth->logout();

// ログイン状態を確認
if (!$ctf->auth->check_login()) {
    // ログインしていなければ移動
    header("Location: /login/login.php");
}

// アカウント設定変更試行
$ctf->auth->modify_account();
?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<title><?php print($ctf->h($_SESSION['username'])); ?> - Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/../global/header.php'); ?>
	<!-- 設定フォーム -->
	<div class="block">
	    <h2>アカウント設定</h2>
	    <div class="border-blue">
		<p>以下のフォームからアカウント情報を変更できます。何も入力されていない項目は変更されません。</p>
		<form method="post" enctype="multipart/form-data">
		    <input type="hidden" name="type" value="config">
		    <label for="upfile" class="label-upload">
			画像を選択
			<input type="file" name="upfile" id="upfile">
		    </label><br>
		    <label for="username">ユーザー名</label>
		    <input type="text" name="username" id="username" placeholder="ユーザー名"><br>
		    <label for="password">パスワード</label>
		    <input type="password" name="password" id="password" placeholder="パスワード"><br>
		    <label for="password_confirm">パスワード(確認)</label>
		    <input type="password" name="password_confirm" id="password_confirm" placeholder="パスワード(確認)"><br>
		    <?php
		    if ($ctf->auth->error_type === 'config') {
			if ($ctf->auth->error_flag) {
			    print("<p class=\"success\">".$ctf->auth->error_msg."</p>");
			} else {
			    print("<p class=\"warning\">".$ctf->auth->error_msg."</p>");
			}
		    }
		    ?>
		    <input type="submit" value="変更する">
		</form>
	    </div>
	</div>
	<!-- ログアウトフォーム -->
	<div class="block">
	    <h2>ログアウト</h2>
	    <div class="border-blue">
		<p>ログアウトするには以下のボタンをクリックしてください。</p>
		<form method="post">
		    <input type="hidden" name="type" value="logout">
		    <input type="submit" value="ログアウト">
		</form>
	    </div>
	</div>
	<!-- アカウント削除フォーム -->
	<div class="block">
	    <h2 class="warning">アカウント削除</h2>
	    <div class="border-red">
		<p class="warning">アカウントを削除するにはパスワードを入力して以下のボタンをクリックしてください。(この操作は元に戻せません。)</p>
		<form method="post">
		    <input type="hidden" name="type" value="del_me">
		    <input type="password" name="password" placeholder="パスワード" class="warning" required><br>
		    <input type="submit" value="アカウント削除" class="warning">
		</form>
	    </div>
	</div>
    </body>
</html>
