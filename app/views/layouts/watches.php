<?php
use app\widgets\currency\Currency;
use app\widgets\menu\Menu;
use ishop\Logger;
?>

<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html" />
    <link rel="shortcut icon" href="<?=PATH;?>/images/star.png" type="image/png" />
    <!-- тег <base> для указания корневого каталога, от которого работают все ссылки ресурсов (href, src) - link, script, img -->
    <!-- "/" - строить ссылки от корня сайта (подставляется перед всеми ссылками) -->
    <!-- добавляет данное значение ко всем относительным ссылкам (без / вначале), делая их абсолютными (от корня) -->
    <base href="<?=ROOT_BASE;?>">
    <!-- получаем каноническую ссылку -->
    <?=$this->getCanonical();?>
    <!-- получаем разметку с мета-тегами из вида -->
    <?=$this->getMeta();?>
    <!-- получаем разметку со стилями из вида -->
    <?=$this->getStyles();?>
    <!-- Vendor-Theme-files -->
    <!-- theme-style -->
    <!-- <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" /> -->
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="adminlte/bower_components/font-awesome/css/font-awesome.min.css">
    <link href="megamenu/css/ionicons.min.css" rel="stylesheet" type="text/css" media="all" />
    <link href="megamenu/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" /> -->
    <!-- //theme-style -->
    <!--start-menu-->
    <!-- <link href="css/memenu.css" rel="stylesheet" type="text/css" media="all" /> -->
    <!--Custom-Theme-files-->
    <!--theme-style-->
    <!-- <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/custom.css" rel="stylesheet" type="text/css" media="all" /> -->
    <!--//theme-style-->
</head>

<body>
<!--top-header-->
<div class="top-header">
    <div class="container">
        <div class="top-header-main">
            <!-- виджеты -->
            <div class="col-md-6 top-header-left">
                <div class="drop">
                    <!-- виджет валюты -->
                    <div class="box">
                        <select id="currency" tabindex="4" class="dropdown drop">
                            <!-- вызываем виджет валюты (создаем объект класса) -->
                            <?php new Currency(); ?>
                        </select>
                    </div>

                    <!-- смена языка -->
                    <div class="box">
                        <select tabindex="4" class="dropdown">
                            <option value="" class="label">English :</option>
                            <option value="1">English</option>
                            <option value="2">French</option>
                            <option value="3">German</option>
                        </select>
                    </div>

                    <!-- вход и регистрация -->
                    <div class="btn-group">
                        <a class="dropdown-toggle" data-toggle="dropdown">Аккаунт <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if (!empty($_SESSION['user'])): ?>
                                <li><a href="user/cabinet">Добро пожаловать, <?=h($_SESSION['user']['name']);?></a></li>
                                <li><a href="user/logout">Выход</a></li>
                            <?php else: ?>
                                <li><a href="user/login">Вход</a></li>
                                <li><a href="user/signup">Регистрация</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <a class="dropdown-toggle" data-toggle="dropdown">Виджеты <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php if (!empty($_SESSION['user'])): ?>
                                <li><a href="calendar">Календарь</a></li>
                            <?php else: ?>
                                <li><a href="chat">Чат</a></li>
                                <li><a href="collection">Коллекции</a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <!-- корзина -->
            <div class="col-md-6 top-header-left">
                <div class="cart box_1">
                    <!-- ссылка для открытия модального окна корзины -->
                    <!-- return false; - для отмены стандартного поведения ссылки -->
                    <a href="cart/show" onclick="getCart(); return false;">
                        <div class="total">
                            <img src="images/cart-1.png" alt="" />
                            <!-- если корзина не пуста, выводим общую сумму заказа -->
                            <?php if (!empty($_SESSION['cart'])): ?>
                                <span class="simpleCart_total"><?=$_SESSION['cart.currency']['symbol_left'] . price_format($_SESSION['cart.sum']) . $_SESSION['cart.currency']['symbol_right'];?></span>
                            <!-- если корзина пуста, выводим данное сообщение -->
                            <?php else: ?>
                                <span class="simpleCart_total">Empty Cart</span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <!--<a href="checkout.html">
                        <div class="total">
                            <span class="simpleCart_total"></span></div>
                        <img src="images/cart-1.png" alt="" />
                    </a>
                    <p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>-->
                    <div class="clearfix"> </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--top-header-->

<!--start-logo-->
<div class="logo">
    <!-- ссылка на главную -->
    <a href="<?=PATH;?>"><h1>Luxury Watches</h1></a>
</div>
<!--start-logo-->

<!--bottom-header-->
<div class="header-bottom">
    <div class="container">
        <div class="header">
            <!-- меню -->
            <div class="col-md-9 header-left">
                <!-- плагин MegaMenu (memenu) -->
                <div class="menu-container">
                    <!-- обертка для меню -->
                    <div class="menu">
                        <!-- объект виджета меню - передаем параметры (пользовательский шаблон) и аттрибуты -->
                        <?php new Menu([
                            'tpl' => WWW . '/menu/menu.php',
                            /* 'attrs' => [
                                'style' => 'color: red; border: 1px solid red;',
                                'id' => 'menu'
                            ] */
                        ]); ?>
                    </div>
                </div>
                <div class="clearfix"> </div>
            </div>

            <!-- поиск -->
            <div class="col-md-3 header-right">
                <div class="search-bar">
                    <!-- форма поиска - плагин typeahead -->
                    <!-- autocomplete - подсказки -->
                    <form action="search" method="get" autocomplete="off">
                        <input type="text" class="typeahead" id="typeahead" name="s">
                        <input type="submit" value="">
                    </form>
                    <!--<input type="text" value="Search" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}">
                    <input type="submit" value="">-->
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<!--bottom-header-->

<!-- выводим динамический контент данной страницы -->
<div class="content">
    <!-- сообщения от ошибках и успехе -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <? // if(isset($errors)) debug($errors); ?>
    <? // debug($_SESSION); ?>
    <!-- динамический контент -->
    <?=$content;?>
</div>

<!--information-starts-->
<div class="information">
    <div class="container">
        <div class="infor-top">
            <div class="col-md-3 infor-left">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="#"><span class="fb"></span><h6>Facebook</h6></a></li>
                    <li><a href="#"><span class="twit"></span><h6>Twitter</h6></a></li>
                    <li><a href="#"><span class="google"></span><h6>Google+</h6></a></li>
                </ul>
            </div>
            <div class="col-md-3 infor-left">
                <h3>Information</h3>
                <ul>
                    <li><a href="#"><p>Specials</p></a></li>
                    <li><a href="#"><p>New Products</p></a></li>
                    <li><a href="#"><p>Our Stores</p></a></li>
                    <li><a href="contact.html"><p>Contact Us</p></a></li>
                    <li><a href="#"><p>Top Sellers</p></a></li>
                </ul>
            </div>
            <div class="col-md-3 infor-left">
                <h3>My Account</h3>
                <ul>
                    <li><a href="account.html"><p>My Account</p></a></li>
                    <li><a href="#"><p>My Credit slips</p></a></li>
                    <li><a href="#"><p>My Merchandise returns</p></a></li>
                    <li><a href="#"><p>My Personal info</p></a></li>
                    <li><a href="#"><p>My Addresses</p></a></li>
                </ul>
            </div>
            <div class="col-md-3 infor-left">
                <h3>Store Information</h3>
                <h4>The company name,
                    <span>Lorem ipsum dolor,</span>
                    Glasglow Dr 40 Fe 72.</h4>
                <h5>+955 123 4567</h5>
                <p><a href="mailto:example@email.com">contact@example.com</a></p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--information-end-->

<!--footer-starts-->
<div class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="col-md-6 footer-left">
                <form>
                    <input type="text" value="Enter Your Email" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Enter Your Email';}">
                    <input type="submit" value="Subscribe">
                </form>
            </div>
            <div class="col-md-6 footer-right">
                <p>© 2015 Luxury Watches. All Rights Reserved | Design by  <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--footer-end-->

<!-- Modal - модальное окно корзины -->
<div class="modal fade" id="cart" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <!-- заголовок/шапка модального окна -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Корзина</h4>
            </div>
            <!-- тело - контент модального окна -->
            <div class="modal-body">

            </div>
            <!-- футер -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Продолжить покупки</button>
                <a href="cart/view" id="cart-order" type="button" class="btn btn-primary">Оформить заказ</a>
                <button type="button" id="cart-clean" class="btn btn-danger" onclick="clearCart()">Очистить корзину</button>
            </div>
        </div>
    </div>
</div>

<!-- прелоадер для скрытия контента при ожидании ответа от сервера (ajax-запрос) -->
<div class="preloader"><img src="images/ring.svg" alt=""></div>

<!-- получаем разметку со скриптами из вида -->
<?=$this->getScripts();?>

<!-- _variables - ряд javaScript переменных (основных), которые будут использоваться в главном скрипте -->
<?php // require_once CONF.'/scripts_consts.php'; ?>

<!-- <script src="js/_variables.js"></script> -->

<!--jquery-->
<!-- <script src="js/jquery-1.11.0.min.js"></script> -->
<!--bootstrap-->
<!-- <script src="js/bootstrap.min.js"></script> -->

<!--validator-->
<!-- <script src="js/validator.js"></script> -->
<!--search-->
<!-- <script src="js/typeahead.bundle.js"></script> -->
<!--imagezoom-->
<!-- <script src="js/imagezoom.js"></script> -->

<!--megamenu-->
<!-- <script src="megamenu/js/megamenu.js"></script> -->
<!--dropdown-->
<!-- <script src="js/jquery.easydropdown.js"></script> -->
<!--accordion-->
<!-- <script src="js/accordion.js"></script> -->

<!--Slider-Starts-Here-->
<!--responsiveslides-->
<!-- <script src="js/responsiveslides.min.js"></script>
<script src="js/slider.js"></script> -->
<!--flexslider-->
<!-- <script defer src="js/jquery.flexslider.js"></script>
<script src="js/flexslider.js"></script> -->
<!--Slider-End-Here-->

<!--CKeditor-Starts-Here-->
<!--ckeditor-->
<!-- <script src="adminlte/bower_components/ckeditor/ckeditor.js"></script>
<script src="adminlte/bower_components/ckeditor/adapters/jquery.js"></script> -->
<!--CKeditor-End-Here-->

<!--editor-->
<!-- <script src="js/editor.js"></script> -->
<!--main script-->
<!-- <script src="js/main.js"></script> -->

<!-- выводим все запросы выполняемые RedBeanPHP -->
<?php Logger::getLog(); ?>
<?php
//$logs = \R::getDatabaseAdapter()->getDatabase()->getLogger();
//debug($logs->grep('SELECT'));
// распечатка массива с логом SQL запросов
/*
Array
(
    [0] => SELECT code, title, symbol_left, symbol_right, value, base FROM currency ORDER BY base DESC
    [1] => SELECT `product`.*  FROM `product`  WHERE alias = ? AND status = '1' LIMIT 1  -- keep-cache
    [2] => SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = ?
    [3] => SELECT `product`.*  FROM `product`  WHERE id IN (?,?,?) LIMIT 3 -- keep-cache
    [4] => SELECT `gallery`.*  FROM `gallery`  WHERE product_id = ? -- keep-cache
)
*/
?>
</body>
</html>
