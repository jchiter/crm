<?php
global $db, $orderTable, $userTable, $orderStatusTable, $orderHistoryTable, $stringHandler;
/*$dateFrom = strlen($_POST['orderfrom_datepicker']) > 0 ? $_POST['orderfrom_datepicker'] : date("d.m.Y", time());
$dateTo = strlen($_POST['orderto_datepicker']) > 0 ? $_POST['orderto_datepicker'] : date("d.m.Y", time() + 604800);

$dateArrayFrom = explode('.', $dateFrom);
$dateCreateFrom = $dateArrayFrom[2] . "-" . $dateArrayFrom[1] . "-" . $dateArrayFrom[0];

$dateArrayTo = explode('.', $dateTo);
$dateCreateTo = $dateArrayTo[2] . "-" . $dateArrayTo[1] . "-" . $dateArrayTo[0];

$whereText = "`" . OrderTableStruct::$columnDate . "` BETWEEN '" . $dateCreateFrom . "' AND '" . $dateCreateTo . "' ORDER BY `" . OrderTableStruct::$columnDate . "`, `" . OrderTableStruct::$columnTime . "` ASC";
*/
$usersArray = array();
$orderStatusArray = array();
$orderArray = array();
$orderHistoryArray = array();

$userTable->Select();
while ($item = $db->fetch_array())
    $usersArray[$item[UserTableStruct::$columnID]] = array($item[UserTableStruct::$columnName], $item[UserTableStruct::$columnViewName]);
	
$orderHistoryTable->Select();
while ($item = $db->fetch_array())
    $orderHistoryArray[$item[OrderHistoryTableStruct::$columnID]] = $item;

$orderStatusTable->Select();
while ($item = $db->fetch_array())
    $orderStatusArray[$item[OrderStatusTableStruct::$columnID]] = array($item[OrderStatusTableStruct::$columnValue], $item[OrderStatusTableStruct::$columnColor], $item[OrderStatusTableStruct::$columnOperaion]);

?>

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <table class="orderView table">
                <col width="200px">
                <col width="200px">
				<col width="200px">
                <col width="250px">
				<col width="40px">
                <tr>
					<th>Дата</th>
					<th>IP</th>
                    <th>Изменил</th>
					<th>Статус заказа</th>
                    <th></th>
                </tr>
                <?php

                $itemIndex = 1;
                foreach ($orderHistoryArray as $item) 
				{
					$statusColor = intval($item[OrderHistoryTableStruct::$columnID_Status]) > 0 ? $orderStatusArray[$item[OrderHistoryTableStruct::$columnID_Status]][1] : "#efefef";
					$statusText = intval($item[OrderHistoryTableStruct::$columnID_Status]) > 0 ? $orderStatusArray[$item[OrderHistoryTableStruct::$columnID_Status]][0] : "редактирование";
					$strUser = strlen($usersArray[$item[OrderHistoryTableStruct::$columnID_User]][1]) > 0 ? $usersArray[$item[OrderHistoryTableStruct::$columnID_User]][1] : $usersArray[$item[OrderHistoryTableStruct::$columnID_User]][0];
				?>
                    <tr style="border-top: 2px solid #248ee6;">
						<td><?php echo $stringHandler->PrintDate($item[OrderHistoryTableStruct::$columnDate]); ?></td>
						<td><?php echo $item[OrderHistoryTableStruct::$columnIP]; ?></td>
						<td><?php echo $strUser; ?></td>
                        <td style="background-color: <?php echo $statusColor; ?>"><?php echo $statusText; ?></td>
						<td>
                            <button style="margin-bottom: 5px; position: relative" class="btn btn-primary"><i style="font-size: 20px;" class="fa fa-arrow-right"></i><a style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" href="?p=view_order&valid=<?php echo $item[OrderHistoryTableStruct::$columnID_Order]; ?>"></a></button>
                        </td>
                    </tr>
                <?php
				}
				?>
            </table>
        </div>
    </div>
</div>
