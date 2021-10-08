<?php
	session_start();
	$mode = "input";
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	


	if( isset($_POST["back"]) && $_POST["back"] ){
		//何もしない
	}else if( isset($_POST["login"]) && $_POST["login"] ){
		/**
		 * トップ画面
		 */

		//ログインIDのバリデーション
		
		if ( !$_POST["email"] ){
			$errmessage[] = "メールアドレスは入力必須です";
		}
		$_SESSION["email"] = htmlspecialchars($_POST["email"], ENT_QUOTES);  //無害化した文字列を入力


		
		//パスワードのバリデーション
		if( !$_POST["password"] ){
			$errmessage[] = "パスワードは入力必須です";
		}
		$_SESSION["password"] = htmlspecialchars($_POST["password"], ENT_QUOTES);  //無害化した文字列を代入


		//エラーメッセージの有無でモード変数の切り替え
		if( $errmessage ){
			$mode = "input";
		}else{
			$mode = "top";
		}
	

	//トップ画面からログアウトボタンが押されたらログアウトしてinputモードに切り替える
	}else if( isset($_POST["logout"]) && $_POST["logout"] ){
		
		MemberLogic::logout();  //MemberLogicのlogoutメソッドを呼び出す
		$mode = "input";
		

	}else{
		
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>管理画面トップ</title>
	<!-- Bootstrapの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		h1{
			text-align: center;
		}
		div.button{
			text-align: center;
			padding-top: 50px;
		}
		body{
			padding: 10px;
			max-width: 600px;
			margin: 0px auto;
		}
		.container{
			text-align: center;
			padding-top: 150px;
		}
		.btn{
			margin: 20px 0 20px 0;  
			padding: 10px 40px 10px 40px;
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
		footer{
			padding-top: 300px;
			padding-bottom: 30px;
			background-color: #CCFFFF;
		}
	</style>
</head>
<body>
		<?php if( $mode == "input"){ ?>
			<!-- 入力フォーム画面 -->
			<?php
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>

			<h1>管理画面</h1>
			<div class="container">
			<div class="mx-auto" style="width:400px;">
				<form action="./top.php" method="post">
					<!-- メールアドレスのみ初期値を表示する -->
					<p>
						メールアドレス（ID）<input type="email"　class="form-control" name="email" value=""><br>
					</p>
					<p>
						パスワード　　　　　<input type="password"　class="form-control" name="password" value=""><br>
					</p>
					<div class="button">
						<input type="submit" class="btn btn-primary btn-lg" name="login" value="ログイン">
					</div>
				</form>
				
			</div>
		</div>
			
		<?php } else if( $mode == "top"){ ?>
			<!-- 管理トップ画面 -->
			
			<?php 
				//連想配列の中身を表示
				print_r($_POST); 
			?>

			<?php
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>

			<header>
				<div class="header-logo">
					<p>ようこそ<?php echo h($login_member["name_sei"]) ?><?php echo h($login_member["name_mei"]) ?>さん</p>
				</div>
				<div class="header-menus">
					<!-- ログアウトボタン -->
					<form action="" class="button" method="POST">
						<input type="submit" name="logout" class="btn btn-secondary btn-lg" name="logout" value="ログアウト">
					</form>
				</div>
			</header>
			<main>
				<div class="container">
					<h1>⭕️⭕️掲示板</h1>
				</div>
			</main>
			
		<?php } else { ?>
			<!-- 完了画面 -->
			<div class="container">
				<h1>会員登録完了</h1>
				<p class="done">会員登録が完了しました。</p>
			</div>
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
			</div>
		<?php } ?>
</body>
</html>

