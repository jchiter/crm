<?
    class HttpHandler
    {   
        // Вытаскиваем или из $_GET или из $_POST параметр текущей страницы
        function GetNumberPage()
        {
            $numPage = 1;
            
            // Проверяем массив $_GET
            if (!empty($_GET) && isset($_GET[NAME_PARAM_NUMPAGE]))
                $numPage = $_GET[NAME_PARAM_NUMPAGE];
            
            // Проверяем массив $_POST
            else if (!empty($_POST) && isset($_POST[NAME_PARAM_NUMPAGE]))
                $numPage = $_POST[NAME_PARAM_NUMPAGE];
                
            // Проверяем массив $_COOKIE
            else if (!empty($_COOKIE) && isset($_COOKIE[NAME_PARAM_NUMPAGE]))
                $numPage = $_COOKIE[NAME_PARAM_NUMPAGE];
    
            return $numPage;
        }
        
        // Вытаскиваем или из $_GET или из $_POST параметр текущей записи идентификатора
        function GetItemId()
        {
            $itemId = 0;
            
            // Проверяем массив $_GET
            if (!empty($_GET) && isset($_GET[NAME_PARAM_ITEMID]))
                $itemId = $_GET[NAME_PARAM_ITEMID];
            
            // Проверяем массив $_POST
            else if (!empty($_POST) && isset($_POST[NAME_PARAM_ITEMID]))
                $itemId = $_POST[NAME_PARAM_ITEMID];
                
            // Проверяем массив $_COOKIE
            else if (!empty($_COOKIE) && isset($_COOKIE[NAME_PARAM_ITEMID]))
                $itemId = $_COOKIE[NAME_PARAM_ITEMID];
    
            return $itemId;
        }
        
        // Вытаскиваем или из $_GET или из $_POST параметр текущей записи идентификатора
        function GetPageName()
        {            
            $pageName = DEFAULT_PAGE;
            
            // Проверяем массив $_GET
            if (!empty($_GET) && isset($_GET[NAME_PARAM_PAGENAME]))
                $pageName = $_GET[NAME_PARAM_PAGENAME];
            
            // Проверяем массив $_POST
            else if (!empty($_POST) && isset($_POST[NAME_PARAM_PAGENAME]))
                $pageName = $_POST[NAME_PARAM_PAGENAME];
                
            // Проверяем массив $_COOKIE
            else if (!empty($_COOKIE) && isset($_COOKIE[NAME_PARAM_PAGENAME]))
                $pageName = $_COOKIE[NAME_PARAM_PAGENAME];
    
            return $pageName;
        }
                
        // Узнает уровень доступа для текущего пользователя
        function GetAccessLevel()
        {
            if (isset($_SESSION["fullInfo"][UserTableStruct::$columnStatus]))
                $accessLevel = intval($_SESSION["fullInfo"][UserTableStruct::$columnStatus]);
            else
                $accessLevel = 0;
                
            return $accessLevel;
        }
        
        function GetClass($pageName)
        {
            if (strcmp($this->GetPageName(), $pageName) == 0)
                return "active";
            else
                return "";
        }
		
		function getRealIP()
		{
		   if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '')
		   {
			  $client_ip =
				 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
					$_SERVER['REMOTE_ADDR']
					:
					( ( !empty($_ENV['REMOTE_ADDR']) ) ?
					   $_ENV['REMOTE_ADDR']
					   :
					   "unknown" );
			  $entries = explode('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

			  reset($entries);
			  while (list(, $entry) = each($entries))
			  {
				 $entry = trim($entry);
				 if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) )
				 {
					$private_ip = array(
						  '/^0\./',
						  '/^127\.0\.0\.1/',
						  '/^192\.168\..*/',
						  '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
						  '/^10\..*/');

					$found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

					if ($client_ip != $found_ip)
					{
					   $client_ip = $found_ip;
					   break;
					}
				 }
			  }
		   }
		   else
		   {
			  $client_ip =
				 ( !empty($_SERVER['REMOTE_ADDR']) ) ?
					$_SERVER['REMOTE_ADDR']
					:
					( ( !empty($_ENV['REMOTE_ADDR']) ) ?
					   $_ENV['REMOTE_ADDR']
					   :
					   "unknown" );
		   }

		   return $client_ip;

		}
    }
?>