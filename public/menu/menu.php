<!-- является ли текущий элемент родительским -->
<?php $parent = isset($category['childs']); ?>
<li>
    <!-- ссылка ведет на контроллер category, в который передается alias данной категории -->
    <a href="category/<?=$category['alias'];?>"><?=$category['title'];?></a>
    <!-- если у данной категории есть потомки, то вызываем метод для формирования разметки для дочерних элементов -->
    <?php if(isset($category['childs'])): ?>
        <ul>
            <!-- рекурсивно формируем html-разметку на основе переданной части дерева (массви дочерних элементов) -->
            <?= $this->getMenuHtml($category['childs']);?>
        </ul>
    <?php endif; ?>
</li>