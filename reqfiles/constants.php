<?
define("IS_REQUIRE_PAGE", true);
define("DEFAULT_PAGE", "main");
define("DEFAULT_THEME", "standart");
define("DEFAULT_COUNT_ITEMS", 5);
define("DEFAULT_TYPE_SORT", "desc");

define("TABLE_USERS", "crm_users");
define("TABLE_STREETS", "crm_streets");
define("TABLE_EVENTS", "crm_events");
define("TABLE_ORDERS", "crm_orders");
define("TABLE_SEX", "crm_sex");
define("TABLE_NAMES", "crm_names");
define("TABLE_PREPAY", "crm_prepay");
define("TABLE_SALES", "crm_sales");
define("TABLE_ORDER_STATUS", "crm_order_status");
define("TABLE_ORDER_HISTORY", "crm_order_history");
define("TABLE_KEYWORDS", "crm_keywords");

// Параметр использующийся в строке адреса для обозначения номера страницы
define("NAME_PARAM_NUMPAGE", "n");

// Параметр использующийся в строке адреса для обозначения идентификатора просматриваемой записи из БД (новость, партнер)
define("NAME_PARAM_ITEMID", "r");

// Параметр использующийся в строке адреса для обозначения имени модуля
define("NAME_PARAM_PAGENAME", "p");

define("MESSAGE_LEVEL_SUCCESS", 1);
define("MESSAGE_LEVEL_INFORMATION", 2);
define("MESSAGE_LEVEL_WARNING", 3);
define("MESSAGE_LEVEL_ERROR", 4);
define("MESSAGE_LEVEL_ERRORDB", 5);

define("LEVEL_SHARED", 0);
define("LEVEL_VIEW", 1);
define("LEVEL_EDIT", 2);

define("RESULT_ERROR_INPUT", 1);
define("RESULT_ERROR_DB", 2);
define("RESULT_SUCCESS", 3);
define("RESULT_ERROR_RECEIVE", 4);
define("RESULT_ERROR_EXISTS", 5);

define("APART_ONE_ROOM", 0);
define("APART_TWO_ROOM", 1);
define("APART_STUDIO", 2);

define("PROPERTY_OPERATION", "property");
define("ITEM_ID", "itemId");

$allEvents = array(
    "SYSTEM_ENTER",
    "SYSTEM_EXIT",
    "CREATE_ORDER",
    "EDIT_ORDER",
    "SHOW_ORDERS",
    "SAVE_ORDER",
    "LIST_ADD",
    "LIST_REMOVE",
    "LIST_EDIT",
    "PROFILE_EDIT",
    "GET_LIST",
    "SHOW_MODULE",
    "SHOW_STATISTIC"
);

class CaptionField
{
    public static $inputId = "idField";
    public static $inputName = "nameField";
    public static $inputViewName = "nameViewField";
    public static $inputPass = "passField";
    public static $inputPassNew = "passNewField";
    public static $inputPassConfirm = "passConfirmField";
    public static $inputPhone = "phoneField";
    public static $inputAddPhone = "phoneAddField";
    public static $inputAge = "ageField";
    public static $inputSex = "sexField";
    public static $inputStreet = "streetField";
    public static $inputEvent = "eventField";
    public static $inputHouse = "houseField";
    public static $inputEntrance = "entranceField";
    public static $inputFloor = "floorField";
    public static $inputApart = "apartField";
    public static $inputAmount = "amountField";
    public static $inputDiscount = "discountField";
    public static $inputPrepay = "prepayField";
    public static $inputPrepayType = "prepayTypeField";
    public static $inputBalanceType = "balanceTypeField";
    public static $inputSalesType = "salesTypeField";
    public static $inputDate = "dateField";
    public static $inputTime = "timeField";
    public static $inputDesc = "descField";
    public static $inputDealType = "dealTypeField";
    public static $inputStatusType = "statusTypeField";
    public static $inputKeyword = "keywordField";


    public static $errorGetReceiveId = "Ошибка получения идентификатора.";
    public static $errorGetReceiveById = "Ошибка получения информации по идентификатору.";

    public static $btnAdd = "добавить";
    public static $btnEdit = "изменить";
    public static $btnMove = "перенести";
    public static $btnCopy = "скопировать";
    public static $btnImage = "изображение";

    public static $fieldSurname = "фамилия";
    public static $fieldFirstName = "имя";
    public static $fieldPatronymic = "отчество";
    public static $fieldWeb = "web-сайт";
    public static $fieldMail = "электронный ящик";
    public static $fieldName = "имя пользователя";
    public static $fieldPass = "пароль";
}

?>