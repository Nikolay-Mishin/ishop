<?php if(isset($item)): ?>
	<!-- Комментарий -->
	<<?=!$item['parent_id'] ? 'li' : 'div';?> class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object img-rounded" src="/images/<?=$item['avatar'];?>" alt="<?=$item['name'];?>">
			</a>
		</div>

		<div class="media-body">
			<div class="media-heading">
				<div class="author">
					<?=ucfirst($item['name']);?>
				</div>
				<div class="metadata">
					<?php
						setlocale(LC_TIME, 'ru_RU.UTF-8');
						$date = strftime('%d %B %Y, %H:%M', strtotime($item['update_at'] ?: $item['date']));
						$date = mb_convert_case($date, MB_CASE_LOWER, "UTF-8");
					?>
					<span class="date">
						<?=$date;?>
					</span>
				</div>
			</div>

			<div class="media-text text-justify">
				<?=$item['content'];?>
			</div>

			<div class="footer-comment">
				<span class="vote plus" title="Нравится">
					<i class="fa fa-thumbs-up"></i>
				</span>
				<span class="rating"><?=($item['rate'] > 0 ? '+' : '') . $item['rate'];?></span>
				<span class="vote minus" title="Не нравится">
					<i class="fa fa-thumbs-down"></i>
				</span>
				<span class="devide">
					|
				</span>
				<span class="comment-reply">
					<a href="#" class="reply">ответить</a>
				</span>
			</div>

			<?php if(isset($item['childs'])): ?>
				<?=$this->getTreeHtml($item['childs']);?>
			<?php endif; ?>
		</div>
	</<?=!$item['parent_id'] ? 'li' : 'div';?>>
	<!-- Конец комментария -->
<?php endif; ?>
