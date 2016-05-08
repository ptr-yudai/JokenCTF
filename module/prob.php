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
	$statement = $this->pdo->prepare('SELECT id FROM problem WHERE category=:category;');
	$statement->bindParam(':category', $category, PDO::PARAM_STR);
	$statement->execute();

	// 問題が見つかった
	if ($statement->rowCount() > 0) {
	    // 問題がある
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
