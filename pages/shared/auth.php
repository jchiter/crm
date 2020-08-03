<?php
echo "<form id='uploadForm' method='post'><input type='hidden' class='caption' value='Пожалуйста, авторизуйтесь'>";

echo "<div class='alert' style='display: none'>";
echo "</div>";

echo "<div class='input-group' style='margin-bottom: 10px;'>";
echo "<span class='input-group-addon'><i class='glyphicon glyphicon-user'></i></span>";
echo "<input class='form-control input-lg' placeholder='Имя пользователя' aria-describedby='inputGroup-sizing-sm' type='text' name='" . CaptionField::$inputName . "' onkeypress='if (event.keyCode == 13) $.coremanage.systemEnter()'>";
echo "</div>";

echo "<div class='input-group' style='margin-bottom: 10px;'>";
echo "<span class='input-group-addon'><i class='glyphicon glyphicon-lock'></i></span>";
echo "<input class='form-control input-lg' placeholder='Пароль' type='password' name='" . CaptionField::$inputPass . "' onkeypress='if (event.keyCode == 13) $.coremanage.systemEnter()'>";
echo "</div>";

echo "<div class='section-footer'>";
echo "<button type='button' class='btn btn-success' onclick='$.coremanage.systemEnter()'>Вход</button>";
echo "<button type='button' class='btn btn-danger' onclick='$(function() { BootstrapDialog.closeAll(); })' name='cancel'>Отмена</button>";
echo "</div>";
echo "</form>";
?>