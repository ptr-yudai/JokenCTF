<?php
class CTFProb
{
    /*
       初期化
     */
    function __construct($pdo)
    {
	// データベース
	$this->pdo = $pdo;
	// エラー
	$this->error_type = "";
	$this->error_flag = false;
	$this->error_msg = "";
    }
    
    function display_problems($category)
    {
	// データベースに接続できていない
	if ($this->pdo === null) {
	    $this->fatal_error('problem', "データベースに接続できません。", false);
	    return;
	}
	
	// ジャンルから問題を選択すう
	$statement = $this->pdo->prepare('SELECT title,score,solved FROM problem WHERE category=:category;');
	$statement->bindParam(':category', $category, PDO::PARAM_STR);
	$statement->execute();

	// 問題が見つかった
	if ($statement->rowCount() > 0) {
	    // 問題がある
	    print("<p>以下の問題が公開されています。</p>");
	    print("<table class=\"problem\">");
	    print("<tr>");
	    print("<th>題名</th>");
	    print("<th>点数</th>");
	    print("<th>解答数</th>");
	    print("</tr>");
	    while($result = $statement->fetch()) {
		print("<tr>");
		print("<td>".$result['title']."</td>");
		print("<td>".$result['score']."</td>");
		print("<td>".$result['solved']."</td>");
		print("</tr>");
	    }
	    print("</table>");
	} else {
	    // 問題が無い
	    print("<p>このジャンルの問題はまだありません。</p>");
	}
    }

    /*
       エラーを設定する
     */
    function fatal_error($type, $msg, $flag)
    {
	$this->error_type = $type;
	$this->error_msg = $msg;
	$this->error_flag = $flag;
    }
}
?>
