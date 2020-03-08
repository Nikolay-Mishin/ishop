<div class="comments">
	<?= $editor; ?>
	<h3 class="title-comments"><?=$this->getTitle();?></h3>
	<ul id="comments" class="media-list"><?=$comments ?: '<p class="text-danger">Здесь пока нет комментариев...</p>';?></ul>
</div>
