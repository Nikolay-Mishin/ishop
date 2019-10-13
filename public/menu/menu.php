<!-- внутри отдельного шаблона доступны переменные метода и объект класса ($this - Menu), в котором подключается шаблон -->
<!-- в видах, подключаемых через класс view доступен объект класса View ($this) -->
<?php //$parent = isset($category['childs']); ?>
<li>
    <a href="category/<?=$category['alias'];?>"><?=$category['title'];?></a>
    <?php if(isset($category['childs'])): ?>
        <ul>
            <?= $this->getMenuHtml($category['childs']);?>
        </ul>
    <?php endif; ?>
</li>