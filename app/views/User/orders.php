<?=$breadcrumbs;?>

<!--prdt-starts-->
<div class="prdt">
    <div class="container">
        <div class="prdt-top">
            <div class="col-md-12 prdt-left">
                <div class="register-top heading">
                    <h2>История заказов</h2>
                </div>

                <div class="product-one">
                    <?php if ($orders): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th style="width: 10%">ID</th>
                                    <th style="width: 30%">Статус</th>
                                    <th style="width: 20%">Сумма</th>
                                    <th style="width: 20%">Дата создания</th>
                                    <th style="width: 20%">Дата изменения</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <?php
                                    if ($order['status'] == '1') {
                                        $class = 'success';
                                        $text = 'Завершен';
                                    }
                                    elseif ($order['status'] == '2') {
                                        $class = 'info';
                                        $text = 'Оплачен';
                                    }
                                    else {
                                        $class = '';
                                        $text = 'Новый';
                                    }
                                    ?>
                                    <tr class="<?=$class;?>">
                                        <td><a href="<?=PATH;?>/user/order?id=<?=$order->id;?>"><i class="fa fa-fw fa-eye"></i></a> <?=$order->id;?></td>
                                        <td><?=$text;?></td>
                                        <td><?=$order->sum;?> <?=$order->currency;?></td>
                                        <td><?=$order->date;?></td>
                                        <td><?=$order->update_at;?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-danger">Вы пока не совершали заказов...</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--product-end-->
