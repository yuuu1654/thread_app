







<div class="container comment">
	<!-- コメント表示 -->
	<?php
		//thread_idが共通のコメントを全てコメントidの昇順で表示する
		$comments = CommentLogic::getCommentsById($thread_id);
		var_dump($comments);
		var_dump($thread_id);
	?>
	<table class="table">
		<?php foreach($comments as $comment): ?>	
			<tr>
				<td>
					<?php echo h($comment["id"]) ?>
					<?php echo h($comment["name_sei"]) ?><?php echo h($comment["name_mei"]) ?>
					<?php echo h($comment["created_at"]) ?>
				</td><br><br>
				<td>
					<?php echo h($comment["comment"]) ?>

					<?php
						//指定したcomment_idに対する総いいね数を取得する
						$comment_id = $comment["id"];  //コメントのid
						$likeCount = LikeLogic::countLikeById($comment_id);
						//いいねしたかどうか検索して、なければいいね出来るようにする
						$current_member = $_SESSION["login_member"];
						$member_id = $current_member["id"];  //ログインしているメンバーのid
						$likeResult = LikeLogic::searchLikeRelation($member_id, $comment_id)
					?>
					
					<?php if($likeResult): ?>
						<span class="fa fa-heart like-btn-unlike"></span><br>
					<?php else: ?>
						<span class="fa fa-heart like-btn"></span>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>