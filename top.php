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
			$_SESSION = $errmessage;  
			$_SESSION["input_email"] = $_POST["email"];
			header("Location: login.php");
			return;
		}


		//ログイン成功時の処理
		$result = MemberLogic::login($_POST["email"], $_POST["password"]);
		//ログイン失敗時の処理
		if( !$result ){
			$_SESSION["input_email"] = $_POST["email"];
			header("Location: login.php");
			return;
		}
		$login_member = $_SESSION["login_member"];  

	}else{  //GETリクエスト

		//ログインしているか判定し、していなかったらlogout.phpに遷移する
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
	<link href="https://fonts.googleapis.com/css?family=Cherry+Swash:700 rel="stylesheet">
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
		.header-logo h2 {
			line-height: 80px;
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
			padding-bottom: 300px;
			padding-top: 200px;
		}
		.container{
			text-align: center;
			padding-top: 200px;
		}
		footer{
			height: 80px;
			margin-bottom: 0px;
			background-color: #FFCC99;
		}
		.footer-menus {
			float: right;
		}
		.footer-menus .button {
			float: left;
			padding: 20px 20px 0 0;
		}
	</style>
</head>
<body>
	<!-- ログイン状態によって画面を切り替えるd -->
	<!-- ログイン状態でのトップページ（デフォルト） -->
	<!-- ヘッダー左に会員氏名を表示、(step2 画面仕様書) -->
	<!-- ヘッダー右にスレッド一覧ボタン・新規スレッド作成ボタン・ログアウトボタンの3つを実装する -->

	<header>
		<div class="header-logo">
			<h2>ようこそ<?php echo h($login_member["name_sei"]) ?><?php echo h($login_member["name_mei"]) ?>さん</h2>
		</div>
		<div class="header-menus">
			<!-- 会員一覧ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='admin/member.php'" value="会員一覧">
			</div>
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
			<h1>〜ニッチなアイデアや悩みを気軽にシェアしよう〜</h1><br><br>
			<h1 style="font-weight: bold; font-size: 120px; color: #9999FF; font-family: 'Cherry Swash', cursive;">32channel</h1>
		</div>
	</main>
	<footer>
		<div class="footer-menus">
			<!-- 退会ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='member_withdrawal.php'" value="退会">
			</div>
		</div>
	</footer>
</body>
</html>