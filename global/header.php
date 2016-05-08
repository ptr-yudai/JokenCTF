<header>
    <h1>JOKEN CTF</h1>
    <hr>
    <nav>
	<ul>
	    <li><a href="/index.php">トップ</a></li>
	    <li><a href="/problem/problem.php">問題</a></li>
	    <li><a href="/member/ranking.php">順位</a></li>
	    <li><a href="/member/member.php">参加者</a></li>
	    <?php if ($ctf->auth->check_login()) { // ログイン済み ?>
		<li><a href="/login/myinfo.php">個人設定</a></li>
	    <?php } else { ?>
		<li><a href="/login/login.php">参加</a></li>
	    <?php } ?>
	</ul>
    </nav>
    <hr>
</header>
