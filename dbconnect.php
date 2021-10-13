<?php
	//DBに接続する為の設定を定義
	require_once "env.php";
	ini_set("display_errors", true);  //エラー内容を表示する

	function connect(){
		//環境設定値を定義
		$host = DB_HOST;
		$db   = DB_NAME;
		$user = DB_USER;
		$pass = DB_PASS;

		$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";  //データベースに接続する時の情報を定義

		//エラーを検知する為にtry-catchで囲む
		try{
			$pdo = new PDO($dsn, $user, $pass, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       //エラーのモードを決める
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC   //配列をキーとバリューで必ず返す
			]);
			var_dump($pdo);  //接続できたかどうかの確認
			echo "接続成功です！";
			return $pdo;
		}catch(PDOException $e){
			echo "接続失敗です！". $e->getMessage();
			exit();
		}

		// //例外処理
		// try{
		// 		$db=new PDO('mysql:dbname=④データベース名;host=③ホスト名;charset=utf8','①ユーザー名','②パスワード');
		// }catch(PDOException $e){
		// 		print('DB接続エラー:'.$e->getMessage());
		// }

		
	}

	//echo connect();
?>