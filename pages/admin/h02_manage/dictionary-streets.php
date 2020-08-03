<?php
global $db, $districtsTable, $citiesTable, $streetTable, $stringHandler;

$streetsArray = [];

$streetTable->Select();
while ($item = $db->fetch_array()) {
    //print_r($item);
    $streetsArray[$item[StreetTableStruct::$columnID]] =
        [
            StreetTableStruct::$columnName => $item[StreetTableStruct::$columnName],
            StreetTableStruct::$columnID_City => $item[CitiesTableStruct::$columnID],
            StreetTableStruct::$columnID_District => $item[DistrictTableStruct::$columnID],
        ];
}

$citiesArray = [];

$citiesTable->Select();
while ($item = $db->fetch_array()) {
    $citiesArray[$item[CitiesTableStruct::$columnID]] = $item[CitiesTableStruct::$columnName];
}

$districtsArray = [];

$districtsTable->Select();
while ($item = $db->fetch_array()) {
    $districtsArray[$item[DistrictTableStruct::$columnID]] = $item[DistrictTableStruct::$columnName];
}
?>

<div class="limiter">
    <div class="container-table100">
        <div class="wrap-table100">
            <table class="streetView table" data-pagination="true" data-search="true" data-toggle="table">
                <col width="5%">
                <col width="67%">
                <col width="13%">
                <col width="13%">
                <col width="2%">
                <thead>
                    <tr>
                        <th data-sortable="true" data-field="id">#</th>
                        <th><span class="top">Улица</span></th>
                        <th><span class="top">Район</span></th>
                        <th><span class="top">Город</span></th>
                        <th><span class="top"></span></th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $itemIndex = 1;
                $typeList = "street";
                foreach ($streetsArray as $itemNameStreet => $itemValStreet) {
                    break;
                    echo "<tr>";
                    echo "<td>" . $itemIndex . "</td>";
                    echo "<td><span list-id='$itemNameStreet' class='text'>" . $itemValStreet[StreetTableStruct::$columnName] . "</span><input class='form-control' style='display: none; width: 100%;' type='text'/></td>";
                    //echo "<td><span list-id='" . $itemVal[DistrictTableStruct::$columnID_City] . "' class='text'>" . $itemVal[CitiesTableStruct::$columnName] . "</span><input class='form-control' style='display: none; width: 100%;' type='text'/></td>";

                    echo "<td><select class=\"selectpicker show-tick\" style='width: 100%' onchange=\"$.coremanage.listEdit($(this), '$typeList')\"><option></option>";
                        foreach ($districtsArray as $itemNameDistrict => $itemValDistrict) {
                            $isSelected = $itemNameDistrict == $itemValStreet[DistrictTableStruct::$columnID] ? "selected=\"\"" : "";
                            echo "<option " . $isSelected . " value='" . $itemNameDistrict ."'>" . $itemValDistrict . "</option>";
                        }
                    echo "</select></td>";

                    echo "<td>" . $streetsArray[CitiesTableStruct::$columnName] . "</td>";

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
