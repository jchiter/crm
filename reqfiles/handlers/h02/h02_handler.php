<?php

class h02Handler extends LoginHandler
{
    function CreateOrder()
    {
        global $httpHandler, $messageHandler, $orderTable, $db;

        if ($httpHandler->GetAccessLevel() != LEVEL_EDIT)
            return $messageHandler->JsonMessageError("У вас нет права доступа для выполнения операции");

        switch ($orderTable->Insert()) {
            case RESULT_ERROR_DB:
                $resultText = $db->GetLastError();
                break;

            case RESULT_ERROR_RECEIVE:
                $resultText = $messageHandler->JsonMessageError("Заказ не добавлен. Ошибка получения данных.");
                break;

            case RESULT_SUCCESS:
                $resultText = $messageHandler->JsonMessageSuccess("Заказ добавлен");
                break;
        }

        echo $resultText;
        return true;
    }

    function EditOrder()
    {
        global $httpHandler, $messageHandler, $userHandler, $orderTable, $db, $page, $orderHistoryTable;

        $isError = false;

        $nameId = intval($_POST[CaptionField::$inputName]);
        $streetId = intval($_POST[CaptionField::$inputStreet]);
        $eventId = intval($_POST[CaptionField::$inputEvent]);
        $sexId = intval($_POST[CaptionField::$inputSex]);
        $salesId = intval($_POST[CaptionField::$inputSalesType]);
        $dateArray = explode('.', $_POST[CaptionField::$inputDate]);
        $dateCreate = $dateArray[2] . "-" . $dateArray[1] . "-" . $dateArray[0];

        $updateArray = array(
            OrderTableStruct::$columnPhone => $_POST[CaptionField::$inputPhone],
            OrderTableStruct::$columnPhoneAdd => $_POST[CaptionField::$inputAddPhone],
            OrderTableStruct::$columnID_Name => $nameId,
            OrderTableStruct::$columnID_Street => $streetId,
            OrderTableStruct::$columnHouse => $_POST[CaptionField::$inputHouse],
            OrderTableStruct::$columnEntrance => $_POST[CaptionField::$inputEntrance],
            OrderTableStruct::$columnFloor => $_POST[CaptionField::$inputFloor],
            OrderTableStruct::$columnApart => $_POST[CaptionField::$inputApart],
            OrderTableStruct::$columnAge => $_POST[CaptionField::$inputAge],
            OrderTableStruct::$columnID_Sex => $sexId,
            OrderTableStruct::$columnID_Event => $eventId,
            OrderTableStruct::$columnAmount => $_POST[CaptionField::$inputAmount],
            OrderTableStruct::$columnDiscount => $_POST[CaptionField::$inputDiscount],
            OrderTableStruct::$columnPrepay => $_POST[CaptionField::$inputPrepay],
            OrderTableStruct::$columnID_Prepay => $_POST[CaptionField::$inputPrepayType],
            OrderTableStruct::$columnDate => $dateCreate,
            OrderTableStruct::$columnTime => $_POST[CaptionField::$inputTime],
            OrderTableStruct::$columnDesc => $_POST[CaptionField::$inputDesc],
            OrderTableStruct::$columnID_Sales => $salesId
        );

        $resultText = $messageHandler->JsonMessageSuccess("Значение успешно изменено");
        switch ($orderTable->Update($updateArray, "`" . OrderTableStruct::$columnID . "` = '" . intval($_SESSION[ITEM_ID]) . "'")) {
            case RESULT_ERROR_DB:
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка базы данных.");
                $isError = true;
                break;

            case RESULT_ERROR_RECEIVE:
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
                $isError = true;
                break;
        }

        if (!$isError) {
            $updateHistoryArray = array(
                OrderHistoryTableStruct::$columnID_User => $userHandler->GetUserId(),
                OrderHistoryTableStruct::$columnID_Order => intval($_SESSION[ITEM_ID]),
                OrderHistoryTableStruct::$columnDate => time(),
				OrderHistoryTableStruct::$columnID_Status => 0,
				OrderHistoryTableStruct::$columnIP => $httpHandler->getRealIP());

            $format = 'INSERT INTO '. $orderHistoryTable->currentTable;
            $columnsFormat = ' (';
            $valuesFormat = 'VALUES(';
            foreach ($updateHistoryArray as $column => $value)
            {
                $columnsFormat .= '`'. $column .'`, ';
                $valuesFormat .= '\''. $value .'\', ';
            }

            $columnsFormat[strlen($columnsFormat) - 2] = ') ';
            $valuesFormat[strlen($valuesFormat) - 2] = ') ';
            $queryText = $format . $columnsFormat . $valuesFormat;

            if (!$db->query($queryText, $dbError))
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка базы данных.");
        }

        echo $resultText;
        return true;
    }

    function ShowOrders()
    {
        global $httpHandler, $messageHandler, $orderTable, $db;

        return true;
    }

    function SaveOrder()
    {
        global $messageHandler, $httpHandler, $orderTable, $orderHistoryTable, $userHandler, $db;
		
		$isError = false;

        if ($httpHandler->GetAccessLevel() != LEVEL_EDIT)
            return $messageHandler->JsonMessageError("У вас нет права доступа для выполнения операции");

        $statusId = intval($_POST["data"]);
        if ($statusId <= 0) {
            echo $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
            return false;
        }

        $valId = intval($_POST["valid"]);
        if ($valId <= 0) {
            echo $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
            return false;
        }

        $typeSelect = $_POST["type"];

        if (strcmp($typeSelect, CaptionField::$inputPrepayType) == 0)
            $updateColumns = array(OrderTableStruct::$columnID_Prepay => $statusId);
        else if (strcmp($typeSelect, CaptionField::$inputBalanceType) == 0)
            $updateColumns = array(OrderTableStruct::$columnID_Balance => $statusId);
        else if (strcmp($typeSelect, CaptionField::$inputStatusType) == 0)
            $updateColumns = array(OrderTableStruct::$columnID_Status => $statusId, OrderTableStruct::$columnID_Balance => intval($_POST["balanceId"]));

        $resultText = $messageHandler->JsonMessageSuccess("Значение успешно изменено");
        switch ($orderTable->Update($updateColumns, "`" . OrderTableStruct::$columnID . "` = '" . $valId . "'")) {
            case RESULT_ERROR_DB:
				$isError = true;
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка базы данных.");
                break;

            case RESULT_ERROR_RECEIVE:
				$isError = true;
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
                break;
        }
		
		if (!$isError) {
            $updateHistoryArray = array(
                OrderHistoryTableStruct::$columnID_User => $userHandler->GetUserId(),
                OrderHistoryTableStruct::$columnID_Order => intval($valId),
                OrderHistoryTableStruct::$columnDate => time(),
				OrderHistoryTableStruct::$columnID_Status => $statusId,
				OrderHistoryTableStruct::$columnIP => $httpHandler->getRealIP());

            $format = 'INSERT INTO '. $orderHistoryTable->currentTable;
            $columnsFormat = ' (';
            $valuesFormat = 'VALUES(';
            foreach ($updateHistoryArray as $column => $value)
            {
                $columnsFormat .= '`'. $column .'`, ';
                $valuesFormat .= '\''. $value .'\', ';
            }

            $columnsFormat[strlen($columnsFormat) - 2] = ') ';
            $valuesFormat[strlen($valuesFormat) - 2] = ') ';
            $queryText = $format . $columnsFormat . $valuesFormat;

            if (!$db->query($queryText, $dbError))
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка базы данных.");
        }

        echo $resultText;
        return true;
    }

    function ListAdd()
    {
        global $messageHandler, $httpHandler, $nameTable, $prepayTable, $eventTable, $salesTable, $keywordTable, $streetTable;

        if ($httpHandler->GetAccessLevel() != LEVEL_EDIT)
            return $messageHandler->JsonMessageError("У вас нет права доступа для выполнения операции");

        $nameVal = strip_tags($_POST["nameVal"]);
        if (strlen($nameVal) <= 0)
            return $messageHandler->JsonMessageError("Значение не добавлено. Ошибка получения данных.");

        $currentTable = 0;
        if (strcmp($nameVal, CaptionField::$inputPrepayType) == 0)
            $currentTable = $prepayTable;
        else if (strcmp($nameVal, CaptionField::$inputName) == 0)
            $currentTable = $nameTable;
        else if (strcmp($nameVal, CaptionField::$inputEvent) == 0)
            $currentTable = $eventTable;
        else if (strcmp($nameVal, CaptionField::$inputSalesType) == 0)
            $currentTable = $salesTable;
        else if (strcmp($nameVal, CaptionField::$inputKeyword) == 0)
            $currentTable = $keywordTable;
        else if (strcmp($nameVal, CaptionField::$inputStreet) == 0)
            $currentTable = $streetTable;

        switch ($currentTable->Insert()) {
            case RESULT_ERROR_DB:
                $resultText = $messageHandler->JsonMessageError("Значение не добавлено. Ошибка базы данных.");
                break;

            case RESULT_ERROR_RECEIVE:
                $resultText = $messageHandler->JsonMessageError("Значение не добавлено. Ошибка получения данных.");
                break;

            case RESULT_SUCCESS:
                $resultText = $messageHandler->JsonMessageSuccess($_SESSION["lastId"]);
                break;
        }

        echo $resultText;
        return true;
    }

    function ListEdit()
    {
        global $messageHandler, $httpHandler, $nameTable, $prepayTable, $eventTable, $salesTable, $streetTable;

        if ($httpHandler->GetAccessLevel() != LEVEL_EDIT) {
            echo $messageHandler->JsonMessageError("У вас нет права доступа для выполнения операции");
            return false;
        }

        $nameVal = strip_tags($_POST["nameVal"]);
        if (strlen($nameVal) <= 0) {
            echo $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
            return false;
        }

        $valId = intval($_POST["valid"]);
        if ($valId <= 0) {
            echo $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
            return false;
        }

        $currentTable = 0;
        $currentStruct = PrepayTableStruct;

        if (strcmp($nameVal, CaptionField::$inputPrepayType) == 0)
            $currentTable = $prepayTable;
        else if (strcmp($nameVal, CaptionField::$inputName) == 0) {
            $currentTable = $nameTable;
            $currentStruct = NameTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputEvent) == 0) {
            $currentTable = $eventTable;
            $currentStruct = EventTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputSalesType) == 0) {
            $currentTable = $salesTable;
            $currentStruct = SalesTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputStreet) == 0) {
            $currentTable = $streetTable;
            $currentStruct = StreetTableStruct;
        }

        $resultText = $messageHandler->JsonMessageSuccess("Значение успешно изменено");
        switch ($currentTable->Update("`" . $currentStruct::$columnID . "` = '" . $valId . "'")) {
            case RESULT_ERROR_DB:
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка базы данных.");
                break;

            case RESULT_ERROR_RECEIVE:
                $resultText = $messageHandler->JsonMessageError("Значение не изменено. Ошибка получения данных.");
                break;
        }

        echo $resultText;
        return true;
    }

    function ListRemove()
    {
        global $messageHandler, $httpHandler, $nameTable, $prepayTable, $eventTable, $salesTable, $keywordTable, $streetTable;

        if ($httpHandler->GetAccessLevel() != LEVEL_EDIT) {
            echo $messageHandler->JsonMessageError("У вас нет права доступа для выполнения операции");
            return false;
        }

        $nameVal = strip_tags($_POST["nameVal"]);
        if (strlen($nameVal) <= 0) {
            echo $messageHandler->JsonMessageError("Значение не удалено. Ошибка получения данных.");
            return false;
        }

        $currentTable = 0;
        $currentStruct = PrepayTableStruct;

        if (strcmp($nameVal, CaptionField::$inputPrepayType) == 0)
            $currentTable = $prepayTable;
        else if (strcmp($nameVal, CaptionField::$inputName) == 0) {
            $currentTable = $nameTable;
            $currentStruct = NameTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputEvent) == 0) {
            $currentTable = $eventTable;
            $currentStruct = EventTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputSalesType) == 0) {
            $currentTable = $salesTable;
            $currentStruct = SalesTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputKeyword) == 0) {
            $currentTable = $keywordTable;
            $currentStruct = KeywordTableStruct;
        } else if (strcmp($nameVal, CaptionField::$inputStreet) == 0) {
            $currentTable = $streetTable;
            $currentStruct = StreetTableStruct;
        }

        $resultText = $messageHandler->JsonMessageSuccess("Значение успешно удалено");
        switch ($currentTable->Delete("WHERE `" . $currentStruct::$columnID . "` = '" . intval($_POST["data"]) . "'")) {
            case RESULT_ERROR_DB:
                $resultText = $messageHandler->JsonMessageError("Значение не удалено. Ошибка базы данных.");
                break;

            case RESULT_ERROR_RECEIVE:
                $resultText = $messageHandler->JsonMessageError("Значение не удалено. Ошибка получения данных.");
                break;
        }

        echo $resultText;
        return true;
    }

    function GetList()
    {
        global $db, $messageHandler, $httpHandler, $nameTable, $prepayTable, $eventTable, $salesTable, $keywordTable, $streetTable;

        if ($httpHandler->GetAccessLevel() != LEVEL_EDIT) {
            echo $messageHandler->JsonMessageError("У вас нет права доступа для выполнения операции");
            return false;
        }

        $typeList = strip_tags($_POST["data"]);
        if (strlen($typeList) <= 0) {
            echo $messageHandler->JsonMessageError("Значение не удалено. Ошибка получения данных.");
            return false;
        }

        if (strcmp($typeList, CaptionField::$inputBalanceType) == 0) {
            $listMessage = "<select type='" . CaptionField::$inputPrepayType . "' class='selectpicker' data-width='100%'>";
            $prepayTable->Select();
            while ($item = $db->fetch_array())
                $listMessage .= "<option value='" . $item[PrepayTableStruct::$columnID] . "'>" . $item[PrepayTableStruct::$columnValue] . "</option>";

            $listMessage .= "</select>";

            echo $listMessage;
            return true;
        }
    }

    function ShowStatistic() {
        global $db, $orderTable, $stringHandler;
        $dateFrom = strlen($_POST['orderfrom_datepicker']) > 0 ? $_POST['orderfrom_datepicker'] : date("d.m.Y", time());
        $dateTo = strlen($_POST['orderto_datepicker']) > 0 ? $_POST['orderto_datepicker'] : date("d.m.Y", time() + 604800);

        $dateArrayFrom = explode('.', $dateFrom);
        $dateCreateFrom = $dateArrayFrom[2] . "-" . $dateArrayFrom[1] . "-" . $dateArrayFrom[0];
        $dateArrayTo = explode('.', $dateTo);
        $dateCreateTo = $dateArrayTo[2] . "-" . $dateArrayTo[1]. "-" . $dateArrayTo[0];

        $whereText = "`" . OrderTableStruct::$columnDate . "` BETWEEN '" . $dateCreateFrom . "' AND '" . $dateCreateTo . "' ORDER BY `" . OrderTableStruct::$columnDate . "`, `" . OrderTableStruct::$columnTime . "` ASC";
        $orderArray = [];
        $orderArray["date"] = [];
        $orderArray["amount"] = [];
        $resultAmount = 0;
        $resultCount = 0;
        $resultOrders = $orderTable->Select($whereText);
        while ($item = $db->fetch_array($resultOrders)) {
            if ($_POST["range-type"] == "m") {
                $keyDate = date("m.Y", strtotime($item[OrderTableStruct::$columnDate]));
            } else {
                $keyDate = date("d.m.Y", strtotime($item[OrderTableStruct::$columnDate]));
            }

            if (!in_array($keyDate, $orderArray["date"])) {
                $orderArray["date"][] = $keyDate;
            }

            $orderArray["amount"][count($orderArray["date"]) - 1] += $item[OrderTableStruct::$columnAmount];
            $resultAmount += $item[OrderTableStruct::$columnAmount];
            $resultCount++;
        }

        $orderArray["summary"] = sprintf("%s %d %s на сумму %s руб.", $stringHandler->numberof($resultCount, 'Выбран', array('', 'о', 'о')), $resultCount, $stringHandler->numberof($resultCount, 'заказ'), number_format($resultAmount, 0, '.', ' '));

        echo json_encode($orderArray);
    }
}

?>