<?php
	//メンバーを登録する&検索する処理を行うクラス

	require_once "dbconnect.php";
	require_once "functions.php";    //XSS・csrf&２重登録防止のセキュリティクラスの読み込み


	class MemberLogic
	{
		/**
		 * [メンバーを登録する]
		 * @param array $memberData
		 * @return bool $result
		 */
		public static function createMember($memberData){
			$result = false;

			//フォームに入力されたmailがすでに登録されていないかチェック
			$sql1 = "SELECT * FROM members WHERE email = :email";
			$stmt = connect()->prepare($sql1);
			$stmt->bindValue(':email', $memberData["email"]);
			$stmt->execute();
			$member = $stmt->fetch();

			if ($member['email'] === $memberData["email"]) {
				//$msg = '同じメールアドレスが存在します。';
				return $result;  //処理を止める
			} else {
				//登録されていなければinsert (DBに登録する処理)
				$sql2 = 'INSERT INTO members(name_sei, name_mei, gender, pref_name, address, password, email, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
				//会員データを配列に入れる
				$array = [];
				$array[] = $memberData["name_sei"];  //name_sei
				$array[] = $memberData["name_mei"];  //name_mei
				$array[] = $memberData["gender"];    //gender
				$array[] = $memberData["pref_name"]; //pref_name
				$array[] = $memberData["address"];   //address

				$array[] = $memberData["password"];  //password
				//$array[] = password_hash($memberData["password"], PASSWORD_DEFAULT);  //ハッシュ化したパスワード

				$array[] = $memberData["email"];     //email
				$array[] = $memberData["created_at"];     //created_at
				try {
					//データベースに接続する
					$stmt = connect()->prepare($sql2);
					$result = $stmt->execute($array);
					return $result;
				} catch(\Exception $e) {
					echo $e;  //エラーを出力
					error_log($e, 3, "error.log");  //ログを出力する
					return $result;
				}
			}
		}



		/**
		 * 会員編集処理
		 */
		public static function updateMember($memberData, $id){
			$result = false;

			//deleted_atに現在時刻を記入
			$sql = 'UPDATE members 
							SET name_sei = :name_sei, 
									name_mei = :name_mei, 
									gender = :gender, 
									pref_name = :pref_name, 
									address = :address, 
									password = :password, 
									email = :email, 
									updated_at = now()  
									WHERE id = :id';
			
			try {
				//データベースに接続する
				$stmt = connect()->prepare($sql);
				$stmt->bindValue(':name_sei', $memberData["name_sei"]);
				$stmt->bindValue(':name_mei', $memberData["name_mei"]);
				$stmt->bindValue(':gender', $memberData["gender"], PDO::PARAM_INT);
				$stmt->bindValue(':pref_name', $memberData["pref_name"]);
				$stmt->bindValue(':address', $memberData["address"]);
				$stmt->bindValue(':password', $memberData["password"]);
				$stmt->bindValue(':email', $memberData["email"]);
				$stmt->bindValue(':id', $id, PDO::PARAM_INT);
				$result = $stmt->execute();
				return $result;
			} catch(\Exception $e) {
				echo $e;  //エラーを出力
				error_log($e, 3, "error.log");  //ログを出力する
				return $result;
			}
		}



		/**
		 * [ログイン処理]
		 * すべての条件を満たしたメンバーがいたらセッションに保存して結果をtrueで返す
		 * @param string $email
		 * @param string $password
		 * @return bool $result
		 */
		public static function login($email, $password){
			//結果
			$result = false;
			//メンバーをemailから検索して取得
			$member = self::getMemberByEmail($email);

			//メールアドレスで検索をかけてメンバーが見つからなかった時の処理
			if ( !$member ){  
				$_SESSION["msg"] = "IDもしくはパスワードが間違っています";
				return $result;
			}
			//パスワードの照会 //password_verify($password, $member["password"])
			if ($password == $member["password"]){
				//ログイン成功
				session_regenerate_id(true);          //セッションハイジャック対策
				$_SESSION["login_member"] = $member;  //emailの照会で見つかり、パスワードも一致したメンバーをセッションに保存
				$result = true;
				return $result;
			}
			//パスワードの照会に失敗した時はエラーを返す
			$_SESSION["msg"] = "IDもしくはパスワードが間違っています";
			return $result;
		}



		/**
		 * [emailからメンバーを取得]
		 * @param string $email
		 * @return array | bool  $member | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function getMemberByEmail($email){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			//$sql = 'SELECT * FROM members WHERE email = ?';
			$sql = 'SELECT * FROM members WHERE email = ? AND deleted_at IS NULL';  //退会した会員はログイン出来ないようにする
			//emailを配列に入れる
			$array = [];
			$array[] = $email;

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				$member = $stmt->fetch();
				return $member;
			} catch(\Exception $e) {
				return false;
			}
		}



		/**
		 * [ログインチェック]
		 * @param void
		 * @return bool $result
		 */
		public static function checkLogin(){
			$result = false;

			//セッションにログインユーザーが入っていなかったらfalse
			if (isset($_SESSION["login_member"]) && $_SESSION["login_member"]["id"] > 0){
				return $result = true;
			}
			return $result;
		}


		/**
		 * ログアウト処理
		 */
		public static function logout(){
			//セッション変数をすべて解除する
			$_SESSION = array();  

			session_destroy();
		}


		/**
		 * 会員退会処理[ソフトデリート]
		 * 以下のコードで動かなければbindValueを使う
		 */
		public static function memberWithdrawal($member_id){
			$result = false;

			//deleted_atに現在時刻を記入
			$sql = 'UPDATE members SET deleted_at = now() WHERE id = :member_id';
			
			try {
				//データベースに接続する
				$stmt = connect()->prepare($sql);
				$stmt->bindValue(':member_id', $member_id, PDO::PARAM_INT);
				$result = $stmt->execute();
				return $result;
			} catch(\Exception $e) {
				echo $e;  //エラーを出力
				error_log($e, 3, "error.log");  //ログを出力する
				return $result;
			}
		}


		/**
		 * [idからメンバー詳細を取得]
		 * @param string $id
		 * @return array | bool  $member | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function getMemberById($id){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			$sql = 'SELECT * FROM members WHERE id = ?';
			
			//idを配列に入れる
			$array = [];
			$array[] = (int)$id;

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute($array);
				//SQLの結果を返す
				$memberDetail = $stmt->fetch();
				return $memberDetail;
			} catch(\Exception $e) {
				return false;
			}
		}



		/**
		 * [全てのメンバー情報を取得]
		 * @param 
		 * @return array | bool  $allMembers | false (成功したらメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function getAllMembers(){
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			$sql = 'SELECT * FROM members WHERE deleted_at IS NULL';

			try {
				$stmt = connect()->prepare($sql);
				$stmt->execute();
				//SQLの結果を返す
				$allMembers = $stmt->fetchAll();
				return $allMembers;
			} catch(\Exception $e) {
				return false;
			}
		}




		/**
		 * [キーワードからメンバー検索して一覧データを取得]
		 * @param string $searchData(複数検索条件のセッションデータ)
		 * @return array | bool  $result | false (成功したら検索したメンバーの配列データ、失敗したらfalseを返す)
		 */
		public static function searchMembers($searchData){
			$result = false;
			//SQLの準備
			//SQLの実行
			//SQLの結果を返す

			$sql = "SELECT * FROM members 
											WHERE id = :id 
											AND gender = :gender 
											AND pref_name = :pref_name 
											AND name_sei = :word 
											OR name_mei = :word 
											OR email = :word 
											ORDER BY id ASC";
											
			try {
				$stmt = connect()->prepare($sql);
				$stmt->bindValue(':id', $searchData["id"], PDO::PARAM_INT);
				$stmt->bindValue(':gender', $searchData["gender"], PDO::PARAM_INT);
				$stmt->bindValue(':pref_name', $searchData["pref_name"]);
				$stmt->bindValue(':name_sei', $searchData["word"]);
				$stmt->bindValue(':name_mei', $searchData["word"]);
				$stmt->bindValue(':email', $searchData["word"]);
				$stmt->execute();
				//SQLの結果を返す
				$result = $stmt->fetchAll();
				return $result;
			} catch(\Exception $e) {
				return false;
			}
		}
	}
?>