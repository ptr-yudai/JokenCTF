<?php
session_start();
require(dirname(__FILE__).'/../config/config.php');
require(dirname(__FILE__).'/auth.php');
require(dirname(__FILE__).'/util.php');
require(dirname(__FILE__).'/notify.php');
require(dirname(__FILE__).'/prob.php');

/* CTF総括クラス */
class CTF
{
    /*
     */
    function __construct()
    {
	$this->config = new CTFConfig();
	// データベースに接続しておく
	try {
	    $this->pdo = new PDO('mysql:host='.$this->config->db_host.
				 ';dbname='.$this->config->db_name.
				 ';charset=utf8',
				 $this->config->db_username,
				 $this->config->db_password);
	} catch (PDOException $error) {
	    $this->pdo = null;
	}
	// 認証モジュール
	$this->auth = new CTFAuth($this->pdo);
	// 各種ユーティリティ
	$this->util = new CTFUtil($this->pdo);
	// 通知設定
	$this->notify = new CTFNotify($this->config);
	// 出題管理
	$this->prob = new CTFProb($this->pdo, $this->notify);
    }

    /*
       エスケープ
     */
    function h($str)
    {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}
?>
