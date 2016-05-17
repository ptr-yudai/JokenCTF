<?php
class CTFAuth
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
    
    /*
       ログインしているか
     */
    function check_login()
    {
	if (empty($_SESSION['login']) || empty($_SESSION['username'])) return false;
	if ($_SESSION['login'] === true) return true;
	return false;
    }

    /*
       アカウント設定変更を試行する
     */
    function modify_account()
    {
	// 正常な変更ポスト以外をフィルタリング
	if (empty($_POST['type'])) return;
	if ($_POST['type'] !== 'config') return;

	// 画像アップロード
	if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error'])) {
	    $this->upload_image($_FILES['upfile']);
	}
	// 名前変更
	if (isset($_POST['username'])) {
	    if ($_POST['username'] !== $_SESSION['username'] && $_POST['username'] !== '') {
		$this->change_username($_POST['username']);
	    }
	}
	// パスワード
	if (isset($_POST['password'])) {
	    if ($_POST['password'] !== '') {
		
	    }
	}
    }

    /*
       ユーザーの画像を差し替える
     */
    function upload_image($upfile)
    {
	// エラーを取得
	switch($upfile['error']) {
	    case UPLOAD_ERR_OK:
		break;
	    case UPLOAD_ERR_NO_FILE:
		return;
	    case UPLOAD_ERR_INI_SIZE:
	    case UPLOAD_ERR_FORM_SIZE:
		$this->fatal_error('config', "画像のファイルサイズが大きすぎます。", false);
		return;
	    default:
		$this->fatal_error('config', "画像のアップロードに失敗しました。", false);
		return;
	}
	// 画像形式を取得
	if (!$info = getimagesize($upfile['tmp_name'])) {
	    $this->fatal_error('config', "有効な画像ファイルを指定してください。", false);
	    return;
        }
        if (!in_array($info[2], array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG), true)) {
	    $this->fatal_error('config', "未対応の形式です。", false);
	    return;
        }

	// 画像を登録
	$statement = $this->pdo->prepare('UPDATE account SET mime=:mime, image=:image WHERE user=:user;');
	$statement->bindParam(':mime', $info[2], PDO::PARAM_INT);
	$statement->bindParam(':image', file_get_contents($upfile['tmp_name']), PDO::PARAM_LOB);
	$statement->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->execute();
	
	$this->fatal_error('config', "画像を正常にアップロードしました。", true);
    }

    /*
       ユーザー名を変更する
     */
    function change_username($username)
    {
	// ユーザー名を検索する
	if ($this->user_exist($username)) {
	    $this->fatal_error('config', "このユーザー名は既に存在します。", false);
	    return;
	}
	// ユーザー名を変更する
	$statement = $this->pdo->prepare('UPDATE account SET user=:new_user WHERE user=:user;');
	$statement->bindParam(':new_user', $username, PDO::PARAM_STR);
	$statement->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->execute();
	// ユーザー名を検索する
	if ($this->user_exist($username)) {
	    $this->fatal_error('config', "ユーザー名を変更しました。", true);
	    $_SESSION['username'] = $username;
	} else {
	    $this->fatal_error('config', "ユーザー名の変更に失敗しました。", false);
	}
    }

    /*
       ログインを試行する
     */
    function login()
    {
	// 正常なログインポスト以外をフィルタリング
	if (empty($_POST['type'])) return;
	if ($_POST['type'] !== 'login') return;
	if (empty($_POST['username']) || empty($_POST['password'])) return;

	// データベースに接続できていない
	if ($this->pdo === null) {
	    $this->fatal_error('login', "データベースに接続できません。", false);
	    return;
	}
	
	// ユーザー名とパスワードを検索する
	$statement = $this->pdo->prepare('SELECT id FROM account WHERE user=:user AND pass=:pass;');
	$statement->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
	$statement->bindParam(':pass', md5($_POST['password']), PDO::PARAM_STR);
	$statement->execute();

	// ユーザーが見つかったか
	if ($statement->rowCount() > 0) {
	    // ログインに成功
	    $_SESSION['login'] = true;
	    $_SESSION['username'] = $_POST['username'];
	} else {
	    // ログインに失敗
	    $this->fatal_error('login', "ユーザー名かパスワードが間違っています。", false);
	}
    }

    function signup()
    {
	// 正常な登録ポスト以外をフィルタリング
	if (empty($_POST['type'])) return;
	if ($_POST['type'] !== "signup") return;
	if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password_confirm'])) return;
	// ユーザー名とパスワードの長さを確認
	if (strlen($_POST['username']) >= 64) {
	    $this->fatal_error('signup', "ユーザー名は64文字未満に設定してください。", false);
	    return;
	}
	if (strlen($_POST['password']) >= 128) {
	    $this->fatal_error('signup', "パスワードは128文字未満に設定してください。", false);
	    return;
	}
	
	// データベースに接続できていない
	if ($this->pdo === null) {
	    $this->fatal_error('signup', "データベースに接続できません。", false);
	    return;
	}

	// ユーザー名が使用できるかを確認
	if ($this->user_exist($_POST['username'])) {
	    $this->fatal_error('signup', "このユーザー名は既に存在します。", false);
	    return;
	}

	// パスワードが正しいかを確認
	if ($_POST['password'] !== $_POST['password_confirm']) {
	    $this->fatal_error('signup', "パスワードが一致していません。", false);
	    return;
	}
	
	// アカウントに登録する
	$statement = $this->pdo->prepare('INSERT INTO account(user, pass, score, solved) VALUES(:user, :pass, 0, "");');
	$statement->bindParam(':user', $_POST['username'], PDO::PARAM_STR);
	$statement->bindParam(':pass', md5($_POST['password']), PDO::PARAM_STR);
	$statement->execute();
	
	// 成功
	$this->fatal_error('signup', "登録が完了しました。ログインしてください。", true);
    }

    /*
       ログアウトを試行する
     */
    function logout()
    {
	// 正常なログアウトポスト以外をフィルタリング
	if (empty($_POST['type'])) return;
	if ($_POST['type'] !== "logout") return;
	
	// ログアウト
	$_SESSION['login'] = false;
	session_destroy();
	// クッキーを削除する
	if (isset($_COOKIE["PHPSESSID"])) {
	    setcookie('PHPSESSID', '', time() - 1800, '/');
	}
    }

    /*
       アカウント削除を試行する
     */
    function delete_account()
    {
	// 正常な登録ポスト以外をフィルタリング
	if (empty($_POST['type'])) return;
	if ($_POST['type'] !== "del_me") return;
	if (empty($_SESSION['username']) || empty($_POST['password'])) return;
	
	// アカウントを削除する
	$statement = $this->pdo->prepare('SELECT id FROM account WHERE user=:user AND pass=:pass;');
	$statement->bindParam(':user', $_SESSION['username'], PDO::PARAM_STR);
	$statement->bindParam(':pass', md5($_POST['password']), PDO::PARAM_STR);
	$statement->execute();

	// ユーザーが見つかったか
	if ($statement->rowCount() > 0) {
	    // 削除する
	    $result = $statement->fetch();
	    $statement = $this->pdo->prepare('DELETE FROM account WHERE id=:id;');
	    $statement->bindValue(':id', (int)$result['id'], PDO::PARAM_INT);
	    $statement->execute();
	    // ログアウト
	    $_SESSION['login'] = false;
	    session_destroy();
	    // クッキーを削除する
	    if (isset($_COOKIE["PHPSESSID"])) {
		setcookie('PHPSESSID', '', time() - 1800, '/');
	    }
	} else {
	    // アカウント削除に失敗
	    $this->fatal_error('del_me', "パスワードが間違っています。", false);
	}
    }

    /*
       指定したユーザーが存在するかを確認する
     */
    function user_exist($username)
    {
	$statement = $this->pdo->prepare('SELECT id FROM account WHERE user=:user;');
	$statement->bindParam(':user', $username, PDO::PARAM_STR);
	$statement->execute();
	if ($statement->rowCount() > 0) return true;
	return false;
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
