<?php
	//スレッドを登録する＆検索する処理を行うクラス

	require_once "dbconnect.php";

	class CommentLogic
	{
		/**
		 * [コメントを登録する]
		 * @param array $commentData
		 * @return bool $result
		 * member_idとの紐付け
		 * thread_idとの紐付け
		 */
		public static function createComment($commentData){
			$result = false;

			//member_idも登録する(まだ実装出来ていない)
			$sql = 'INSERT INTO comments(member_id, thread_id, comment, created_at) VALUES(?, ?, ?, ?)';
			//スレッドデータを配列に入れる
			$array = [];
			$array[] = $commentData["member_id"];      //member_id
			$array[] = $commentData["thread_id"];      //thread_id
			$array[] = $commentData["comment"];        //comment
			$array[] = $commentData["created_at"];     //created_at
			
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