let totalSync   = 0;
let currentSync = 0;
let batchSize   = 500;
let startTime   = null;

datarrjdetail();
quadrantdokter();
quadrantsmf();
quadrantresource();
datadetailtidakadasep();

$('#selectperiode').on('change', function () {
    datarrjdetail();
    quadrantdokter();
    quadrantsmf();
});

$("#modal_upload_bahv").on("show.bs.modal", function () {
    $("#filebahv").val("");
    dataBahv = [];
    $("#jmlDataBahv").text("0");
    $("#totalLayakBahv").text("0");
    $("#totalTidakLayakBahv").text("0");
    $("#resultpreviewbahv").empty();
});

$("#modal_upload_farmasi").on("show.bs.modal", function () {
    $("#filefarmasi").val("");

    dataFarmasi = [];

    $("#jmlDataFarmasi").text("0");
    $("#totalNilaiFarmasi").text("0");
    $("#resultpreviewfarmasi").empty();
});

$("#btnImportBahv").on("click", function () {

    if (dataBahv.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Data Available",
            text: "Please select and import an Excel file before proceeding."
        });
        return;
    }

    Swal.fire({
        title: "Import Data BAHV",
        text: "Are you sure you want to import " + dataBahv.length.toLocaleString("id-ID") + " records?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Import",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#0d6efd", // Biru (Bootstrap Primary)
        cancelButtonColor: "#6c757d"   // Abu-abu (Bootstrap Secondary)
    }).then(function (result) {
        if (result.isConfirmed) {
            prosesImportBahv();
        }
    });
});

$("#btnImportFarmasi").on("click", function () {
    if (dataFarmasi.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Data Available",
            text: "Please select and preview an Excel file before proceeding."
        });
        return;
    }
    Swal.fire({
        title: "Import Data Klaim Farmasi",
        html: `
            Are you sure you want to import
            <strong>${dataFarmasi.length.toLocaleString("id-ID")}</strong>
            records?
        `,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Import",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#6c757d"
    }).then(function (result) {
        if (result.isConfirmed) {
            prosesImportFarmasi();
        }

    });
});

$("#btnImportEklaim").on("click", function () {

    if (dataEklaim.length === 0) {

        Swal.fire({
            icon: "warning",
            title: "No Data Available",
            text: "Please select and preview an Excel file before proceeding."
        });

        return;

    }

    Swal.fire({

        title: "Import Data E-Klaim",

        html: `
            Are you sure you want to import
            <strong>${dataEklaim.length.toLocaleString("id-ID")}</strong>
            records?
        `,

        icon: "question",

        showCancelButton: true,

        confirmButtonText: "Yes, Import",

        cancelButtonText: "Cancel",

        confirmButtonColor: "#0d6efd",

        cancelButtonColor: "#6c757d"

    }).then(function (result) {

        if (result.isConfirmed) {

            prosesImportEklaim();

        }

    });

});

$("#filebahv").on("change", function () {
    const file = this.files[0];

    if(!file){return;}

    dataBahv = [];

    $("#resultpreviewbahv").html("");
    $("#jmlDataBahv").text("0");
    $("#totalLayakBahv").text("0");
    $("#totalTidakLayakBahv").text("0");


    const reader = new FileReader();
    reader.onload = function(e){
        const workbook = XLSX.read(e.target.result,{type:"array"});
        const sheet    = workbook.Sheets[workbook.SheetNames[0]];
        const rows     = XLSX.utils.sheet_to_json(sheet,{header:1,defval:""});

        let headerRow = -1;
        let idxSep    = -1;
        let idxStatus = -1;


        for(let i=0;i<rows.length;i++){
            let header    = rows[i].map(normalizeHeader);
                idxSep    = header.indexOf("NOSEP");
                idxStatus = header.indexOf("BAHV");

            if(idxSep !== -1 && idxStatus !== -1){
                headerRow=i;
                break;
            }
        }


        if(headerRow === -1){
            Swal.fire({
                icon: "error",
                title: "Invalid Excel Format",
                html: `
                    <div class="text-start">
                        <p class="mb-3">
                            The uploaded Excel file does not match the required template.
                            Please ensure the following column headers are present:
                        </p>
                        <ul class="mb-3">
                            <li><strong>NO SEP</strong></li>
                            <li><strong>BAHV</strong></li>
                        </ul>
                        <p class="mb-0 text-muted">
                            Please use the correct template and try uploading the file again.
                        </p>
                    </div>
                `
            });

            $("#filebahv").val("");
            return;
        }

        let totalData = 0;
        for (let i = headerRow + 1; i < rows.length; i++) {
            let noSep = String(rows[i][idxSep] || "").trim();
            if (noSep !== "") {
                totalData++;
            }
        }

        Swal.fire({
            title:"Preparing Data",
            html: `
                <div class="text-center">
                    <div class="fs-5 mb-3">
                        Please wait...
                    </div>
                    <div class="fs-3 fw-bold text-primary">
                        <span id="swalProgress">0</span> /
                        ${totalData.toLocaleString("id-ID")}
                    </div>
                    <div class="progress mt-3">
                        <div id="swalProgressBar"class="progress-bar progress-bar-striped progress-bar-animated bg-primary"style="width:0%"></div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <div>
                            Processing Speed:
                            <span id="swalSpeed">0</span> records/sec
                        </div>
                        <div>
                            Estimated Time Remaining:
                            <span id="swalEta">Calculating...</span>
                        </div>
                    </div>
                </div>
            `,
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen          : function () {
                Swal.showLoading();

                let html            = "";
                let current         = headerRow + 1;
                let processed       = 0;
                let totalLayak      = 0;
                let totalTidakLayak = 0;
                let startTime       = Date.now();

                function processChunk() {
                    let chunk = 0;

                    while (current < rows.length && chunk < 100) {

                        let row   = rows[current];
                        let noSep = String(row[idxSep] || "").trim().toUpperCase();
                        let bahv  = String(row[idxStatus] || "").trim().toUpperCase();

                        if (noSep !== "") {

                            processed++;

                            if(bahv === "Y" || bahv === "LAYAK"){
                                totalLayak++;
                            }else if(bahv === "T" || bahv === "TIDAK LAYAK"){
                                totalTidakLayak++;
                            }

                            let statusBahv = "";

                            if(bahv === "Y" || bahv === "LAYAK"){
                                statusBahv = "<span class='badge badge-light-success'>Layak</span>";
                            }else if(bahv === "T" || bahv === "TIDAK LAYAK") {
                                statusBahv = "<span class='badge badge-light-danger'>Tidak Layak</span>";
                            }else{
                                statusBahv = "<span class='badge badge-light-warning'>" + bahv + "</span>";
                            }

                            dataBahv.push({
                                NO_SEP: noSep,
                                BAHV  : bahv
                            });

                            html += `
                                <tr>
                                    <td class="ps-4">${processed}</td>
                                    <td class="fw-bold">${noSep}</td>
                                    <td class="text-end pe-4">${statusBahv}</td>
                                </tr>
                            `;
                        }

                        current++;
                        chunk++;
                    }


                    let percent = totalData > 0 ? Math.min((processed / totalData) * 100, 100) : 100;

                    $("#swalProgress").text(processed.toLocaleString("id-ID"));
                    $("#swalProgressBar").css("width", percent + "%").attr("aria-valuenow", percent);


                    let elapsed = (Date.now() - startTime) / 1000;
                    let speed = elapsed > 0 ? processed / elapsed : 0;
                    $("#swalSpeed").text(speed.toFixed(2));


                    let remaining = totalData - processed;
                    let eta = speed > 0 ? remaining / speed : 0;
                    $("#swalEta").text(remaining > 0 ? formatDuration(eta) : "Completed");


                    if(current < rows.length){
                        requestAnimationFrame(processChunk);
                    }else{
                        $("#swalProgress").text(totalData.toLocaleString("id-ID"));
                        $("#swalProgressBar").css("width", "100%").attr("aria-valuenow", 100);
                        $("#swalSpeed").text(speed.toFixed(2));
                        $("#swalEta").text("Completed");
                        $("#resultpreviewbahv").html(html);
                        $("#jmlDataBahv").text(processed.toLocaleString("id-ID"));
                        $("#totalLayakBahv").text(totalLayak.toLocaleString("id-ID"));
                        $("#totalTidakLayakBahv").text(totalTidakLayak.toLocaleString("id-ID"));

                        setTimeout(function () {
                            Swal.fire({
                                icon: "success",
                                title: "Preview Ready",
                                html: `
                                    <div class="text-center">
                                        <div class="fs-5 mb-2">
                                            The file has been processed successfully.
                                        </div>
                                        <div class="fs-2 fw-bold text-primary">
                                            ${processed.toLocaleString("id-ID")}
                                        </div>
                                        <div class="text-muted">
                                            records are ready for import.
                                        </div>
                                    </div>
                                `,
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        }, 150);
                    }
                }
                processChunk();
            }
        });
    };
    reader.readAsArrayBuffer(file);
});

$("#filefarmasi").on("change", function () {
    const file = this.files[0];

    if(!file){return;}

    dataFarmasi = [];

    $("#resultpreviewfarmasi").html("");
    $("#jmlDataFarmasi").text("0");
    $("#totalNilaiFarmasi").text("0");

    const reader = new FileReader();

    reader.onload = function (e) {
        const workbook = XLSX.read(e.target.result, {type: "array"});
        const sheet    = workbook.Sheets[workbook.SheetNames[0]];
        const rows     = XLSX.utils.sheet_to_json(sheet, {header: 1,defval: ""});

        let headerRow = -1;
        let idxSep    = -1;
        let idxBiaya  = -1;

        for (let i = 0; i < rows.length; i++) {
            let header = rows[i].map(normalizeHeader);

            idxSep   = header.indexOf("NOSEP");
            idxBiaya = header.indexOf("BIAYADISETUJUI");

            if (idxSep !== -1 && idxBiaya !== -1) {
                headerRow = i;
                break;
            }
        }

        if (headerRow === -1) {
            Swal.fire({
                icon: "error",
                title: "Invalid Excel Format",
                html: `
                    <div class="text-start">
                        <p class="mb-3">
                            The uploaded Excel file does not match the required template.
                            Please ensure the following column headers are present:
                        </p>
                        <ul class="mb-3">
                            <li><strong>NO SEP</strong></li>
                            <li><strong>BIAYA DISETUJUI</strong></li>
                        </ul>
                        <p class="mb-0 text-muted">
                            Please use the correct template and try uploading the file again.
                        </p>
                    </div>
                `
            });

            $("#filefarmasi").val("");
            return;
        }

        let totalData = 0;
        for (let i = headerRow + 1; i < rows.length; i++) {
            let noSep = String(rows[i][idxSep] || "").trim();

            if (noSep !== "") {
                totalData++;
            }
        }

        Swal.fire({
            title: "Preparing Data",
            html: `
                <div class="text-center">
                    <div class="fs-5 mb-3">
                        Please wait...
                    </div>
                    <div class="fs-3 fw-bold text-primary">
                        <span id="swalProgress">0</span> /
                        ${totalData.toLocaleString("id-ID")}
                    </div>
                    <div class="progress mt-3">
                        <div
                            id="swalProgressBar"
                            class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            style="width:0%">
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <div>
                            Processing Speed :
                            <span id="swalSpeed">0</span> records/sec
                        </div>
                        <div>
                            Estimated Time Remaining :
                            <span id="swalEta">Calculating...</span>
                        </div>
                    </div>
                </div>
            `,

            allowOutsideClick: false,
            showConfirmButton: false,

            didOpen: function () {
                Swal.showLoading();

                let html       = "";
                let current    = headerRow + 1;
                let processed  = 0;
                let totalNilai = 0;
                let startTime  = Date.now();

                function processChunk() {
                    let chunk = 0;

                    while (current < rows.length && chunk < 100) {
                        let row   = rows[current];
                        let noSep = String(row[idxSep] || "").trim().toUpperCase();
                        let biaya = parseFloat(String(row[idxBiaya] || "0").replace(/\./g, "").replace(",", ".")) || 0;

                        if (noSep !== "") {
                            processed++;
                            totalNilai += biaya;

                            dataFarmasi.push({
                                NO_SEP         : noSep,
                                BIAYA_DISETUJUI: biaya
                            });

                            html += `
                                <tr>
                                    <td class="ps-4">${processed}</td>
                                    <td class="fw-bold">
                                        ${noSep}
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-success">
                                        ${biaya.toLocaleString("id-ID")}
                                    </td>
                                </tr>
                            `;
                        }

                        current++;
                        chunk++;
                    }

                    let percent = totalData > 0 ? Math.min((processed / totalData) * 100, 100) : 100;
                    $("#swalProgress").text(processed.toLocaleString("id-ID"));
                    $("#swalProgressBar").css("width", percent + "%").attr("aria-valuenow", percent);

                    let elapsed = (Date.now() - startTime) / 1000;
                    let speed   = elapsed > 0 ? processed / elapsed : 0;
                    $("#swalSpeed").text(speed.toFixed(2));

                    let remaining = totalData - processed;
                    let eta       = speed > 0 ? remaining / speed : 0;
                    $("#swalEta").text(remaining > 0 ? formatDuration(eta) : "Completed");

                    if (current < rows.length) {
                        requestAnimationFrame(processChunk);
                    } else {

                        $("#swalProgress").text(totalData.toLocaleString("id-ID"));
                        $("#swalProgressBar").css("width", "100%").attr("aria-valuenow", 100);
                        $("#swalSpeed").text(speed.toFixed(2));
                        $("#swalEta").text("Completed");

                        $("#resultpreviewfarmasi").html(html);
                        $("#jmlDataFarmasi").text(processed.toLocaleString("id-ID"));
                        $("#totalNilaiFarmasi").text(totalNilai.toLocaleString("id-ID"));

                        setTimeout(function () {
                            Swal.fire({
                                icon: "success",
                                title: "Preview Ready",
                                html: `
                                    <div class="text-center">
                                        <div class="fs-5 mb-2">
                                            The file has been processed successfully.
                                        </div>
                                        <div class="fs-2 fw-bold text-primary">
                                            ${processed.toLocaleString("id-ID")}
                                        </div>
                                        <div class="text-muted">
                                            records are ready for import.
                                        </div>
                                        <div class="mt-3">
                                            <span class="badge badge-light-success fs-6">
                                                Total Nilai :
                                                ${totalNilai.toLocaleString("id-ID")}
                                            </span>
                                        </div>
                                    </div>
                                `,
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        }, 150);
                    }
                }
                processChunk();
            }
        });
    };
    reader.readAsArrayBuffer(file);
});

$("#fileeklaim").on("change", function () {

    const file = this.files[0];

    if (!file) return;

    dataEklaim = [];

    $("#resultprevieweklaim").html("");
    $("#jmlDataEklaim").text("0");
    $("#totalNilaiEklaim").text("0");

    const reader = new FileReader();

    reader.onload = function (e) {

        const workbook = XLSX.read(e.target.result, { type: "array" });
        const sheet    = workbook.Sheets[workbook.SheetNames[0]];
        const rows     = XLSX.utils.sheet_to_json(sheet, {header: 1,defval: ""});

        let headerRow = -1;
        let idxSep    = -1;
        let idxIna    = -1;

        for (let i = 0; i < rows.length; i++) {

            const header = rows[i].map(normalizeHeader);

            idxSep = header.indexOf("NOSEP");
            idxIna = header.indexOf("NILAIINACBG");

            if (idxSep !== -1 && idxIna !== -1) {
                headerRow = i;
                break;
            }

        }

        if (headerRow === -1) {

            Swal.fire({
                icon: "error",
                title: "Invalid Excel Format",
                html: `
                    <div class="text-start">
                        <p class="mb-3">
                            The uploaded Excel file does not match the required template.
                        </p>
                        <p class="mb-2">
                            Required columns:
                        </p>
                        <ul>
                            <li><strong>NO SEP</strong></li>
                            <li><strong>NILAI INACBG</strong></li>
                        </ul>
                    </div>
                `
            });

            $("#fileeklaim").val("");
            return;
        }

        let totalData = 0;

        for (let i = headerRow + 1; i < rows.length; i++) {

            if (String(rows[i][idxSep] || "").trim() !== "") {
                totalData++;
            }

        }

        Swal.fire({

            title: "Preparing Data",
            html: `
                <div class="text-center">
                    <div class="fs-5 mb-3">
                        Please wait...
                    </div>
                    <div class="fs-3 fw-bold text-primary">
                        <span id="swalProgress">0</span> /
                        ${totalData.toLocaleString("id-ID")}
                    </div>
                    <div class="progress mt-3">
                        <div
                            id="swalProgressBar"
                            class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                            style="width:0%">
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <div>
                            Processing Speed :
                            <span id="swalSpeed">0</span>
                            records/sec
                        </div>
                        <div>
                            Estimated Time Remaining :
                            <span id="swalEta">Calculating...</span>
                        </div>
                    </div>
                </div>
            `,

            allowOutsideClick: false,
            showConfirmButton: false,

            didOpen: function () {

                Swal.showLoading();

                let current = headerRow + 1;
                let processed = 0;
                let totalNilai = 0;
                let html = "";

                const startTime = Date.now();

                function processChunk() {

                    let chunk = 0;

                    while (current < rows.length && chunk < 100) {
                        const row   = rows[current];
                        const noSep = String(row[idxSep] || "").trim().toUpperCase();
                        const nilai = parseFloat(String(row[idxIna] || "0").replace(/\./g, "").replace(",", ".")) || 0;

                        if (noSep !== "") {

                            processed++;
                            totalNilai += nilai;
                            dataEklaim.push({
                                NO_SEP      : noSep,
                                NILAI_INACBG: nilai
                            });

                            html += `
                                <tr>
                                    <td class="ps-4">${processed}</td>
                                    <td class="fw-bold">
                                        ${noSep}
                                    </td>
                                    <td class="text-end pe-4 fw-bold text-success">
                                        ${nilai.toLocaleString("id-ID")}
                                    </td>
                                </tr>
                            `;
                        }

                        current++;
                        chunk++;

                    }

                    const percent = totalData > 0 ? (processed / totalData) * 100 : 100;

                    $("#swalProgress").text(
                        processed.toLocaleString("id-ID")
                    );

                    $("#swalProgressBar")
                        .css("width", percent + "%");

                    const elapsed = (Date.now() - startTime) / 1000;

                    const speed = elapsed > 0
                        ? processed / elapsed
                        : 0;

                    $("#swalSpeed").text(speed.toFixed(2));

                    const remaining = totalData - processed;

                    const eta = speed > 0
                        ? remaining / speed
                        : 0;

                    $("#swalEta").text(
                        remaining > 0
                            ? formatDuration(eta)
                            : "Completed"
                    );

                    if (current < rows.length) {

                        requestAnimationFrame(processChunk);

                    } else {

                        $("#resultprevieweklaim").html(html);

                        $("#jmlDataEklaim").text(
                            processed.toLocaleString("id-ID")
                        );

                        $("#totalNilaiEklaim").text(
                            totalNilai.toLocaleString("id-ID")
                        );

                        setTimeout(function () {

                            Swal.fire({

                                icon: "success",

                                title: "Preview Ready",

                                html: `
                                    <div class="text-center">

                                        <div class="fs-5 mb-2">
                                            The file has been processed successfully.
                                        </div>

                                        <div class="fs-2 fw-bold text-primary">
                                            ${processed.toLocaleString("id-ID")}
                                        </div>

                                        <div class="text-muted">
                                            records are ready for import.
                                        </div>

                                        <div class="mt-3">
                                            <span class="badge badge-light-success fs-6">
                                                Total Nilai :
                                                ${totalNilai.toLocaleString("id-ID")}
                                            </span>
                                        </div>

                                    </div>
                                `,

                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false

                            });

                        }, 150);

                    }

                }

                processChunk();

            }

        });

    };

    reader.readAsArrayBuffer(file);

});

function formatDuration(seconds) {
    seconds = Math.max(0, Math.round(seconds));

    let h = Math.floor(seconds / 3600);
    let m = Math.floor((seconds % 3600) / 60);
    let s = seconds % 60;

    if (h > 0) {return h + " hour " + m + " minute " + s + " second";}
    if (m > 0) {return m + " minute " + s + " second";}
    return s + " second";
}

function normalizeHeader(text) {
    return String(text).replace(/^\uFEFF/, "").trim().toUpperCase().replace(/[\s\-_]+/g, "");
}

function datarrjdetail(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/urbpjs/rawatjalan/datarrjdetail",
        type     : "POST",
        dataType : "JSON",
        data     : {selectperiode:selectperiode},

        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });
        },

        success: function (response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon             : 'warning',
                    title            : 'No Records Found',
                    text             : 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer            : 2000
                });
                return;
            }

            const result             = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap       = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan          = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            const mapkunjungan       = {};
            const maptarifrs         = {};
            const mapinacbg          = {};
            const mapkasuspending    = {};
            const mapnilaipending    = {};
            const mapkasustidaklayak = {};
            const mapnilaitidaklayak = {};

            var tableresult            = "";
            let totaljmlkunjungan      = 0;
            let totaljmlunsep          = 0;
            let totaljmlsep            = 0;
            let totaljmlgrouping       = 0;
            let totaljmlungrouping     = 0;
            let totaljmlbahvpending    = 0;
            let totaljmlbahvlayak      = 0;
            let totaljmlbahvtidaklayak = 0;

            let totalnilaitarifrs        = 0;
            let totalnilaiunsep          = 0;
            let totalnilaiungrouping     = 0;
            let totalnilaigrouping       = 0;
            let totalnilaiabd            = 0;
            let totalnilaifarmasi        = 0;
            let totalselisih             = 0;
            let totalnilaibahvpending    = 0;
            let totalnilaibahvlayak      = 0;
            let totalnilaibahvtidakLayak = 0;

            for (var i in result) {
                let jmlkunjungan   = Number(result[i].JUMLAH_KUNJUNGAN) || 0;
                let jmlunsep       = Number(result[i].JUMLAH_UN_SEP) || 0;
                let jmlsep         = Number(result[i].JUMLAH_SEP) || 0;
                let jmlgrouping    = Number(result[i].JUMLAH_GROUPING) || 0;
                let jmlungrouping  = Number(result[i].JUMLAH_UN_GROUPING) || 0;
                let bahvpending    = Number(result[i].JUMLAH_BAHV_N) || 0;
                let bahvlayak      = Number(result[i].JUMLAH_BAHV_Y) || 0;
                let bahvtidaklayak = Number(result[i].JUMLAH_BAHV_T) || 0;

                let nilaitarifrs        = Number(result[i].TOTAL_TARIF_RS) || 0;
                let nilaiunsep          = Number(result[i].NILAI_UN_SEP) || 0;
                let nilaiungrouping     = Number(result[i].NILAI_UN_GROUPING) || 0;
                let nilaigrouping       = Number(result[i].NILAI_GROUPING) || 0;
                let nilaiabd            = Number(result[i].NILAI_ABD) || 0;
                let nilaifarmasi        = Number(result[i].NILAI_FARMASI) || 0;
                let nilaibahvpending    = Number(result[i].NILAI_BAHV_N) || 0;
                let nilaibahvlayak      = Number(result[i].NILAI_BAHV_Y) || 0;
                let nilaibahvtidaklayak = Number(result[i].NILAI_BAHV_T) || 0;

                let selisih    = (nilaigrouping+nilaiabd+nilaifarmasi)-nilaitarifrs;
                let clsSelisih = selisih < 0 ? "text-danger fw-bold" : "text-success fw-bold";

                totaljmlkunjungan      += jmlkunjungan;
                totaljmlunsep          += jmlunsep;
                totaljmlsep            += jmlsep;
                totaljmlgrouping       += jmlgrouping;
                totaljmlungrouping     += jmlungrouping;
                totaljmlbahvpending    += bahvpending;
                totaljmlbahvlayak      += bahvlayak;
                totaljmlbahvtidaklayak += bahvtidaklayak;

                totalnilaitarifrs        += nilaitarifrs;
                totalnilaiunsep          += nilaiunsep;
                totalnilaiungrouping     += nilaiungrouping;
                totalnilaigrouping       += nilaigrouping;
                totalnilaiabd            += nilaiabd;
                totalnilaifarmasi        += nilaifarmasi;
                totalnilaibahvpending    += nilaibahvpending;
                totalnilaibahvlayak      += nilaibahvlayak;
                totalnilaibahvtidakLayak += nilaibahvtidaklayak;

                totalselisih    += selisih;

                mapkunjungan[result[i].BULAN]       = jmlkunjungan;
                maptarifrs[result[i].BULAN]         = nilaitarifrs;
                mapinacbg[result[i].BULAN]          = nilaigrouping+nilaiabd+nilaifarmasi;
                mapkasuspending[result[i].BULAN]    = bahvpending;
                mapnilaipending[result[i].BULAN]    = nilaibahvpending;
                mapkasustidaklayak[result[i].BULAN] = bahvtidaklayak;
                mapnilaitidaklayak[result[i].BULAN] = nilaibahvtidaklayak;

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>" + (result[i].BULAN_LAYANAN || "") + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(jmlkunjungan) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaitarifrs) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaigrouping) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaiabd) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaifarmasi) + "</td>";
                tableresult += "<td class='text-end'><span class='" + clsSelisih + "'>" + todesimal(selisih) + "</span></td>";
                tableresult += "<td class='text-end'>" + todesimal(bahvpending) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaibahvpending) + "</td>";
                tableresult += "<td class='text-end'>" + (nilaitarifrs > 0 ? ((nilaibahvpending / nilaitarifrs) * 100).toFixed(4) : "0.00") + "%</td>";
                tableresult += "<td class='text-end'>" + todesimal(bahvlayak) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaibahvlayak) + "</td>";
                tableresult += "<td class='text-end'>" + (nilaitarifrs > 0 ? ((nilaibahvlayak / nilaitarifrs) * 100).toFixed(4) : "0.00") + "%</td>";
                tableresult += "<td class='text-end'>" + todesimal(bahvtidaklayak) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaibahvtidaklayak) + "</td>";
                tableresult += "<td class='text-end'>" + (nilaitarifrs > 0 ? ((nilaibahvtidaklayak / nilaitarifrs) * 100).toFixed(4) : "0.00") + "%</td>";
                tableresult += "</tr>";
            }

            let coverage = totalnilaitarifrs > 0 ? (((totalnilaigrouping+totalnilaiabd+totalnilaifarmasi) / totalnilaitarifrs) * 100) : 0;

            $("#resultdataurrjdetail").html(tableresult);

            //ROW 1
            $("#jmlkunjungan").text(todesimal(totaljmlkunjungan));
            $("#jmlsepbelum").text(todesimal(totaljmlunsep)+" / "+todesimal(totalnilaiunsep));
            $("#jmlsep").text(todesimal(totaljmlsep));
            $("#jmlbelumgrouping").text(todesimal(totaljmlungrouping)+" / "+todesimal(totalnilaiungrouping));
            $("#jmlgrouping").text(todesimal(totaljmlgrouping));
            

            //ROW 2
            $("#totaltarifrs").text(todesimal(totalnilaitarifrs));
            $("#totalinacbg").text(todesimal(totalnilaigrouping));
            $("#totalabd").text(todesimal(totalnilaiabd));
            $("#totalfarmasi").text(todesimal(totalnilaifarmasi));
            $("#totalselisih").text(todesimal(totalselisih)).removeClass("text-success text-danger").addClass(totalselisih < 0 ? "text-danger" : "text-success");
            $("#coverage").text(coverage.toFixed(4));

            //ROW 3
            let presentasipending    = totalnilaigrouping > 0 ? ((totalnilaibahvpending / totalnilaigrouping) * 100) : 0;
            let presentasilayak      = totalnilaigrouping > 0 ? ((totalnilaibahvlayak / totalnilaigrouping) * 100) : 0;
            let presentasitidaklayak = totalnilaigrouping > 0 ? ((totalnilaibahvtidakLayak / totalnilaigrouping) * 100) : 0;

            $("#pending_kasus").text(todesimal(totaljmlbahvpending));
            $("#pending_nilai").text(todesimal(totalnilaibahvpending));
            $("#pending_persen").text(presentasipending.toFixed(2));

            $("#layak_kasus").text(todesimal(totaljmlbahvlayak));
            $("#layak_nilai").text(todesimal(totalnilaibahvlayak));
            $("#layak_persen").text(presentasilayak.toFixed(2));

            $("#tidaklayak_kasus").text(todesimal(totaljmlbahvtidaklayak));
            $("#tidaklayak_nilai").text(todesimal(totalnilaibahvtidakLayak));
            $("#tidaklayak_persen").text(presentasitidaklayak.toFixed(2));

            const chartdatakunjungan = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : mapkunjungan[b] ?? 0
            }));

            const chartdatatarif = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : maptarifrs[b] || 0,
                value2 : mapinacbg[b] || 0
            }));

            const chartdatapending = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : mapkasuspending[b] || 0,
                value2 : mapnilaipending[b] || 0
            }));

            const chartdatatidaklayak = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : mapkasustidaklayak[b] || 0,
                value2 : mapnilaitidaklayak[b] || 0
            }));

            renderchartarea("trenkunjungan",chartdatakunjungan,"Periode Pelayanan","Jumlah Kunjungan",["Jumlah Kunjungan"],["value1"],null,"","value1","Rata-rata Kunjungan",null);
            renderChartbarline("perbadingantarif",chartdatatarif,"Periode Pelayanan","Nilai Billing (Rp)","Nilai Klaim (Rp)","Nilai Billing","Nilai Klaim (Rp)");
            renderChartbarline("klaimpending",chartdatapending,"Periode Pelayanan","Jumlah Kasus","Nilai Klaim (Rp)","Jumlah Kasus","Nilai Klaim (Rp)");
            renderChartbarline("klaimtidaklayak",chartdatatidaklayak,"Periode Pelayanan","Jumlah Kasus","Nilai Klaim (Rp)","Jumlah Kasus","Nilai Klaim (Rp)");
        },

        complete: function () {
            Swal.close();
        },

        error: function () {
            Swal.fire({
                icon             : "error",
                title            : "Request Failed",
                text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
}

function quadrantdokter(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/urbpjs/rawatjalan/quadrantdokter",
        type     : "POST",
        dataType : "JSON",
        data     : {selectperiode:selectperiode},

        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataquadrant").empty();
        },

        success: function (response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon             : 'warning',
                    title            : 'No Records Found',
                    text             : 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer            : 2000
                });
                return;
            }

            const result = Array.isArray(response.responResult) ? response.responResult : [];

            const seriesData = result.map(item => ({
                x      : Number(item.SELISIH) || 0,
                y      : Number(item.JUMLAH_KUNJUNGAN) || 0,
                nama   : item.NAMADOKTER,
                dokter : item.DOKTER_ID,
                tarifrs: Number(item.TOTAL_TARIF_RS) || 0,
                inacbg : (Number(item.NILAI_GROUPING) || 0) + (Number(item.NILAI_ABD) || 0) + (Number(item.NILAI_FARMASI) || 0)

            }));

            const minRevenue = Math.min(
                ...seriesData.map(item => item.x)
            );

            const maxRevenue = Math.max(
                ...seriesData.map(item => item.x)
            );

            const revenueMin = minRevenue < 0 ? Math.floor(minRevenue / 50000000) * 50000000 : 0;
            const revenueMax = maxRevenue > 0 ? Math.ceil(maxRevenue / 50000000) * 50000000 : 0;

            const minPasien = Math.min(
                ...seriesData.map(item => item.y)
            );

            const maxPasien = Math.max(
                ...seriesData.map(item => item.y)
            );

            const patientLine = (minPasien + maxPasien) / 2;

            const patientRange = Math.max(
                patientLine - minPasien,
                maxPasien - patientLine
            );

            const patientScale = Math.ceil(patientRange * 1.10);

            seriesData.forEach(item => {
                item.crr = item.tarifrs > 0 ? (item.inacbg / item.tarifrs) * 100 : 0;
            });

            seriesData.sort((a, b) => b.crr - a.crr);

            let html = "";
            seriesData.forEach((item, index) => {

                let quadrant = "";
                let badge = "";

                if (item.x >= 0 && item.y >= patientLine) {
                    quadrant = "Q1";
                    badge    = "success";
                } else if (item.x >= 0 && item.y < patientLine) {
                    quadrant = "Q2";
                    badge    = "primary";
                } else if (item.x < 0 && item.y < patientLine) {
                    quadrant = "Q3";
                    badge    = "danger";
                } else {
                    quadrant = "Q4";
                    badge    = "warning";
                }

                html += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td>${item.nama}</td>
                                <td class="text-center">${todesimal(item.y)}</td>
                                <td class="text-end">${todesimal(item.tarifrs)}</td>
                                <td class="text-end">${todesimal(item.inacbg)}</td>
                                <td class="text-end fw-bold ${item.x >= 0 ? "text-success" : "text-danger"}">${item.x >= 0 ? "+" : ""}${todesimal(item.x)}</td>
                                <td class="text-center fw-bold">${item.crr.toFixed(2)}%</td>
                                <td class="text-end pe-4"><span class="badge badge-light-${badge}">${quadrant}</span></td>
                            </tr>
                        `;
            });

            $("#resultdataquadrant").html(html);

            const table = initDataTable("#dataquadrant_table","#searchtable");

            renderquadrant("#chartquadrant",seriesData,0,patientLine,patientScale);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon             : "error",
                title            : "Request Failed",
                text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
}

function quadrantsmf(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/urbpjs/rawatjalan/quadrantsmf",
        type     : "POST",
        dataType : "JSON",
        data     : {selectperiode:selectperiode},

        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataquadrantsmf").empty();
        },

        success: function (response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon             : 'warning',
                    title            : 'No Records Found',
                    text             : 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer            : 2000
                });
                return;
            }

            const result = Array.isArray(response.responResult) ? response.responResult : [];

            const seriesData = result.map(item => ({
                x      : Number(item.SELISIH) || 0,
                y      : Number(item.JUMLAH_KUNJUNGAN) || 0,
                nama   : item.KOLEGIUM,
                dokter : item.KOLEGIUM_ID,
                tarifrs: Number(item.TOTAL_TARIF_RS) || 0,
                inacbg : (Number(item.NILAI_GROUPING) || 0) + (Number(item.NILAI_ABD) || 0) + (Number(item.NILAI_FARMASI) || 0)

            }));

            const minRevenue = Math.min(
                ...seriesData.map(item => item.x)
            );

            const maxRevenue = Math.max(
                ...seriesData.map(item => item.x)
            );

            const revenueMin = minRevenue < 0 ? Math.floor(minRevenue / 50000000) * 50000000 : 0;
            const revenueMax = maxRevenue > 0 ? Math.ceil(maxRevenue / 50000000) * 50000000 : 0;

            const minPasien = Math.min(
                ...seriesData.map(item => item.y)
            );

            const maxPasien = Math.max(
                ...seriesData.map(item => item.y)
            );

            const patientLine = (minPasien + maxPasien) / 2;

            const patientRange = Math.max(
                patientLine - minPasien,
                maxPasien - patientLine
            );

            const patientScale = Math.ceil(patientRange * 1.10);

            seriesData.forEach(item => {
                item.crr = item.tarifrs > 0 ? (item.inacbg / item.tarifrs) * 100 : 0;
            });

            seriesData.sort((a, b) => b.crr - a.crr);

            let html = "";
            seriesData.forEach((item, index) => {

                let quadrant = "";
                let badge = "";

                if (item.x >= 0 && item.y >= patientLine) {
                    quadrant = "Q1";
                    badge    = "success";
                } else if (item.x >= 0 && item.y < patientLine) {
                    quadrant = "Q2";
                    badge    = "primary";
                } else if (item.x < 0 && item.y < patientLine) {
                    quadrant = "Q3";
                    badge    = "danger";
                } else {
                    quadrant = "Q4";
                    badge    = "warning";
                }

                html += `
                            <tr>
                                <td class="ps-4">${index + 1}</td>
                                <td>${item.nama}</td>
                                <td class="text-center">${todesimal(item.y)}</td>
                                <td class="text-end">${todesimal(item.tarifrs)}</td>
                                <td class="text-end">${todesimal(item.inacbg)}</td>
                                <td class="text-end fw-bold ${item.x >= 0 ? "text-success" : "text-danger"}">${item.x >= 0 ? "+" : ""}${todesimal(item.x)}</td>
                                <td class="text-center fw-bold">${item.crr.toFixed(2)}%</td>
                                <td class="text-end pe-4"><span class="badge badge-light-${badge}">${quadrant}</span></td>
                            </tr>
                        `;
            });

            $("#resultdataquadrantsmf").html(html);

            const table = initDataTable("#dataquadrantsmf_table","#searchtable");

            renderquadrant("#chartquadrantsmf",seriesData,0,patientLine,patientScale);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon             : "error",
                title            : "Request Failed",
                text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
}

function quadrantresource(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/urbpjs/rawatjalan/quadrantresource",
        type     : "POST",
        dataType : "JSON",
        data     : {selectperiode:selectperiode},

        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataquadrantsmf").empty();
        },

        success: function (response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon             : 'warning',
                    title            : 'No Records Found',
                    text             : 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer            : 2000
                });
                return;
            }

            const result = Array.isArray(response.responResult) ? response.responResult : [];

            const seriesData = result.map(item => {

                const resource = {
                    "REGISTRASI": Number(item.REGISTRASI) || 0,
                    "JASA DOKTER": Number(item.JASA_DOKTER) || 0,
                    "OBAT": Number(item.OBAT) || 0,
                    "LABORATORIUM": Number(item.LABORATORIUM) || 0,
                    "RADIOLOGI": Number(item.RADIOLOGI) || 0,
                    "RADIOTERAPI": Number(item.RADIOTERAPI) || 0,
                    "TINDAKAN": Number(item.TINDAKAN) || 0,
                    "AMBULAN": Number(item.AMBULAN) || 0
                };

                const totalResource = Object.values(resource)
                    .reduce((sum, value) => sum + value, 0);

                let resourceTertinggi = "";
                let nilaiTertinggi = 0;

                Object.entries(resource).forEach(([nama, nilai]) => {

                    if (nilai > nilaiTertinggi) {
                        nilaiTertinggi = nilai;
                        resourceTertinggi = nama;
                    }

                });

                const persentaseResource = totalResource > 0
                    ? (nilaiTertinggi / totalResource) * 100
                    : 0;

                const config = resourceConfig[resourceTertinggi];

                return {

                    x: config ? config.x : 0,

                    y: persentaseResource,

                    nama: item.NAMADOKTER || item.KOLEGIUM || "-",

                    dokter: item.DOKTER_ID || item.KOLEGIUM_ID || "",

                    resourceTertinggi: resourceTertinggi,

                    persentaseResource: persentaseResource,

                    nilaiResourceTertinggi: nilaiTertinggi,

                    totalResource: totalResource,

                    resource: resource,

                    color: config ? config.color : "#6c757d"

                };

            }).filter(item => item.totalResource > 0);

renderscatterresource(
    "#chartquadrantresource",
    seriesData
);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon             : "error",
                title            : "Request Failed",
                text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
}

function prosesImportBahv() {
    let total = dataBahv.length;
    let index = 0;
    let batchSize = 500;
    let startTime = Date.now();

    Swal.fire({
        title: "Importing BAHV Data",
        html: `
            <div class="text-center">

                <div class="fs-5 mb-3">
                    Please wait...
                </div>

                <div class="fs-3 fw-bold text-primary">
                    <span id="importProgressBahv">0</span> /
                    ${total.toLocaleString("id-ID")}
                </div>

                <div class="progress mt-3" style="height:10px;">
                    <div
                        id="importProgressBarBahv"
                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width:0%">
                    </div>
                </div>

                <div class="mt-3 small text-muted">

                    <div>
                        Processing Speed :
                        <span id="importSpeedBahv">0</span> records/sec
                    </div>

                    <div>
                        Estimated Time Remaining :
                        <span id="importEtaBahv">Calculating...</span>
                    </div>

                </div>

            </div>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function () {
            Swal.showLoading();
            kirimBatchbahv();
        }
    });

    function kirimBatchbahv() {
        let batch = dataBahv.slice(index, index + batchSize);
        $.ajax({
            url     : url + "index.php/urbpjs/syncurbpjsrj/importbahv",
            type    : "POST",
            dataType: "JSON",
            data    : {data: JSON.stringify(batch)},
            success : function (res) {

                if (!res.status) {
                    Swal.fire({
                        icon: "error",
                        title: "Import Failed",
                        text: res.message
                    });
                    return;
                }

                index += batch.length;

                if(index > total){index = total;}
                $("#importProgressBahv").text(index.toLocaleString("id-ID"));

                let percent = (index / total) * 100;
                $("#importProgressBarBahv").css("width", percent + "%").attr("aria-valuenow", percent);

                let elapsed = (Date.now() - startTime) / 1000;
                let speed = elapsed > 0 ? index / elapsed : 0;
                $("#importSpeedBahv").text(speed.toFixed(2));


                let remaining = total - index;
                let eta = speed > 0 ? remaining / speed : 0;

                $("#importEtaBahv").text(remaining > 0 ? formatDuration(eta) : "Completed");

                if(index < total){
                    kirimBatchbahv();
                }else{
                    $("#importProgressBarBahv").css("width", "100%");

                    setTimeout(function () {
                        Swal.fire({
                            icon: "success",
                            title: "Import Completed",
                            text: total.toLocaleString("id-ID") + " records have been imported successfully.",
                            confirmButtonColor: "#009EF7",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(function () {
                            dataBahv = [];

                            $("#filebahv").val("");
                            $("#jmlDataBahv").text("0");
                            $("#totalLayakBahv").text("0");
                            $("#totalTidakLayakBahv").text("0");
                            $("#resultpreviewbahv").html("");
                            $("#modal_upload_bahv").modal("hide");

                            datarrjdetail();
                        });
                    }, 200);
                }
            },
            error: function () {
                Swal.fire({
                    icon             : "error",
                    title            : "Request Failed",
                    text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                    confirmButtonText: "OK"
                });
            }
        });
    }

}

function prosesImportFarmasi() {
    let total     = dataFarmasi.length;
    let index     = 0;
    let batchSize = 500;
    let startTime = Date.now();

    Swal.fire({
        title: "Importing Pharmacy Claim Data",
        html: `
            <div class="text-center">
                <div class="fs-5 mb-3">
                    Please wait...
                </div>
                <div class="fs-3 fw-bold text-primary">
                    <span id="importProgressFarmasi">0</span> /
                    ${total.toLocaleString("id-ID")}
                </div>
                <div class="progress mt-3" style="height:10px;">
                    <div
                        id="importProgressBarFarmasi"
                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width:0%">
                    </div>
                </div>
                <div class="mt-3 small text-muted">
                    <div>
                        Processing Speed :
                        <span id="importSpeedFarmasi">0</span> records/sec
                    </div>
                    <div>
                        Estimated Time Remaining :
                        <span id="importEtaFarmasi">Calculating...</span>
                    </div>
                </div>
            </div>
        `,

        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,

        didOpen: function () {
            Swal.showLoading();
            kirimBatchFarmasi();
        }

    });

    function kirimBatchFarmasi() {
        let batch = dataFarmasi.slice(index, index + batchSize);

        $.ajax({
            url     : url + "index.php/urbpjs/syncurbpjsrj/importfarmasi",
            type    : "POST",
            dataType: "JSON",
            data    : {data: JSON.stringify(batch)},

            success: function (res) {

                if (!res.status) {
                    Swal.fire({
                        icon: "error",
                        title: "Import Failed",
                        text: res.message
                    });
                    return;
                }

                index += batch.length;

                if (index > total) {
                    index = total;
                }

                $("#importProgressFarmasi").text(index.toLocaleString("id-ID"));

                let percent = (index / total) * 100;

                $("#importProgressBarFarmasi").css("width", percent + "%").attr("aria-valuenow", percent);

                let elapsed = (Date.now() - startTime) / 1000;
                let speed = elapsed > 0 ? index / elapsed : 0;
                $("#importSpeedFarmasi").text(speed.toFixed(2));

                let remaining = total - index;
                let eta = speed > 0 ? remaining / speed : 0;

                $("#importEtaFarmasi").text(remaining > 0 ? formatDuration(eta) : "Completed");

                if (index < total) {
                    kirimBatchFarmasi();
                } else {

                    $("#importProgressBarFarmasi").css("width", "100%");

                    setTimeout(function () {
                        Swal.fire({
                            icon              : "success",
                            title             : "Import Completed",
                            text              : total.toLocaleString("id-ID") + " records have been imported successfully.",
                            confirmButtonColor: "#009EF7",
                            timer             : 3000,
                            timerProgressBar  : true,
                            showConfirmButton : false

                        }).then(function () {
                            dataFarmasi = [];

                            $("#filefarmasi").val("");
                            $("#jmlDataFarmasi").text("0");
                            $("#totalNilaiFarmasi").text("0");
                            $("#resultpreviewfarmasi").html("");
                            $("#modal_upload_farmasi").modal("hide");

                            datarrjdetail();
                        });
                    }, 200);
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Request Failed",
                    text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                    confirmButtonText: "OK"
                });
            }
        });
    }
}

function prosesImportEklaim() {

    let total = dataEklaim.length;
    let index = 0;
    let batchSize = 500;
    let startTime = Date.now();

    Swal.fire({

        title: "Importing E-Klaim Data",

        html: `
            <div class="text-center">

                <div class="fs-5 mb-3">
                    Please wait...
                </div>

                <div class="fs-3 fw-bold text-primary">
                    <span id="importProgressEklaim">0</span> /
                    ${total.toLocaleString("id-ID")}
                </div>

                <div class="progress mt-3" style="height:10px;">
                    <div
                        id="importProgressBarEklaim"
                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width:0%">
                    </div>
                </div>

                <div class="mt-3 small text-muted">

                    <div>
                        Processing Speed :
                        <span id="importSpeedEklaim">0</span>
                        records/sec
                    </div>

                    <div>
                        Estimated Time Remaining :
                        <span id="importEtaEklaim">
                            Calculating...
                        </span>
                    </div>

                </div>

            </div>
        `,

        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,

        didOpen: function () {

            Swal.showLoading();

            kirimBatchEklaim();

        }

    });

    function kirimBatchEklaim() {

        const batch = dataEklaim.slice(index, index + batchSize);

        $.ajax({

            url: url + "index.php/urbpjs/syncurbpjsrj/importeklaim",

            type: "POST",

            dataType: "JSON",

            data: {
                data: JSON.stringify(batch)
            },

            success: function (res) {

                if (!res.status) {

                    Swal.fire({
                        icon: "error",
                        title: "Import Failed",
                        text: res.message
                    });

                    return;

                }

                index += batch.length;

                if (index > total) {
                    index = total;
                }

                $("#importProgressEklaim").text(
                    index.toLocaleString("id-ID")
                );

                const percent = (index / total) * 100;

                $("#importProgressBarEklaim")
                    .css("width", percent + "%")
                    .attr("aria-valuenow", percent);

                const elapsed = (Date.now() - startTime) / 1000;

                const speed = elapsed > 0
                    ? index / elapsed
                    : 0;

                $("#importSpeedEklaim")
                    .text(speed.toFixed(2));

                const remaining = total - index;

                const eta = speed > 0
                    ? remaining / speed
                    : 0;

                $("#importEtaEklaim").text(
                    remaining > 0
                        ? formatDuration(eta)
                        : "Completed"
                );

                if (index < total) {

                    kirimBatchEklaim();

                } else {

                    $("#importProgressBarEklaim")
                        .css("width", "100%");

                    setTimeout(function () {
                        Swal.fire({
                            icon              : "success",
                            title             : "Import Completed",
                            text              : total.toLocaleString("id-ID") + " records have been imported successfully.",
                            confirmButtonColor: "#009EF7",
                            timer             : 3000,
                            timerProgressBar  : true,
                            showConfirmButton : false
                        }).then(function () {
                            dataEklaim = [];
                            $("#fileeklaim").val("");
                            $("#jmlDataEklaim").text("0");
                            $("#totalNilaiEklaim").text("0");
                            $("#resultprevieweklaim").html("");
                            $("#modal_upload_eklaim").modal("hide");
                            datarrjdetail();
                        });
                    }, 200);
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Request Failed",
                    text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                    confirmButtonText: "OK"
                });
            }
        });
    }

}

function datadetailtidakadasep(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/urbpjs/rawatjalan/datadetailtidakadasep",
        data      : {selectperiode:selectperiode},
        type      : "POST",
        dataType  : "JSON",
        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatadetailtidakadasep").empty();
        },
        success:function(response){

            if (response.responCode !== "00") {
                Swal.fire({
                    icon             : 'warning',
                    title            : 'No Records Found',
                    text             : 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer            : 2000
                });
                return;
            }

            const result      = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatacarakeluar = result;

            var tableresult    = "";
            for (var i in result) {

                let btnaction = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].MRPAS||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMAPASIEN||"")+"</td>";
                tableresult += "<td>"+(result[i].POLIKLINIK||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMADOKTER||"")+"</td>";
                tableresult += "<td class='text-center'>"+(result[i].TGLMASUK||"")+"</td>";
                tableresult += "<td>" + ((result[i].LASTUPDATE || "") === "MJKN-TOLOP" ? '<span class="badge badge-light-success">Tol-Ops</span>' : ("" || "")) + "</td>";
                tableresult += "<td class='fw-bold text-end'>";
                    tableresult += "<div class='btn-group'>";
                    tableresult += "<button type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown'>Actions</button>";
                    tableresult += "<div class='dropdown-menu'>";
                    tableresult += btnaction;
                    tableresult += "</div></div>";
                tableresult +="</td>";
                tableresult += "</tr>";
            }

            $("#resultdatadetailtidakadasep").html(tableresult);
            const table = initDataTable("#datadetailtidakadasep_table","#searchtable");
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
};