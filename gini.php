<?php 
    $title = "Gini";
    $css = array("./css/output.css");
    require_once "./header.php";
?>
<body>
    <button onClick="test()" class="btn btn-primary">Test</button>
    <script>
    const test = () => {
        let dt = 10;
        $.ajax({
            url: "./php/gini.php",
            type: "POST",
            data: { dt },
            success: function(data) {
                // console.table(data);
                // console.table(data.class);
                // console.table(data.gender);
                // console.table(data.car);
                // console.log(data.gender);
                // console.log(data.car);
                // console.log(data.giniGender);
                // console.log(data.giniCar);
                // console.log(data);

                //* All Gini
                for (const [key, val] of Object.entries(data.allGini)) {
                    console.log(`Gini(${key}) = ${val}`);
                }

                //* Best Split
                let key = Object.keys(data.bestSplit);

                //* Kalo ada 1
                if (Object.keys(data.bestSplit).length == 1) {
                    console.log(`Best attribute to split by Gini is ${key} with Gini = ${data.bestSplit[key]}`);
                } else {
                    let res = "Best attribute to split by Gini are ";
                    for (const [key, val] of Object.entries(data.bestSplit)) {
                        res += `${key}, `;
                        // console.log(`${key}: ${val}`);
                    }
                    res += `with Gini = ${data.bestSplit[key]}`; 
                }
            }
        })
    };
    </script>
</body>