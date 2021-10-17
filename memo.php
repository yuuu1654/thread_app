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

<table class="quiz-table">
      <tr>
        <th>#</th>
　　　<th>問題</th>
　　　<th>答え</th>
　　　<th>選択肢</th>
       </tr>
       　 <?php $c=1; foreach ($answers as $value): ?>
       <tr>
        <td>
       　 <?php echo $c++ ?>
　　　</td>
　　　<td>
　　　　　　<?php echo $value['question'] ?> 
　　　</td>
        <td>
         　<?php echo $value['answer'] ?>
        </td>
        <td>
         　<?php echo $value['option_name'] ?>
        </td>
      </tr>
      <?php endforeach ?>
 </table>