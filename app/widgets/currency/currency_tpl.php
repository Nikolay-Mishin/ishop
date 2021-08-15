<!-- активная валюта -->
<option value="" class="label"><?=$this->currency['code'];?></option>
<!-- в цикле выводим все валюты, кроме активной -->
<?php foreach ($this->currencies as $k => $v): ?>
    <?php if ($k != $this->currency['code']): ?>
        <option value="<?=$k;?>"><?=$k;?></option>
    <?php endif; ?>
<?php endforeach; ?>
