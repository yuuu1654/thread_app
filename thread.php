<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>スレッド一覧ページ</title>
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
			padding-top: 300px;
			padding-bottom: 30px;
			background-color: #CCFFFF;
		}
	</style>
</head>
<body>
	<!-- step4 画面仕様書 -->
	<!-- スレッド検索フォーム（1行）とスレッド検索ボタン -->
	<!-- スレッド一覧表示 -->
	<!-- 「スレッドタイトル」をクリックするとスレッド詳細ページに遷移する -->
	<!-- トップに戻るボタン作成 -->

	<!-- step5 画面仕様書/スレッド詳細ページ -->
	<!-- ①スレッドタイトルと登録日時を表示 -->
	<!-- ②スレッド作者名・作成者コメント・登録日時を表示 -->
	<!-- ログイン時のみコメント投稿フォーム・コメントするボタンを表示 -->

	<header>
		<div class="header-logo">
		
		</div>
		<div class="header-menus">
			<!-- 新規スレッド作成 -->
			<div class="button">
				<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='thread_regist.php'" value="新規スレッド作成">
			</div>
		</div>
	</header>
	
	<main>
		<div class="container">
			<form action="">
				<!-- 検索フォーム -->
				<input type="text">
				<!-- 検索ボタン -->
				<input type="submit">
			</form>
		</div>
	</main>
	<footer>
		<div class="button">
			<input type="submit" class="btn btn-secondary btn-lg" onclick="location.href='top.php'" value="トップに戻る">
		</div>
	</footer>
</body>
</html>