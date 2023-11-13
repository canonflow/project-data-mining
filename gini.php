<?php 
    $title = "Gini";
    $css = array("./css/output.css");
    require_once "./header.php";
?>
<body>
    <input type="file" name="file" id="fileInput">
    <button onClick="test()" class="btn btn-primary">Test</button>
    <!-- <form id="uploadForm" enctype="multipart/form-data">
    </form> -->
    <script>
        const test = () => {
            var file_data = $('#fileInput').prop('files')[0];   
            var form_data = new FormData();                  
            form_data.append('file', file_data);
            alert(form_data);                    
            $.ajax({
                url: './php/gini.php', // <-- point to server-side PHP script 
                dataType: 'json',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(data){
                    // console.log(data);
                    // console.log(php_script_response); // <-- display response from the PHP script, if any
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
            });

        return;
        let dt = 10;
        var formData = new FormData($("#uploadForm")[0]);
        console.log(formData);
        $.ajax({
            url: "./php/gini.php",
            type: "POST",
            data: formData,
            success: function(data) {
                console.log(data);

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