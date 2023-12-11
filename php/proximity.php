<?php 
    //! Gk boleh diakses dengan GET METHOD
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        echo 'Method Not Allowed';
        exit;
    }
    
    $isFileFinished = false;
    $data = array();
    $length = 0;
    $label = array();

    //* Read CSV for Proximity
    /*
        $data = [
            [1,2,3],
            [4,5,6]
        ]
        but the value will be string, so we can cast it later
    */
    function readCSV($path) {
        global $label;
        $data = array();
        if (($handle = fopen($path,"r")) !== FALSE) {
            while(($row = fgetcsv($handle)) !== FALSE) {
                //* Ambil labelnya
                $label[] = $row[0];
                $data[] = array_slice($row, 1);
            }
        }
        fclose($handle);

        return $data;
    }

    $euclidean = array();  //* 2 dimensi
    $cityBlok = array();  //* 2 dimensi
    $supremum = array(); //* 2 dimensi
    //* Kalo pake file input
    if (isset($_FILES['file']['name'])) {
        if($_FILES['file']['name'] != ''){
            $isFileFinished = true;
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
            $data = readCSV($location);
            $length = count($data);
        } 
    } else {
        $data = json_decode($_POST["data"], false);  //* Data yg diterima berupa JSON
        $length = count($data);
        for ($i = 1; $i <= $length; $i++) {
            $label[] = "P".$i;
        }
    }


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
                $resEuclidean += pow(abs(floatval($data1[$i]) - floatval($data2[$i])), 2);
                $resCityBlok += abs(floatval($data1[$i]) - floatval($data2[$i]));

                //* Supremum maximum checking
                $temp = abs(floatval($data1[$i]) - floatval($data2[$i]));
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

    if ($isFileFinished) {
        unlink($location);
    }

    //* Data yg akan dikirim berupa array associate
    $response = array(
        "euclidean" => $euclidean,
        "cityBlok" => $cityBlok,
        "supremum" => $supremum,
        "label" => $label
    );

    //* Response berupa JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>