<?php 
    $classAttrName;
    $classes;
    $attributes = array();
    function parseCsv($path) {
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

    // Ngambil Nama Class dari data (e.g. C0, C1, ...)
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

    function getUniqueTypeValue($data, $attr) {
        $val = array();
        foreach($data[$attr] as $value) {
            if (!in_array($value, $val)) {
                array_push($val, $value);
            }
        }

        return $val;
    }

    /*  TODO:
        e.g.:
        "Kuali"
        "gender" => [
            "att1" => [
                "C0" => 2,
                "C1" => 2,
                "C2" => 1
                "sum" => 5
            ],
            "att2" => [
                "C0" => 2,
                "C1" => 2,
                "C2" => 1,
                "sum" => 5
            ],
        ]

        "Kuanti" => utk setiap key-nya adl split position
        "Salary" => [
            "70" => [
                "<=" => [
                    "C0" => 2,
                    "C1" => 2,
                    "C2" => 1,
                    "sum" => 5
                ],
                ">" => [
                    "C0" => 2,
                    "C1" => 2,
                    "C2" => 1,
                    "sum" => 5
                ]
            ],
            "80" => [
                "<=" => [
                    "C0" => 2,
                    "C1" => 2,
                    "C2" => 1,
                    "sum" => 5
                ],
                ">" => [
                    "C0" => 2,
                    "C1" => 2,
                    "C2" => 1,
                    "sum" => 5
                ]
            ],
        ]

        @param $data => data csv yg udh di-parse
        @param attr => attribute / kolom (e.g. gender, cartype)
    */
    function parseAttribute($data, $attr) {
        global $classAttrName;
        $values = array();  //* Nilai utk perhitungan nanti
        $dataAttr = $data[$attr];  //* Data dari kolom tertentu (e.g. gender, car,...)
        $values[$attr] = array();  //* Set key berdasarkan nama kolom
        $keyCheck = array();
        $classes = getClasses($data);
        
        //* Make default array key-value-type
        foreach ($dataAttr as $index => $value) {
            if (!in_array($value, $keyCheck)) {
                array_push($keyCheck, $value);
                $values[$attr][$value] = array();
            }
        }

        $type = getUniqueTypeValue($data, $attr);  //* (e.g. Male, Female)

        //* Make default value of key
        for ($i = 0; $i < count($type); $i++) {
            // return "a";
            $classCheck = array();
            $currClass = $type[$i];  // (e.g. Male, Female)

            foreach ($classes as $class) {
                $values[$attr][$currClass] += [$class => 0];
            }

            $values[$attr][$currClass] += ["sum" => 0];  //* Utk semua

            //* Make default value dari key
            // foreach ($dataAttr as $index => $value) {
            //     $class = $data[$classAttrName][$index];  //* Ambil class utk tiap value
            //     if ($value == $currClass) {  
            //         if (!in_array($class, $classCheck)) {
            //             array_push($classCheck, $class);
            //             // return $classCheck;
            //             // return $values;
            //             // return $values[$attr][$value][$class];
            //             // $values[$attr][$value][$currClass] = 5;
            //             $values[$attr][$value] += [$class => 0];
            //         }
            //     }
            // }
            // $values[$attr][$currClass]

        }


        foreach ($dataAttr as $index => $value) {
            $class = $data[$classAttrName][$index];  //* Ambil class utk tiap value
            $values[$attr][$value][$class] = $values[$attr][$value][$class] + 1;
            $values[$attr][$value]["sum"] = $values[$attr][$value]["sum"] + 1;
            // $values[$attr][$value][$class] += 1;
            //* Check attr-key
            // if (isset($values[$attr][$value])) {
            //     //* Kalo key yg berupa class udh ada di array
            //     if (isset($values[$attr][$value][$class])) {
            //         $values[$attr][$value][$class] += 1;
            //     } else {
            //         $values[$attr][$value][$class] = array();
            //         $values[$attr][$value][$class] = 2;
            //     }
            // }
            // else {
            //     $values[$attr][$value] = array();
            // }

            // if (isset($values[$attr][$class])) {
            //     $values[$attr][$class] += 1;
            // } else {
            //     $values[$attr][$class] = 1;
            // }
        }

        return $values;
    }

    /*
        @param $data => data yg dihasilkan dari fungsi parseAttribute
    */
    function getGini($data) {
        global $classes;
        $n = 0;
        $gini = 0;

        //* Get n of 
        foreach ($data as $attr) {
            foreach ($attr as $key) {
                $n += $key["sum"];
            }
        }

        //* Calculale
        foreach ($data as $attr) {
            foreach ($attr as $child) {
                $currGini = 1;
                foreach ($classes as $class) {
                    $currGini = $currGini - pow($child[$class] / $child["sum"], 2);
                }
                $gini = $gini + ($child["sum"] / $n) * $currGini;
            }
        }

        return round($gini, 3);
    }

    // print_r(parseCsv("../files/csv/test.csv"));
?>