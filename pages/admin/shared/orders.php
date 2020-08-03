<?php
global $db, $orderTable, $sexTable, $eventTable, $streetTable, $prepayTable, $nameTable, $userTable, $orderStatusTable, $stringHandler;
$dateFrom = strlen($_POST['orderfrom_datepicker']) > 0 ? $_POST['orderfrom_datepicker'] : date("d.m.Y", time());
$dateTo = strlen($_POST['orderto_datepicker']) > 0 ? $_POST['orderto_datepicker'] : date("d.m.Y", time() + 604800);

$dateArrayFrom = explode('.', $dateFrom);
$dateCreateFrom = $dateArrayFrom[2] . "-" . $dateArrayFrom[1] . "-" . $dateArrayFrom[0];

$dateArrayTo = explode('.', $dateTo);
$dateCreateTo = $dateArrayTo[2] . "-" . $dateArrayTo[1] . "-" . $dateArrayTo[0];

$whereText = "`" . OrderTableStruct::$columnDate . "` BETWEEN '" . $dateCreateFrom . "' AND '" . $dateCreateTo . "' ORDER BY `" . OrderTableStruct::$columnDate . "`, `" . OrderTableStruct::$columnTime . "` ASC";

$namesArray = array();
$streetsArray = array();
$eventsArray = array();
$usersArray = array();
$orderStatusArray = array();
$orderArray = array();
$prepayArray = array();

$nameTable->Select();
while ($item = $db->fetch_array())
    $namesArray[$item[NameTableStruct::$columnID]] = $item[NameTableStruct::$columnName];

$streetTable->Select();
while ($item = $db->fetch_array())
    $streetsArray[$item[StreetTableStruct::$columnID]] = $item[StreetTableStruct::$columnName];

$eventTable->Select();
while ($item = $db->fetch_array())
    $eventsArray[$item[EventTableStruct::$columnID]] = $item[EventTableStruct::$columnName];

$userTable->Select();
while ($item = $db->fetch_array())
    $usersArray[$item[UserTableStruct::$columnID]] = array($item[UserTableStruct::$columnName], $item[UserTableStruct::$columnViewName]);
	
$prepayTable->Select();
while ($item = $db->fetch_array())
    $prepayArray[$item[PrepayTableStruct::$columnID]] = $item[PrepayTableStruct::$columnValue];

$orderStatusTable->Select();
while ($item = $db->fetch_array())
    $orderStatusArray[$item[OrderStatusTableStruct::$columnID]] = array($item[OrderStatusTableStruct::$columnValue], $item[OrderStatusTableStruct::$columnColor], $item[OrderStatusTableStruct::$columnOperaion]);

$resultOrders = $orderTable->Select($whereText);
$allAmount = 0;
while ($item = $db->fetch_array($resultOrders))
{
    array_push($orderArray, $item);
    $allAmount += intval($item[OrderTableStruct::$columnAmount]);
}

$filterDayArr = array("сегодня" => 0, "завтра" => 86400, "неделя" => 86400 * 7, "две недели" => 86400 * 14, "месяц" => 86400 * 30, "два месяца" => 86400 * 60, "квартал" => 86400 * 90, "год" => 86400 * 365);
?>

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <form class="contact2-form validate-form" method="post" id="uploadForm">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="filterDayContent">
                                <?php
                                $i = 0;
                                $dateFromUnix = strtotime($_POST["orderfrom_datepicker"]);
                                $dateToUnix = strtotime($_POST["orderto_datepicker"]);
                                $dateRangeUnix = ($dateToUnix - $dateFromUnix) > 0 ? $dateToUnix - $dateFromUnix : 86400 * 7;

                                foreach ($filterDayArr as $filterDay => $val)
                                {
                                    $isActive = "onclick='$.coremanage.showOrdersByLink(". $i .")'";

                                    if ($dateRangeUnix == $val)
                                        $isActive = "class='active'";

                                    echo "<span ". $isActive .">". $filterDay ."</span>";

                                    $i++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row orders-header">
                        <div class='col-md-2'>
                            <div class="form-group">
                                <div class='input-group date' id='orderfrom_datepicker'>
                                    <input type='text' class="form-control" name="orderfrom_datepicker" value="<?php echo $dateFrom; ?>"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-2'>
                            <div class="form-group">
                                <div class='input-group date' id='orderto_datepicker'>
                                    <input type='text' class="form-control" name="orderto_datepicker" value="<?php echo $dateTo; ?>"/>
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button id="showOrder" data-toggle="tooltip" title="Применить фильтр" type="submit" class="btn btn-success btn-md"><span class="glyphicon glyphicon-ok"></span></button>
                            <button data-toggle="tooltip" title="Сбросить фильтр" type="button" class="btn btn-danger btn-md" onclick="$.coremanage.clearFilter(); $('#showOrder').trigger('click');"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>
                        <div class='col-md-6' style="text-align: right">
                            <div class="form-group">
                                <?php
                                if (count($orderArray) > 0)
                                    echo "<span class='text'>" . $stringHandler->numberof(count($orderArray), 'Выбран', array('', 'о', 'о')) . ' '
                                        . count($orderArray) . ' ' . $stringHandler->numberof(count($orderArray), 'заказ')
                                        . ' на сумму ' . number_format($allAmount, 0, '.', ' ') . ' руб.' . "</span>";
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <table class="dataView table" data-pagination="true" data-search="true" data-toggle="table">
                <col width="40px">
                <col width="560px">
                <col width="400px">
                <col width="440px">
                <col width="200px">
                <col width="120px">
                <col width="120px">
                <col width="220px">
                <col width="100px">
                <col width="40px">
				<thead>
                <tr>
                    <th data-sortable="true" data-field="id">#</th>
                    <th>
                        <span class="top">Дата создания</span>
                        <span class="bottom">Дата заказа</span>
                    </th>
                    <th>
                        <span class="top">Клиент</span>
                    </th>
                    <th>
                        <span class="top">Доставка</span>
                    </th>
                    <th>Принял</th>
                    <th>Сумма</th>
                    <th>Предоплата</th>
                    <th>Остаток</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
				</thead>
				<tbody>
                <?php

                $itemIndex = 1;
                foreach ($orderArray as $item) {
                    $sexIcon = intval($item[OrderTableStruct::$columnID_Sex]) == 1 ? "male" : "female";
                    $strAge = intval($item[OrderTableStruct::$columnAge]) > 0 ? $stringHandler->AgeToStr(intval($item[OrderTableStruct::$columnAge])) : "";
                    $strStreet = "Самовывоз";
                    $strAmount = intval($item[OrderTableStruct::$columnAmount]);
                    $strPrepay = intval($item[OrderTableStruct::$columnPrepay]);
                    $strBalanceType = intval($item[OrderTableStruct::$columnID_Balance]) > 0 ? $prepayArray[intval($item[OrderTableStruct::$columnID_Balance])] : "не указано";
                    $strUser = strlen($usersArray[$item[OrderTableStruct::$columnID_User]][1]) > 0 ? $usersArray[$item[OrderTableStruct::$columnID_User]][1] : $usersArray[$item[OrderTableStruct::$columnID_User]][0];

                    if (intval($item[OrderTableStruct::$columnID_Street]) > 0) {
						$strStreet = "<a style='cursor: pointer;' onclick=\"$.coremanage.showMap('" . $streetsArray[$item[OrderTableStruct::$columnID_Street]] . ", " . $item[OrderTableStruct::$columnHouse] . "')\"><i class='fa fa-map-marker fa-lg'></i>";
                        $strStreet .= $streetsArray[$item[OrderTableStruct::$columnID_Street]];
                        if (strlen($item[OrderTableStruct::$columnApart]) > 0)
                            $strStreet .= "<br>д. " . $item[OrderTableStruct::$columnHouse];
                        else
                            $strStreet .= ",&nbsp;" . $item[OrderTableStruct::$columnHouse];

                        if (strlen($item[OrderTableStruct::$columnApart]) > 0) {
                            $strStreet .= ", кв. " . $item[OrderTableStruct::$columnApart];

                            if (strlen($item[OrderTableStruct::$columnEntrance]) > 0)
                                $strStreet .= ", п. " . $item[OrderTableStruct::$columnEntrance];

                            if (strlen($item[OrderTableStruct::$columnFloor]) > 0)
                                $strStreet .= ", эт. " . $item[OrderTableStruct::$columnFloor];
                        }
						
						$strStreet .= "</a>";
						//$strStreet .= "<br><button class='btn btn-default'><i class='fa fa-map-marker fa-lg'></i>На карте</button>";
                    }
                    ?>
                    <tr id="id<?php echo $item[OrderTableStruct::$columnID]; ?>" style="background-color: <?php echo $orderStatusArray[$item[OrderTableStruct::$columnID_Status]][1]; ?>">
                        <td style="vertical-align: middle"><?php echo $itemIndex; ?></td>
                        <td>
                            <span class="top" style="vertical-align: middle; font-size: 18px;">
                                <?php
                                echo "<span style='font-size: 10px';>" . $stringHandler->PrintDate($item[OrderTableStruct::$columnCreate]) . "</span>";
                                $isStrongOpen = "";
                                $isStrongClosed = "";
                                if (strtotime($item[OrderTableStruct::$columnDate] . $item[OrderTableStruct::$columnTime]) > time())
                                {
                                    $isStrongOpen = "<strong>";
                                    $isStrongClosed = "</strong>";
                                }

                                echo $isStrongOpen . $stringHandler->PrintDate(strtotime($item[OrderTableStruct::$columnDate]), false) . $item[OrderTableStruct::$columnTime] . $isStrongClosed;
                                ?>
                            </span>
                        </td>
                        <td>
                            <span class="top"><i class="fa fa-<?php echo $sexIcon; ?>"></i>&nbsp;<?php echo $namesArray[$item[OrderTableStruct::$columnID_Name]] . "<sup>" . $strAge . "</sup>"; ?></span>
                            <span class="bottom">
                                <?php
                                $phoneOne = "1";
                                $phoneTwo = "2";
                                if (strlen($item[OrderTableStruct::$columnPhone]) > 3) {
                                    echo "<i class='fa fa-phone'><sub>1</sub></i>" . $item[OrderTableStruct::$columnPhone];
                                    if (strlen($item[OrderTableStruct::$columnPhoneAdd]) > 0)
                                        echo "<br><i class='fa fa-phone'><sub>2</sub></i>" . $item[OrderTableStruct::$columnPhoneAdd];
                                } else if (strlen($item[OrderTableStruct::$columnPhoneAdd]) > 0)
                                    echo "<i class='fa fa-phone'><sub>1</sub></i>" . $item[OrderTableStruct::$columnPhoneAdd];
                                ?>
                            </span>
                        </td>
                        <td>
                            <span class="top"><?php echo $strStreet; ?></span>
                        </td>
                        <td>
                            <span class="top"><?php echo $strUser; ?></span>
                        </td>
                        <td>
                            <span id="amount" class="top"><?php echo number_format($strAmount, 0, '.', ' '); ?></span>
                        </td>
                        <td>
                            <span id="prepay" class="top"><?php echo number_format($strPrepay, 0, '.', ' '); ?></span>
                        </td>
                        <td>
                            <span id="balance" class="top"><?php echo $strBalanceType; ?></span>
                        </td>
                        <td>
                            <select id="<?php echo $item[OrderTableStruct::$columnID]; ?>" type="<?php echo CaptionField::$inputStatusType; ?>" class="selectpicker show-tick" data-width="100px">
                                <?php
                                foreach ($orderStatusArray as $orderStatusId => $orderStatus) {
                                    $isSelected = intval($orderStatusId) == intval($item[OrderTableStruct::$columnID_Status]) ? "selected" : "";
                                    echo "<option " . $isSelected . " value=\"" . $orderStatusId . "\" color=\"" . $orderStatus[1] . "\" operation=\"" . $orderStatus[2] . "\">" . $orderStatus[0] . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <button style="margin-bottom: 5px; position: relative" class="btn btn-primary"><i style="font-size: 20px;" class="fa fa-pencil"></i><a style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" href="?p=edit_order&valid=<?php echo $item[OrderTableStruct::$columnID]; ?>"></a></button>
                            <button style="display: none" class="btn btn-danger"><i style="font-size: 21px;" class="fa fa-remove"></i></button>
                        </td>
                    </tr>
                    <tr class="desc_line" style="background-color: <?php echo $orderStatusArray[$item[OrderTableStruct::$columnID_Status]][1]; ?>">
                        <td colspan="11" class="desc">
                            <span style="color: gray" class="top"><?php echo $stringHandler->UrlParseFilter($item[OrderTableStruct::$columnDesc]); ?></span>
                        </td>
                    </tr>
                    <?php
                    $itemIndex++;
                }
                ?>
				</tbody>
            </table>
        </div>
    </div>
</div>
