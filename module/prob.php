<?php
class CTFProb
{
    /*
       初期化
     */
    function __construct($pdo, $notify)
    {
	// データベース
	$this->pdo = $pdo;
	// 通知
	$this->notify = $notify;
	// エラー
	$this->error_type = "";
	$this->error_flag = false;
	$this->error_msg = "";
	// 問題
	$this->prob_id = "";
	$this->prob_title = "";
	$this->prob_problem = "";
	$this->prob_note = "";
	$this->prob_flag = "";
	$this->prob_category = "";
	$this->prob_score = 0;
	$this->prob_solved = 0;
	$this->prob_last_user = "";
	$this->prob_last_date = "";
    }

    /*
       フラグのチェックを試行する
     */
    function post_flag()
    {
	// 正常なフラグをフィルタリングする
	if (empty($_POST['flag'])) return;
	
	// データベースに接続できていない
	if ($this->pdo === null) {
	    $this->fatal_error('problem', "データベースに接続できません。", false);
	    return;
	}

	// 解答済み
	if ($this->check_solved($_SESSION['username'], $this->prob_id)) {
	    $this->fatal_error('problem', "この問題は既に解答済みです。", true);
	    return;
	}

	// フラグを確認する
	if (md5($_POST['flag']) !== $this->prob_flag) {
	    // 不正解
	    $this->fatal_error('problem', "送信されたフラグは不正解です。", false);
	    return;
	}
	// 正解
	$this->fatal_error('problem', "送信されたフラグは正解です。".$this->prob_score."[pt]を獲得しました。", true);
	
	// 解答者のスコアを取得する
	$statement = $this->pdo->prepare('SELECT score,solved FROM account WHERE user=:user;');
	$statement->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->execute();
	if ($statement->rowCount() <= 0) {
	    // 異常
	    $this->fatal_error('problem', "フラグの送信に失敗しました。一度ログアウトしてから試してください。", false);
	    return;
	}
	$result = $statement->fetch();
	
	// 解答者のスコアを加算する
	$statement = $this->pdo->prepare('UPDATE account SET score=:new_score, solved=:new_solved WHERE user=:user;');
	$statement->bindValue(':new_score', (int)$result['score'] + $this->prob_score, PDO::PARAM_INT);
	$statement->bindValue(':new_solved', $result['solved'].(string)$this->prob_id.",", PDO::PARAM_STR);
	$statement->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->execute();

	// 問題の情報を更新する
	$statement = $this->pdo->prepare('UPDATE problem SET solved=:new_solved, last_user=:last_user, last_date=:last_date  WHERE id=:id;');
	$statement->bindValue(':new_solved', (int)$this->prob_solved+1, PDO::PARAM_INT);
	$statement->bindParam(':last_user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->bindValue(':last_date', date('Y-m-d H:i:s'), PDO::PARAM_STR);
	$statement->bindParam(':id', $this->prob_id, PDO::PARAM_INT);
	$statement->execute();
	
	// 現在の情報を更新する
	$this->prob_last_user = $_SESSION['username'];
	$this->prob_last_date = date('Y-m-d H:i:s');
	$this->prob_solved = $this->prob_solved + 1;

	// 通知を送る
	$this->notify->notify("".$_SESSION['username']."が"
			     .$this->prob_category."の「"
			     .$this->prob_title."」を解答し、"
			     .$this->prob_score."[pt]を獲得しました。\n現在の合計得点は"
			     .((int)$result['score'] + $this->prob_score)."[pt]で"
			     .$this->get_rank($_SESSION['username'])."位です。");
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
	    $this->prob_id = (int)$_GET['id'];
	    $this->prob_title = $result['title'];
	    $this->prob_problem = $result['problem'];
	    $this->prob_note = $result['note'];
	    $this->prob_flag = md5($result['flag']);
	    $this->prob_category = $result['category'];
	    $this->prob_score = $result['score'];
	    $this->prob_solved = (int)$result['solved'];
	    $this->prob_last_user = $result['last_user'];
	    $this->prob_last_date = $result['last_date'];
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
	$statement = $this->pdo->prepare('SELECT id,title,score,solved FROM problem WHERE category=:category ORDER BY score ASC;');
	$statement->bindParam(':category', $category, PDO::PARAM_STR);
	$statement->execute();

	// 問題が見つかった
	if ($statement->rowCount() > 0) {
	    // 問題がある
	    print("<p>以下の問題が公開されています。</p>");
	    print("<table class=\"problem\">");
	    print("<tr>");
	    print("<th></th>");
	    print("<th>題名</th>");
	    print("<th>点数</th>");
	    print("<th>解答数</th>");
	    print("</tr>");
	    while($result = $statement->fetch()) {
		print("<tr>");
		if ($this->check_solved($_SESSION['username'], $result['id'])) {
		    print("<td><p class=\"success\">&#10003;</p></td>");
		} else {
		    print("<td><p class=\"warning\">&#10008;</p></td>");
		}
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
       問題を既に解いているか
     */
    function check_solved($username, $id)
    {
	// 解答済みの問題を選ぶ
	$statement = $this->pdo->prepare('SELECT solved FROM account WHERE user=:username;');
	$statement->bindParam(':username', $username, PDO::PARAM_STR);
	$statement->execute();
	
	// 問題が見つかった
	if ($statement->rowCount() > 0) {
	    $result = $statement->fetch();
	    // 解答済みリストを探索
	    foreach(explode(',', $result['solved']) as $solved) {
		// 解答済み
		if ((int)$solved === (int)$id) return true;
	    }
	}
	return false;
    }

    /*
       指定されたジャンルの問題が何問あるかを調べる
     */
    function count_available_problems($category)
    {
	$count = 0;

	// 解答済みの問題を選ぶ
	$statement = $this->pdo->prepare('SELECT COUNT(id) FROM problem WHERE category=:category;');
	$statement->bindParam(':category', $category, PDO::PARAM_STR);
	$statement->execute();
	
	// 問題が見つかった
	if ($statement->rowCount() > 0) {
	    $result = $statement->fetch();
	    // 数を取得
	    $count = $result['COUNT(id)'];
	}
	return $count;
    }

    /*
       順位を取得する
     */
    function get_rank($username)
    {
	// 順位を取得する
	$statement = $this->pdo->prepare('SELECT (SELECT COUNT(*) FROM account AS b WHERE a.score < b.score) + 1 AS rank FROM account AS a WHERE user=:user ORDER BY a.score DESC;');
	$statement->bindParam(':user', $username, PDO::PARAM_STR);
	$statement->execute();
	if ($statement->rowCount() <= 0) {
	    return 0;
	}
	$result = $statement->fetch();
	return $result['rank'];
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
