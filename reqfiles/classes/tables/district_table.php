<?

class DistrictTableStruct
{
    public static $columnID = "district_id";
    public static $columnName = "district_name";
    public static $columnID_City = "district_id_city";
}

class DistrictTable
{
    var $currentTable = TABLE_DISTRICT;

    public function Select($whereText = "")
    {
        global $db;

        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        $leftJoin = sprintf('LEFT JOIN %1$s ON %1$s.%2$s = %3$s.%4$s', TABLE_CITIES, CitiesTableStruct::$columnID, $this->currentTable, DistrictTableStruct::$columnID_City);

        $format = 'SELECT * FROM %1$s %2$s %3$s';
        $queryText = sprintf($format, $this->currentTable, $isWhere, $leftJoin);

        return $db->query($queryText);
    }

    public function Insert()
    {
        global $db;

        $data = strip_tags($_POST["data"]);

        $format = 'INSERT INTO %1$s (`%2$s`) VALUES (\'%3$s\')';
        $queryText = sprintf($format, $this->currentTable,
            DistrictTableStruct::$columnName,
            $data);

        if ($db->query($queryText)) {
            $_SESSION["lastId"] = mysqli_fetch_row($db->query("SELECT LAST_INSERT_ID()"))[0];
            return RESULT_SUCCESS;
        }
        else
            return RESULT_ERROR_DB;
    }

    public function Update($columns, $where = [])
    {
        global $db;

        $queryText = $db->PrepareQueryUpdate($this->currentTable, $columns, $where);

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