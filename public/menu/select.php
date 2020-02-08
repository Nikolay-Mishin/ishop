<option value="<?=$id;?>"><?=$tab . $category['title'];?></option>
<?php // если у текущей категории есть потомки ?>
<?php if(isset($category['childs'])): ?>
    <?= $this->getMenuHtml($category['childs'], '&nbsp;' . $tab . '-') ?>
<?php endif; ?>