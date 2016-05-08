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
	// 問題
	$this->prob_title = "";
	$this->prob_problem = "";
	$this->prob_note = "";
	$this->prob_category = "";
	$this->prob_score = 0;
	$this->prob_solved = 0;
    }
    
    /*
       問題の取得を試行する
     */
    function get_problem()
    {
	// 正常なポストをフィルタリングする
	if (empty($_GET['id'])) return false;
	
	// データベースに接続できていない
	if ($this->pdo === null) {
	    $this->fatal_error('problem', "データベースに接続できません。", false);
	    return false;
	}

	// IDから問題を選択する
	$statement = $this->pdo->prepare('SELECT * FROM problem WHERE id=:id;');
	$statement->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$statement->execute();
	
	if ($statement->rowCount() > 0) {
	    // 問題がある
	    $result = $statement->fetch();
	    $this->prob_title = $result['title'];
	    $this->prob_problem = $result['problem'];
	    $this->prob_note = $result['note'];
	    $this->prob_category = $result['category'];
	    $this->prob_score = $result['score'];
	    $this->prob_solved = $result['solved'];
	    return true;
	}
	return false;
    }

    /*
       HTMLに問題一覧を描画する
    */
    function display_problems($category)
    {
	// データベースに接続できていない
	if ($this->pdo === null) {
	    print("<p class=\"warning\">データベースに接続できません。</p>");
	    return;
	}
	
	// ジャンルから問題を選択する
	$statement = $this->pdo->prepare('SELECT id,title,score,solved FROM problem WHERE category=:category;');
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
		print("<td><a href=\"/problem/view.php?id=".$result['id'].
		      "\" target=\"_blank\">".$result['title']."</a></td>");
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
