<?php
//unset($_POST);
global $db, $orderTable, $sexTable, $eventTable, $streetTable, $prepayTable, $nameTable, $userTable, $orderStatusTable, $stringHandler;
$dateFrom = strlen($_POST['orderfrom_datepicker']) > 0 ? $_POST['orderfrom_datepicker'] : date("d.m.Y", time());
$dateTo = strlen($_POST['orderto_datepicker']) > 0 ? $_POST['orderto_datepicker'] : date("d.m.Y", time() + 604800);

$dateArrayFrom = explode('.', $dateFrom);
$dateCreateFrom = $dateArrayFrom[2] . "-" . $dateArrayFrom[1] . "-" . $dateArrayFrom[0];

$dateArrayTo = explode('.', $dateTo);
$dateCreateTo = $dateArrayTo[2] . "-" . $dateArrayTo[1] . "-" . $dateArrayTo[0];

$whereText = "`" . OrderTableStruct::$columnDate . "` BETWEEN '" . $dateCreateFrom . "' AND '" . $dateCreateTo . "' ORDER BY `" . OrderTableStruct::$columnDate . "`, `" . OrderTableStruct::$columnTime . "` ASC";

$orderArray = array();

$resultOrders = $orderTable->Select($whereText);
$allAmount = 0;
while ($item = $db->fetch_array($resultOrders))
{
    array_push($orderArray, $item);
    $allAmount += intval($item[OrderTableStruct::$columnAmount]);
}

$filterDayArr = array("неделя" => 86400 * 7, "две недели" => 86400 * 14, "месяц" => 86400 * 30, "два месяца" => 86400 * 60, "квартал" => 86400 * 90, "год" => 86400 * 365);
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
                                /*$i = 0;
                                $dateFromUnix = strtotime($_POST["orderfrom_datepicker"]);
                                $dateToUnix = strtotime($_POST["orderto_datepicker"]);
                                $dateRangeUnix = ($dateToUnix - $dateFromUnix) > 0 ? $dateToUnix - $dateFromUnix : 86400 * 7;

                                foreach ($filterDayArr as $filterDay => $val)
                                {
                                    $isActive = "onclick='$.coremanage.showOrders(". $i .")'";

                                    if ($dateRangeUnix == $val)
                                        $isActive = "class='active'";

                                    echo "<span ". $isActive .">". $filterDay ."</span>";

                                    $i++;
                                }*/
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
                            <button id="showStatistic" data-toggle="tooltip" title="Применить фильтр" type="button" class="btn btn-success btn-md" onclick="$.coremanage.showStatistic();"><span class="glyphicon glyphicon-ok"></span></button>
                            <button data-toggle="tooltip" title="Сбросить фильтр" type="button" class="btn btn-danger btn-md" onclick="$.coremanage.clearFilter(); $('#showStatistic').trigger('click');"><span class="glyphicon glyphicon-remove"></span></button>
                        </div>
                        <div class='col-md-6 summary' style="text-align: right">
                            <div class="form-group"></div>
                        </div>
                    </div>
                </div>
            </form>
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>
