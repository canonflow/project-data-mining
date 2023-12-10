<?php 
    $classAttrName;
    $classes;
    $attributes = array();
    $kuantiAttrResult;
    define("KUANTITATIF", "kuantitatif");
    define("KUALITATIF", "kualitatif");

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

    function getKuantiAttrResult() {
        global $kuantiAttrResult;
        return $kuantiAttrResult;
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

    //* e.g. [50, 60, 70, 80, 90, 100]
    function getSplitPosition($data, $attr) {
        $position = array();
        $listOfValue = $data[$attr];
        sort($listOfValue);  //* Sort ASC
        $listOfValue = array_unique($listOfValue);  //* Get just unique value
        for ($i = 0; $i < count($listOfValue) - 1; $i++) {
            $temp1 = floatval($listOfValue[$i]);
            $temp2 = floatval($listOfValue[$i+1]);
            $calc = abs($temp2 + $temp1) / 2;
            array_push($position, $calc);
        }

        return $position;
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
        // $keyCheck = array();
        $classes = getClasses($data);
        
        //* Make default array key-value-type
        // foreach ($dataAttr as $index => $value) {
        //     if (!in_array($value, $keyCheck)) {
        //         array_push($keyCheck, $value);
        //         $values[$attr][$value] = array();
        //     }
        // }

        //* Check whether the value is a numeric or a string
        if (is_numeric($data[$attr][0])) {
            $splitPosition = getSplitPosition($data, $attr);
            $markers = ["<=", ">"];
            //* Make default array key-value-type
            foreach ($splitPosition as $position) {
                $values[$attr][$position] = array();

                //* Position Marker e.g. <= and >
                $values[$attr][$position] = array();
                foreach ($markers as $marker) {
                    $temp = array();
                    foreach ($classes as $class) {
                        $temp += [$class => 0];
                    }
                    $temp += ["sum" => 0];  //* Utk semua
                    $values[$attr][$position] += [$marker => $temp];  //* Add Marker and default class's values
                }
            }

            foreach ($dataAttr as $index => $value) {
                $class = $data[$classAttrName][$index];  //* Ambil class utk tiap value
                // $values[$attr][$value][$class] = $values[$attr][$value][$class] + 1;
                // $values[$attr][$value]["sum"] = $values[$attr][$value]["sum"] + 1;

                foreach ($splitPosition as $position) {
                    if ($value <= $position) {
                        $values[$attr][$position]["<="][$class] = $values[$attr][$position]["<="][$class] + 1;
                        $values[$attr][$position]["<="]["sum"] = $values[$attr][$position]["<="]["sum"] + 1;
                    } else {
                        $values[$attr][$position][">"][$class] = $values[$attr][$position][">"][$class] + 1;
                        $values[$attr][$position][">"]["sum"] = $values[$attr][$position][">"]["sum"] + 1;
                    }
                    
                }
            }

            return $values;
        } else {
            $type = getUniqueTypeValue($data, $attr);  //* (e.g. Male, Female)
            //* Make default array key-value-type
            foreach ($type as $val) {
                $values[$attr][$val] = array();
            }
    
            //* Make default value of key
            for ($i = 0; $i < count($type); $i++) {
                // return "a";
                $classCheck = array();
                $currClass = $type[$i];  // (e.g. Male, Female)
    
                foreach ($classes as $class) {
                    $values[$attr][$currClass] += [$class => 0];
                }
    
                $values[$attr][$currClass] += ["sum" => 0];  //* Utk semua
            }
    
    
            foreach ($dataAttr as $index => $value) {
                $class = $data[$classAttrName][$index];  //* Ambil class utk tiap value
                $values[$attr][$value][$class] = $values[$attr][$value][$class] + 1;
                $values[$attr][$value]["sum"] = $values[$attr][$value]["sum"] + 1;
            }
            return $values;
        }

    }

    function kualiOrKuanti($data, $attr) {
        // If kuantitatif
        if (is_numeric($data[$attr][0])) {
            return KUANTITATIF;
        } 

        return KUALITATIF;
    }

    /*
        @param $data => data yg dihasilkan dari fungsi parseAttribute
    */
    function getGini($data, $type) {
        global $classes, $kuantiAttrResult;
        $n = 0;
        $gini = 0;

        //* If kuantitatif
        if ($type == KUANTITATIF) {
            //* Let's say we want to know gini each position
            $gini = PHP_INT_MAX;
            $label = "";

            foreach ($data as $lbl => $position) {
                $n = 0;
                $label = $lbl;
                foreach ($position as $attr) {
                    foreach ($attr as $key) {
                        $n += $key["sum"];
                    }
                    break;
                }
                
                //* Calculate Gini Position
                foreach ($position as $splitPosition => $attr) {
                    $giniPosition = 0;  //* Gini each position
                    foreach ($attr as $child) {
                        $currGini = 1;
                        foreach ($classes as $class) {
                            if ($child[$class] == 0 && $child["sum"] == 0) {
                                $currGini = $currGini - 0;
                            } else {
                                $currGini = $currGini - pow($child[$class] / $child["sum"], 2);
                            }
                        }
                        $giniPosition = $giniPosition + ($child["sum"] / $n) * $currGini;
                    }
                    // return $giniPosition;

                    if ($giniPosition < $gini) {
                        $gini = $giniPosition;
                        $kuantiAttrResult = $label . " [$splitPosition]";
                    } 
                }
            }

            return round($gini, 3);
        } else {
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
    }

    // print_r(parseCsv("../files/csv/test.csv"));
?>