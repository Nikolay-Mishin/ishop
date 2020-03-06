<!-- внутри отдельного шаблона доступны переменные метода и объект класса ($this - Filter), в котором подключается шаблон -->
<!-- в видах, подключаемых через класс view доступен объект класса View ($this) -->
<!-- формируем поле текстового редактора -->

<div class="form-group has-feedback">
    <label for="content"><?=$this->label;?></label>
    <textarea name="content" id="<?=$this->id;?>" class="editor" cols="<?=$this->cols;?>" rows="<?=$this->rows;?>" data-required="<?=$this->isRequired;?>">
        <?=$item;?>
    </textarea>
</div>
