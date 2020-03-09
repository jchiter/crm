<?php
global $db, $orderTable, $sexTable, $eventTable, $streetTable, $prepayTable, $nameTable, $userTable, $orderStatusTable, $stringHandler;

$whereText = "`" . OrderTableStruct::$columnID . "` = '" . intval($_GET['valid']) . "'";

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
    $usersArray[$item[UserTableStruct::$columnID]] = $item[UserTableStruct::$columnName];
	
$prepayTable->Select();
while ($item = $db->fetch_array())
    $prepayArray[$item[PrepayTableStruct::$columnID]] = $item[PrepayTableStruct::$columnValue];

$orderStatusTable->Select();
while ($item = $db->fetch_array())
    $orderStatusArray[$item[OrderStatusTableStruct::$columnID]] = array($item[OrderStatusTableStruct::$columnValue], $item[OrderStatusTableStruct::$columnColor], $item[OrderStatusTableStruct::$columnOperaion]);
	
$resultOrders = $orderTable->Select($whereText);
while ($item = $db->fetch_array($resultOrders))
    array_push($orderArray, $item);

if (count($orderArray) <= 0)
{
	echo "<button style=\"position: relative;\" class=\"btn btn-default\" type=\"button\"><i style=\"font-size: 20px; padding-right: 10px;\" class=\"fa fa-arrow-left\"></i><a style=\"position: absolute; top: 0; left: 0; width: 100%; height: 100%;\" href=\"?p=history\"></a>Назад</button><br><br>";
	echo "<div class=\"wrap-contact2\">";
    echo "<span class=\"contact2-form-title\">Ошибка получения информации о заказе</span>";
	echo "</div>";
	die();
}
?>

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
		<form class="contact2-form validate-form" method="post">
                <div class="container">
					<button style="position: relative;" class="btn btn-default" type="button"><i style="font-size: 20px; padding-right: 10px;" class="fa fa-arrow-left"></i><a style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" href="?p=history"></a>Назад</button>
				</div>
		</form>
            <table class="orderView table">
                <col width="40px">
                <col width="560px">
                <col width="400px">
                <col width="440px">
                <col width="200px">
                <col width="120px">
                <col width="120px">
                <col width="220px">
                <col width="100px">
                <tr>
                    <th>#</th>
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
                </tr>
                <?php

                foreach ($orderArray as $item) {
                    $sexIcon = intval($item[OrderTableStruct::$columnID_Sex]) == 1 ? "male" : "female";
                    $strAge = intval($item[OrderTableStruct::$columnAge]) > 0 ? $stringHandler->AgeToStr(intval($item[OrderTableStruct::$columnAge])) : "";
                    $strStreet = "Самовывоз";
                    $strAmount = intval($item[OrderTableStruct::$columnAmount]);
                    $strPrepay = intval($item[OrderTableStruct::$columnPrepay]);
					$strStatusType = $orderStatusArray[intval($item[OrderTableStruct::$columnID_Status])][0];
                    $strBalanceType = intval($item[OrderTableStruct::$columnID_Balance]) > 0 ? $prepayArray[intval($item[OrderTableStruct::$columnID_Balance])] : "не указано";

                    if (intval($item[OrderTableStruct::$columnID_Street]) > 0) {
                        $strStreet = $streetsArray[$item[OrderTableStruct::$columnID_Street]];
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
                    }
                    ?>
                    <tr id="id<?php echo $item[OrderTableStruct::$columnID]; ?>" style="background-color: <?php echo $orderStatusArray[$item[OrderTableStruct::$columnID_Status]][1]; ?>">
                        <td style="vertical-align: middle"><?php echo $item[OrderTableStruct::$columnID]; ?></td>
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
                            <span class="top"><?php echo $usersArray[$item[OrderTableStruct::$columnID_User]]; ?></span>
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
							<span class="top"><?php echo $strStatusType; ?></span>
                        </td>
                    </tr>
                    <tr style="background-color: <?php echo $orderStatusArray[$item[OrderTableStruct::$columnID_Status]][1]; ?>">
                        <td colspan="10" class="desc">
                            <span style="color: gray" class="top"><?php echo $item[OrderTableStruct::$columnDesc]; ?></span>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
    </div>
</div>
