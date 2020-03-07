<!--start-breadcrumbs-->
<div class="breadcrumbs">
    <div class="container">
        <div class="breadcrumbs-main">
            <ol class="breadcrumb">
                <li><a href="<?= PATH ?>">Главная</a></li>
                <li><a href="<?= PATH ?>/user/cabinet">Личный кабинет</a></li>
                <li class="active">Профиль пользователя <?=$user->login;?></li>
            </ol>
        </div>
    </div>
</div>
<!--end-breadcrumbs-->

<!--prdt-starts-->
<div class="prdt">
    <div class="container">
        <div class="prdt-top">
            <div class="col-md-12 prdt-left">
                <div class="register-top heading">
                    <h2>Профиль пользователя <?=$user->login;?></h2>
                </div>

                <div class="product-one">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped table-condensed">
                            <tbody>
                                <tr>
                                    <td>Имя</td>
                                    <td><?=$user->name;?></td>
                                </tr>
                                <tr>
                                    <td>Роль</td>
                                    <td><?=$user->role;?></td>
                                </tr>
                                <tr>
                                    <td>Никнейм</td>
                                    <td>
                                        <?=$user->login;?>
                                        <img class="media-object img-rounded" src="images/<?=$user->avatar;?>" alt="<?=$user->name;?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--product-end-->
