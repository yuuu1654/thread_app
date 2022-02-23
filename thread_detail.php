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



	$thread_id = $threadDetail["id"];  //表示しているスレッドのid
	$comments = CommentLogic::getCommentsById2($thread_id);  //コメントのデータ

	$max = 5; // 1ページの記事の表示数
	$comments_num = count($comments);  //トータルデータ件数

	$max_page = ceil($comments_num / $max);
	$max_page = (int)$max_page;
	//var_dump($max_page);

	if( isset($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $max_page ){
		$page = $_GET["page"];
	}else{
		$page = "1";
	}

	$page = (int)$page;
	//var_dump($page);

	$start = ($page - 1) * $max; // 配列の何番目から取得すればよいか

	// array_sliceは、配列の何番目($start)から何番目(MAX)まで切り取る関数
	$disp_comments = array_slice($comments, $start, $max, true);

	



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
			// //コメントが重複していないかチェック
			// $resultDupComment = CommentLogic::searchDupComment($_SESSION);
			// if( $resultDupComment ){
			// 	//コメントを登録
			// 	CommentLogic::createComment($_SESSION);
			// }

			//コメントを登録
			CommentLogic::createComment($_SESSION);
			$_SESSION["comment"] = "";
			//リダイレクト
			header("Location: thread_detail.php?id=$id&page=$page");
			return;
		}
	}else{  //GETリクエスト
		
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
		form {
			width: 50%;
			margin: 10% auto;
			min-width: 9rem;
			background: darken(#f9f9f9, 10%);
		}
		textarea{
			border-radius: 0.3rem;
  		background: darken(#f9f9f9, 10%);
		}

		.like-btn{
			color: #8899a6;
		}
		.like-btn-unlike{
			color: #ff2581;
		}
		footer{
			margin-top: 0;
			padding-top: 100px;
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
			//バリデーションに引っ掛かったらエラーを表示
			if( $errmessage ){
				echo '<div class="alert alert-danger" role="alert">';
				echo implode("<br>", $errmessage);
				echo "</div>";
			}
		?>
		<div class="container">
			<h2><?php echo h($threadDetail["title"]) ?></h2><br>
			<p><?php echo $comments_num ?>コメント　<?php echo h($threadDetail["created_at"]) ?></p>
		</div>

		


		<!-- ページ切り替えリンク&表示条件 -->
		<?php
			//var_dump($max_page);
			//var_dump($page);
		?>
		<?php if( $page == 1 && $max_page == 1 ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="#" style="pointer-events: none; color: #344853;">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="#" style="pointer-events: none; color: #344853;">次へ></a>
				</div>
			</nav>
		<?php } ?>
		<?php if( $page == 1 && $max_page != 1 ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="#" style="pointer-events: none; color: #344853;">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page + 1); ?>">次へ></a>
				</div>
			</nav>
		<?php } ?>
		<?php if( $page != 1 && $page != $max_page ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page - 1); ?>">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page +1 ); ?>">次へ></a>
				</div>
			</nav>
		<?php } ?>
		<?php if( $page == $max_page && $page != 1 ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page - 1); ?>">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="#" style="pointer-events: none; color: #344853;">次へ></a>
				</div>
			</nav>
		<?php } ?>
		



		<!-- スレッド表示 -->
		<div class="container thread">
			<?php
				//スレッドの投稿者のメンバー詳細
				$member_id = $threadDetail["member_id"];
				$memberDetail = MemberLogic::getMemberById($member_id);
			?>
			投稿者：<?php echo h($memberDetail["name_sei"]) ?><?php echo h($memberDetail["name_mei"]) ?>
			　　　　　　　　　　<?php echo h($threadDetail["created_at"]) ?><br><br>
			<?php 
				if( isset($login_member) && $login_member ){
					echo h("ログインID: ".$login_member["id"]);
				}
			?>
			<br><br>
			<p cols=40 rows=5><?php echo h($threadDetail["content"]) ?></p>
		</div>



		<!-- コメント表示 -->
		<div class="container comment">
			<table class="table">
				<?php
					//ピンクハートを押したら
					if( isset($_GET["destroy"]) && $_GET["destroy"] > 0 ){

						$comment_id = $_GET["destroy"];
						$_SESSION["comment_id"] = $comment_id;
						$_SESSION["page"] = $page;  //ページ情報
						header("Location: comment_dislike_logic.php");
						
					}
					//グレーハートを押したら
					if( isset($_GET["create"]) && $_GET["create"] > 0 ){

						//ログインしていなければ会員登録ページに遷移させる
						if( !isset($_SESSION["login_member"]) || !$_SESSION["login_member"] ){
							header("Location: member_regist.php");
							return;
						}
						$comment_id = $_GET["create"];
						$_SESSION["comment_id"] = $comment_id;
						$_SESSION["page"] = $page;  //ページ情報
						header("Location: comment_like_logic.php");

						//セッションを初期化
						//$_SESSION["comment_id"] = "";
					}
				?>



				<?php foreach($disp_comments as $comment): ?>	
					<tr>
						<td>
							<?php echo h($comment["id"]) ?>.
							<?php 
								//$idを引数にしてメンバーを検索 & そのメンバーの名前を表示
								$id = $comment["member_id"];
								$memberDetail = MemberLogic::getMemberById($id);
							?>
							<?php echo h($memberDetail["name_sei"]) ?><?php echo h($memberDetail["name_mei"]) ?>
							　　　<?php echo h($comment["created_at"]) ?><br><br>
							<?php echo h($comment["comment"]) ?><br>
							<?php
								//コメントのいいね数を取得
								$comment_id = $comment["id"];
								$likeCount = LikeLogic::countLikeById($comment_id);
								//いいねしたかどうかを検索
								$member_id = $_SESSION["member_id"]; //ログインしているメンバーのid
								$likeResult = LikeLogic::searchLikeRelation($member_id, $comment_id);
								//画面に表示されているスレッドのid
								$id = $_SESSION["thread_id"];
							?>
							<?php if($likeResult){ ?>
								<!-- ピンクのハート -->
								<a href="thread_detail.php?id=<?php echo $_SESSION["thread_id"] ?>&page=<?php echo $page ?>&destroy=<?php echo $comment_id ?>"><span class="fa fa-heart like-btn-unlike"></span></a>
								<?php echo $likeCount ?>
							<?php }else{ ?>
								<!-- グレーのハート -->
								<a href="thread_detail.php?id=<?php echo $_SESSION["thread_id"] ?>&page=<?php echo $page ?>&create=<?php echo $comment_id ?>"><span class="fa fa-heart like-btn"></span></a>
								<?php echo $likeCount ?>
							<?php } ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>


















		<!-- ページ切り替えリンク&表示条件 -->
		<?php
			//var_dump($max_page);
			// var_dump($page);
		?>
		<?php if( $page == 1 && $max_page == 1 ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="#" style="pointer-events: none; color: #344853;">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="#" style="pointer-events: none; color: #344853;">次へ></a>
				</div>
			</nav>
		<?php } ?>
		<?php if( $page == 1 && $max_page != 1 ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="#" style="pointer-events: none; color: #344853;">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page + 1); ?>">次へ></a>
				</div>
			</nav>
		<?php } ?>
		<?php if( $page != 1 && $page != $max_page ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page - 1); ?>">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page +1 ); ?>">次へ></a>
				</div>
			</nav>
		<?php } ?>
		<?php if( $page == $max_page && $page != 1 ){ ?>
			<nav>
				<div class="nav-logo">
					<a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page - 1); ?>">前へ></a>
				</div>
				<div class="nav-menus">
					<a href="#" style="pointer-events: none; color: #344853;">次へ></a>
				</div>
			</nav>
		<?php } ?>
	</main>


	<footer>
		<?php 
			//ログインしているかどうかチェック
			$loginResult = MemberLogic::checkLogin();
		?>
		<!-- ログインしていたらフォームからコメントを投稿できるようにする -->
		<?php if($loginResult): ?>
			<form action="" method="post">
				<textarea class="form-control" placeholder="Comment" name="comment" id="" cols="40" rows="8" value="<?php echo $_SESSION["comment"] ?>"></textarea><br>
				<div class="button">
					<input type="submit" class="btn btn-primary btn-lg" name="create_comment" value="コメントする"><br>
				</div>
			</form>
		<?php endif; ?>
	</footer>
</body>
</html>