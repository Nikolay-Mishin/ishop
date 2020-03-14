<?php if(!empty($_SESSION['user'])): ?>
	<form method="post" action="comment/add" id="comment_add" role="form" data-toggle="validator" data-ajax="true">
		<input type="hidden" name="product_id" value="<?=$this->id;?>">
		<input type="hidden" name="user_id" value="<?=$_SESSION['user']['id'];?>">
		<?php
			unset($this->tpl);
			unset($this->id);
		?>
		<?=new \app\widgets\editor\Editor($this);?>
		<button type="submit" class="btn btn-default">Добавить</button>
	</form>
<?php else: ?>
	<div class="text-danger">Авторизируйтесь, чтобы оставлять комментарии</div>
<?php endif; ?>
