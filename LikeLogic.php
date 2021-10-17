<?php
	//いいねを作成する＆検索して削除する処理を行うクラス

	require_once "dbconnect.php";

	//変更しました
	class LikeLogic
	{
		/**
		 * [いいねを作成する]
		 * @param array $likeData
		 * @return bool $result
		 * member_idとの紐付け
		 * comment_idとの紐付け
		 */
		public static function createLike($likeData){
			$result = false;

			$sql = 'INSERT INTO likes(member_id, comment_id) VALUES(?, ?)';
			//スレッドデータを配列に入れる
			$array = [];
			$array[] = $likeData["member_id"];       //member_id
			$array[] = $likeData["comment_id"];      //comment_id
			
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