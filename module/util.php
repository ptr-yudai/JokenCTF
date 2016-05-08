<?php 
class CTFUtil
{
    /*
       初期化
     */
    function __construct($pdo)
    {
	// リスト
	$this->list_username = array();
	$this->list_score = array();
	$this->list_image = array();
	$this->list_mime = array();
	// データベース
	$this->pdo = $pdo;
	// エラー
	$this->error_type = '';
	$this->error_flag = false;
	$this->error_msg = "";
    }

    /*
       メンバーリストを取得する
     */
    function get_members()
    {
	// データベースに接続できていない
	if ($this->pdo === null) {
	    $this->fatal_error('member', "データベースに接続できません。", false);
	    return;
	}

	// ユーザー一覧を取得する
	$statement = $this->pdo->prepare('SELECT user,score,image,mime FROM account ORDER BY score ASC');
	$ret = $statement->execute();
	
	// クエリに失敗
	if (!$ret) {
	    $this->fatal_error('member', "ログイン要求に失敗しました。", false);
	    return;
	}

	// 初期化
	$this->list_username = array();
	$this->list_score = array();
	$this->list_image = array();
	$this->list_mime = array();
	// 一覧を作成
	while($result = $statement->fetch()) {
	    array_push($this->list_username, $result['user']);
	    array_push($this->list_score, $result['score']);
	    array_push($this->list_image, $result['image']);
	    array_push($this->list_mime, $result['mime']);
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
