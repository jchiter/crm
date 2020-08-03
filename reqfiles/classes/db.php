<?
//
// DB_mysql
//
// Database abstraction class for MySQL databases.
//
class DB_mysql
{
    var $db_addr;
    var $db_user;
    var $db_pass;
    var $db_name;

    var $link;
    var $result;
    var $rowdata;
    var $insert_id;
    var $numrows;
    var $affected_rows;
    var $query;
    var $lastError;

    function DB_mysql($db_addr = -1, $db_user = -1, $db_pass = -1, $db_name = -1)
    {
        if ($db_addr == -1)
            $db_addr = DB_ADDR;
        if ($db_user == -1)
            $db_user = DB_USER;
        if ($db_pass == -1)
            $db_pass = DB_PASS;
        if ($db_name == -1)
            $db_name = DB_NAME;

        $this->db_addr = $db_addr;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_name = $db_name;
        
        $this->link = new mysqli($db_addr, $db_user, $db_pass, $db_name);
        
        if ($this->link->connect_errno) 
        {
            $this->error("Не удалось установить соединение с сервером базы данных.<br>Проверьте настройки подключения в конфигурационном файле.");
            exit();
        }
        
        //$this->link = mysqli_connect() or die($this->error("Не удалось установить соединение с сервером базы данных.<br>Проверьте настройки подключения в конфигурационном файле."));
        //mysqli_select_db($this->link, $db_name) or $this->error("Не удалось подключиться к указанной базе данных.<br>Проверьте настройки подключения в конфигурационном файле.");

        //@mysqli_query($this->link, "SET NAMES 'utf8'");
        $this->link->query("SET NAMES 'utf8'");
    }

    function data_seek($row_number, $result = -1)
    {
        if (!is_object($result))
            $result = $this->result;
            
        return mysqli_data_seek($result, $row_number);
    }

    function fetch_array($result = -1)
    {
        if (!is_object($result))
            $result = $this->result;
            
        $this->rowdata = mysqli_fetch_array($result);
        return $this->rowdata;
    }

    function fetch_row($result = -1)
    {
        if (!is_object($result))
            $result = $this->result;
            
        $this->rowdata = mysqli_fetch_row($result);
        return $this->rowdata;
    }

    function free_result($result = -1)
    {
        if (!is_object($result))
            $result = $this->result;
            
        return mysqli_free_result($result);
    }

    function num_rows($result = -1)
    {
        if (!is_object($result))
            $result = $this->result;
            
        $this->numrows = mysqli_num_rows($result);
        return $this->numrows;
    }

    function affected_rows($result = -1)
    {
        if (!is_object($result))
            $result = $this->result;
            
        $this->affected_rows = mysqli_affected_rows($this->link);
        return $this->affected_rows;
    }

    function query($query, &$dberror = null)
    {
        $this->query = $query;
        $this->result = $this->link->query($query);
        $this->affected_rows = mysqli_affected_rows($this->link);

        if (!$this->result && $dberror != null)
        {
            $dberror = $this->error("Bad query.");
            return 0;
        }

        return $this->result;
    }

    function result($row, $field, $result = -1)
    {
        /*if ($result < 0)
            $result = $this->result;

        return mysqli_result($result, $row, $field);*/
    }

    function error($message)
    {     
        global $messageHandler;

        $this->lastError = $messageHandler->JsonMessageErrorDb($messageHandler->MessageErrorDb($message, $this->db_addr, $this->db_name, $this->db_user, $this->query, $this->link->error, true));
        return $this->lastError;
    }

    function GetLastError()
    {
        return $this->lastError;
    }

    function dberror()
    {
        return mysqli_errno($this->link);
    }

    function PrepareQueryUpdate($tableName, $elements, $whereElements = [], $returnColumn = "id") {
        $values = "";
        foreach ($elements as $name => $value) {
            if ($name == "id")
                continue;

            if ($value == "NULL" || strlen($value) == 0)
                $values .= $name . " = NULL, ";
            else
                $values .= $name . " = '" . $value . "', ";
        }

        $values = substr($values, 0, strlen($values) - 2);
        $strFormat = "UPDATE %s SET %s ";
        if (empty($whereElements) && isset($elements["id"])) {
            $strFormat .= " WHERE %s";
            $queryString = sprintf($strFormat, $tableName, $values, "id = " . $elements["id"]);
        } else if (!empty($whereElements)) {
            $whereText = "";
            foreach ($whereElements as $elemName => $elemValue) {
                $whereText .= $elemName . " = '" . $elemValue . "' AND ";
            }

            $strFormat .= " WHERE %s";
            $whereText = substr($whereText, 0, strlen($whereText) - 4);
            $queryString = sprintf($strFormat, $tableName, $values, $whereText);
        }

        return $queryString;
    }
}

?>