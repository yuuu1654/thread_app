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


		/**
		 * [キーワードからスレッド検索して取得]
		 * @param string $word(セッションデータ)
		 * @return array | bool  $result | false (成功したら検索したスレッドの配列データ、失敗したらfalseを返す)
		 */
		public static function searchThreads($word){
			$result = false;

			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			$sql = "SELECT * FROM threads WHERE title = ? OR content LIKE '%?%' ORDER BY created_at DESC";
			//'%".?."%'
			//$sql = 'SELECT * FROM threads WHERE title = ?';

			//emailを配列に入れる
			$array = [];
			$array[] = $word["search_word"];

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				
				//SQLの結果を返す
				$result = $stmt->fetchAll();
				return $result;
			} catch(\Exception $e) {
				return false;
			}
		}


		/**
		 * [idからスレッド詳細を取得]
		 * @param string $id
		 * @return array | bool  $thread | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function getThreadById($id){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			//$sql = 'SELECT * FROM threads INNER JOIN comments ON threads.id = comments.thread_id WHERE id = ?';
			$sql = 'SELECT * FROM threads WHERE id = ?';
			
			//idを配列に入れる
			$array = [];
			$array[] = (int)$id;

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				
				//SQLの結果を返す
				$threadDetail = $stmt->fetch();
				return $threadDetail;
			} catch(\Exception $e) {
				return false;
			}
		}
	}
?>