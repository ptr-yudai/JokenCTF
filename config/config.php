<?php
/* サーバー設定 */
class CTFConfig
{
    /*
       この関数では重要な情報を初期化します。
       管理者はあらかじめこれらの項目を必ず設定してください。
     */
    function __construct()
    {
	/*
	   CTFの設定
	 */
	// 開催場所(時刻)
	$this->place = 'Asia/Tokyo';                     // タイムゾーン
	date_default_timezone_set($this->place);      // ここは変更しない
	$this->start_time = date('2016/05/10 12:00:00'); // 開始時刻
	$this->end_time = date('2016/05/10 13:00:00');   // 終了時刻
	
	/*
	   データベースの設定
	 */
	$this->db_host = 'localhost';          // データベースのあるホスト名
	$this->db_name = 'joken_ctf';          // データベース名
	$this->db_username = 'ctf_master';     // アクセスするユーザー名
	$this->db_password = 'debug_password'; // ユーザーのパスワード

	/*
	   Slack通知設定(使用しない場合はFalse)
	 */
	$this->notify_slack = false;    // 通知するか
	$this->slack_webhook = ""; // WebhookのURL
	$this->slack_channel = "#random";       // 投稿チャンネル
	$this->slack_username = "bot";    // 投稿ユーザー名
	// 投稿アイコン
	$this->slack_icon = ":ghost:";
    }

    /*
       開催期限内かを確認する
     */
    function is_ctf_running()
    {
	// 現在時刻を取得
	$date = new DateTime();
	$date->setTimeZone(new DateTimeZone($this->place));
	$now = $date->format('Y/m/d H:i:s');
	// 未開始
	if (strtotime($now) < strtotime($this->start_time)) {
	    return -1;
	}
	// 終了済
	if (strtotime($now) > strtotime($this->end_time)) {
	    return 1;
	}
	// 期間内
	return 0;
    }
}
?>
