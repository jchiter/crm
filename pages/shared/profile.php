<form class="contact2-form" method="post" id="uploadForm" style="width: 400px;">
    <fieldset>
        <div class="wrap-input2 validate-input" style="border-bottom: 0;">
            <label for="<?php echo CaptionField::$inputName; ?>">Имя пользователя</label>
            <input id="<?php echo CaptionField::$inputName; ?>" name="<?php echo CaptionField::$inputName; ?>" class="form-control" type="text" value="<?php echo $_SESSION["fullInfo"][UserTableStruct::$columnName]; ?>">
        </div>

        <div class="wrap-input2 validate-input" style="border-bottom: 0;">
            <label for="<?php echo CaptionField::$inputViewName; ?>">Отображаемое имя</label>
            <input id="<?php echo CaptionField::$inputViewName; ?>" name="<?php echo CaptionField::$inputViewName; ?>" class="form-control" type="text" value="<?php echo $_SESSION["fullInfo"][UserTableStruct::$columnViewName]; ?>">
        </div>

        <div class="section-footer">
            <button type="button" class="btn btn-info" onclick="$.coremanage.showModule('pass', true)">Изменить пароль</button><br><br>
            <button type="button" class="btn btn-success" onclick="$.coremanage.profileEdit()">Сохранить</button>
        </div>
    </fieldset>
</form>