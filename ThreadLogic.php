<?php
	//スレッドを登録する＆検索する処理を行うクラス

	require_once "dbconnect.php";

	class ThreadLogic
	{
		/**
		 * [スレッドを登録する]
		 * @param array $threadData
		 * @return bool $result
		 * member_idとの紐付け
		 */
		public static function createThread($threadData){
			$result = false;

			//member_idも登録する(まだ実装出来ていない)
			$sql = 'INSERT INTO threads(member_id, title, content, created_at) VALUES(?, ?, ?, ?)';
			//スレッドデータを配列に入れる
			$array = [];
			$array[] = $threadData["member_id"];      //member_id
			$array[] = $threadData["title"];          //title
			$array[] = $threadData["content"];        //content
			$array[] = $threadData["created_at"];     //created_at
			
			try {
				//データベースに接続する
				$stmt = connect()->prepare($sql);
				$result = $stmt->execute($array);
				return $result;
			} catch(\Exception $e) {
				echo $e;  //エラーを出力
				error_log($e, 3, "error.log");  //ログを出力する
				return $result;
			}
		}
	}
?>