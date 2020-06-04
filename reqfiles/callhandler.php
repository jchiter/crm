<?
session_start();

if (empty($_POST))
    die("Access denied.");

require("../common.php");

global $httpHandler, $userHandler, $allEvents;

$opIndex = intval($_POST["opindex"]);

if ($opIndex >= 0 && $opIndex <= count($allEvents))
    $nameEvent = $allEvents[$opIndex];
else
    die("Not found event.");

$sharedArray = array(
    "SYSTEM_ENTER" => "SystemEnter",
    "SHOW_MODULE" => "ShowModule"
);

$sharedLoginArray = array(
    "SYSTEM_EXIT" => "SystemExit",
    "PROFILE_EDIT" => "ProfileEdit"
);

$sharedAdminArray = array();

$h02Array = [
    "CREATE_ORDER" => "CreateOrder",
    "EDIT_ORDER" => "EditOrder",
    "SAVE_ORDER" => "SaveOrder",
    "SHOW_ORDERS" => "ShowOrders",
    "LIST_ADD" => "ListAdd",
    "LIST_REMOVE" => "ListRemove",
    "LIST_EDIT" => "ListEdit",
    "GET_LIST" => "GetList",
    "SHOW_STATISTIC" => "ShowStatistic"
];

$h01Array = array();

$h00Array = array();

switch ($httpHandler->GetAccessLevel()) {
    // Не авторизованный
    case LEVEL_SHARED:
        $callArray = $h00Array;
        break;

    // Просмотр ресурса
    case LEVEL_VIEW:
        $callArray = $h01Array;
        break;

    // Просмотр ресурса
    case LEVEL_EDIT:
        $callArray = $h02Array;
        break;
}

foreach ($sharedArray as $key => $value)
    $callArray[$key] = $value;

// Аутентифицированный пользователь
if ($httpHandler->GetAccessLevel() >= LEVEL_MAIN) {
    foreach ($sharedLoginArray as $key => $value)
        $callArray[$key] = $value;
}

if (array_key_exists($nameEvent, $callArray)) {
    global $messageHandler;

    $resultOperation = $userHandler->{$callArray[$nameEvent]}();
    switch ($resultOperation) {
        case RESULT_SUCCESS:
            $messageHandler->JsonMessageSuccess("Операция успешно выполнена.");
            break;

        case RESULT_ERROR_INPUT:
            $messageHandler->JsonMessageError("Введите в поля ввода корректные данные.");
            break;

        case RESULT_ERROR_EXISTS:
            $messageHandler->JsonMessageError("С указанным именем в базе данных уже существует пользователь.");
            break;

        case RESULT_ERROR_DB:
            $messageHandler->JsonMessageError("Ошибка базы данных.");
            break;

        case RESULT_ERROR_RECEIVE:
            $messageHandler->JsonMessageError("Ошибка получения данных для операции.");
            break;
    }
}
?>