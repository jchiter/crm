<?php
echo "<form id='passForm' method='post'><input type='hidden' class='caption' value='Изменение пароля'>";

echo "<div class='alert' style='display: none'>";
echo "</div>";

echo "<div class='input-group' style='margin-bottom: 10px;'>";
echo "<span class='input-group-addon'>Старый пароль</span>";
echo "<input class='form-control input-lg' type='password' name='" . CaptionField::$inputPass . "'>";
echo "</div>";

echo "<div class='input-group' style='margin-bottom: 10px;'>";
echo "<span class='input-group-addon'>Новый пароль</span>";
echo "<input class='form-control input-lg' type='password' name='" . CaptionField::$inputPassNew . "'>";
echo "</div>";

echo "<div class='input-group' style='margin-bottom: 10px;'>";
echo "<span class='input-group-addon'>Подтвердите пароль</span>";
echo "<input class='form-control input-lg' type='password' name='" . CaptionField::$inputPassConfirm . "'>";
echo "</div>";

echo "<div class='section-footer'>";
echo "<button type='button' class='btn btn-success' onclick='$.coremanage.profileEditPass()'>Сохранить</button>";
echo "<button type='button' name='cancel' class='btn btn-danger' onclick='$(function() { BootstrapDialog.closeAll(); })'>Отмена</button>";
echo "</div>";
echo "</form>";
?>