<?

class NameTableStruct
{
    public static $columnID = "names_id";
    public static $columnID_Sex = "names_id_sex";
    public static $columnName = "names_name";
}

class NameTable
{
    var $currentTable = TABLE_NAMES;
    
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
        global $db;

        $nameVal = strip_tags($_POST["data"]);
        $sexId = intval($_POST[CaptionField::$inputSex]);

        if ($sexId <= 0)
            return RESULT_ERROR_RECEIVE;

        $format = 'INSERT INTO %1$s (`%2$s`, `%3$s`) VALUES (\'%4$s\', %5$s)';
        $queryText = sprintf($format, $this->currentTable,
            NameTableStruct::$columnName, NameTableStruct::$columnID_Sex,
            $nameVal, $sexId);

        if ($db->query($queryText)) {
            $_SESSION["lastId"] = mysqli_fetch_row($db->query("SELECT LAST_INSERT_ID()"))[0];
            return RESULT_SUCCESS;
        }
        else
            return RESULT_ERROR_DB;
    }

    public function Update($whereText = "")
    {
        global $db;

        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        $data = strip_tags($_POST["data"]);

        $format = 'UPDATE %1$s SET `%2$s` = \'%3$s\' %4$s';
        $queryText = sprintf($format, $this->currentTable,
            NameTableStruct::$columnName, $data, $isWhere);

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