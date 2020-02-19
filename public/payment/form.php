<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<p>Через несколько секунд Вы будете перенаправлены на страницу оплаты. Нажмите кнопку, если не хотите ждать...</p>

<?php if(!empty($_SESSION['payment'])): ?>
    <form id="payment" name="payment" method="post" action="https://sci.interkassa.com/" enctype="utf-8">
        <input type="hidden" name="ik_co_id" value="<?=ishop\App::$app->getProperty('ik_id');?>" />
        <input type="hidden" name="ik_pm_no" value="<?=$_SESSION['payment']['id'];?>" />
        <input type="hidden" name="ik_am" value="<?=$_SESSION['payment']['sum'];?>" />
        <input type="hidden" name="ik_cur" value="<?=$_SESSION['payment']['curr'];?>" />
        <input type="hidden" name="ik_desc" value="Платеж <?=ishop\App::$app->getProperty('shop_name');?>" />
        <input type="submit" value="Оплатить">
    </form>
<?php endif; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
    setTimeout(function(){
        $('form').submit();
    }, 20000);
</script>

</body>
</html>
