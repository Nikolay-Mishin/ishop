<?php
use ishop\App;
?>

<?php // получаем из реестра id родительской категории
$parent_id = App::$app->getProperty('parent_id');
// если текущая категория является родительской, то делаем ее активной (selected)
// делаем неактивной (блокируем - disabled) текущую категорию
?>
<option value="<?=$id;?>" <?=($id == $parent_id) ? 'selected' : ((isset($_GET['id']) && $id == $_GET['id']) ? 'disabled' : '');?>>
    <?= $tab . $item['title']; ?>
</option>
<?php // если у текущей категории есть потомки
if (isset($item['childs'])): ?>
    <?= $this->getTreeHtml($item['childs'], '&nbsp;' . $tab. '-') ?>
<?php endif; ?>
