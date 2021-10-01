<?php
	if( $_POST ){
		//post情報がある時の処理
	}else{
		//getの時の処理を書く
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ログインフォーム</title>
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
	</style>
</head>
<body>
	<!-- メールアドレス・パスワードの2つを入力できるフォームを作成 -->
	<!-- ログインボタン・トップに戻るボタンの2つを作成 -->
	<!-- ログイン成功したらトップ画面に遷移 -->
	<!-- step2　の画面仕様書・課題詳細 -->
	

	<!-- ログインフォーム画面 -->
	<h1>ログイン</h1><br>
	<div class="container">
		<div class="mx-auto" style="width:400px;">
			<form action="./top.php" method="post">
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
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='logout.php'" value="トップに戻る">
			</div>
		</div>
	</div>
</body>
</html>