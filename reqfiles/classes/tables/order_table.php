<?

class OrderTableStruct
{
    public static $columnID = "orders_id";
    public static $columnPhone = "orders_phone";
    public static $columnPhoneAdd = "orders_phone_add";
    public static $columnID_Name = "orders_id_name";
    public static $columnID_Street = "orders_id_street";
    public static $columnHouse = "orders_house";
    public static $columnEntrance = "orders_entrance";
    public static $columnFloor = "orders_floor";
    public static $columnApart = "orders_apart";
    public static $columnAge = "orders_age";
    public static $columnID_Sex = "orders_id_sex";
    public static $columnID_Event = "orders_id_event";
    public static $columnAmount = "orders_amount";
    public static $columnDiscount = "orders_discount";
    public static $columnPrepay = "orders_prepay";
    public static $columnID_Prepay = "orders_id_prepay";
    public static $columnID_Balance = "orders_id_balance";
    public static $columnDate = "orders_date";
    public static $columnTime = "orders_time";
    public static $columnDesc = "orders_desc";
    public static $columnCreate = "orders_create";
    public static $columnID_Sales = "orders_id_sales";
    public static $columnID_Status = "orders_id_status";
    public static $columnID_User = "orders_id_user";

}

class OrderTable
{
    var $currentTable = TABLE_ORDERS;
    
    public function Select($whereText = "")
    {
        global $db;
        
        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        
        $format = 'SELECT * FROM %1$s %2$s';
        $queryText = sprintf($format, $this->currentTable, $isWhere);

        return $db->query($queryText);
    }

    public function Insert()
    {
        global $db, $userHandler;

        $nameId = intval($_POST[CaptionField::$inputName]);
        $streetId = intval($_POST[CaptionField::$inputStreet]);
        $eventId = intval($_POST[CaptionField::$inputEvent]);
        $sexId = intval($_POST[CaptionField::$inputSex]);
        $salesId = intval($_POST[CaptionField::$inputSalesType]);

        if (($eventId <= 0 || $sexId <= 0 || $nameId <= 0 || $salesId <= 0))
            return RESULT_ERROR_RECEIVE;

        $dateNow = time();
        $dateArray = explode('.', $_POST[CaptionField::$inputDate]);
        $dateCreate = $dateArray[2] ."-". $dateArray[1] ."-". $dateArray[0];
        $insertArray = array(
            OrderTableStruct::$columnPhone => $_POST[CaptionField::$inputPhone],
            OrderTableStruct::$columnPhoneAdd => $_POST[CaptionField::$inputAddPhone],
            OrderTableStruct::$columnID_Name => $nameId,
            OrderTableStruct::$columnID_Street => $streetId,
            OrderTableStruct::$columnHouse => $_POST[CaptionField::$inputHouse],
            OrderTableStruct::$columnEntrance => $_POST[CaptionField::$inputEntrance],
            OrderTableStruct::$columnFloor => $_POST[CaptionField::$inputFloor],
            OrderTableStruct::$columnApart => $_POST[CaptionField::$inputApart],
            OrderTableStruct::$columnAge => $_POST[CaptionField::$inputAge],
            OrderTableStruct::$columnID_Sex => $sexId,
            OrderTableStruct::$columnID_Event => $eventId,
            OrderTableStruct::$columnAmount => $_POST[CaptionField::$inputAmount],
            OrderTableStruct::$columnDiscount => $_POST[CaptionField::$inputDiscount],
            OrderTableStruct::$columnPrepay => $_POST[CaptionField::$inputPrepay],
            OrderTableStruct::$columnID_Prepay => $_POST[CaptionField::$inputPrepayType],
            OrderTableStruct::$columnDate => $dateCreate,
            OrderTableStruct::$columnTime => $_POST[CaptionField::$inputTime],
            OrderTableStruct::$columnDesc => $_POST[CaptionField::$inputDesc],
            OrderTableStruct::$columnCreate => $dateNow,
            OrderTableStruct::$columnID_Sales => $salesId,
            OrderTableStruct::$columnID_User => $userHandler->GetUserId()
        );

        $format = 'INSERT INTO '. $this->currentTable;
        $columnsFormat = ' (';
        $valuesFormat = 'VALUES(';
        foreach ($insertArray as $column => $value)
        {
            $columnsFormat .= '`'. $column .'`, ';
            $valuesFormat .= '\''. $value .'\', ';
        }

        $columnsFormat[strlen($columnsFormat) - 2] = ') ';
        $valuesFormat[strlen($valuesFormat) - 2] = ') ';
        $queryText = $format . $columnsFormat . $valuesFormat;

        if ($db->query($queryText, $dbError))
            return RESULT_SUCCESS;
        else
            return RESULT_ERROR_DB;
    }

    public function Update($updateColumns = array(), $whereText = "")
    {
        global $db;

        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        $columnsFormat = '';

        foreach ($updateColumns as $column => $value) {
            if (is_array($value))
                $columnsFormat .= '`' . key($value) . '` = \'' . $value[key($value)] . '\',';
            else
                $columnsFormat .= '`' . $column . '` = \'' . $value . '\',';
        }

        $columnsFormat = substr($columnsFormat, 0, strlen($columnsFormat) - 1);

        $format = 'UPDATE %1$s SET %2$s %3$s';
        $queryText = sprintf($format, $this->currentTable, $columnsFormat, $isWhere);

        if ($db->query($queryText))
            return RESULT_SUCCESS;
        else
            return RESULT_ERROR_DB;
    }
}

?>