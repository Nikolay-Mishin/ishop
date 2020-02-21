<p>Через несколько секунд Вы будете перенаправлены на страницу оплаты. Нажмите кнопку, если не хотите ждать...</p>

<?php if($pay): ?>
    <form id="payment" name="payment" method="post" action="https://sci.interkassa.com/" enctype="utf-8">
        <input type="hidden" name="ik_co_id" value="<?=$pay['ik_id'];?>" />
        <input type="hidden" name="ik_pm_no" value="<?=$pay['id'];?>" />
        <input type="hidden" name="ik_am" value="<?=$pay['sum'];?>" />
        <input type="hidden" name="ik_cur" value="<?=$pay['curr'];?>" />
        <input type="hidden" name="ik_desc" value="Платеж <?=$pay['shop_name'];?>" />
        <input type="submit" value="Оплатить">
    </form>
<?php endif; ?>

<script>
    /* setTimeout(function(){
        $('form').submit();
    }, 20000); */
</script>
