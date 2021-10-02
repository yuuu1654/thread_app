<?php
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	session_start();

	//メールアドレス&パスワードのバリデーション
	/**
	 * 登録したメールアドレス・パスワードと一致しない場合もエラーを返す処理
	 */
	
	if ( !$_POST["email"] || !$_POST["password"] ){
		$errmessage[] = "IDもしくはパスワードが間違っています";
	}

	$_SESSION["email"] = htmlspecialchars($_POST["email"], ENT_QUOTES);  //無害化した文字列を入力
	$_SESSION["password"] = htmlspecialchars($_POST["password"], ENT_QUOTES);  //無害化した文字列を代入

	if ( count($errmessage) > 0 ){
		//エラーがあった場合はログイン画面に戻す
		$_SESSION["errmessage"] = $errmessage;
		header("Location: login.php");
		return;
	}

	//ログイン成功時の処理
	echo "ログインしました！"


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<h1>トップページ（ログイン状態時）</h1>
	<!-- ログイン状態によって画面を切り替えるd -->
	<!-- ログイン状態でのトップページ（デフォルト） -->
	<!-- ヘッダー左に会員氏名を表示、(step2 画面仕様書) -->
	<!-- ヘッダー右にスレッド一覧ボタン・新規スレッド作成ボタン・ログアウトボタンの3つを実装する -->
</body>
</html>