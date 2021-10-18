<?php
	//スレッドを登録する＆検索する処理を行うクラス

	require_once "dbconnect.php";

	//変更しました
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



		/**
		 * [thread_idを指定して、総コメント数を取得]
		 * @param string $thread_id
		 * @return array | bool  $thread | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function countCommentsById($thread_id){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			//$sql = 'SELECT * FROM threads INNER JOIN comments ON threads.id = comments.thread_id WHERE id = ?';
			$sql = 'SELECT * FROM comments WHERE thread_id = ?';
			//idを配列に入れる
			$array = [];
			$array[] = (int)$thread_id;
			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				$commentCount = $stmt->rowCount();
				return $commentCount;
			} catch(\Exception $e) {
				return false;
			}
		}



		/**
		 * [thread_idを指定して、全てのコメントデータをコメントのidを昇順にして取得]
		 * @param string $thread_id
		 * @return array | bool  $thread | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function getCommentsById($thread_id){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			//$sql = 'SELECT * FROM comments WHERE thread_id = ? ORDER BY id ASC';

			// $sql = "SELECT
			// 	comments.id AS id,
			// 	members.name_sei AS name_sei,
			// 	members.name_mei AS name_mei,
			// 	comments.created_at AS created_at,
			// 	comments.comment AS comment,
			// 	FROM comments
			// 	INNER JOIN members 
			// 	ON comments.member_id = members.id
			// 	WHERE comments.thread_id = ?";

			$sql = "SELECT comments.id AS id, members.name_sei AS name_sei, members.name_mei AS name_mei, comments.created_at AS created_at, comments.comment AS comment, FROM comments INNER JOIN members ON comments.member_id = members.id WHERE comments.thread_id = ?";

			//idを配列に入れる
			$array = [];
			$array[] = (int)$thread_id;
			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				//$comments = $stmt->fetchAll();

				$comments = array();
				while($row = $stmt->fetchAll()){
					$comments[]=array(
						"id" =>$row["id"],
						"name_sei" =>$row["name_sei"],
						"name_mei" =>$row["name_mei"],
						"created_at" =>$row["created_at"],
						"comment" =>$row["comment"]
					);
				}
				return $comments;
			} catch(\Exception $e) {
				return false;
			}
		}
	}
?>