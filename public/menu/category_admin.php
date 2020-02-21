<?php
$parent = isset($category['childs']); // возвращает true/false в зависимости от наличия потомков в категории
// если нет потомков формируем ссылку для удаления категории
if(!$parent){
    $delete = '<a href="' . ADMIN . '/category/delete?id=' . $id . '" class="delete"><i class="fa fa-fw fa-close text-danger"></i></a>';
}else{
    $delete = '<i class="fa fa-fw fa-close"></i>';
}
?>
<!-- формируем ссылку с категорией -->
<p class="item-p">
    <a class="list-group-item" href="<?=ADMIN;?>/category/view?id=<?=$id;?>"><?=$category['title'];?></a>
    <span><?=$delete;?></span>
</p>
<!-- если есть потомки у данной категории, рекурсивно вызываем метод getMenuHtml и передаем ему параметром дерево потомков -->
<?php if($parent): ?>
    <div class="list-group">
        <?= $this->getMenuHtml($category['childs']); ?>
    </div>
<?php endif; ?>
