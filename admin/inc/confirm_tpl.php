<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>確認画面</title>
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
		.done{
			padding-top: 50px;
		}
		.btn{
			margin: 20px 0 20px 0;  
			padding: 10px 40px 10px 40px;
		}
	</style>
</head>
<body>
	<!-- 会員登録確認画面 -->
	<?php
		if($_SESSION["gender"] == 1){
			$gender = "男性";
		}else{
			$gender = "女性";
		}
	?>

	<header>
		<div class="header-logo">
			<h1>会員登録</h1>
		</div>
		<div class="header-menus">
			<!-- 戻るボタン -->
			<form action="" method="post">
				<div class="button">
					<input type="submit" class="btn btn-secondary btn-lg" name="back" value="前に戻る">
				</div>
			</form>
		</div>
	</header>
	<main>
		<?php
			if( $errmessage ){
				echo '<div class="alert alert-danger" role="alert">';
				echo implode("<br>", $errmessage);
				echo "</div>";
			}
		?>
		<form action="" method="post">
			ID　　　　　　　登録後に自動採番<br>
			氏名　　　　　　<?php echo $_SESSION["name_sei"] ?>　<?php echo $_SESSION["name_mei"] ?><br>
			性別　　　　　　<?php echo $gender ?><br>
			住所　　　　　　<?php echo $_SESSION["pref_name"] ?><?php echo $_SESSION["address"] ?><br>
			パスワード　　　セキュリティのため非表示<br>
			メールアドレス　<?php echo $_SESSION["email"] ?><br>
			<input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
			<div class="button">
				<input type="submit" class="btn btn-primary btn-lg" name="members" value="登録完了"><br>
			</div>
			<!-- <button type="button" onclick="history.back()">戻る</button> -->
		</form>
	</main>
</body>
</html>

