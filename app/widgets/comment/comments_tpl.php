<div class="comments">
	<?= $editor; ?>
	<?php if($comments): ?>
		<h3 class="title-comments">Комментарии (<?=count($this->data);?>)</h3>
		<ul class="media-list"><?=$comments;?></ul>
	<?php else: ?>
		<p class="text-danger">Здесь пока нет комментариев...</p>
	<?php endif; ?>
</div>
