<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	
	require_once "MemberLogic.php";
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	
	// 「ログイン」ボタンが押されて、POST通信のとき
	if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
		//emailもしくはpasswordが空だった時のエラー
		if ( !$_POST["email"] || !$_POST["password"] ){
			$errmessage["msg"] = "IDもしくはパスワードが空です";
		}


		/**
		 * 警告：count（）：パラメーターは、Countableを実装する配列またはオブジェクトである必要があります。
		 */
		if ( count($errmessage) > 0 ){
			//メールアドレスの検索または、パスワードの照会に失敗してエラーがあった場合はログイン画面に戻す(MemberLogic.php)
			$_SESSION = $errmessage;  //セッションにエラーメッセージを保存
			header("Location: login.php");
			return;
		}


		//ログイン成功時の処理
		$result = MemberLogic::login($_POST["email"], $_POST["password"]);
		//ログイン失敗時の処理
		if( !$result ){
			header("Location: login.php");
			return;
		}
		echo "ログイン成功です";
		$login_member = $_SESSION["login_member"];  //セッションにあるログインユーザーのデータを変数に格納
		//デバッグ用表示
		var_dump($login_member);

	}else{  //GETリクエストだった場合の処理

		//ログインしているか判定して、していなかったらlogout.phpに遷移する
		$result = MemberLogic::checkLogin();
		if ( !$result ){
			$_SESSION["login_err"] = "会員登録してログインしてください！";
			header("Location: logout.php");
			return;
		}

		$login_member = $_SESSION["login_member"];  //セッションにあるログインユーザーのデータを変数に格納

	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>トップページ</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{
			background-color: #CCFFFF;
		}
		header{
			height: 80px;
			background-color: #FFCC99; 
		}
		.header-logo {
			float: left;
		}
		.header-menus {
			float: right;
		}
		.header-menus .button {
			float: left;
			
			padding: 20px 20px 0 0;
		}
		div.button{
			text-align: center;
		}
		main{
			background-color: #CCFFFF;
		}
		.container{
			text-align: center;
			padding-top: 200px;
		}
		footer{
			padding-top: 300px;
			padding-bottom: 30px;
			background-color: #CCFFFF;
		}
	</style>
</head>
<body>
	<h1>トップページ（ログイン状態時）</h1>
	<!-- ログイン状態によって画面を切り替えるd -->
	<!-- ログイン状態でのトップページ（デフォルト） -->
	<!-- ヘッダー左に会員氏名を表示、(step2 画面仕様書) -->
	<!-- ヘッダー右にスレッド一覧ボタン・新規スレッド作成ボタン・ログアウトボタンの3つを実装する -->

	<header>
		<div class="header-logo">
			<p>ようこそ<?php echo h($login_member["name_sei"]) ?><?php echo h($login_member["name_mei"]) ?>さん</p>
		</div>
		<div class="header-menus">
			<!-- スレッド一覧ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='thread.php'" value="スレッド一覧">
			</div>
			<!-- 新規スレッド作成 -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='thread_regist.php'" value="新規スレッド作成">
			</div>
			<!-- ログアウトボタン -->
			<form action="logout.php" class="button" method="POST">
				<input type="submit" class="btn btn-secondary btn-lg" name="logout" value="ログアウト">
			</form>
		</div>
	</header>
	<main>
		<div class="container">
			<h1>⭕️⭕️掲示板</h1>
		</div>
	</main>
</body>
</html>