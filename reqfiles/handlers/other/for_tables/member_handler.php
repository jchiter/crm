<?php           
    class MemberHandler extends BaseHandler
    {
        private function AddOrEdit($typeFunction = 1)
        {
            global $core, $db, $userTable;
            
            error_reporting(E_ERROR);
            
            $fieldSurname = strip_tags($_POST[CaptionField::$inputSurname]);
            $fieldFirstName = strip_tags($_POST[CaptionField::$inputFirstName]);
            $fieldPatronymic = strip_tags($_POST[CaptionField::$inputPatronymic]);
            $fieldWeb = strip_tags($_POST[CaptionField::$inputWeb]);
            $fieldMail = strip_tags($_POST[CaptionField::$inputMail]);
            $fieldName = strip_tags($_POST[CaptionField::$inputName]);
            $fieldPass = strip_tags($_POST[CaptionField::$inputPass]);
            
            if (strcmp($fieldSurname, CaptionField::$fieldSurname) == 0)
                return RESULT_ERROR_INPUT;
            else if (strcmp($fieldFirstName, CaptionField::$fieldFirstName) == 0)
                return RESULT_ERROR_INPUT;
            else if (strcmp($fieldPatronymic, CaptionField::$fieldPatronymic) == 0)
                return RESULT_ERROR_INPUT;
            else if (strcmp($fieldWeb, CaptionField::$fieldWeb) == 0)
                return RESULT_ERROR_INPUT;
            else if (strcmp($fieldMail, CaptionField::$fieldMail) == 0)
                return RESULT_ERROR_INPUT;
            else if (strcmp($fieldPass, CaptionField::$fieldPass) == 0)
                return RESULT_ERROR_INPUT;
            else if (strcmp($fieldPass, CaptionField::$fieldPass) == 0)
                return RESULT_ERROR_INPUT;
                
            $data = array(
                CaptionField::$inputSurname => $fieldSurname, 
                CaptionField::$inputFirstName => $fieldFirstName,
                CaptionField::$inputPatronymic => $fieldPatronymic,
                CaptionField::$inputWeb => $fieldWeb,
                CaptionField::$inputMail => $fieldMail,
                CaptionField::$inputName => $fieldName,
                CaptionField::$inputPass => $fieldPass
            );
            
            if ($typeFunction == 1)
            {
                $userTable->Select(sprintf("`%s` = '%s'", UserTableStruct::$columnName, $fieldName));
                if ($db->num_rows() >= 1)
                    return RESULT_ERROR_EXISTS;
                else
                    $userTable->Insert($data);
            }
            else
            {
                $userTable->Update($data);
                if ($db->dberror() == 0)
                {
                    $_SESSION["fullInfo"][UserTableStruct::$columnFirstName] = $fieldFirstName;
                    $_SESSION["fullInfo"][UserTableStruct::$columnSurname] = $fieldSurname;
                    $_SESSION["fullInfo"][UserTableStruct::$columnPatronymic] = $fieldPatronymic;
                    $_SESSION["fullInfo"][UserTableStruct::$columnWeb] = $fieldWeb;
                    $_SESSION["fullInfo"][UserTableStruct::$columnMail] = $fieldMail;
                }
            }
            
            return $db->dberror() > 0 ? RESULT_ERROR_DB : RESULT_SUCCESS;
        }
        
        function Add()
        {
            return $this->AddOrEdit(1);
        }
        
        function Edit()
        {
            return $this->AddOrEdit(2);
        }
        
        function Delete()
        {
            global $userTable;
            
            $whereText = "";
            
            if ($_SESSION[ITEM_ID] > 0)
                $whereText = sprintf(" WHERE `%s` = %d", HouseTableStruct::$columnID, $_SESSION[ITEM_ID]);
                
            return $userTable->Delete($whereText);
        }
    }
?>