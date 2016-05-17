<?php
class CTFNotify
{
    /*
       初期化
     */
    function __construct($config)
    {
	// Slack
	$this->slack = $config->notify_slack;
	$this->slack_webhook = $config->slack_webhook;
	$this->slack_channel = $config->slack_channel;
	$this->slack_username = $config->slack_username;
	$this->slack_icon = $config->slack_icon;
    }

    /*
       通知する
     */
    function notify($message)
    {
	if ($this->slack === true) {
	    $ch = curl_init();
	    curl_setopt_array($ch,
			      $this->slack_create_options($message));
//	    print_r($this->slack_create_options($message));
	    curl_exec($ch);
            curl_close($ch);
	}
    }

    /*
       送信データを生成(Slack用)
     */
    function slack_create_options($message)
    {
	return array(
            CURLOPT_URL            => $this->slack_webhook, // URL
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => array(
		// ここに実体
		'payload' => json_encode(
		    array(
			'channel'    => $this->slack_channel,
			'username'   => $this->slack_username,
			'icon_emoji' => $this->slack_icon,
			'text'       => $message
		    )
		)
	    ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
	);
    }
}
?>
