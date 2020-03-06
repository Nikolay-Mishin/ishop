<?php if(!empty($_SESSION['user'])): ?>
	<form method="post" action="comment/add" id="comment" role="form" data-toggle="validator">
		<?php $editor_options = \ishop\App::$app->getProperty('editor_options'); ?>
		<?= new \app\widgets\editor\Editor(arrayUnset($editor_options, 'tpl')); ?>
		<input type="hidden" name="product_id" value="<?=$editor_options['id'];?>">
		<input type="hidden" name="user_id" value="<?=$_SESSION['user']['id'];?>">
		<button type="submit" class="btn btn-default">Добавить</button>
	</form>
<?php else: ?>
	<div class="text-danger">Авторизируйтесь, чтобы оставлять комментарии</div>
<?php endif; ?>
