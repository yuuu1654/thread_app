<?php
	session_start();
	
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員一覧ページ</title>
</head>
<body>
	<header>
		<div class="header-logo">
			<h1>会員一覧</h1>
		</div>
		<div class="header-menus">
			<!-- 会員一覧ページボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='login.php'" value="トップへ戻る">
			</div>
		</div>
	</header>
</body>
</html>