<!-- внутри отдельного шаблона доступны переменные метода и объект класса ($this - Filter), в котором подключается шаблон -->
<!-- в видах, подключаемых через класс view доступен объект класса View ($this) -->
<!-- формируем список групп фильтров -->
<?php foreach($this->groups as $group_id => $group_item): ?>
    <section  class="sky-form">
        <!-- наименование группы фильтров -->
        <h4><?=$group_item;?></h4>
        <div class="row1 scroll-pane">
            <div class="col col-4">
                <!-- для каждой группы фильтров выводим список соответствующих аттрибутов данной группы -->
                <?php foreach($this->attrs[$group_id] as $attr_id => $value): ?>
                <label class="checkbox">
                    <input type="checkbox" name="checkbox" value="<?=$attr_id;?>"><i></i><?=$value;?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endforeach; ?>