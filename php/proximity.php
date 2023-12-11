<?php 
    //! Gk boleh diakses dengan GET METHOD
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        echo 'Method Not Allowed';
        exit;
    }

    $euclidean = array();  //* 2 dimensi
    $cityBlok = array();  //* 2 dimensi
    $supremum = array(); //* 2 dimensi
    //* Kalo pake file input
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

    $data = json_decode($_POST["data"], false);  //* Data yg diterima berupa JSON
    $length = count($data);

    //* Isi nilai awal
    for ($i = 0; $i < $length; $i++) {
        $euclidean[$i] = array();
        $cityBlok[$i] = array();
        $supremum[$i] = array();
        for ($j = 0; $j < $length; $j++) { 
            $euclidean[$i][$j] = 0;
            $cityBlok[$i][$j] = 0;
            $supremum[$i][$j] = 0;
        }
    }

    //* Calculate
    for ($titik1 = 0; $titik1 < $length - 1; $titik1++) {
        $data1 = $data[$titik1];
        for ($titik2 = $titik1 + 1; $titik2 < $length; $titik2++) {
            $data2 = $data[$titik2];
            $resEuclidean = 0;
            $resCityBlok = 0;
            $resSupremum = PHP_INT_MIN;  //* Default kasih nilai int paling kecil

            //* data
            for ($i = 0; $i < count($data1); $i++) {
                $resEuclidean += pow(abs($data1[$i] - $data2[$i]), 2);
                $resCityBlok += abs($data1[$i] - $data2[$i]);

                //* Supremum maximum checking
                $temp = abs($data1[$i] - $data2[$i]);
                if ($temp > $resSupremum) $resSupremum = $temp;
            }

            //* Euclidean
            $resEuclidean = round(sqrt($resEuclidean), 3);
            $euclidean[$titik1][$titik2] = $resEuclidean;
            $euclidean[$titik2][$titik1] = $resEuclidean;

            //* City Blok
            $cityBlok[$titik1][$titik2] = $resCityBlok;
            $cityBlok[$titik2][$titik1] = $resCityBlok;

            //* Supremum
            $supremum[$titik1][$titik2] = $resSupremum;
            $supremum[$titik2][$titik1] = $resSupremum;
        }
    }

    //* Data yg akan dikirim berupa array associate
    $response = array(
        "euclidean" => $euclidean,
        "cityBlok" => $cityBlok,
        "supremum" => $supremum,
    );

    //* Response berupa JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>