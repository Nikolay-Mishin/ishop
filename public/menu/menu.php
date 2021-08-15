<?php //$parent = isset($category['childs']); ?>
<li>
    <a href="category/<?=$item['alias'];?>"><?=$item['title'];?></a>
    <?php if (isset($item['childs'])): ?>
        <ul>
            <?= $this->getTreeHtml($item['childs']);?>
        </ul>
    <?php endif; ?>
</li>
