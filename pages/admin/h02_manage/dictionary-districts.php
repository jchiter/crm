<?php
global $db, $districtsTable, $citiesTable, $stringHandler;

$districtsArray = [];

$districtsTable->Select();
while ($item = $db->fetch_array()) {
    //print_r($item);
    $districtsArray[$item[DistrictTableStruct::$columnID]] =
        [
            DistrictTableStruct::$columnName => $item[DistrictTableStruct::$columnName],
            DistrictTableStruct::$columnID_City => $item[DistrictTableStruct::$columnID_City],
            CitiesTableStruct::$columnName => $item[CitiesTableStruct::$columnName]
        ];
}

$citiesArray = [];

$citiesTable->Select();
while ($item = $db->fetch_array()) {
    $citiesArray[$item[CitiesTableStruct::$columnID]] = $item[CitiesTableStruct::$columnName];
}
?>

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <table class="dataView table" data-pagination="true" data-search="true" data-toggle="table">
                <col width="5%">
                <col width="80%">
                <col width="13%">
                <col width="2%">
                <thead>
                    <tr>
                        <th data-sortable="true" data-field="id">#</th>
                        <th><span class="top">Район</span></th>
                        <th><span class="top">Город</span></th>
                        <th><span class="top"></span></th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $itemIndex = 1;
                $typeList = "district";
                foreach ($districtsArray as $itemNameDistrict => $itemValDistrict) {
                    echo "<tr>";
                    echo "<td>" . $itemIndex . "</td>";
                    echo "<td><span list-id='$itemNameDistrict' class='text'>" . $itemValDistrict[DistrictTableStruct::$columnName] . "</span><input class='form-control' style='display: none; width: 100%;' type='text'/></td>";
                    //echo "<td><span list-id='" . $itemVal[DistrictTableStruct::$columnID_City] . "' class='text'>" . $itemVal[CitiesTableStruct::$columnName] . "</span><input class='form-control' style='display: none; width: 100%;' type='text'/></td>";

                    echo "<td><select class=\"selectpicker show-tick\" style='width: 100%' onchange=\"$.coremanage.listEdit($(this), '$typeList')\">";
                        foreach ($citiesArray as $itemName => $itemVal) {
                            $isSelected = $itemName == $itemValDistrict[DistrictTableStruct::$columnID_City] ? "selected=\"\"" : "";
                            echo "<option " . $isSelected . " value='" . $itemName ."'>" . $itemVal . "</option>";
                        }
                    echo "</select></td>";

                    echo "<td>";
                        echo "<i class=\"fas fa-pen\" onclick=\"$.coremanage.listEdit($(this), '$typeList')\"></i>";
                        echo "<i style='display: none' class=\"fas fa-check\" onclick=\"$.coremanage.listEdit($(this), '$typeList')\"></i>";
                    echo "</td>";
                    echo "</tr>";

                    $itemIndex++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
