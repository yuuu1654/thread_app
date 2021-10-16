<?php
	session_start();
	require_once "../MemberLogic.php";  //会員登録の処理を行うクラスの読み込み
	require_once "../functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み

	/**
	 * ①idを受け取る
	 * ②受け取ったidをもとにgetMemberById($id)メソッドでメンバー詳細を検索
	 * ③getMemberById($id)で返ってきた$memberDetailを以下のフォームの初期値にセットする
	 */

	$id = $_GET["id"];
	$memberDetail = MemberLogic::getMemberById($id);  //idからメンバーの詳細を検索して取得

	if( isset($_POST["delete"]) && $_POST["delete"] ){
		//削除ボタンを押したら、DBからその会員をソフトデリートして会員一覧ページに戻る
		MemberLogic::memberWithdrawal($_SESSION);
		header("Location: member.php");
		return;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員詳細</title>
	<!-- Bootstrapの読み込み -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{

		}
		header{
			height: 80px;
			background-color: #FFCC99; 
		}
		.header-logo {
			float: left;
		}
		.header-logo h1 {
			line-height: 80px;
			padding-left: 40px;
		}
		.header-menus {
			float: right;
		}
		.header-menus .button {
			float: left;
			padding-right: 40px;
		}
		main{
			padding: 50px 10px 10px 10px;
			max-width: 600px;
			margin: 0px auto;
		}
		div.button{
			text-align: center;
		}
		.container{
			text-align: center;
			padding-top: 150px;
		}
		.btn{
			margin: 20px 0 20px 0;  
			padding: 10px 40px 10px 40px;
		}
	</style>
</head>
<body>
	<?php
		if($_SESSION["gender"] == 1){
			$gender = "男性";
		}else{
			$gender = "女性";
		}
	?>

	<header>
		<div class="header-logo">
			<h1>会員詳細</h1>
		</div>
		<div class="header-menus">
			<!-- 会員一覧ページボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='member.php'" value="一覧へ戻る">
			</div>
		</div>
	</header>
	<main>
		
		<form action="" method="post">
			ID　　　　　　　<?php echo $memberDetail["id"] ?><br>
			氏名　　　　　　<?php echo $memberDetail["name_sei"] ?>　<?php echo $_SESSION["name_mei"] ?><br>
			性別　　　　　　<?php echo $gender ?><br>
			住所　　　　　　<?php echo $memberDetail["pref_name"] ?><?php echo $memberDetail["address"] ?><br>
			パスワード　　　セキュリティのため非表示<br>
			メールアドレス　<?php echo $memberDetail["email"] ?><br>
			<input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
			<!-- 編集ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='member_edit.php?id=<?php //echo h($column['id']) ?>'" value="編集">
			</div>
			<!-- 会員削除ボタン -->
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" name="delete" value="削除"><br>
			</div>
		</form>
	</main>
</body>
</html>