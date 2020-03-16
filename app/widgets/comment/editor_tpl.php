<?php if(!empty($_SESSION['user'])): ?>
	<form method="post" action="comment/add" id="<?=$this->meta('form_id');?>" role="form" data-toggle="validator" data-ajax="true">
		<?=new \app\widgets\editor\Editor($this->getOptions(clone $this, 'tpl'));?>
		<input type="hidden" name="product_id" value="<?=$this->meta('id');?>">
		<input type="hidden" name="user_id" value="<?=$_SESSION['user']['id'];?>">
		<input type="hidden" name="parent_id" value="<?=$this->meta('parent_id');?>">
		<button type="submit" class="btn btn-default">Добавить</button>
	</form>
<?php else: ?>
	<div class="text-danger">Авторизируйтесь, чтобы оставлять комментарии</div>
<?php endif; ?>
