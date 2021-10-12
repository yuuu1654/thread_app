<?php
	


	if( isset($_POST["withdrawal"]) && $_POST["withdrawal"] ){
		//退会した際はDBからその会員をソフトデリートしてトップに戻る
		header("Location: top.php");
		return;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>会員退会画面</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<style>
		body{
			background-color: #CCFFFF;
			
		}
		header{
			height: 80px;
			background-color: #FFCC99; 
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
			padding-bottom: 300px;
		}
		.container{
			text-align: center;
			padding-top: 200px;
		}
		
	</style>
</head>
<body>
	<header>
		<div class="header-menus">
			<!-- トップに戻るボタン -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
			</div>
		</div>
	</header>
	<main>
		<div class="container">
			<h1>退会</h1><br>
			<h2>退会しますか？</h2>
		</div>
		<!-- ログアウトボタン -->
		<div class="container">
			<form action="" class="button" method="POST">
				<input type="submit" class="btn btn-primary btn-lg" name="withdrawal" value="退会する">
			</form>
		</div>
	</main>
</body>
</html>