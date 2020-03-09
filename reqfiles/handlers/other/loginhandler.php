<?php

class LoginHandler extends BaseHandler
{
    function SystemExit()
    {
        global $messageHandler;

        session_destroy();
        unset($_SESSION);

        if (empty($_SESSION))
            echo $messageHandler->JsonMessageSuccess("Вы вышли из системы.");
        else
            echo $messageHandler->JsonMessageError("Произошла ошибка при выходе из системы.");
    }

    function ProfileEdit()
    {
        global $messageHandler, $userHandler, $userTable;

        $nameVal = strip_tags($_POST[CaptionField::$inputName]);
        $nameViewVal = strip_tags($_POST[CaptionField::$inputViewName]);
        $passVal = strip_tags($_POST[CaptionField::$inputPass]);
        $passNewVal = strip_tags($_POST[CaptionField::$inputPassNew]);
        $passConfirmVal = strip_tags($_POST[CaptionField::$inputPassConfirm]);

        $updateArray = array(UserTableStruct::$columnActive => time());

        if (strlen($nameVal) > 0)
            array_push($updateArray, array(UserTableStruct::$columnName => $nameVal));

        if (strlen($nameViewVal) > 0)
            array_push($updateArray, array(UserTableStruct::$columnViewName => $nameViewVal));

        if (strlen($passVal) > 0) {
            if (strcmp(md5($passVal), $_SESSION["fullInfo"][UserTableStruct::$columnPass]) == 0) {
                if (strcmp($passNewVal, $passConfirmVal) == 0)
                    array_push($updateArray, array(UserTableStruct::$columnPass => md5($passNewVal)));
                else {
                    echo $messageHandler->JsonMessageError("Проверьте правильность ввода нового пароля.");
                    return true;
                }
            } else {
                echo $messageHandler->JsonMessageError("Введите корректный текущий пароль.");
                return true;
            }
        }

        $resultText = $messageHandler->JsonMessageSuccess("Значение успешно изменено");
        switch ($userTable->Update($updateArray, "`" . UserTableStruct::$columnID . "` = '" . $userHandler->GetUserId() . "'")) {
            case RESULT_ERROR_DB:
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка базы данных.");
                break;

            case RESULT_ERROR_RECEIVE:
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
                break;

            case RESULT_SUCCESS:
                if (strlen($nameViewVal) > 0)
                    $_SESSION["fullInfo"][UserTableStruct::$columnViewName] = $nameViewVal;

                if (strlen($nameVal) > 0)
                    $_SESSION["fullInfo"][UserTableStruct::$columnName] = $nameVal;

                if (strlen($passVal) > 0 &&
                    strcmp(md5($passVal), $_SESSION["fullInfo"][UserTableStruct::$columnPass]) == 0 &&
                    strcmp($passNewVal, $passConfirmVal == 0)
                ) {
                    $_SESSION["fullInfo"][UserTableStruct::$columnPass] = md5($passNewVal);
                }

                break;
        }

        echo $resultText;
        return true;
    }

    function GetUserId()
    {
        return intval($_SESSION["fullInfo"][UserTableStruct::$columnID]);
    }
}

?>