<?

class SexTableStruct
{
    public static $columnID = "sex_id";
    public static $columnName = "sex_name";
}

class SexTable
{
    var $currentTable = TABLE_SEX;
    
    public function Select($whereText = "")
    {
        global $db;
        
        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        
        $format = 'SELECT * FROM %1$s %2$s';
        $queryText = sprintf($format, $this->currentTable, $isWhere);

        return $db->query($queryText);
    }
}

?>