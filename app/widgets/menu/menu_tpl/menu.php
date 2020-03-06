<!-- внутри отдельного шаблона доступны переменные и свойства класса, использованные внутри метода, где подключается шаблон -->
<li>
    <a href="?id=<?=$id;?>"><?=$item['title'];?></a>
    <?php if(isset($item['childs'])): ?>
        <ul>
            <?= $this->getTreeHtml($item['childs']);?>
        </ul>
    <?php endif; ?>
</li>
