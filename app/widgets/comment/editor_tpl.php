<?php if(!empty($_SESSION['user'])): ?>
	<form method="post" action="comment/add" id="comment_add" role="form" data-toggle="validator" data-ajax="true">
		<?php
			$editor_options = \ishop\App::$app->getProperty('editor_options');
			$id = $editor_options['id'];
			arrayUnset($editor_options, 'id');
		?>
		<?= new \app\widgets\editor\Editor(arrayUnset($editor_options, 'tpl')); ?>
		<input type="hidden" name="product_id" value="<?=$id;?>">
		<input type="hidden" name="user_id" value="<?=$_SESSION['user']['id'];?>">
		<button type="submit" class="btn btn-default">Добавить</button>
	</form>
<?php else: ?>
	<div class="text-danger">Авторизируйтесь, чтобы оставлять комментарии</div>
<?php endif; ?>
