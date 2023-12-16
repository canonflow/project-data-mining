<?php 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        echo 'Method Not Allowed';
        exit;
    }
    require_once __DIR__ . '/vendor/autoload.php';
    use Shuchkin\SimpleXLSXGen;
    $label = json_decode($_POST["label"], false);
    $cityBlok = json_decode($_POST["cityBlok"], false);
    $euclidean = json_decode($_POST["euclidean"], false);
    $supremum = json_decode($_POST["supremum"], false);

    //* Buat di atas
    $labelHeader = $label;
    array_unshift($labelHeader, "");
    
    //* Excel
    $xlsx = new SimpleXLSXGen();
    for ($i = 0; $i < count($euclidean); $i++) {
        array_unshift($cityBlok[$i], $label[$i]);
        array_unshift($euclidean[$i], $label[$i]);
        array_unshift($supremum[$i], $label[$i]);
    }
    array_unshift($cityBlok, $labelHeader);
    array_unshift($euclidean, $labelHeader);
    array_unshift($supremum, $labelHeader);

    $xlsx->addSheet($cityBlok, 'City Blok');
    //* Adjust Column Width
    for ($col = 1; $col <= count($labelHeader); $col++) {
        $xlsx->setColWidth($col, 18);
    }

    $xlsx->addSheet($euclidean, 'Euclidean');
    //* Adjust Column Width
    for ($col = 1; $col <= count($labelHeader); $col++) {
        $xlsx->setColWidth($col, 18);
    }

    $xlsx->addSheet($supremum, 'Supremum');
    //* Adjust Column Width
    for ($col = 1; $col <= count($labelHeader); $col++) {
        $xlsx->setColWidth($col, 18);
    }
    $filename = __DIR__ . '/result-proximity.xlsx';  //! If the file is exist, it will be overided
    $xlsx->saveAs($filename);  //! Save the file in server

    $response = [
        "file" => "result-proximity.xlsx",
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
?>