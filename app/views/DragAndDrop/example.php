<div id="dragAndDrop" class="container">
    <!-- Ограничение допустимой области перемещения элемента -->
    <div id="dragH" class="dragElement ui-widget ui-corner-all ui-state-error">
        горизонталь
    </div> 
    <div class="dragElement ui-widget ui-corner-all ui-state-error">
        родитель
    </div>

    <!-- Использование взаимодействия Draggable и Droppable - Обработка перекрывания элементов -->
    <div id="droppable">
        Оставь
    </div> 
    <div id="draggable" class="ui-widget ui-corner-all ui-state-error">
        Перетащи
    </div>

    <!-- Настройка взаимодействия Droppable -->
    <div id="dropContainer">
        <div id="fitDrop" class="fit-touch_droppable droppable">
            <span>Fit</span>
        </div>
        <div id="touchDrop" class="fit-touch_droppable droppable">
            <span>Touch</span>
        </div>        
    </div>
    <div class="fit-touch draggable ui-widget ui-corner-all ui-state-error">
        <span>Fit, Touch</span>
    </div>

    <!-- Использование опции Scope -->
    <div id="dropContainerScope">
        <div id="flowerDrop" class="droppable">
            <span>Цветы</span>
        </div>
        <div id="fruitDrop" class="droppable">
            <span>Фрукты</span>
        </div>        
    </div>
    <div id="orchid" class="draggable ui-state-error">
        <span>Орхидея</span>
    </div>
    <div id="apple" class="draggable ui-state-error">
        <span>Яблоко</span>
    </div>

    <!-- Использование вспомогательного элемента -->
    <div id="dropContainerHelper">
        <div id="basket" class="droppable">
            <span>Корзина</span>
        </div> 
    </div>
    <div id="lily" class="draggable ui-state-error">
        <img src="http://professorweb.ru/downloads/jquery/lily.png"/>
        <label for="lily">Лилия</label>
    </div>
</div>
