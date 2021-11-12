<?php
	$sql = "SELECT
	　　　　　 options.option_details AS answer,
	　　　　　 options.question_id AS question_id,
	　　　　　 options.option_name AS option_name,
	　　　　　 questions.question_deitails AS question,
	　　　　　 questions.quiz_id AS quiz_id
	　　　　　 FROM answer
	　　　　　 INNER JOIN questions 
	　　　　　 ON answer.question_id = questions.question_id
	　　　　　 INNER JOIN options 
	　　　　　 ON answer.answer_id = options.option_id
	　　　　　 WHERE quiz_id ='" . $quiz_id . "'";
	
// 	　$stmt = ($dbh->prepare($sql));
// 	　$stmt->execute();

// 	$answers = array();
// 	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
// 	　　$answers[]=array(
// 	　　　　'answer' =>$row['answer'],
// 	　　　　'question' =>$row['question'],
// 	　　　　'question_id'=>$row['question_id'],
// 	　　　　'option_name'=>$row['option_name']
// 	　　);
// 　}
?>



 /* SQL */
		$sql = SELECT * FROM hogetable WHERE name like :name;
		$stmt = $dbh->prepare($sql);
		
		/* nameのLIKE検索 */
		$name = '検索する名前';
		$name = '%'.$name.'%';
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->execute();



<?php
		$stmt = $pdo->prepare('
		SELECT * FROM table 
		WHERE (`分類` = :category OR :category IS NULL)
			AND (`色` = :coler OR :coler IS NULL)
	');

// PDO::PARAM_INT 部分は、実際の型に合わせる
$stmt->bindValue(':category', $category, is_null($category) ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':coler',    $coler,    is_null($coler)    ? PDO::PARAM_NULL : PDO::PARAM_INT);
?>


<?php
//ページネーションパターン１

define('MAX','3'); // 1ページの記事の表示数
 
$books = array( // 表示データを配列に入れる
          array('book_kind' => 'ライトノベル', 'book_name' => 'ライトノベルの本'),
          array('book_kind' => '歴史', 'book_name' => '歴史の本'),
          array('book_kind' => '料理', 'book_name' => '料理の本'),
          array('book_kind' => '啓発本', 'book_name' => '啓発の本'),
          array('book_kind' => 'コミック', 'book_name' => 'コミックの本'),
          array('book_kind' => '推理小説', 'book_name' => '推理小説の本'),
          array('book_kind' => 'フォトブック', 'book_name' => 'フォトブックの本'),
            );
            
$books_num = count($books); // トータルデータ件数
 
$max_page = ceil($books_num / MAX); // トータルページ数※ceilは小数点を切り捨てる関数
 
if(!isset($_GET['page_id'])){ // $_GET['page_id'] はURLに渡された現在のページ数
    $now = 1; // 設定されてない場合は1ページ目にする
}else{
    $now = $_GET['page_id'];
}
 
$start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか
 
// array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
$disp_data = array_slice($books, $start_no, MAX, true);
 
foreach($disp_data as $val){ // データ表示
    echo $val['book_kind']. '　'.$val['book_name']. '<br />';
}
 
for($i = 1; $i <= $max_page; $i++){ // 最大ページ数分リンクを作成
    if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない
        echo $now. '　'; 
    } else {
        //echo '<a href='/test.php?page_id='. $i. '')>'. $i. '</a>'. '　';
    }
}
 
?>









<?php
	//ページネーションパターン2
	
  $max = 5; //コンテンツの最大数
  $contents = array();

  for ($i = 0; $i < 50; $i++) {
    $contents[] = ($i+1) . '個目のコンテンツ';
  }

  $contents_sum = count($contents); //コンテンツの総数
  $max_page = ceil($contents_sum / $max); //ページの最大値

  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }

  $start = $max * ($page - 1); //スタートするページを取得
  $view_page = array_slice($contents, $start, $max, true); //表示するページを取得

 ?>

 <!DOCTYPE html>
 <html lang="ja" dir="ltr">
   <head>
     <meta charset="utf-8">
     <title>ページング</title>
   </head>
   <body>
     <!-- コンテンツを表示 -->
     <?php
     foreach ($view_page as $value) {
       echo $value . '<br />';
     }
      ?>
      <!-- ページ移動 -->
    <?php  if ($page > 1): ?>
      <a href="index.php?page=<?php echo ($page-1); ?>">前のページへ</a>
    <?php endif; ?>
    <?php  if ($page < $max_page): ?>
      <a href="index.php?page=<?php echo ($page+1); ?>">次のページへ</a>
    <?php endif; ?>

   </body>
 </html>


 <?php if( $page = 1 ): ?>
  <nav>
    <div class="nav-logo">
      <a href="#" style="pointer-events: none; color: #344853;">前へ></a>
    </div>
    <div class="nav-menus">
      <a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page + 1); ?>">次へ></a>
    </div>
  </nav>
<?php endif; ?>
<?php if( $page > 1 && $page < $max_page): ?>
  <nav>
    <div class="nav-logo">
      <a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page - 1); ?>">前へ></a>
    </div>
    <div class="nav-menus">
      <a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page +1 ); ?>">次へ></a>
    </div>
  </nav>
<?php endif; ?>
<?php if( $page = $max_page ): ?>
  <nav>
    <div class="nav-logo">
      <a href="thread_detail.php?id=<?php echo $id; ?>&page=<?php echo ($page - 1); ?>">前へ></a>
    </div>
    <div class="nav-menus">
      <a href="#" style="pointer-events: none; color: #344853;">次へ></a>
    </div>
  </nav>
<?php endif; ?>
