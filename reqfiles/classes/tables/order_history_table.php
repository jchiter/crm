<?

class OrderHistoryTableStruct
{
    public static $columnID = "order_history_id";
    public static $columnID_Order = "order_history_id_order";
    public static $columnID_User = "order_history_id_user";
    public static $columnDate = "order_history_date";
	public static $columnID_Status = "order_history_id_status";
	public static $columnIP = "order_history_ip";
}

class OrderHistoryTable
{
    var $currentTable = TABLE_ORDER_HISTORY;
    public $currentStruct = OrderHistoryTable;

    public function Select($whereText = "")
    {
        global $db;

        $isWhere = strlen($whereText) > 1 ? "WHERE " . $whereText : "";

        $format = 'SELECT * FROM %1$s %2$s';
        $queryText = sprintf($format, $this->currentTable, $isWhere);

        return $db->query($queryText);
    }

    public function Update($updateColumns = array(), $whereText = "")
    {
        global $db;

        $isWhere = strlen($whereText) > 1 ? "WHERE " . $whereText : "";
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

    public function Delete($whereText = "")
    {
        global $db;

        $format = 'DELETE FROM %1$s %2$s';
        $queryText = sprintf($format, $this->currentTable, $whereText);

        if ($db->query($queryText))
            return RESULT_SUCCESS;
        else
            return RESULT_ERROR_DB;
    }
}

?>