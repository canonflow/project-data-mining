<?php 
    $title = "Proximity";
    $css = array("./css/output.css", "./css/proximity.css");
    require_once "header.php";
?>
<body id="body" class="starting-body">
    <div id="wrapper">
        <div class="flex flex-col gap-4 items-center text-sky-900 py-5" id="content">
        <div class="join join-vertical lg:join-horizontal">
            <a class="btn btn-active btn-neutral join-item btn-wide">Proximity</a>
            <a class="btn btn-neutral btn-outline join-item btn-wide" href="./gini.php">Gini</a>
            <a class="btn btn-outline btn-neutral join-item btn-wide" href="./kmeans.php">K-Means</a>
        </div>
            <h1 class="text-4xl font-semibold text-white">Proximity</h1>
            <!-- Card File Input -->
            <div class="max-w-xl border-stone-200 rounded-lg px-5 py-10 bg-slate-200">
                <input type="file" name="file" id="fileInput" class="file-input text-white">
                <button onClick="submit()" class="btn btn-primary">Submit</button>
            </div>

            <!-- Card Manual -->
            <div class="max-w-xl border border-stone-200 rounded-lg px-5 py-10 bg-slate-200">
                <!-- Inisialisasi -->
                <div class="flex flex-col gap-4">
                    <h3 class="text-xl text-red-500 font-bold text-center">Masukkan Jumlah Titik dan Banyak Data</h3>
                    <div class="flex flex-col gap-3">
                        <label for="input_titik">Jumlah Titik: </label>
                        <input type="number" id="input_titik" min="2" value="2" class="input input-bordered input-primary w-full text-white">
                        <label for="input_banyak_data">Banyak Data: </label>
                        <input type="number" id="input_banyak_data" min="1" value="1" class="input input-bordered input-primary w-full text-white">
                        <button onClick="init()" class="btn btn-primary">Init</button>
                    </div>
                </div>

                <div class="divider before:bg-zinc-800  after:bg-zinc-800 hidden" id="dividerInput">Input</div>

                <!-- Inputan Data -->
                <div id="input" class="flex flex-col gap-4 mt-8 hidden">
                    <h3 class="text-xl text-red-500 font-bold text-center">Pisahkah antar data dengan spasi (1 2 12 45 ...)</h3>
                    <div id="div_data" class="flex flex-col gap-3">
                        <!-- <input type="text" name="data_1" id="data_1" class="data_children">
                        <input type="text" name="data_2" id="data_2" class="data_children"> -->
                    </div>
                    <button onClick="input()" id="btn_input_data" class="btn btn-primary btn-outline">Input</button>
                </div>
            </div>

            <!-- Output -->
            <!-- City Blok -->
            <button class="btn btn-wide btn-outline btn-success hidden" onclick="output_city_blok.showModal()" id="btn_city_blok">City Blok</button>
            <dialog id="output_city_blok" class="modal modal-bottom sm:modal-middle text-slate-300">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-5">City Blok</h3>
                <!-- Table -->
                <div class="overflow-auto">
                    <table class="table table-zebra table-xs table-pin-rows table-pin-cols">
                        <thead id="output_head_city_blok" class="text-slate-300">
                        </thead>
                        <tbody id="output_body_city_blok">
                        </tbody>
                        <tfoot id="output_foot_city_blok">
                        </tfoot>
                    </table>
                </div>
                <div class="modal-action">
                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Close</button>
                    </form>
                </div>
            </div>
            </dialog>

            <!-- Euclidean -->
            <button class="btn btn-wide btn-outline btn-success hidden" onclick="output_euclidean.showModal()" id="btn_euclidean">Euclidean</button>
            <dialog id="output_euclidean" class="modal modal-bottom sm:modal-middle text-slate-300">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-5">Euclidean</h3>
                <!-- Table -->
                <div class="overflow-auto">
                    <table class="table table-zebra table-xs table-pin-rows table-pin-cols">
                        <thead id="output_head_euclidean" class="text-slate-300">
                        </thead>
                        <tbody id="output_body_euclidean">
                        </tbody>
                        <tfoot id="output_foot_euclidean">
                        </tfoot>
                    </table>
                </div>
                <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="btn">Close</button>
                </form>
                </div>
            </div>
            </dialog>

            <!-- Supremum -->
            <button class="btn btn-wide btn-outline btn-success hidden" onclick="output_supremum.showModal()" id="btn_supremum">Supremum</button>
            <dialog id="output_supremum" class="modal modal-bottom sm:modal-middle text-slate-300">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-5">Supremum</h3>
                <!-- Table -->
                <div class="overflow-auto">
                    <table class="table table-zebra table-xs table-pin-rows table-pin-cols">
                        <thead id="output_head_supremum" class="text-slate-300">
                        </thead>
                        <tbody id="output_body_supremum">
                        </tbody>
                        <tfoot id="output_foot_supremum">
                        </tfoot>
                    </table>
                </div>
                <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="btn">Close</button>
                </form>
                </div>
            </div>
            </dialog>
            <!-- Button download -->
            <button class="btn btn-wide btn-outline btn-warning hidden" onclick="download()" id="btn_download">Download as XLSX</button>
        </div>
    </div>

    <!-- VANTA JS -->
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
        let banyakData = 1;
        let isInit = false;
        let label, euclideanData, cityBlokData, supremumData;
        //* Sweet Alert
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

        const download = () => {
            label = JSON.stringify(label);
            let cityBlokDataJSON = JSON.stringify(cityBlokData);
            let euclideanDataJSON = JSON.stringify(euclideanData);
            let supremumDataJSON = JSON.stringify(supremumData);
            $.ajax({
                url: 'proximitySave.php',
                method: 'post',
                data: {
                    label: label,  
                    cityBlok: cityBlokDataJSON,
                    euclidean: euclideanDataJSON,
                    supremum: supremumDataJSON
                },
                success: function(data) {
                    window.location.href = data.file;
                    // Kasih alert
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil mengunduh hasil perhitungan PROXIMITY 😎'
                    });
                    console.log(data);
                }
            });
        }

        const submit = () => {
            var file_data = $('#fileInput').prop('files')[0];   
            var form_data = new FormData();                  
            form_data.append('file', file_data);

            console.log(form_data);
            //* VANTA SECTION
            $("#dividerInput").addClass("hidden");
            $("#input").addClass("hidden");

            if ($("#content").height() + 40 < window.innerHeight) {
                $("#body").addClass("starting-body");
            } else {
                $("#body").removeClass("starting-body");  // Biar height-nya gk 100vh dan vanta bisa responsive
            }
            vanta.resize();

            //* AJAX SECTION
            $.ajax({
                url: "php/proximity.php",
                method: "post",
                dataType: 'json',  // <-- what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(data) {
                    // console.log(data);
                    let euclidean = data.euclidean;  // 2d
                    let cityBlok = data.cityBlok; // 2d
                    let supremum = data.supremum; // 2d
                    euclideanData = euclidean;
                    cityBlokData = cityBlok;
                    supremumData = supremum;

                    label = data.label;
                    console.log(data);

                    // Kasih alert
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil menghitung Proximity 😄'
                    });

                    // Tampilkan output
                    $("#btn_city_blok").removeClass("hidden");
                    $("#btn_euclidean").removeClass("hidden");
                    $("#btn_supremum").removeClass("hidden");
                    $("#btn_download").removeClass("hidden");

                    // ---------------- City Blok ----------------
                    //* Input
                    let th = "<th></th>";

                    for (let i = 0; i < cityBlok.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_head_city_blok").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    //* Output
                    let items = "";
                    for (let i = 0; i < cityBlok.length; i++) {
                        let tr = `<tr class="text-center"><th class="text-lg">P${i+1}</th>`;
                        for (let j = 0; j < cityBlok[0].length; j++) {
                            tr += `<td class="text-center">${cityBlok[i][j]}</td>`;
                        }
                        tr += '</tr>';
                        items += tr;
                    }
                    $("#output_body_city_blok").html(items);

                    //* Foot
                    th = "<th></th>";

                    for (let i = 0; i < cityBlok.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_foot_city_blok").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);
                    
                    // ---------------- Euclidean ----------------
                    //* Input
                    th = "<th></th>";

                    for (let i = 0; i < euclidean.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_head_euclidean").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    //* Output
                    items = "";
                    for (let i = 0; i < euclidean.length; i++) {
                        let tr = `<tr class="text-center"><th class="text-lg">P${i+1}</th>`;
                        for (let j = 0; j < euclidean[0].length; j++) {
                            tr += `<td class="text-center">${euclidean[i][j]}</td>`;
                        }
                        tr += '</tr>';
                        items += tr;
                    }
                    $("#output_body_euclidean").html(items);

                    //* Foot
                    th = "<th></th>";

                    for (let i = 0; i < euclidean.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_foot_euclidean").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    // ---------------- Supremum ----------------
                    //* Input
                    th = "<th></th>";

                    for (let i = 0; i < supremum.length; i++) {
                        th += `<th class="text-center text-lg">P${i+ 1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_head_supremum").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    //* Output
                    items = "";
                    for (let i = 0; i < supremum.length; i++) {
                        let tr = `<tr class="text-center"><th class="text-lg">P${i+1}</th>`;
                        for (let j = 0; j < supremum[0].length; j++) {
                            tr += `<td class="text-center">${supremum[i][j]}</td>`;
                        }
                        tr += '</tr>';
                        items += tr;
                    }
                    $("#output_body_supremum").html(items);

                    //* Foot
                    th = "<th></th>";

                    for (let i = 0; i < supremum.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_foot_supremum").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);
                    //* Resize - VANTA SECTION
                    if ($("#content").height() + 40 < window.innerHeight) {
                        $("#body").addClass("starting-body");
                    } else {
                        $("#body").removeClass("starting-body");  // Biar height-nya gk 100vh dan vanta bisa responsive
                    }
                    vanta.resize();
                    // Scroll ke bawah
                    window.scrollTo(0, document.body.scrollHeight);
                }
            })
        }

        // TODO: Menentukkan jumlah titik yg ada (min: 2) dan banyak data (min: 1)
        const init = () => {
            let titik = parseInt($("#input_titik").val())
            banyakDataTemp = parseInt($("#input_banyak_data").val());

            //* Kalo titik-nya kurang dari 2, kasih alert
            if (titik < 2) return alert("Masukan titik minimal 2!");

            //* Kalo banyakDataTemp kurang dari 1, kasih alert
            if (banyakDataTemp < 1) return alert("Masukan banyak data minimal 1!");

            //* Kalo banyakDataTemp >= 1, ganti value banyakData
            banyakData = banyakDataTemp;

            //* Kalo jum titik dan banyak data valid, dan baru init. Perbarui value init
            if (!isInit) isInit = true;

            $("#dividerInput").removeClass("hidden");
            $("#input").removeClass("hidden");

            Toast.fire({
                icon: 'success',
                title: 'Sukses Inisialisasi!'
            })

            //* Clear HTML di div_data
            $("#div_data").html("");

            for (let i = 0; i < titik; i++) {
                //* Masukkan element
                $("#div_data").append(
                    `<input type"text" name="data_${i+1}" id="data_${i+1}" class="data_children input input-bordered input-primary w-full text-white" placeholder="P${i+1}">`
                );
            }

            // +40 karena padding atas + bawah = 40
            // $("canvas").height($("#content").height() + 40);
            // console.log($("canvas").height());
            // console.log($("#content").height());
            // Resize Vanta, kalo pake kode di atas kalo tinggi banget jdi crash
            //* Buat Responsive Canvas dari VANTA (+40 soalnya padding atas + bawah content => 40)
            if ($("#content").height() + 40 < window.innerHeight) {
                $("#body").addClass("starting-body");
            } else {
                $("#body").removeClass("starting-body");  // Biar height-nya gk 100vh dan vanta bisa responsive
            }
            vanta.resize();
        }

        const input = async() => {
            //* Kalo blm inisialisasi
            if (!isInit) {
                // return alert("Silahkan inisialisasi dulu!");
                Toast.fire({
                    icon: 'error',
                    title: 'Silahkan inisialisasi dulu! 🙄'
                })
                return;
            }

            //* Jumlah data
            let jumlah_data = $(".data_children").length
            let result = []  // 2D array yg akan dikirim
            
            for(let i = 1; i <= jumlah_data; i++) {
                let data = $(`#data_${i}`).val().trim();
                data = data.split(" ");
                
                //* Kalo banyak data dari input tidak sesuai dengan yg di-Init sblmnya
                if (data.length != banyakData) {
                    Toast.fire({
                        icon: 'warning',
                        title: `Banyak Data pada titik ke-${i} tidak sesuai! 🙄`
                    });
                    // return alert(`Banyak Data pada titik ke-${i} tidak sesuai`); 
                    return;
                }

                //* Cek data apakah berupa int atau bukan, kalo bukan kasih alert
                for (let j = 0; j < data.length; j++) {
                    if (!Number.isInteger(parseInt(data[j]))) {
                        // return alert(`Data ke-${j+1} pada titik ke-${i} bukan berupa angka!`);
                        Toast.fire({
                            icon: 'warning',
                            title: `Data ke-${j+1} pada titik ke-${i} bukan berupa angka! 😠`
                        });
                        return;
                    } else {
                        data[j] = parseInt(data[j]);
                    }
                }

                // console.log(data);
                result.push(data);
            }

            //* Jadiin result ke JSON
            let resultJson = await JSON.stringify(result);

            //* Kirim resultJson lewat AJAX
            await $.ajax({
                url: "./php/proximity.php",
                type: "POST",
                data: {
                    data: resultJson
                },
                success: function (data) {
                    let euclidean = data.euclidean;  // 2d
                    let cityBlok = data.cityBlok; // 2d
                    let supremum = data.supremum; // 2d
                    euclideanData = euclidean;
                    cityBlokData = cityBlok;
                    supremumData = supremum;

                    console.log(data);
                    label = data.label;

                    // Kasih alert
                    Toast.fire({
                        icon: 'success',
                        title: 'Berhasil menghitung Proximity 😄'
                    });

                    // Tampilkan output
                    $("#btn_city_blok").removeClass("hidden");
                    $("#btn_euclidean").removeClass("hidden");
                    $("#btn_supremum").removeClass("hidden");
                    $("#btn_download").removeClass("hidden");

                    // ---------------- City Blok ----------------
                    //* Input
                    let th = "<th></th>";

                    for (let i = 0; i < cityBlok.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_head_city_blok").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    //* Output
                    let items = "";
                    for (let i = 0; i < cityBlok.length; i++) {
                        let tr = `<tr class="text-center"><th class="text-lg">P${i+1}</th>`;
                        for (let j = 0; j < cityBlok[0].length; j++) {
                            tr += `<td class="text-center">${cityBlok[i][j]}</td>`;
                        }
                        tr += '</tr>';
                        items += tr;
                    }
                    $("#output_body_city_blok").html(items);

                    //* Foot
                    th = "<th></th>";

                    for (let i = 0; i < cityBlok.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_foot_city_blok").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);
                    
                    // ---------------- Euclidean ----------------
                    //* Input
                    th = "<th></th>";

                    for (let i = 0; i < euclidean.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_head_euclidean").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    //* Output
                    items = "";
                    for (let i = 0; i < euclidean.length; i++) {
                        let tr = `<tr class="text-center"><th class="text-lg">P${i+1}</th>`;
                        for (let j = 0; j < euclidean[0].length; j++) {
                            tr += `<td class="text-center">${euclidean[i][j]}</td>`;
                        }
                        tr += '</tr>';
                        items += tr;
                    }
                    $("#output_body_euclidean").html(items);

                    //* Foot
                    th = "<th></th>";

                    for (let i = 0; i < euclidean.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_foot_euclidean").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    // ---------------- Supremum ----------------
                    //* Input
                    th = "<th></th>";

                    for (let i = 0; i < supremum.length; i++) {
                        th += `<th class="text-center text-lg">P${i+ 1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_head_supremum").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);

                    //* Output
                    items = "";
                    for (let i = 0; i < supremum.length; i++) {
                        let tr = `<tr class="text-center"><th class="text-lg">P${i+1}</th>`;
                        for (let j = 0; j < supremum[0].length; j++) {
                            tr += `<td class="text-center">${supremum[i][j]}</td>`;
                        }
                        tr += '</tr>';
                        items += tr;
                    }
                    $("#output_body_supremum").html(items);

                    //* Foot
                    th = "<th></th>";

                    for (let i = 0; i < supremum.length; i++) {
                        th += `<th class="text-center text-lg">P${i+1}</th>`;
                    }
                    th += '<th></th>';

                    $("#output_foot_supremum").html(`
                        <tr>
                            ${th}
                        </tr>
                    `);
                    if ($("#content").height() + 40 < window.innerHeight) {
                        $("#body").addClass("starting-body");
                    } else {
                        $("#body").removeClass("starting-body");  // Biar height-nya gk 100vh dan vanta bisa responsive
                    }
                    vanta.resize();

                    // +40 karena padding atas + bawah = 40
                    // $("canvas").height($("#content").height() + 40);
                    // console.log($("canvas").height());
                    // console.log($("#content").height());
                    // Resize Vanta, kalo pake kode di atas kalo tinggi banget jdi crash
                    // vanta.resize();
                }
            });

            // Scroll ke bawah
            window.scrollTo(0, document.body.scrollHeight);
        };

        // const coba = () => {
        //     $.ajax({
        //         url: "../php/proximity.php",
        //         type: "POST",
        //         success: function (data) {
        //             $("#output").html(data.message);
        //         }
        //     });
        // };
    </script>
        <!--Start of Tawk.to Script-->
        <!--End of Tawk.to Script-->
</body>
</html>