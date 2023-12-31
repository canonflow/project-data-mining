<?php 
    require("./function.php");
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        echo 'Method Not Allowed';
        exit;
    }

    //* Error
    if($_FILES['file']['name'] != ''){
        $test = explode('.', $_FILES['file']['name']);
        $extension = end($test);    
        $name = rand(100,999).'.'.$extension;
        $directory = 'uploads/';
    
        $location = 'uploads/'.$name;
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        move_uploaded_file($_FILES['file']['tmp_name'], $location);
        // echo '<img src="'.$location.'" height="100" width="100" />';
    }

    $csvData = parseCsv($location);
    
    $classes = getClasses($csvData);
    $attributes = getAttributes();

    //* Get all Gini
    $allGini = array();

    foreach ($attributes as $attr) {
        $gini = getGini(parseAttribute($csvData, $attr), kualiOrKuanti($csvData, $attr));
        if (kualiOrKuanti($csvData, $attr) == KUANTITATIF) {
            $lbl = getKuantiAttrResult();
            $allGini += [$lbl => $gini];
        } else {
            $allGini += [$attr => $gini];
        }
    }

    $data = getHeaderAndValue();

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
        "data" => $data
    ];

    //! Hapus Data
    unlink($location);

    header('Content-Type: application/json');
    echo json_encode($response);
?>