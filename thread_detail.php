<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "CommentLogic.php";  //コメント登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み
	require_once "LikeLogic.php";

	
	$login_member = $_SESSION["login_member"];  //セッションにあるログインユーザーのデータを変数に格納
	//var_dump("$login_member");

	echo $login_member["id"];
	//コメントのmember_idがログインしているメンバーのidになるようにする
	$_SESSION["member_id"] = $login_member["id"];

	$id = $_GET["id"];
	$_SESSION["thread_id"] = $id;  //スレッドのidをセッションに保存(コメント作成に利用)

	$threadDetail = ThreadLogic::getThreadById($id);  //idからスレッドの詳細を検索
	//var_dump($threadDetail);


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
	<!-- font-awesomeの読み込み -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
			padding: 50px 0 100px 0;
		}

		nav{
			height: 40px;
			background-color: #FFCC99; 
		}
		.nav-logo {
			float: left;
			text-align: center;
			vertical-align: middle;
		}
		.nav-menus {
			float: right;
			text-align: center;
		}
		.nav-logo a{
			line-height: 40px;
			padding: 0 25px 0 25px;
			display: block;
		}
		.nav-menus a{
			line-height: 40px;
			padding: 0 25px 0 25px;
			display: block;
		}

		.thread{
			color: blue;
			max-width: 500px;
			margin: 0px auto;
		}
		.comment{
			max-width: 500px;
			margin: 0px auto;
		}

		.like-btn{
			color: #8899a6;
		}
		.like-btn-unlike{
			color: #ff2581;
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
		<?php
			//総コメント数を取得する
			$thread_id = $threadDetail["id"];
			$commentCount = CommentLogic::countCommentsById($thread_id);
			//var_dump($commentCount);
		?>
		<div class="container">
			<h2><?php echo h($threadDetail["title"]) ?></h2><br>
			<p><?php echo $commentCount ?>コメント　<?php echo h($threadDetail["created_at"]) ?></p>
			<!-- いいねアイコン -->
			<span class="fa fa-heart like-btn-unlike"></span><br>
			<span class="fa fa-heart like-btn"></span>
		</div>

		<nav>
			<div class="nav-logo">
				<!-- 前へリンク -->
				<a class="link" href="#">前へ></a>
			</div>
			<div class="nav-menus">
				<!-- 次へリンク -->
				<a href="#">次へ></a>
			</div>
		</nav>

		<div class="container thread">
			<!-- スレッド表示 -->
			<?php
				//スレッドの投稿者のメンバー詳細を検索
				$member_id = $threadDetail["member_id"];
				$memberDetail = MemberLogic::getMemberById($member_id);
			?>
			投稿者：<?php echo h($memberDetail["name_sei"]) ?><?php echo h($memberDetail["name_mei"]) ?>
			<?php echo h($threadDetail["created_at"]) ?>
			<p cols=40 rows=5><?php echo h($threadDetail["content"]) ?></p>
		</div>










		<div class="container comment">
			<!-- コメント表示 -->
			<?php
				//thread_idが共通のコメントを全てコメントidの昇順で表示する
				$comments = CommentLogic::getCommentsById($thread_id);
				var_dump($comments);
				var_dump($thread_id);
			?>
			<table class="table">
				<?php foreach($comments as $comment): ?>	
					<tr>
						<td>
							<?php echo h($comment["id"]) ?>
							<?php echo h($comment["name_sei"]) ?><?php echo h($comment["name_mei"]) ?>
							<?php echo h($comment["created_at"]) ?>
						</td><br><br>
						<td>
							<?php echo h($comment["comment"]) ?>

							<?php
								//指定したcomment_idに対する総いいね数を取得する
								$comment_id = $comment["id"];  //コメントのid
								$likeCount = LikeLogic::countLikeById($comment_id);
								//いいねしたかどうか検索して、なければいいね出来るようにする
								$current_member = $_SESSION["login_member"];
								$member_id = $current_member["id"];  //ログインしているメンバーのid
								$likeResult = LikeLogic::searchLikeRelation($member_id, $comment_id)
							?>
							
							<?php if($likeResult): ?>
								<span class="fa fa-heart like-btn-unlike"></span><br>
							<?php else: ?>
								<span class="fa fa-heart like-btn"></span>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>










		<nav>
			<div class="nav-logo">
				<!-- 前へリンク -->
				<a class="link" href="#">前へ></a>
			</div>
			<div class="nav-menus">
				<!-- 次へリンク -->
				<a href="#">次へ></a>
			</div>
		</nav>
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