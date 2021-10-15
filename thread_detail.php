<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "CommentLogic.php";  //コメント登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	
	$login_member = $_SESSION["login_member"];  //セッションにあるログインユーザーのデータを変数に格納
	var_dump("$login_member");
	echo $login_member["id"];
	//コメントのmember_idがログインしているメンバーのidになるようにする
	$_SESSION["member_id"] = $login_member["id"];

	$id = $_GET["id"];
	echo $id;
	$_SESSION["thread_id"] = $id;  //スレッドのidをセッションに保存(コメント作成に利用)

	$thread = ThreadLogic::getThreadById($id);
	var_dump($thread);


	//コメントするボタンが押されたらバリデーションにかけて、OKならDBにコメントを登録する
	if( isset($_POST["create_comment"]) && $_POST["create_comment"] ){
		if( !$_POST["comment"] ){
			$errmessage[] = "コメントを入力して下さい";
		}else if( mb_strlen($_POST["comment"]) > 500 ){
			$errmessage[] = "コメントは500文字以内で入力してください";
		}
		$_SESSION["comment"] = htmlspecialchars($_POST["comment"], ENT_QUOTES);  //無害化した文字列を代入
		//コメントを登録
		CommentLogic::createComment($_SESSION);
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>スレッド詳細ページ</title>
	<!-- Bootstrapの読み込み -->
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
			padding-top: 150px;
			padding-bottom: 30px;
			background-color: #CCFFFF;
		}
		
	</style>
</head>
<body>
	<!-- step6 画面仕様書 -->
	<header>
		<div class="header-logo">

		</div>
		<div class="header-menus">
			<!-- 新規スレッド作成 -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='thread.php'" value="スレッド一覧に戻る">
			</div>
		</div>
	</header>
	
	<main>
		<div class="container">
			<h2><?php echo h($thread["title"]) ?></h2><br>
			<p><?php echo h($thread["created_at"]) ?></p>
		</div>
		<div class="container">
			
		</div>
	</main>
	<footer>
		<?php
			//バリデーションに引っ掛かったらエラーを表示する
			if( $errmessage ){
				echo '<div class="alert alert-danger" role="alert">';
				echo implode("<br>", $errmessage);
				echo "</div>";
			}
		?>
		<?php 
			//ログインしているかどうかチェックする
			$loginResult = MemberLogic::checkLogin();
			var_dump($loginResult);
		?>
		<!-- ログインしていたらフォームからコメントを投稿できるようにする -->
		<?php if($loginResult): ?>
			<form action="" method="post">
				<textarea class="form-control" name="comment" id="" cols="40" rows="8" value="<?php echo $_SESSION["comment"] ?>"></textarea><br>
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" name="create_comment" value="コメントする"><br>
				</div>
			</form>
		<?php endif; ?>
	</footer>
</body>
</html>