<?php if(isset($item)): ?>
	<?php //debug($item); ?>
	<!-- Комментарий (уровень 1) -->
	<li class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object img-rounded" src="/images/<?=$item['avatar'];?>" alt="<?=$item['name'];?>">
			</a>
		</div>
		<div class="media-body">
			<div class="media-heading">
				<div class="author"><?=ucfirst($item['name']);?></div>
				<div class="metadata">
					<span class="date"><?php setlocale(LC_TIME, 'ru_RU.UTF-8'); echo strftime('%d %B %Y, %H:%M', strtotime($item['update_at'] ?: $item['date'])); ?></span>
				</div>
			</div>
			<div class="media-text text-justify"><?=$item['content'];?></div>
			<div class="footer-comment">
				<span class="vote plus" title="Нравится">
					<i class="fa fa-thumbs-up"></i>
				</span>
				<span class="rating">
					+1
				</span>
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
				<?php foreach($item['childs'] as $child): ?>
					<?php debug($child); ?>
					<?= $this->getTreeHtml($item['childs']);?>
				<?php endforeach; ?>
			<?php endif; ?>

			<!-- Вложенный медиа-компонент (уровень 2) -->
			<div class="media">
				<div class="media-left">
					<a href="#">
						<img class="media-object img-rounded" src="/images/avatar2.jpg" alt="">
					</a>
				</div>
				<div class="media-body">
					<div class="media-heading">
						<div class="author">Пётр</div>
						<div class="metadata">
							<span class="date">19 ноября 2015, 10:28</span>
						</div>
					</div>
					<div class="media-text text-justify">Dolor sit, amet, consectetur, adipisci velit. Aperiam
						eaque ipsa, quae. Amet, consectetur, adipisci velit, sed quia consequuntur magni
						dolores. Ab illo inventore veritatis et quasi architecto. Quisquam est, omnis voluptas
						nulla. Obcaecati cupiditate non numquam eius modi tempora. Corporis suscipit laboriosam,
						nisi ut labore et aut reiciendis.
					</div>
					<div class="footer-comment">
						<span class="vote plus" title="Нравится">
							<i class="fa fa-thumbs-up"></i>
						</span>
						<span class="rating">
							+0
						</span>
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

					<!-- Вложенный медиа-компонент (уровень 3) -->
					<div class="media">
						<div class="media-left">
							<a href="#">
								<img class="media-object img-rounded" src="/images/avatar3.jpg" alt="">
							</a>
						</div>
						<div class="media-body">
							<div class="media-heading">
								<div class="author">Александр</div>
								<div class="metadata">
									<span class="date">Вчера в 19:34</span>
								</div>
							</div>
							<div class="media-text text-justify">Amet, consectetur, adipisci velit, sed ut
								labore et dolore. Maiores alias consequatur aut perferendis doloribus
								asperiores. Voluptas nulla vero eos. Minima veniam, quis nostrum exercitationem
								ullam corporis. Atque corrupti, quos dolores eos, qui blanditiis praesentium
								voluptatum deleniti atque corrupti. Quibusdam et harum quidem rerum
								necessitatibus saepe eveniet, ut enim ipsam. Magni dolores et dolorum fuga
								nostrum exercitationem ullam. Eligendi optio, cumque nihil molestiae
								consequatur.
							</div>
							<div class="footer-comment">
							<span class="vote plus" title="Нравится">
								<i class="fa fa-thumbs-up"></i>
							</span>
								<span class="rating">
									+5
								</span>
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
						</div>
					</div>
					<!-- Конец вложенного комментария (уровень 3) -->
				</div>
			</div>
			<!-- Конец вложенного комментария (уровень 2) -->

			<!-- Ещё один вложенный медиа-компонент (уровень 2) -->
			<div class="media">
				<div class="media-left">
					<a href="#">
						<img class="media-object img-rounded" src="/images/avatar4.jpg" alt="">
					</a>
				</div>
				<div class="media-body">
					<div class="media-heading">
						<div class="author">Сергей</div>
						<div class="metadata">
							<span class="date">20 ноября 2015, 17:45</span>
						</div>
					</div>
					<div class="media-text text-justify">Ex ea voluptate velit esse, quam nihil impedit, quo
						minus id quod. Totam rem aperiam eaque ipsa, quae ab illo. Minima veniam, quis nostrum
						exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid. Iste natus error sit
						voluptatem. Sunt, explicabo deleniti atque corrupti, quos dolores et expedita.
					</div>
					<div class="footer-comment">
						<span class="vote plus" title="Нравится">
							<i class="fa fa-thumbs-up"></i>
						</span>
						<span class="rating">
							+1
						</span>
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
				</div>
			</div>
			<!-- Конец ещё одного вложенного комментария (уровень 2) -->
		</div>
	</li>
	<!-- Конец комментария (уровень 1) -->

	<!-- Комментарий (уровень 1) -->
	<li class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object img-rounded" src="/images/avatar5.jpg" alt="">
			</a>
		</div>
		<div class="media-body">
			<div class="media-heading">
				<div class="author">Иван</div>
				<div class="metadata">
					<span class="date">Вчера в 17:34</span>
				</div>
			</div>
			<div class="media-text text-justify">Eum iure reprehenderit, qui dolorem eum fugiat. Sint et
				expedita distinctio velit. Architecto beatae vitae dicta sunt, explicabo unde omnis. Qui aperiam
				eaque ipsa, quae ab illo inventore veritatis et quasi architecto. Nemo enim ipsam voluptatem
				quia. Eos, qui ratione voluptatem sequi nesciunt, neque porro. A sapiente delectus, ut enim
				ipsam voluptatem, quia non recusandae architecto beatae.
			</div>
			<div class="footer-comment">
				<span class="vote plus" title="Нравится">
					<i class="fa fa-thumbs-up"></i>
				</span>
				<span class="rating">
					+2
				</span>
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
		</div>
	</li>
	<!-- Конец комментария (уровень 1) -->

	<!-- Комментарий (уровень 1) -->
	<li class="media">
		<div class="media-left">
			<a href="#">
				<img class="media-object img-rounded" src="/images/avatar1.jpg" alt="">
			</a>
		</div>
		<div class="media-body">
			<div class="media-heading">
				<div class="author">Дима</div>
				<div class="metadata">
					<span class="date">3 минуты назад</span>
				</div>
			</div>
			<div class="media-text text-justify">Tempore, cum soluta nobis est et quas. Quas molestias excepturi
				sint, obcaecati cupiditate non provident, similique sunt in. Obcaecati cupiditate non recusandae
				impedit. Hic tenetur a sapiente delectus. Facere possimus, omnis dolor repellendus inventore
				veritatis et voluptates. Ipsa, quae ab illo inventore veritatis et quasi architecto beatae. In
				culpa, qui in culpa. Cum soluta nobis est laborum et aut perferendis doloribus. Vitae dicta
				sunt, explicabo perspiciatis. Amet, consectetur, adipisci velit, sed quia voluptas sit,
				aspernatur. Obcaecati cupiditate non provident, similique sunt in. Reiciendis voluptatibus
				maiores alias consequatur aut officiis debitis aut perferendis doloribus asperiores. Assumenda
				est, omnis dolor repellendus voluptas assumenda est omnis.
			</div>
			<div class="footer-comment">
				<span class="vote plus" title="Нравится">
					<i class="fa fa-thumbs-up"></i>
				</span>
				<span class="rating">
					+0
				</span>
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
		</div>
	</li>
	<!-- Конец комментария (уровень 1) -->
<?php endif; ?>
