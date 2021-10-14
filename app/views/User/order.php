<?=$breadcrumbs;?>

<!--prdt-starts-->
<div class="prdt">
    <div class="container">
        <div class="prdt-top">
            <div class="col-md-12 prdt-left">
                <div class="register-top heading">
                    <h2>Заказ №<?=$order['id'];?></h2>
                </div>

                <div class="product-one">
                    <?php if ($order): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td>Номер заказа</td>
                                        <td><?=$order['id'];?></td>
                                    </tr>
                                    <tr>
                                        <td>Дата заказа</td>
                                        <td><?=$order['date'];?></td>
                                    </tr>
                                    <tr>
                                        <td>Дата изменения</td>
                                        <td><?=$order['update_at'];?></td>
                                    </tr>
                                    <tr>
                                        <td>Кол-во позиций в заказе</td>
                                        <td><?=count($order_products);?></td>
                                    </tr>
                                    <tr>
                                        <td>Сумма заказа</td>
                                        <td><?=$order['sum'];?> <?=$order['currency'];?></td>
                                    </tr>
                                    <tr>
                                        <td>Имя заказчика</td>
                                        <td><?=$order['name'];?></td>
                                    </tr>
                                    <tr>
                                        <td>Статус</td>
                                        <td>
                                            <?php
                                            if ($order['status'] == '1') {
                                                echo 'Завершен';
                                            }
                                            elseif ($order['status'] == '2') {
                                                echo 'Оплачен';
                                            }
                                            else {
                                                echo 'Новый';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Комментарий</td>
                                        <td><?=$order['note'];?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php if ($order_products): ?>
                        <h3>Детали заказа</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th style="width: 10%">ID</th>
                                    <th style="width: 50%">Наименование</th>
                                    <th style="width: 20%">Кол-во</th>
                                    <th style="width: 20%">Цена</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $qty = 0; foreach ($order_products as $product): ?>
                                    <tr>
                                        <td><?=$product->id;?></td>
                                        <td><?=$product->title;?></td>
                                        <td><?=$product->qty; $qty += $product->qty?></td>
                                        <td><?=$product->price;?></td>
                                    </tr>
                                <?php endforeach; ?>
                                    <tr class="active">
                                        <td colspan="2">
                                            <b>Итого:</b>
                                        </td>
                                        <td><b><?=$qty;?></b></td>
                                        <td><b><?=$order['sum'];?> <?=$order['currency'];?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php if ($order['status'] == '0'): ?>
                        <form method="post" action="payment/pay">
                            <input type="hidden" name="id" value="<?=$order['id'];?>" />
                            <input type="hidden" name="sum" value="<?=$order['sum'];?>" />
                            <input type="hidden" name="curr" value="<?=$order['currency'];?>" />
                            <button type="submit" class="btn btn-default">Оплатить</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--product-end-->
