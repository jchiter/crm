<?    
    class Block
    {   
        function Content()
        {
            global $page, $httpHandler;
            
            echo "<div id=\"viewPage\">";

            $page->LoadPageToContent($httpHandler->GetPageName());
            
            echo "</div>";
        }
    }
?>