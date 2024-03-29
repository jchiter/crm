<?

class StreetTableStruct
{
    public static $columnID = "streets_id";
    public static $columnName = "streets_name";
    public static $columnID_City = "streets_id_city";
    public static $columnID_District = "streets_id_district";
}

class StreetTable
{
    var $currentTable = TABLE_STREETS;
    
    public function Select($whereText = "", $limitText = "")
    {
        global $db;
        
        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        $isLimit = strlen($limitText) > 1 ? "LIMIT ". $limitText : "";
        $leftJoinCities = sprintf('LEFT JOIN %1$s ON %1$s.%2$s = %3$s.%4$s', TABLE_CITIES, CitiesTableStruct::$columnID, $this->currentTable, StreetTableStruct::$columnID_City);
        $leftJoinDistrict = sprintf('LEFT JOIN %1$s ON %1$s.%2$s = %3$s.%4$s', TABLE_DISTRICT, DistrictTableStruct::$columnID, $this->currentTable, StreetTableStruct::$columnID_City);

        $format = 'SELECT * FROM %1$s %2$s %3$s %4$s %5$s';
        $queryText = sprintf($format, $this->currentTable, $leftJoinCities, $leftJoinDistrict, $isWhere, $isLimit);
        //echo $queryText;

        return $db->query($queryText);
    }

    public function Count()
    {
        global $db;

        $format = 'SELECT COUNT(*) FROM %1$s';
        $queryText = sprintf($format, $this->currentTable);

        return $db->query($queryText);
    }

    public function Insert()
    {
        global $db;

        $data = strip_tags($_POST["data"]);

        $format = 'INSERT INTO %1$s (`%2$s`) VALUES (\'%3$s\')';
        $queryText = sprintf($format, $this->currentTable,
            StreetTableStruct::$columnName,
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