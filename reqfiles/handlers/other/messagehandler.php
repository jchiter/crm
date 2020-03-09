<?
    class MessageHandler
    {
        function JsonMessageError($textMessage)
        {
            return "{\"status\":\"". MESSAGE_LEVEL_ERROR ."\", \"message\":\"". $textMessage ."\"}";
        }
        
        function JsonMessageErrorDb($textMessage)
        {
            return "{\"status\":\"". MESSAGE_LEVEL_ERRORDB ."\", \"message\":\"". $textMessage ."\"}";
        }
        
        function JsonMessageWarning($textMessage)
        {
            return "{\"status\":\"". MESSAGE_LEVEL_WARNING ."\", \"message\":\"". $textMessage ."\"}";
        }
        
        function JsonMessageSuccess($textMessage)
        {
            return "{\"status\":\"". MESSAGE_LEVEL_SUCCESS ."\", \"message\":". json_encode($textMessage) ."}";
        }
        
        function JsonMessageInformation($textMessage)
        {
            return "{\"status\":\"". MESSAGE_LEVEL_INFORMATION ."\", \"message\":\"". $textMessage ."\"}";
        }
        
        function CreateJsonMessage($fromArray)
        {
            $index = 0;
            echo "{";
            foreach ($fromArray as $key => $value)
            {
                if (is_array($value))
                    echo json_encode($value);
                else
                    echo json_encode($key) . ":" . json_encode($value);
                
                $index++;
                
                if ($index < count($fromArray))
                    echo ",";
            }
            echo "}";
        }
        
        function MessageErrorDb($message, $dbServer, $dbName, $dbUser, $dbLastQuery, $mysqlError, $isReturn = false)
        { 
            $message = 
            "<div class='messageBox' id='errordb'>".
            
            "<div id='title'>Ошибка базы данных</div>".
            "<div id='text'>".
            "<p>Сервер: <strong>". $dbServer ."</strong>".
            "<p>База данных: <strong>". $dbName ."</strong>".
            "<p>Пользователь: <strong>". $dbUser ."</strong>".
            "<p>Ошибка: <strong>". $mysqlError ."</strong>".
            "<div id='query' class='expanded'>SQL-запрос <div>". $dbLastQuery ."</div></div>".
            "</div>".
            "</div>";
            
            if (!$isReturn)
                echo $message;
            else
                return $message;
        }
        
        function MessageError($message)
        {
            echo 
            "<div class='messageBox' id='error'>".
            
            "<div id='title'>Ошибка</div>".
            "<div id='text'>". $message ."</div>".
            "</div>";
        }
        
        function MessageWarning($message)
        {
            echo 
            "<div class='messageBox' id='warning'>".
            
            "<div id='title'>Внимание</div>".
            "<div id='text'>". $message ."</div>".
            "</div>";
        }
        
        function MessageInfo($message)
        {
            echo 
            "<div class='messageBox' id='info'>".
            
            "<div id='title'>Информация</div>".
            "<div id='text'>". $message ."</div>".
            "</div>";
        }
    }
?>