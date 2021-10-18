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




		/**
		 * [comment_idを指定して、コメントに対する総いいね数を取得]
		 * @param string $comment_id
		 * @return array | bool  $likeCount | false (成功したらlikeのカウント数データ、失敗したらfalseを返す)
		 */
		public static function countLikeById($comment_id){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			//$sql = 'SELECT * FROM threads INNER JOIN comments ON threads.id = comments.thread_id WHERE id = ?';
			$sql = 'SELECT * FROM likes WHERE comment_id = ?';
			//idを配列に入れる
			$array = [];
			$array[] = (int)$comment_id;
			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				$likeCount = $stmt->rowCount();
				return $likeCount;
			} catch(\Exception $e) {
				return false;
			}
		}



		/**
		 * [ログインメンバーがコメントにいいねしたかどうかのレコード結果を取得]
		 * @param string $member_id, $comment_id
		 * @return array | bool  $result | false (成功したらログインメンバーがいいねしたレコード、失敗したらfalseを返す)
		 */
		public static function searchLikeRelation($member_id, $comment_id){
			$result = false;
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			$sql = "SELECT * FROM likes WHERE member_id = :member_id AND comment_id = :comment_id";

			// //フォームに入力されたmailがすでに登録されていないかチェック
			// $sql1 = "SELECT * FROM members WHERE email = :email";
			// $stmt = connect()->prepare($sql1);
			// $stmt->bindValue(':email', $memberData["email"]);
			// $stmt->execute();
			// $member = $stmt->fetch();

			try {
				$stmt = connect()->prepare($sql);
				$stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
				$stmt->bindValue(':comment_id', $comment_id, PDO::PARAM_INT);
				$stmt->execute();
				//SQLの結果を返す
				$result = $stmt->fetch();
				return $result;
			} catch(\Exception $e) {
				return false;
			}
		}
	}
?>