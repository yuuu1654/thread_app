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
		 * トップ画面に遷移
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

			//ログイン成功時の処理
			$result = MemberLogic::login($_POST["email"], $_POST["password"]);
			$login_member = $_SESSION["login_member"];

			//ログインに失敗したらモードを切り替える
			if( !$result ){ 
				$mode = "input";
				//ログイン失敗時のエラーメッセージがあれば表示する
				$err = array();
				$err[] = $_SESSION["msg"];
				if( $err ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $err);
					echo "</div>";
				}
			}
			//$login_member = $_SESSION["login_member"];
		}
	

	//トップ画面からログアウトボタンが押されたらログアウトしてinputモードに切り替える
	}else if( isset($_POST["logout"]) && $_POST["logout"] ){

		MemberLogic::logout();  //MemberLogicのlogoutメソッドを呼び出す
		$mode = "input";

	}else{  //GETリクエストの時の処理

		//ログインしているか判定して、している場合はトップモードに切り替える
		$result = MemberLogic::checkLogin();
		if ( $result ){
			$mode = "top";
			$login_member = $_SESSION["login_member"];
		}
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
			font-weight: bold;
		}
		h2{
			font-weight: bold;
		}
		div.button{
			text-align: center;
			padding-top: 50px;
		}
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
		.header-menus .logo {
			float: left;
			padding: 20px 20px 0 0;
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
	</style>
</head>
<body>
		<?php if( $mode == "input"){ ?>
			<!-- 入力フォーム画面 -->

			<h1>管理画面</h1>
			<div class="container">
			<div class="mx-auto" style="width:400px;">
				<?php
					//バリデーションに引っ掛かった時のエラーメッセージ
					if( $errmessage ){
						echo '<div class="alert alert-danger" role="alert">';
						echo implode("<br>", $errmessage);
						echo "</div>";
					}
					// //ログイン失敗した時のエラーメッセージ
					// $err[] = $_SESSION["msg"];
					// if( $err ){
					// 	echo '<div class="alert alert-danger" role="alert">';
					// 	echo implode("<br>", $err);
					// 	echo "</div>";
					// }
				?>
				<form action="" method="post">
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
				if( $errmessage ){
					echo '<div class="alert alert-danger" role="alert">';
					echo implode("<br>", $errmessage);
					echo "</div>";
				}
			?>

			<header>
				<div class="header-logo">
					<h2>掲示板管理画面メインメニュー</h2>
				</div>
				<div class="header-menus">
					<h3 class="logo">ようこそ<?php echo h($login_member["name_sei"]) ?><?php echo h($login_member["name_mei"]) ?>さん</h3>
					<!-- ログアウトボタン -->
					<form action="" class="logo" method="POST">
						<input type="submit" name="logout" class="btn btn-secondary btn-lg" name="logout" value="ログアウト">
					</form>
				</div>
			</header>
			<main>
				<div class="container">
					<!-- 会員一覧ボタン -->
					<div class="button">
						<input type="submit" class="btn btn-primary btn-lg" onclick="location.href='member.php'" value="会員一覧">
					</div>
				</div>
			</main>
		<?php } ?>
</body>
</html>

