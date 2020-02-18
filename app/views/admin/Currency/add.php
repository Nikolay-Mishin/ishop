<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая валюта
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=ADMIN;?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?=ADMIN;?>/currency">Список валют</a></li>
        <li class="active">Новая валюта</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form id="course-form" action="<?=ADMIN;?>/currency/add" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Наименование валюты</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Наименование валюты" required value="<?= isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="code">Код валюты</label>
                            <input type="text" name="code" class="form-control" id="code" placeholder="Код валюты" required value="<?= isset($_SESSION['form_data']['code']) ? h($_SESSION['form_data']['code']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="courses">Коды валют</label>
                            <select name="courses" id="courses" class="form-control">
                                <option data-code="<?= isset($_SESSION['form_data']['code']) ? h($_SESSION['form_data']['code']) : null; ?>" data-course="<?= isset($_SESSION['form_data']['course']) ? h($_SESSION['form_data']['course']) : null; ?>" data-title="<?= isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>">Выберите код валюты</option>
                                <?php foreach($courses as $code => $course): ?>
                                    <option value="<?=$course['CharCode'];?>" <?=in_array($course['CharCode'], $codeList) ? ' disabled' : '';?> data-code="<?=$code;?>" data-course="<?=$course['Value'];?>" data-title="<?=$course['Name'];?>"<?= isset($_SESSION['form_data']['code']) ? ' selected' : ''; ?>>
                                        <?=$code;?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="symbol_left">Символ слева</label>
                            <input type="text" name="symbol_left" class="form-control" id="symbol_left" placeholder="Символ слева" value="<?= isset($_SESSION['form_data']['symbol_left']) ? h($_SESSION['form_data']['symbol_left']) : null; ?>">
                        </div>
                        <div class="form-group">
                            <label for="symbol_right">Символ справа</label>
                            <input type="text" name="symbol_right" class="form-control" id="symbol_right" placeholder="Символ справа" value="<?= isset($_SESSION['form_data']['symbol_right']) ? h($_SESSION['form_data']['symbol_right']) : null; ?>">
                        </div>
                        <div class="form-group has-feedback">
                            <label for="course">Курс</label>
                            <input type="text" name="course" class="form-control" id="course" placeholder="Курс" required data-error="Допускаются цифры и десятичная точка" pattern="^[0-9.]{1,}$" value="<?= isset($_SESSION['form_data']['course']) ? h($_SESSION['form_data']['course']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="value">
                                <input type="checkbox" name="base"<?= isset($_SESSION['form_data']['base']) ? ' checked' : ''; ?>>
                                Базовая валюта</label>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
                <?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->
