<?

class UserTableStruct
{
    public static $columnID = "users_id";
    public static $columnName = "users_name";
    public static $columnViewName = "users_view_name";
    public static $columnPass = "users_pass";
    public static $columnDate = "users_create";
    public static $columnActive = "users_active";
    public static $columnStatus = "users_status";
}

class UserTable
{
    var $currentTable = TABLE_USERS;
    
    public function Select($whereText = "")
    {
        global $db;
        
        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        
        $format = 'SELECT * FROM %1$s %2$s';
        $queryText = sprintf($format, $this->currentTable, $isWhere);
        return $db->query($queryText);
    }
    
    public function Insert($data)
    {
        global $db;
        
        $dateNow = time();
        $to      = $data[CaptionField::$inputMail];
        $subject = 'Подтверждение регистрации';
        $message = 'http://localhost/?p=reg&create='. md5($dateNow);
        $headers = 'From: webmaster@example.com' . "\r\n" .
                   'Reply-To: webmaster@example.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        $format = 'INSERT INTO %1$s (`%2$s`, `%3$s`, `%4$s`, `%5$s`, `%6$s`, `%7$s`, `%8$s`, `%9$s`) VALUES '.
                                   '(\'%10$s\', \'%11$s\', \'%12$s\', \'%13$s\', \'%14$s\', \'%15$s\', \'%16$s\', MD5(%17$s))';
        $queryText = sprintf($format, $this->currentTable, 
            UserTableStruct::$columnSurname,
            UserTableStruct::$columnFirstName,
            UserTableStruct::$columnPatronymic,
            UserTableStruct::$columnWeb,
            UserTableStruct::$columnMail,
            UserTableStruct::$columnName,
            UserTableStruct::$columnPass,
            UserTableStruct::$columnDate,
            $data[CaptionField::$inputSurname],
            $data[CaptionField::$inputFirstName],
            $data[CaptionField::$inputPatronymic],
            $data[CaptionField::$inputWeb],
            $data[CaptionField::$inputMail],
            $data[CaptionField::$inputName],
            md5(crc32($data[CaptionField::$inputPass])),
            $dateNow);
            
        mail($to, $subject, $message, $headers);
            
        return $db->query($queryText); 
    }
    
    public function Status($dateCreate)
    {
        global $db;
        
        $formatSelect = 'SELECT `%1$s` FROM %2$s WHERE `%3$s` = \'%4$s\'';
        $querySelect = sprintf($formatSelect, UserTableStruct::$columnStatus, $this->currentTable, UserTableStruct::$columnDate, $dateCreate);
        
        $formatUpdate = 'UPDATE %1$s SET `%2$s` = 1 WHERE `%3$s` = \'%4$s\'';
        $queryUpdate = sprintf($formatUpdate, $this->currentTable, UserTableStruct::$columnStatus, UserTableStruct::$columnDate, $dateCreate);
        
        $db->query($querySelect);
        list($userStatus) = $db->fetch_row();
        
        if ($db->num_rows() == 1 && $userStatus == 0)
        {
            $db->query($queryUpdate);
            
            return $db->affected_rows();
        }
        else if ($userStatus == 1)
            return 2;
        
        return 0;
    }
    
    public function UpdateActive($whereText = "")
    {
        global $db;
        
        $isWhere = strlen($whereText) > 1 ? "WHERE ". $whereText : "";
        
        $format = 'UPDATE %1$s SET `%2$s` = \'%3$s\' %4$s';
        $queryText = sprintf($format, $this->currentTable, 
            UserTableStruct::$columnActive, time(), $isWhere);
            
        return $db->query($queryText);
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
    
    public function Delete($whereText = "")
    {
        global $db;
        
        $format = 'DELETE FROM %1$s %2$s';
        $queryText = sprintf($format, $this->currentTable, $whereText);
        
        $_SESSION[ITEM_ID] = -1;
        return $db->query($queryText);
    }
}

?>