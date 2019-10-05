<!--A Design by W3layouts
Author: W3layout
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- тег <base> для указания корневого каталога, от которого работают все ссылки ресурсов (href, src) - link, script, img -->
    <!-- "/" - строить ссылки от корня сайта (подставляется перед всеми ссылками) -->
    <!-- добавляет данное значение ко всем относительным ссылкам (без / вначале), делая их абсолютными (от корня) -->
    <base href="/">
    <!-- получаем разметку с мета-тегами из вида -->
    <?=$this->getMeta();?>
    <!-- Vendor-Theme-files -->
    <!-- theme-style -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="megamenu/css/ionicons.min.css" rel="stylesheet" type="text/css" media="all" />
    <link href="megamenu/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
    <!-- //theme-style -->
    <!--Custom-Theme-files-->
    <!--theme-style-->
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!--//theme-style-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--start-menu-->
    <!-- <link href="css/memenu.css" rel="stylesheet" type="text/css" media="all" /> -->
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
                            <?php new \app\widgets\currency\Currency(); ?>
                        </select>
                    </div>

                    <!-- смена языка -->
                    <div class="box1">
                        <select tabindex="4" class="dropdown">
                            <option value="" class="label">English :</option>
                            <option value="1">English</option>
                            <option value="2">French</option>
                            <option value="3">German</option>
                        </select>
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
                            <?php if(!empty($_SESSION['cart'])): ?>
                                <span class="simpleCart_total"><?=$_SESSION['cart.currency']['symbol_left'] . price_format($_SESSION['cart.sum']) . $_SESSION['cart.currency']['symbol_right'];?></span>
                            <!-- если корзина пуста, выводи данноге сообщение -->
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
            <div class="col-md-9 header-left">
                <!-- плагин MegaMenu (memenu) -->
                <div class="menu-container">
                    <!-- обертка для меню -->
                    <div class="menu">
                        <!-- объект виджета меню - передаем параметры (пользовательский шаблон) и аттрибуты -->
                        <?php new \app\widgets\menu\Menu([
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
            <div class="col-md-3 header-right">
                <div class="search-bar">
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

<!-- получаем активную валюту из контейнера -->
<?php $curr = \ishop\App::$app->getProperty('currency'); ?>
<!-- ряд javaScript переменных (основных), которые будут использоваться в главном скрипте -->
<script>
    var path = '<?=PATH;?>', // ссылка на главную - абсолютный путь (для ajax-запросов и другого)
        course = <?=$curr['value'];?>, // текущий курс валюты
        symboleLeft = '<?=$curr['symbol_left'];?>', // символ слева ($ 1)
        symboleRight = '<?=$curr['symbol_right'];?>'; // символ справа (1 руб.)
</script>

<script src="js/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/typehead.bundle.js"></script>
<!--dropdown-->
<script src="js/jquery.easydropdown.js"></script>
<!--Slider-Starts-Here-->
<script src="js/responsiveslides.min.js"></script>
<script>
    // You can also use "$(window).load(function() {"
    $(function () {
        // Slideshow 4
        $("#slider4").responsiveSlides({
            auto: true,
            pager: true,
            nav: true,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });

    });
</script>
<script src="megamenu/js/megamenu.js"></script>
<script src="js/imagezoom.js"></script>
<script defer src="js/jquery.flexslider.js"></script>
<script>
    // Can also be used with $(document).ready()
    $(window).load(function() {
        $('.flexslider').flexslider({
            animation: "slide",
            controlNav: "thumbnails"
        });
    });
</script>
<script src="js/jquery.easydropdown.js"></script>
<script type="text/javascript">
    $(function() {

        var menu_ul = $('.menu_drop > li > ul'),
            menu_a  = $('.menu_drop > li > a');

        menu_ul.hide();

        menu_a.click(function(e) {
            e.preventDefault();
            if(!$(this).hasClass('active')) {
                menu_a.removeClass('active');
                menu_ul.filter(':visible').slideUp('normal');
                $(this).addClass('active').next().stop(true,true).slideDown('normal');
            } else {
                $(this).removeClass('active');
                $(this).next().stop(true,true).slideUp('normal');
            }
        });

    });
</script>
<script src="js/main.js"></script>
<!--End-slider-script-->

<!-- выводим все запросы выполняемые RedBeanPHP -->
<?php
$logs = \R::getDatabaseAdapter()
    ->getDatabase()
    ->getLogger();

debug( $logs->grep( 'SELECT' ) );
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