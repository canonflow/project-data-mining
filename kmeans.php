<?php 
    $title = "Gini";
    $css = array("./css/output.css", "./css/gini.css", "./css/proximity.css");
    require_once "./header.php";
?>
<body id="body" class="starting-body">
    <div id="wrapper">
        <div class="flex flex-col gap-4 items-center text-sky-900 py-5" id="content">
            <div class="join join-vertical lg:join-horizontal">
                <a class="btn btn-outline join-item btn-wide" href="./index.php">Proximity</a>
                <a class="btn btn-outline join-item btn-wide" href="./gini.php">Gini</a>
                <a class="btn btn-active btn-neutral join-item btn-wide">K-Means</a>
            </div>
            <h1 class="text-4xl font-semibold text-white">K-Means</h1>
            <!-- Input -->
            <div class="max-w-2xl border border-stone-200 rounded-lg px-5 py-10 bg-slate-200 text-white">
                <input type="file" name="file" id="fileInput" class="file-input">
                <select class="select select-bordered" id="clusterInput">
                    <option disabled selected>Select cluster</option>
                    <option>2</option>
                    <option>3</option>
                    <option>4</option>
                    <option>5</option>
                    <option>6</option>
                    <option>7</option>
                    <option>8</option>
                    <option>9</option>
                    <option>10</option>
                </select>
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

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast'
            },
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true
        });
        const vanta = createVanta();
    </script>

    <script>
        // $(document).ready(function() {
        //     $("#clusterInput").select2();
        // })
        const submit = () => {
            var file_data = $('#fileInput').prop('files')[0];   
            let clusterInput = $('#clusterInput').val();
            if (clusterInput == null) {
                Toast.fire({
                    icon: 'error',
                    title: 'Inputkan jumlah cluster!'
                });
                return;
            }
            var form_data = new FormData();                  
            form_data.append('file', file_data);
            form_data.append('cluster', clusterInput);     
            // console.log(form_data);         
            // return;
            $.ajax({
                url: './php/kmeans.php', // <-- point to server-side PHP script 
                dataType: 'json',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                        
                type: 'post',
                success: function(data){
                    if (data.error_num_cluster == 'yes') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Inputkan jumlah cluster dengan benar!'
                        });
                        return;
                    }
                    
                    if (data.error_no_file == 'yes') {
                        Toast.fire({
                            icon: 'error',
                            title: 'Inputkan file!'
                        });
                        return;
                    }
                    console.log(data);
                },
                error: function(xhr) {
                    console.log(xhr);
                }
            });
        };
    </script>
</body>