<?php 
    require("./function.php");

    //* Error
    $csvData = parseCsv("../files/csv/test2.csv");
    $classes = getClasses($csvData);
    $attributes = getAttributes();
    // $gender = parseAttribute($csvData, "gender");
    // $giniGender = getGini($gender);
    // $car = parseAttribute($csvData, "car");
    // $giniCar = getGini($car);

    // $response = [
    //     "csv" => $csvData,
    //     "class" => $classes,
    //     "gender" => $gender,
    //     "car" => $car,
    //     "giniGender" => $giniGender,
    //     "giniCar" => $giniCar
    // ];
    //* Get all Gini
    $allGini = array();

    foreach ($attributes as $attr) {
        $gini = getGini(parseAttribute($csvData, $attr));
        $allGini += [$attr => $gini];
    }

    //* Get best split
    array_multisort($allGini, SORT_ASC);  // Sort ASC buat cari nilai impurity terkecil

    //* Jika ada 2 atau lebih attribute yg memiliki nilai impurity terkecil dan sama
    $bestSplitKey = key($allGini);
    $bestSplit = array($bestSplitKey => $allGini[$bestSplitKey]);

    for ($i = 1; $i < count($allGini); $i++) {
        next($allGini);  //* Ubah pointer ke alamat selanjutnya
        if ($allGini[key($allGini)] == $allGini[$bestSplitKey]) {
            $bestSplit += array(key($allGini) => $allGini[key($allGini)]);
        }
    }

    reset($allGini);

    $response = [
        "csv" => $csvData,
        "classes" => $classes,
        "attributes" => $attributes,
        "allGini" => $allGini,
        "bestSplit" => $bestSplit,
        // "gender" => getGini(parseAttribute($csvData, "gender")),
        // "carType" => getGini(parseAttribute($csvData, "car type")),
        // "shirtSize" => getGini(parseAttribute($csvData, "shirt size")),
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
?>