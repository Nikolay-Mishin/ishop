<!--banner-starts-->
<div class="bnr" id="home">
    <div  id="top" class="callbacks_container">
        <ul class="rslides" id="slider4">
            <li>
                <img src="images/bnr-1.jpg" alt=""/>
            </li>
            <li>
                <img src="images/bnr-2.jpg" alt=""/>
            </li>
            <li>
                <img src="images/bnr-3.jpg" alt=""/>
            </li>
        </ul>
    </div>
    <div class="clearfix"> </div>
</div>
<!--banner-ends-->

<!--about-starts-->
<!-- выводим бренды -->
<?php if($brands): ?>
<div class="about">
    <div class="container">
        <div class="about-top grid-1">
            <!-- выводим отдельно каждый бренд -->
            <?php foreach($brands as $brand): ?>
                <div class="col-md-4 about-left">
                <figure class="effect-bubba">
                    <!-- изображение -->
                    <img class="img-responsive" src="images/<?=$brand->img;?>" alt=""/>
                    <figcaption>
                        <!-- наименование -->
                        <h2><?=$brand->title;?></h2>
                        <!-- описание -->
                        <p><?=$brand->description;?></p>
                    </figcaption>
                </figure>
            </div>
            <?php endforeach; ?>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--about-end-->

<!--product-starts-->
<!-- выводим хиты -->
<?php if($hits): ?>
<div class="product">
    <div class="container">
        <div class="product-top">
            <div class="product-one">
            <!-- выводим отдельно каждый хит -->
            <?php foreach($hits as $hit): ?>
                <div class="col-md-3 product-left">
                    <div class="product-main simpleCart_shelfItem">
                        <!-- изображение -->
                        <a href="product/<?=$hit->alias;?>" class="mask"><img class="img-responsive zoom-img" src="images/<?=$hit->img;?>" alt="" /></a>
                        <div class="product-bottom">
                            <!-- алиас - короткое наименование для ссылки (ЧПУ) -->
                            <!-- наименование -->
                            <h3><a href="product/<?=$hit->alias;?>"><?=$hit->title;?></a></h3>
                            <p>Explore Now</p>
                            <h4>
                                <!-- ссылка для добавления товара в корзину (текущая цена) -->
                                <!-- в контроллере cart вызывается экшен add и передается id товара -->
                                <a class="add-to-cart-link" href="cart/add?id=<?=$hit->id;?>"><i></i></a> <span class=" item_price">$ <?=$hit->price;?></span>
                                <!-- выводим старую цену, если такая есть -->
                                <?php if($hit->old_price): ?>
                                    <small><del><?=$hit->old_price;?></del></small>
                                <?php endif; ?>
                            </h4>
                        </div>
                        <div class="srch">
                            <span>-50%</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<!--product-end-->
