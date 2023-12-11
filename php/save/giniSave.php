<?php 
    // require_once __DIR__ . '/vendor/autoload.php'; 
    // require_once '/vendor/autoload.pnp';
    use Shuchkin\SimpleXLSXGen;
    $data = json_decode($_POST["data"], false);
    // $data = $_POST["data"];
    $xlsx = SimpleXLSXGen::fromArray($data);
    // $xlsx->downloadAs("result.xlsx");
    $response = [
        "data" => $data
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
?>