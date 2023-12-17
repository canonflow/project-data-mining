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

    //TODO: Random initial centroid
    /*
        @param $data, $cluster
        @return array[]
                initialCentroid: [
                    [x,y],  // Centroid 0
                    [x,y],  // Centroid 1
                    [x,y],  // Centroid 2
                ]
    */
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
    
    //TODO: Mapping all points into cluster based on closest centroid using eudlidean distance
    /*
        @param $data, $centroids
        @return array[]
                cluster: [
                    [p0, p2],  // CLuster 0 with Centroid 0
                    [p1, p3],  // Cluster 1 with Centroid 1
                ]
                p0 => 0, index of point in $data
    */
    function clustering($data, $centroids) {
        global $classAttrName;

        //* Initialize clusters
        $clusters = [];
        for($i = 0; $i < count($centroids); $i++) {
            array_push($clusters, array());
        }

        //* Iterate all points
        for ($point = 0; $point < count($data[$classAttrName]); $point++) {
            $value = array();  // [1,2] from current point
            foreach ($data as $attribute => $values) {
                if ($attribute != $classAttrName) {
                    array_push($value, $values[$point]);
                }
            }

            //* Check distance between centroid
            $closestCentroidDistance = PHP_INT_MAX;
            $closesCentroidIdx = PHP_INT_MAX;
            foreach($centroids as $idx => $centroid) {
                $currCentroidDistance = 0.0;
                for ($i = 0; $i < count($centroid); $i++) {
                    $currCentroidDistance += pow(abs(floatval($value[$i]) - $centroid[$i]), 2);
                }

                $currCentroidDistance = sqrt($currCentroidDistance);
                //* Compare
                if ($currCentroidDistance < $closestCentroidDistance) {
                    $closestCentroidDistance = $currCentroidDistance;
                    $closesCentroidIdx = $idx;
                }
            }

            array_push($clusters[$closesCentroidIdx], $point);
        }
        return $clusters;
    }

    function calculateCentroid($data, $clusters) {
        global $classAttrName;
        $centroids = [];
        for ($i = 0; $i < count($clusters); $i++) {
            array_push($centroids, array());
        }

        foreach($clusters as $idx => $cluster) {
            foreach($data as $attribute => $values) {
                if ($attribute != $classAttrName) {
                    $tempCentroidValue = 0;
                    foreach($cluster as $point) {
                        $tempCentroidValue += floatval($values[$point]);
                    }

                    //* Kalo cluster ada anggotanya
                    if (count($cluster) != 0) {
                        array_push($centroids[$idx], round($tempCentroidValue/count($cluster), 3));
                    }
                }
            }
        }
        
        return $centroids;
    }
    
    function checkingCluster($clusters) {
        foreach($clusters as $cluster) {
            if (count($cluster) == 0) {
                return true;
            }
        }
        return false;
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
    $centroids = initialCentroid($data, $numCluster);

    //* Cek apakah jumlah cluster yg dipilih > $jumlah data / titik atau ==
    if ($numCluster > count($data[$classAttrName])) {
        unlink($location);
        header('Content-Type: application/json');
        echo json_encode([
            'error_num_cluster' => 'yes'
        ]);
        return;
    } else if ($numCluster == count($data[$classAttrName])) {
        unlink($location);
        $output = [
            'Label' => array(),
            'Vector' => array(),
            'Cluster Id' => array(),
            'Cluster Centroid' => array()
        ];

        //* Iterate all points
        for ($i = 0; $i < count($data[$classAttrName]); $i++) {
            //* Add Label
            array_push($output['Label'], $data[$classAttrName][$i]);
            //* Add Cluster ID
            array_push($output['Cluster Id'], $i);

            //* Add Point's Vector
            $tempVector = array();
            foreach ($data as $attribute => $values) {
                if ($attribute != $classAttrName) {
                    array_push($tempVector, $values[$i]);
                }
            }
            array_push($output['Vector'], join(", ", $tempVector));
            array_push($output['Cluster Centroid'], join(", ", $tempVector));
        }
        header('Content-Type: application/json');
        echo json_encode([
            'output' => $output
        ]);
        return;
    } else {
        $clusters = clustering($data, $centroids);
        $currCentroid = calculateCentroid($data, $clusters);
        $checkingCluster = true;
        
        //* Check if there are cluster that didnt have single point / data
        while(checkingCluster($clusters)) {
            $centroids = initialCentroid($data, $numCluster);
            $clusters = clustering($data, $centroids);
        }
        $currCentroid = calculateCentroid($data, $clusters);

        $currIteration = 1;
        $maxIteratation = 100;  //* Set the maximum iteration (just in case, u can added it on while loop) -> && $currIteration < $maxIteratation
        while ($currCentroid != $centroids) {
            $centroids = $currCentroid;
            $clusters = clustering($data, $currCentroid);
            $currCentroid = calculateCentroid($data, $clusters);
            $currIteration++;
        }
    }

    unlink($location);
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data, 
        'attributes' => $attributes, 
        'classes' => $classes, 
        'centroid' => $centroids,
        'numOfCluster' => $numCluster,
        'classAttrName' => $classAttrName,
        'clusters' => $clusters,
        'currentCentroid' => $currCentroid,
    ]);
?>