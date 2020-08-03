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
?>

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <form class="contact2-form validate-form" method="post" id="uploadForm">
                <div class="container">Выберите даты для фильтрации статистики выручки<br><br>
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
                        <div class='col-md-2'>
                            <div class="form-group">
                                <select class="form-control" id="range-type" name="range-type">
                                    <option value="d">дни</option>
                                    <option value="m">месяца</option>
                                </select>
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
            <div class="chartTableBlock">
                <table class="chartTable table" data-pagination="true" data-search="true" data-toggle="table"></table>
            </div>
        </div>
    </div>
</div>
