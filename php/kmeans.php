<?php 
    $classAttrName;
    $classes;
    $attributes = array();
    $kuantiAttrResult;
    $saveDataHeader = array();
    $saveDataValue = array();
    function parseCSV($path) {
        global $classAttrName, $attributes;
        $csvData = array();

        if (($handle = fopen($path,"r")) !== FALSE) {
            //* Header
            $header = fgetcsv($handle);
            
            //* Init array each header
            $cnt = 0;
            foreach ($header as $key) {
                $csvData[$key] = array();

                //* Biar attribute class nggak dimasukkin
                if ($cnt < count($header) - 1) {
                    $attributes[] = $key;
                }
                $cnt++;
            }


            //* Data based on its header
            /* Example:
                Name => ["John", "Alice"],
                Age => [20, 30],
                Location => ["Jakarta", "Bali"]
            */
            while (($row = fgetcsv($handle)) !== FALSE) {
                foreach ($header as $index => $key) {
                    $csvData[$key][] = $row[$index];
                }
            }
        }
        fclose($handle);

        end($csvData);  // Set $csvData pointer to the end
        $classAttrName = key($csvData);
        reset($csvData);

        return $csvData;
    }

    function getAttributes() {
        global $attributes;
        return $attributes;
    }

    function getClasses($data) {
        global $classAttrName, $classes;
        $classes = array();

        foreach($data[$classAttrName] as $class) {
            if (!in_array($class, $classes)) {
                array_push($classes, $class);
            }
        }

        return $classes;
    }

    function initialCentroid($data, $cluster) {
        global $classAttrName;  // e.g. label, class
        $initialCentoid = array();
        //* Initial value for each centroi
        // for ($i = 0; $i < $centroid; $i++) {
        //     $initialCentoid[$i] = array();
        // }

        for ($i = 0; $i < $cluster; $i++) {
            // $tempArray = array();
            if (count($initialCentoid) == 0) {
                $tempArray = array();
                foreach ($data as $attribute => $values) {
                    if ($attribute != $classAttrName) {
                        $tempValue = rand(min($values), max($values));
                        array_push($tempArray, $tempValue);
                    }
                }
                array_push($initialCentoid, $tempArray);
            } else {
                $pos = 0;
                $currCentroid = $initialCentoid[$pos];
                $tempArray = array();
                $needChange = true;  //* Apakah perlu mengganti centroid sementara
                while ($pos < count($initialCentoid) || $tempArray == $currCentroid) {
                    if ($needChange) {
                        foreach ($data as $attribute => $values) {
                            if ($attribute != $classAttrName) {
                                $tempValue = rand(min($values), max($values));
                                array_push($tempArray, $tempValue);
                            }
                        }
                        $needChange = false;
                    }
                    if ($tempArray != $currCentroid) {
                        $pos++;
                        if ($pos < count($initialCentoid)) {
                            $currCentroid = $initialCentoid[$pos];
                        }
                    } else {
                        //* Artinya centroid sementara sudah dipakai
                        $tempArray = array();
                        $needChange = true;  //* Jadi perlu buat centroid sementara lagi
                    }
                }
                array_push($initialCentoid, $tempArray);
            }
        }
        return $initialCentoid;
    }
?>

<?php 
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.1 405 Method Not Allowed');
        echo 'Method Not Allowed';
        exit;
    }

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

    $numCluster = (int)$_POST['cluster'];
    $data = parseCSV($location);
    $attributes = getAttributes();
    $classes = getClasses($data);
    $initialCentroid = initialCentroid($data, $numCluster);

    //* Cek apakah jumlah cluster yg dipilih > $jumlah data / titik atau ==
    if ($numCluster > count($data[$classAttrName])) {
        header('Content-Type: application/json');
        echo json_encode([
            'error_num_cluster' => 'yes'
        ]);
        return;
    } 

    unlink($location);
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data, 
        'attributes' => $attributes, 
        'classes' => $classes, 
        'centroid' => $initialCentroid,
        'cluster' => $numCluster,
        'classAttrName' => $classAttrName,
    ]);
?>