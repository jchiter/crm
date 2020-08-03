<?php
global $db, $citiesTable, $stringHandler;

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
                <col width="93%">
                <col width="2%">
                <thead>
                    <tr>
                        <th data-sortable="true" data-field="id">#</th>
                        <th><span class="top">Город</span></th>
                        <th><span class="top"></span></th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $itemIndex = 1;
                $typeList = "cities";
                foreach ($citiesArray as $itemName => $itemVal) {
                    echo "<tr>";
                    echo "<td>" . $itemIndex . "</td>";
                    echo "<td><span list-id='$itemName' class='text'>" . $itemVal . "</span><input class='form-control' style='display: none; width: 100%;' type='text'/></td>";
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
