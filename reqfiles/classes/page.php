<?

class Page
{
    // Общие модули для всех уровней доступа
    var $pages_shared = array(
        "main",
        "auth"
    );

    var $pages_login = array(
        "profile",
        "pass"
    );

    var $pages_admin_shared = array(
        "orders",
        "history",
        "view_order",
        "statistics-pay",
        "statistics-sale"
    );

    var $pages_admin_view = array();

    var $pages_admin_edit = array(
        "new_order",
        "edit_order"
    );

    var $name_pages = array(
        "main" => "Главная",
        "profile" => "Профиль",
        "orders" => "Список заказов",
        "view_order" => "Просмотр заказа",
        "new_order" => "Новый заказ",
        "edit_order" => "Изменение заказа",
        "history" => "История"
    );

    public function RenameTitleText($pageName)
    {
        $Title = PREFIX_TITLE . $this->name_pages[$pageName];

        return $Title;
    }

    public function GetDefaultPage()
    {
        // Страница, которая будет загружаться по умолчанию, в случае отсутствия запрашиваемого модуля
        $array_keys = array_keys($this->name_pages);
        return $array_keys[0];
    }

    public function GetCaptionPage($namePage)
    {
        return $this->name_pages[$namePage];
    }

    public function FillPageArray()
    {
        global $httpHandler;

        $pages = array();

        $access_level = $httpHandler->GetAccessLevel();
        switch ($access_level) {
            case LEVEL_SHARED:
                $pages = $this->pages_shared;
                break;

            case LEVEL_VIEW:
                $pages = $this->pages_admin_view;
                break;

            case LEVEL_EDIT:
                $pages = $this->pages_admin_edit;
                break;
        }

        if ($access_level >= 1) {
            foreach ($this->pages_shared as $value) array_push($pages, $value);
            foreach ($this->pages_login as $value) array_push($pages, $value);
            foreach ($this->pages_admin_shared as $value) array_push($pages, $value);
        }

        if (is_array($pages))
            return $pages;
    }

    public function GetFolder($name_module)
    {
        global $httpHandler;

        if (in_array($name_module, $this->pages_shared) || in_array($name_module, $this->pages_login))
            return "shared";
        else if (in_array($name_module, $this->pages_admin_shared))
            return "admin/shared";
        else if ($httpHandler->GetAccessLevel() >= 1)
            return "admin" . "/h0" . $httpHandler->GetAccessLevel() . "_manage";
    }

    public function GetCurrentPageName()
    {
        global $httpHandler;

        //$pageName = in_array($httpHandler->GetPageName(), $this->name_pages) ? $httpHandler->GetPageName() : "main";
        return $this->name_pages[$httpHandler->GetPageName()];
    }

    public function LoadPageToContent($pageName, $isRequire = true)
    {
        $mainDir = "pages";

        // Получаем массив, соответствующий нашему уровню доступа на сайте
        $access_pages = $this->FillPageArray();

        if (in_array($pageName, $access_pages)) {
            $moduleDir = $this->GetFolder($pageName);
            $modulePath = $mainDir . "/" . $moduleDir . "/" . $pageName . ".php";

            if (!file_exists($modulePath)) {
                $moduleDir = $this->GetFolder($this->GetDefaultPage());
                $modulePath = $mainDir . "/" . $moduleDir . "/" . $this->GetDefaultPage() . ".php";
            }

            if ($isRequire) {
                if (strstr($pageName, "admin_") != "") {
                    $adminPage = $mainDir . "/" . $moduleDir . "/admin.php";
                    require($adminPage);
                    require($modulePath);
                } else {
                    require($modulePath);
                }
            } else
                return file_get_contents($modulePath);
        } else {
            echo "<div class=\"wrap-contact2\" style='padding: 0px;'>";
            echo "<span class=\"contact2-form-title\" style='padding: 50px 5px;'>Произошла ошибка при загрузке страницы (" . $pageName . ").
            <br>Уточните у администратора уровень вашего доступа к системе.
            <br>Перейти на главную <a style='font: inherit;' href='?p=main'>страницу</a>.</span>";
            echo "</div>";
            return false;
        }
    }
}

?>