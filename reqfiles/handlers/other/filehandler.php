<?php    
    class FileHandler
    {
        function cleanDir($dir) 
        {
            $files = scandir($dir);
            array_shift($files); // remove ‘.’ from array
            array_shift($files); // remove ‘..’ from array
            
            foreach ($files as $file) 
            {
                $file = $dir . '/' . $file;
                if (is_dir($file)) 
                {
                    cleanDir($file);
                    if (is_dir($file))
                        rmdir($file);
                } 
                else 
                {
                    unlink($file);
                }
            }
            
            rmdir($dir);
        }
        
        // Имя файла без расширения
        function GetFileNameNotExt($filePath)
        {
            for ($i = strlen($filePath); $i > 0; $i--)
            {
                if ($filePath[$i] == '.')
                    break;
            }
            
            return substr($filePath, 0, $i);
        }
        
        // Имя файла без пути
        function GetFileNameNotPath($filePath)
        {
            for ($i = strlen($filePath); $i > 0; $i--)
            {
                if ($filePath[$i] == '/')
                    break;
            }
            
            return substr($filePath, $i+1);
        }
        
        // Вырезает из строки (пути к файлу) расширение
        function GetFileExt($nameFile)
        {
            $fileExt = "";
            
            for ($i = strlen($nameFile); $i > 0; $i--)
            {
                if ($nameFile[$i] == '.')
                {
                    $fileExt = substr($nameFile, $i + 1);
                    break;
                }
            }
            
            return $fileExt;
        }
        
        function GetDateFolder($typeCatalog, $timestamp = 0)
        {
            // Каталогизируем ихображения по году, месяцу, дню
            
            chdir("..");
            
            $catalog_name = $typeCatalog;
            
            if ($timestamp <= 0)
                return $catalog_name;
            
            $catalog_year = date("Y", $timestamp);
            $catalog_month = date("F", $timestamp);
            $catalog_day = date("d", $timestamp);
            
            $catalog_name = $catalog_year . "/" . 
                $catalog_month . "/" . 
                $catalog_day . "/" . 
                date("H_i_s", $timestamp);
            
            // Пытаемся создать директорию для изображений
            if (!file_exists($catalog_name))
                if (!mkdir($catalog_name, 0777, true))
                    die("Не удалось создать каталог для изображения."); // error create dir
            
            return $catalog_name;
        }
    }
?>