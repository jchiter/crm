<?
if (!isset($_SESSION))
    session_start();

date_default_timezone_set("Europe/Moscow");

// Файл содержит имя и номер операции, связывающий js и php
require("reqfiles/constants.php");

// Файл содержит настройки подключения к БД
require("config.php");

// Файл содержит класс для работы с БД
require("reqfiles/classes/db.php");

// Файл содержит класс для работы со страницами сайта (модули)
include("reqfiles/classes/page.php");

// Файл содержит класс для работы выводом информации блочно
require("reqfiles/classes/block.php");

require("reqfiles/handlers/other/basehandler.php");
require("reqfiles/handlers/other/loginhandler.php");

require("reqfiles/handlers/other/stringhandler.php");
require("reqfiles/handlers/other/filehandler.php");
require("reqfiles/handlers/other/httphandler.php");
require("reqfiles/handlers/other/messagehandler.php");

require("reqfiles/handlers/other/for_tables/member_handler.php");

// Таблицы
require("reqfiles/classes/tables/user_table.php");
require("reqfiles/classes/tables/street_table.php");
require("reqfiles/classes/tables/event_table.php");
require("reqfiles/classes/tables/sex_table.php");
require("reqfiles/classes/tables/order_table.php");
require("reqfiles/classes/tables/name_table.php");
require("reqfiles/classes/tables/prepay_table.php");
require("reqfiles/classes/tables/sales_table.php");
require("reqfiles/classes/tables/order_status_table.php");
require("reqfiles/classes/tables/order_history_table.php");
require("reqfiles/classes/tables/keywords_table.php");

$messageHandler = new MessageHandler();

$db = new DB_mysql();
$page = new Page();
$block = new Block();

$stringHandler = new StringHandler();
$fileHandler = new FileHandler();
$httpHandler = new HttpHandler();
$loginHandler = new LoginHandler();

$memberHandler = new MemberHandler();

$userTable = new UserTable();
$streetTable = new StreetTable();
$eventTable = new EventTable();
$sexTable = new SexTable();
$orderTable = new OrderTable();
$nameTable = new NameTable();
$prepayTable = new PrepayTable();
$salesTable = new SalesTable();
$orderStatusTable = new OrderStatusTable();
$orderHistoryTable = new OrderHistoryTable();
$keywordTable = new KeywordTable();

include("reqfiles/handlers/h0" . $httpHandler->GetAccessLevel() . "/h0" . $httpHandler->GetAccessLevel() . "_handler.php");
switch ($httpHandler->GetAccessLevel()) {
    case LEVEL_SHARED:
        $userHandler = new h00Handler();
        break;

    case LEVEL_VIEW:
        $userHandler = new h01Handler();
        break;

    case LEVEL_EDIT:
        $userHandler = new h02Handler();
        break;
}

?>