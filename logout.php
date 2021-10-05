<?php
	session_start();
	require_once "MemberLogic.php";

	$login_err = isset($_SESSION["login_err"]) ? $_SESSION["login_err"] : null;  //三項演算子での条件分岐
	unset($_SESSION["login_err"]);

	// 「ログアウト」ボタンが押されて、POST通信のとき
	if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
		//ログアウトする
		MemberLogic::logout();
		
		//連想配列の中身を表示(デバッグ)
		var_dump($_SESSION["login_member"]);
			
	}else{  //GETリクエストだった場合の処理

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
		.header-logo {
			float: left;
		}
		.header-menus {
			float: right;
		}
		.header-menus div {
			float: left;
			padding-left: 15px;
		}
		div.button{
			text-align: center;
		}
		.container{
			text-align: center;
			padding-top: 200px;
		}
	</style>
</head>
<body>
	<p>ログアウト状態でのトップページ</p>
	<!--  -->
	<!-- ヘッダーにスレッド一覧ボタン・新規会員登録フォーム・ログインフォームに遷移するボタン3つを作成する -->

	<header>
		<div class="header-logo">
		
		</div>
		<div class="header-menus">
			<!-- スレッド一覧ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='thread.php'" value="スレッド一覧">
			</div>
			<!-- 新規会員登録 -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='member_regist.php'" value="新規会員登録">
			</div>
			<!-- ログインページ -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='login.php'" value="ログイン">
			</div>
		</div>
	</header>
	<?php
		//エラーメッセージがあれば表示する
		if( isset($login_err) ){
			echo '<div class="alert alert-danger" role="alert">';
			echo implode("<br>", $login_err);
			echo "</div>";
		}
		var_dump($login_err);
	?>
	<main>
		<div class="container">
			<h1>⭕️⭕️掲示板</h1>
		</div>
	</main>
</body>
</html>