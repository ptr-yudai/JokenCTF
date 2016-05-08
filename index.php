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
	<!-- はじめに -->
	<div class="block">
	    <h2>ようこそ</h2>
	    <div class="border-blue">
		<p>Joken CTFへようこそ。</p>
	    </div>
	</div>
	<!-- ルール -->
	<div class="block">
	    <h2>ルール</h2>
	    <div class="border-blue">
		<h3>開催期間</h3>
		<ul>
		    <li>開始日時：<?php print($ctf->config->start_time); ?></li>
		    <li>終了日時：<?php print($ctf->config->end_time); ?></li>
		</ul>
		
		<h3>競技規定</h3>
		<p>競技についての基本的なルールは以下の通りです。詳しいルールについては運営に連絡してください。</p>
		<ul>
		    <li>各問題には「フラグ」と呼ばれる解答があります。</li>
		    <li>フラグをスコアサーバーに送信し、正解すると得点が入ります。</li>
		    <li>競技終了時点で最も点数の高い人が優勝となります。</li>
		    <li>同点の場合、先にその点数を獲得した人が上位となります。。</li>
		    <li>競技中に問題が追加されたり、変更されたりする場合があります。</li>
		</ul>

		<h3>禁止事項</h3>
		<p>以下の行為は競技の開催時間に関係なく禁止されています。不正行為が発覚した場合は、運営の判断により減点または失格となります。</p>
		<ul>
		    <li>運営のサーバーに対して運営を困難にさせる程の負荷を与える行為。</li>
		    <li>本競技で問題として与えられるサーバー以外への攻撃。(スコアサーバーを含む。)</li>
		    <li>フラグを他人に知らせる行為。</li>
		</ul>
	    </div>
	</div>
    </body>
</html>
