<?php

class BaseHandler
{
    function SystemEnter()
    {
        global $db, $httpHandler, $messageHandler, $userTable;

        if ($httpHandler->GetAccessLevel() > 0) {
            echo $messageHandler->JsonMessageWarning("Вы уже авторизованы в системе.");
            exit();
        }

        $userName = strip_tags($_POST[CaptionField::$inputName]);
        $password = strip_tags($_POST[CaptionField::$inputPass]);

        $userTable->Select(sprintf("`%s` = '%s' AND `%s` = MD5('%s')",
            UserTableStruct::$columnName, $userName,
            UserTableStruct::$columnPass, $password));

        if ($db->num_rows() > 1) {
            echo $messageHandler->JsonMessageError("В базе данных несколько пользователей с таким именем и паролем.<br />Обратитесь к администратору.");
            exit();
        } else if ($db->num_rows() == 0) {
            echo $messageHandler->JsonMessageError("В базе данных не существует пользователя с таким именем и паролем.");
            exit();
        }

        $_SESSION["fullInfo"] = $db->fetch_array();

        $userTable->UpdateActive(sprintf("`%s` = %s",
            UserTableStruct::$columnID, $_SESSION["fullInfo"][UserTableStruct::$columnID]));

        echo $messageHandler->JsonMessageSuccess("Добро пожаловать в систему.");
    }

    function ShowModule()
    {
        global $page;
        chdir("..");
        //$strModule = file_get_contents(getcwd() . "/pages/" . $page->GetFolder($_POST["data"]) . "/" . $_POST["data"] . ".php");
        //echo str_replace('\n', '4', $strModule);
        require(getcwd() . "/pages/" . $page->GetFolder($_POST["data"]) . "/" . $_POST["data"] . ".php");
    }
}

?>