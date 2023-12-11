<?php 
    require_once __DIR__ . '/vendor/autoload.php';
    use Shuchkin\SimpleXLSXGen;
    $data = json_decode($_POST["data"], true);
    $gini = array();
    foreach ($data as $value) {
        $gini[] = $value;
    }
    // $data = $_POST["data"];
    $xlsx = SimpleXLSXGen::fromArray($gini);
    $filename = __DIR__ . '/result.xlsx';  //! If the file is exist, it will be overided
    $xlsx->saveAs($filename);  //! Save the file in server

    $response = [
        "file" => "result.xlsx",
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
?>