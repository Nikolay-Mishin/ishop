<!-- внутри отдельного шаблона доступны переменные и свойства класса, использованные внутри метода, где подключается шаблон -->
<li>
    <a href="?id=<?=$id;?>"><?=$category['title'];?></a>
    <?php if(isset($category['childs'])): ?>
        <ul>
            <?= $this->getMenuHtml($category['childs']);?>
        </ul>
    <?php endif; ?>
</li>