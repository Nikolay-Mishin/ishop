<!-- внутри отдельного шаблона доступны переменные метода и объект класса ($this - Menu), в котором подключается шаблон -->
<!-- в видах, подключаемых через класс view доступен объект класса View ($this) -->
<?php // если корзина не пуста, отображаем шаблон ?>
<?php if(!empty($_SESSION['cart'])): ?>
    <!-- адаптивная таблица (table-responsive) -->
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <!-- шапка таблицы -->
            <thead>
            <tr>
                <th>Фото</th>
                <th>Наименование</th>
                <th>Кол-во</th>
                <th>Цена</th>
                <!-- удаление товара из корзины -->
                <th><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></th>
            </tr>
            </thead>
            <!-- контент таблицы -->
            <tbody>
            <?php foreach($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><a href="product/<?=$item['alias'];?>"><img src="images/<?=$item['img'];?>" alt="<?=$item['title'];?>"></a></td>
                    <td><a href="product/<?=$item['alias'];?>"><?=$item['title'];?></td>
                    <td>
                        <!-- счетчик количества товара -->
                        <div class="quantity">
                            <input type="number" size="4" value="<?=$item['qty'];?>" name="quantity" min="1" step="1" data-id="<?=$id;?>" data-qty="<?=$item['qty'];?>">
                        </div>
                    </td>
                    <td><?=$_SESSION['cart.currency']['symbol_left'] . price_format($item['price']) . $_SESSION['cart.currency']['symbol_right'];?></td>
                    <td><span data-id="<?=$id;?>" class="glyphicon glyphicon-remove text-danger del-item" aria-hidden="true"></span></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td>Итого:</td>
                    <td colspan="4" class="text-right cart-qty"><?=$_SESSION['cart.qty'];?></td>
                </tr>
                <tr>
                    <td>На сумму:</td>
                    <td colspan="4" class="text-right cart-sum"><?=$_SESSION['cart.currency']['symbol_left'] . price_format($_SESSION['cart.sum']) . $_SESSION['cart.currency']['symbol_right'];?></td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="cart-recalc" class="btn btn-primary" onclick="cartRecalc()" disabled>Пересчитать</button>
    </div>
<?php else: ?>
    <h3>Корзина пуста</h3>
<?php endif; ?>