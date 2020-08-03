<?php
require_once "../common.php";

global $db, $districtsTable, $citiesTable, $streetTable, $stringHandler;

function FillArray($districtsArray, $item, $index) {
    return
        [
            "id" => $index,
            "street" => ["id" => $item[StreetTableStruct::$columnID], "name" => $item[StreetTableStruct::$columnName]],
            "district" => ["all" => $districtsArray, "active" => $item[StreetTableStruct::$columnID_District]],
            "city" => $item[CitiesTableStruct::$columnName],
            "manage" => ""
        ];
}

$districtsArray = [];

$districtsTable->Select();
while ($item = $db->fetch_array()) {
    $districtsArray[$item[DistrictTableStruct::$columnID]] = $item[DistrictTableStruct::$columnName];
}

$streetsArray = [];

$streetTable->Count();
$index = $_GET["start"] + 1;
$streetTable->Count();
$recordsTotal = $db->fetch_row();

$typeList = "street";
$whereText = !empty($_GET["search"]["value"]) ? StreetTableStruct::$columnName . " LIKE '%" . $_GET["search"]["value"] . "%'" : "";
$streetTable->Select($whereText, empty($whereText) ? $_GET["start"] . ", " . $_GET["length"] : "");
//$recordsTotal = empty($whereText) ? $recordsTotal : $db->num_rows();
$recordsFiltered = empty($whereText) ? $recordsTotal : $db->num_rows();
if (!empty($whereText)) {
    for ($i = 0; $i < $_GET["start"] + $_GET["length"]; $i++) {
        $item = $db->fetch_array();
        if ($i < $_GET["start"]) {
            continue;
        }

        if ($i >= $recordsFiltered) {
            break;
        }

        $streetsArray[] = FillArray($districtsArray, $item, $index);
        $index++;
    }
} else {
    while ($item = $db->fetch_array()) {
        $streetsArray[] = FillArray($districtsArray, $item, $index);
        $index++;
    }
}

echo json_encode(
    [
        "data" => $streetsArray,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered
    ]
);

/*echo json_encode(
    [
        "data" => [
            [
                "Tiger Nixon",
                "System Architect",
                "Edinburgh",
                "5421",
                "2011/04/25",
                "$320,800"
            ]
        ]
    ]
);*/