<?php
ini_set('display_errors', 1);
require(dirname(__FILE__).'/../module/init.php');
$ctf = new CTF();
// 参加者一覧を取得
$ctf->util->get_members();
?>
<!DOCTYPE html>
<html>
    <!-- HEAD -->
    <head>
	<?php require(dirname(__FILE__).'/../global/head.php'); ?>
	<title>順位 - Joken CTF</title>
    </head>

    <!-- BODY -->
    <body>
	<!-- HEADER -->
	<?php require(dirname(__FILE__).'/../global/header.php'); ?>
	<!-- 設定フォーム -->
	<div class="block">
	    <h2>順位</h2>
	    <div class="border-blue">
		<p>現在の順位は以下の通りです。</p>
		<table class="ranking">
		    <tr>
			<th>順位</th>
			<th>名前</th>
			<th>得点</th>
		    </tr>
		    <?php for($i = 0; $i < count($ctf->util->list_username); ++$i) { ?>
			<tr>
			    <td><?php print($i + 1); ?></td>
			    <td><?php
				print($ctf->util->list_username[$i]." ");
				if ($ctf->util->list_image[$i] == null) {
				    print("<img src=\"/etc/image/no_image.png\" alt=\"失\">");
				} else {
				    print("<img src=\"data:".image_type_to_mime_type($ctf->util->list_mime[$i]).
					  ";base64,".base64_encode($ctf->util->list_image[$i])."\" alt=\"失\">");
				}
				?></td>
			    <td><?php print($ctf->util->list_score[$i]); ?></td>
			</tr>
		    <?php } ?>
		</table>
	    </div>
	</div>
    </body>
</html>
