<?php 
    $title = "Gini";
    $css = array("./css/output.css", "./css/gini.css");
    require_once "./header.php";
?>
<body id="body" class="starting-body">
    <div id="wrapper">
        <div class="flex flex-col gap-4 items-center text-sky-900 py-5" id="content">
            <div class="join join-vertical lg:join-horizontal">
                <a class="btn btn-outline join-item btn-wide" href="./index.php">Proximity</a>
                <a class="btn btn-active btn-neutral join-item btn-wide">Gini</a>
            </div>
            <h1 class="text-4xl font-semibold text-white">Gini</h1>
            <!-- Input -->
            <div class="max-w-xl border border-stone-200 rounded-lg px-5 py-10 bg-slate-200 text-white">
                <input type="file" name="file" id="fileInput" class="file-input">
                <button onClick="submit()" class="btn btn-primary">Submit</button>
            </div>

            <!-- Output -->
            <div id="output-container" class="">
                <h1 class="text-4xl font-semibold text-white">Output</h1>
                <div id="output" class="max-w-xl border border-stone-200 rounded-lg px-5 py-10 bg-slate-200 text-black">

                </div>
                <button class="btn btn-outline btn-accent" onClick="saveResult()">Download Result</button>
            </div>
        </div>
    </div>
    <!-- <form id="uploadForm" enctype="multipart/form-data">
    </form> -->
    <!-- FANTA -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r121/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
    <script>
        const createVanta = () => {
            return VANTA.WAVES({
                        el: "#body",
                        mouseControls: true,
                        touchControls: true,
                        gyroControls: false,
                        minHeight: 400.00,
                        minWidth: 200.00,
                        scale: 1.00,
                        scaleMobile: 1.00,
                        color: 0x14171c,
                        shinnies: 7.00,
                        waveSpeed: 0.8,
                    })
        }
        const vanta = createVanta();
    </script>
    <script>
        let saveData;
        const saveResult = () => {
            saveData = JSON.stringify(saveData);

            $.ajax({
                url: 'giniSave.php',
                method: 'post',
                data: {
                    data: saveData,   
                },
                success: function(data) {
                    window.location.href = data.file;
                    console.log(data);
                }
            });
        }
        const submit = () => {
            var file_data = $('#fileInput').prop('files')[0];   
            var form_data = new FormData();                  
            form_data.append('file', file_data);
            // alert(form_data);           
            // console.log(form_data);         
            $.ajax({
                url: './php/gini.php', // <-- point to server-side PHP script 
                dataType: 'json',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         
                type: 'post',
                success: function(data){

                    saveData = data.data;
                    console.log(saveData);
                    // console.log(php_script_response); // <-- display response from the PHP script, if any
                    let result = "";
                    //* All Gini
                    for (const [key, val] of Object.entries(data.allGini)) {
                        console.log(`Gini(${key}) = ${val}`);
                        result += `Gini(${key}) = ${val}<br />`;
                    }

                    //* Best Split
                    let key = Object.keys(data.bestSplit);

                    //* Kalo ada 1
                    if (Object.keys(data.bestSplit).length == 1) {
                        console.log(`Best attribute to split by Gini is ${key} with Gini = ${data.bestSplit[key]}`);
                        result += `<br />Best attribute to split by Gini is <strong>${key}</strong> with Gini = <strong>${data.bestSplit[key]}</strong> <br />`
                    } else {
                        let res = "Best attribute to split by Gini are ";
                        result += "<br />Best attribute to split by Gini are "
                        for (const [key, val] of Object.entries(data.bestSplit)) {
                            res += `<strong>${key}</strong>, `;
                            result += `<strong>${key}</strong>, `;
                            // console.log(`${key}: ${val}`);
                        }
                        res += `with Gini = ${data.bestSplit[key[0]]}`; 
                        result += `with Gini = <strong>${data.bestSplit[key[0]]}</strong>`
                    }

                    const outputContainer = document.getElementById("output-container");
                    const output =document.getElementById("output");
                    outputContainer.classList.add('output-show');
                    outputContainer.style.display = "flex";
                    output.innerHTML = result;
                    
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