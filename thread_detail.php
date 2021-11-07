<?php
	session_start();
	$errmessage = array();  //エラーメッセージ用の配列を初期化
	require_once "MemberLogic.php";
	require_once "ThreadLogic.php";  //スレッド登録の処理を行うクラスの読み込み
	require_once "CommentLogic.php";  //コメント登録の処理を行うクラスの読み込み
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み
	require_once "LikeLogic.php";

	
	


	if( isset($_SESSION["login_member"]) && $_SESSION["login_member"] ){
		//セッションにあるログインユーザーのデータを変数に格納
		$login_member = $_SESSION["login_member"];

		//コメントのmember_idがログインしているメンバーのidになるようにする
		$_SESSION["member_id"] = $login_member["id"];
	}


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
		

		if( $errmessage ){
			//エラーのみを表示して、コメントのDB登録はしない
		}else{
			//コメントを登録
			CommentLogic::createComment($_SESSION);
			$_SESSION["comment"] = "";
		}
	}else{  //GETリクエストの時の処理
		//セッションを初期化
		// $_SESSION["member_id"] = "";
		$_SESSION["comment_id"] = "";
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
			max-width: 400px;
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
	</header><br>
	
	<main>
		<?php
			//バリデーションに引っ掛かったらエラーを表示する
			if( $errmessage ){
				echo '<div class="alert alert-danger" role="alert">';
				echo implode("<br>", $errmessage);
				echo "</div>";
			}
		?>
		<?php
			//総コメント数を取得する
			$thread_id = $threadDetail["id"];
			$commentCount = CommentLogic::countCommentsById($thread_id);
			//var_dump($commentCount);
		?>
		<div class="container">
			<h2><?php echo h($threadDetail["title"]) ?></h2><br>
			<p><?php echo $commentCount ?>コメント　<?php echo h($threadDetail["created_at"]) ?></p>
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
			　　　　　　　　　　<?php echo h($threadDetail["created_at"]) ?><br><br>
			<p cols=40 rows=5><?php echo h($threadDetail["content"]) ?></p>


			ログインID:<?php var_dump($_SESSION["member_id"]); ?>
		</div>










		<div class="container comment">
			<!-- コメント表示 -->
			<?php
				//thread_idが共通のコメントを全てコメントidの昇順で表示する
				//$comments = CommentLogic::getCommentsById($thread_id);
				$comments = CommentLogic::getCommentsById2($thread_id);
			?>
			
			<table class="table">
				<?php
					//いいね取り消しハートを押した時の処理(ピンクハートを押した時)
					if( isset($_POST["destroy"]) && $_POST["destroy"] ){
						$comment_id = $_POST["destroy"];
						$_SESSION["comment_id"] = $comment_id;
						header("Location: comment_dislike_logic.php");
					}
					//いいね作成ハートを押した時の処理(グレーハートを押した時)
					if( isset($_POST["create"]) && $_POST["create"] ){
						//ログインしていなければ会員登録ページに遷移させる
						if( !isset($_SESSION["login_member"]) || !$_SESSION["login_member"] ){
							header("Location: member_regist.php");
							return;
						}
						$comment_id = $_POST["create"];
						$_SESSION["comment_id"] = $comment_id;
						header("Location: comment_like_logic.php");
					}
				?>
				<?php foreach($comments as $comment): ?>	
					<tr>
						<td>
							<?php echo h($comment["id"]) ?>.
							<?php 
								$id = $comment["member_id"];
								//$idを引数にしてメンバーを検索して、そのメンバーの名前を表示する
								$memberDetail = MemberLogic::getMemberById($id);
							?>
							<?php echo h($memberDetail["name_sei"]) ?><?php echo h($memberDetail["name_mei"]) ?>
							　　　<?php echo h($comment["created_at"]) ?><br><br>
							<?php echo h($comment["comment"]) ?><br>



							<?php
								//指定したcomment_idに対する総いいね数を取得する
								$comment_id = $comment["id"];  //コメントのid
								var_dump($comment_id);
								
								$likeCount = LikeLogic::countLikeById($comment_id);

								//いいねしたかどうか検索して、なければいいね出来るようにする
								$member_id = $_SESSION["member_id"]; //ログインしているメンバーのid
								var_dump($member_id);

								$likeResult = LikeLogic::searchLikeRelation($member_id, $comment_id);
								//画面に表示されているスレッドのid
								$id = $_SESSION["thread_id"];
							?>
							

							<?php if($likeResult): ?>
								<!-- ピンクのハート -->
								<!-- ハートをクリックしたらポストで送信してnameがある時にdestroyメソッドを実行する -->
								<form method="POST" name="destroy_like" action="thread_detail.php?id=<?php echo $id ?>">
									<input type="hidden" name="destroy" value="<?php echo $comment["id"] ?>">
									<a href="#" onclick="document.forms.destroy_like.submit();"><span class="fa fa-heart like-btn-unlike"></span></a>
								</form>
								<?php echo $likeCount ?>
							<?php else: ?>
								<!-- グレーのハート -->
								<form method="POST" name="create_like" action="thread_detail.php?id=<?php echo $id ?>">
									<input type="hidden" name="create" value="<?php echo $comment["id"] ?>">
									<a href="#" onclick="document.forms.create_like.submit();"><span class="fa fa-heart like-btn"></span></a>
								</form>
								<?php echo $likeCount ?>
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
			//ログインしているかどうかチェックする
			$loginResult = MemberLogic::checkLogin();
			//var_dump($loginResult);
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