<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// 参加者リストを取得
$ctf->util->get_members();

?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<title>参加者 - Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/../global/header.php'); ?>
	<!-- STOMACH -->
	<div class="block">
	    <?php
	    if ($ctf->util->error_type === 'member') {
		print("<p class=\"warning\">".$ctf->util->error_msg."</p>");
	    }
	    ?>
	    <!-- 参加者のリストを表示 -->
	    <?php for($i = 0; $i < count($ctf->util->list_username); ++$i) { ?>
		<div class="profile">
		    <div class="score-decoration">Pt</div>
		    <div class="score"><?php print($ctf->util->list_score[$i]); ?></div>
		    <div class="image">
			<?php if ($ctf->util->list_image[$i] == null) { // 画像がない ?>
			    <img src="/etc/image/no_image.png" alt="読み込み失敗">
			<?php } else { // 画像がある
			    print("<img src=\"data:".image_type_to_mime_type($ctf->util->list_mime[$i]).
				  ";base64,".base64_encode($ctf->util->list_image[$i])."\" alt=\"読み込み失敗\">");
			} ?>
			<div class="username"><?php print($ctf->util->list_username[$i]); ?></div>
		    </div>
		</div>
	    <?php } ?>
	</div>
    </body>
</html>
