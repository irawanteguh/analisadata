let globalDataTopDiagnosaIGD        = [];
let globalDataTopDiagnosaRJ         = [];
let globalDataTopDiagnosaRJgeriatri = [];
let globalDataTopDiagnosaRI         = [];
let globalDataTopDiagnosaRJSMF      = [];
let globalDataTopDiagnosaRISMF      = [];

datarjgeriatri();
datarj();
dataigd();
datari();
datarjsmf();
datarismf();

$('#selectperiode').on('change', function () {
    datarjgeriatri();
    datarj();
    dataigd();
    datari();
    datarjsmf();
    datarismf();
});

$("#btnDownloadExcelIGD").on("click", function () {

    const formatterDiagnosa = (item, index) => ({
        No          : index + 1,
        "Kode ICD"  : item.ICD10PRIMARY,
        "Diagnosa"  : item.DESCRIPTION,
        "Jumlah"    : Number(item.JUMLAH),
        "Laki-Laki" : Number(item.LAKI_LAKI),
        "Perempuan" : Number(item.PEREMPUAN)
    });

    exportToExcel(
        globalDataTopDiagnosaIGD,
        "",
        "Top_Diagnosa_IGD.xlsx",
        {
            multiSheet: [
                {
                    name: "Tahunan",
                    data: globalDataTopDiagnosaIGD.filter(x => x.TRIWULAN == 0),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 1",
                    data: globalDataTopDiagnosaIGD.filter(x => x.TRIWULAN == 1),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 2",
                    data: globalDataTopDiagnosaIGD.filter(x => x.TRIWULAN == 2),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 3",
                    data: globalDataTopDiagnosaIGD.filter(x => x.TRIWULAN == 3),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 4",
                    data: globalDataTopDiagnosaIGD.filter(x => x.TRIWULAN == 4),
                    formatter: formatterDiagnosa
                }
            ]
        }
    );

});

$("#btnDownloadExcelRI").on("click", function () {

    const formatterDiagnosa = (item, index) => ({
        No          : index + 1,
        "Kode ICD"  : item.ICD10PRIMARY,
        "Diagnosa"  : item.DESCRIPTION,
        "Jumlah"    : Number(item.JUMLAH),
        "Laki-Laki" : Number(item.LAKI_LAKI),
        "Perempuan" : Number(item.PEREMPUAN)
    });

    exportToExcel(
        globalDataTopDiagnosaRI,
        "",
        "Top_Diagnosa_Rawat_Inap.xlsx",
        {
            multiSheet: [
                {
                    name: "Tahunan",
                    data: globalDataTopDiagnosaRI.filter(x => x.TRIWULAN == 0),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 1",
                    data: globalDataTopDiagnosaRI.filter(x => x.TRIWULAN == 1),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 2",
                    data: globalDataTopDiagnosaRI.filter(x => x.TRIWULAN == 2),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 3",
                    data: globalDataTopDiagnosaRI.filter(x => x.TRIWULAN == 3),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 4",
                    data: globalDataTopDiagnosaRI.filter(x => x.TRIWULAN == 4),
                    formatter: formatterDiagnosa
                }
            ]
        }
    );

});

$("#btnDownloadExcelRJ").on("click", function () {

    const data = globalDataTopDiagnosaRJ || [];

    const formatterDiagnosa = (item, index) => ({
        No          : index + 1,
        "Kode ICD"  : item.ICD10PRIMARY,
        "Diagnosa"  : item.DESCRIPTION,
        "Jumlah"    : Number(item.JUMLAH || 0),
        "Laki-Laki" : Number(item.LAKI_LAKI || 0),
        "Perempuan" : Number(item.PEREMPUAN || 0)
    });

    exportToExcel(
        data,
        "",
        "Top_Diagnosa_Rawat_Jalan.xlsx",
        {
            multiSheet: [
                {
                    name: "Tahunan",
                    data: data.filter(x => x.TRIWULAN == 0),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 1",
                    data: data.filter(x => x.TRIWULAN == 1),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 2",
                    data: data.filter(x => x.TRIWULAN == 2),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 3",
                    data: data.filter(x => x.TRIWULAN == 3),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 4",
                    data: data.filter(x => x.TRIWULAN == 4),
                    formatter: formatterDiagnosa
                }
            ]
        }
    );

});

$("#btnDownloadExcelRJgeriatri").on("click", function () {

    const data = globalDataTopDiagnosaRJgeriatri || [];

    const formatterDiagnosa = (item, index) => ({
        No          : index + 1,
        "Kode ICD"  : item.ICD10PRIMARY,
        "Diagnosa"  : item.DESCRIPTION,
        "Jumlah"    : Number(item.JUMLAH || 0),
        "Laki-Laki" : Number(item.LAKI_LAKI || 0),
        "Perempuan" : Number(item.PEREMPUAN || 0)
    });

    exportToExcel(
        data,
        "",
        "Top_Diagnosa_Rawat_Jalan_Geriatri.xlsx",
        {
            multiSheet: [
                {
                    name: "Tahunan",
                    data: data.filter(x => x.TRIWULAN == 0),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 1",
                    data: data.filter(x => x.TRIWULAN == 1),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 2",
                    data: data.filter(x => x.TRIWULAN == 2),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 3",
                    data: data.filter(x => x.TRIWULAN == 3),
                    formatter: formatterDiagnosa
                },
                {
                    name: "Triwulan 4",
                    data: data.filter(x => x.TRIWULAN == 4),
                    formatter: formatterDiagnosa
                }
            ]
        }
    );

});

$("#btnDownloadExcelRJSMF").on("click", function (e) {

    e.preventDefault();

    if (!globalDataTopDiagnosaRJSMF || !globalDataTopDiagnosaRJSMF.length) {
        Swal.fire("Info", "Data belum tersedia", "warning");
        return;
    }

    exportTopDiagnosaRJSMF(globalDataTopDiagnosaRJSMF, "Rawat Jalan");

});

$("#btnDownloadExcelRISMF").on("click", function (e) {

    e.preventDefault();

    if (!globalDataTopDiagnosaRJSMF || !globalDataTopDiagnosaRJSMF.length) {
        Swal.fire("Info", "Data belum tersedia", "warning");
        return;
    }

   exportTopDiagnosaRJSMF(globalDataTopDiagnosaRISMF, "Rawat Inap");

});


function dataigd() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/topdiagnosa/dataigd",
        data      : {selectperiode: selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,

        beforeSend: function () {
            Swal.fire({
                title             : 'Processing',
                html              : 'Please wait while the system displays the requested data.',
                allowOutsideClick : false,
                allowEscapeKey    : false,
                showConfirmButton : false,
                didOpen           : () => Swal.showLoading()
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

            const result      = Array.isArray(response.responResult) ? response.responResult : [];
            globalDataTopDiagnosaIGD = result;

            const tahunan = result
                .filter(x => x.TRIWULAN == 0)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan1 = result
                .filter(x => x.TRIWULAN == 1)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan2 = result
                .filter(x => x.TRIWULAN == 2)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan3 = result
                .filter(x => x.TRIWULAN == 3)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan4 = result
                .filter(x => x.TRIWULAN == 4)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const seriesSex = [
                { name: 'Laki-Laki', field: 'laki_laki' },
                { name: 'Perempuan', field: 'perempuan' }
            ];

            renderBarHorizontal('grafiktopdiagnosaigd', seriesSex, tahunan, 'kategori');
            renderBarHorizontal('grafiktopdiagnosaigd_1', seriesSex, triwulan1, 'kategori');
            renderBarHorizontal('grafiktopdiagnosaigd_2', seriesSex, triwulan2, 'kategori');
            renderBarHorizontal('grafiktopdiagnosaigd_3', seriesSex, triwulan3, 'kategori');
            renderBarHorizontal('grafiktopdiagnosaigd_4', seriesSex, triwulan4, 'kategori');
        },

        complete: function () {
            Swal.close();
        },

        error: function () {
            Swal.fire({
                icon  : 'error',
                title : 'Error',
                text  : 'Unable to retrieve visit data.'
            });
        }
    });

};

function datari() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/topdiagnosa/datari",
        data      : {selectperiode: selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,

        beforeSend: function () {
            Swal.fire({
                title             : 'Processing',
                html              : 'Please wait while the system displays the requested data.',
                allowOutsideClick : false,
                allowEscapeKey    : false,
                showConfirmButton : false,
                didOpen           : () => Swal.showLoading()
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

            const result      = Array.isArray(response.responResult) ? response.responResult : [];
            globalDataTopDiagnosaRI = result;

            const seriesSex = [
                { name: 'Laki-Laki', field: 'laki_laki' },
                { name: 'Perempuan', field: 'perempuan' }
            ];


            const tahunan = result
                .filter(x => x.TRIWULAN == 0)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan1 = result
                .filter(x => x.TRIWULAN == 1)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan2 = result
                .filter(x => x.TRIWULAN == 2)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan3 = result
                .filter(x => x.TRIWULAN == 3)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan4 = result
                .filter(x => x.TRIWULAN == 4)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));



            renderBarHorizontal('grafiktopdiagnosari',seriesSex,tahunan,'kategori');
            renderBarHorizontal('grafiktopdiagnosari_1',seriesSex,triwulan1,'kategori');
            renderBarHorizontal('grafiktopdiagnosari_2',seriesSex,triwulan2,'kategori');
            renderBarHorizontal('grafiktopdiagnosari_3',seriesSex,triwulan3,'kategori');
            renderBarHorizontal('grafiktopdiagnosari_4',seriesSex,triwulan4,'kategori');
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

function datarj() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/topdiagnosa/datarj",
        data      : {selectperiode: selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,

        beforeSend: function () {
            Swal.fire({
                title             : 'Processing',
                html              : 'Please wait while the system displays the requested data.',
                allowOutsideClick : false,
                allowEscapeKey    : false,
                showConfirmButton : false,
                didOpen           : () => Swal.showLoading()
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

            const result      = Array.isArray(response.responResult) ? response.responResult : [];
            globalDataTopDiagnosaRJ = result;

            const seriesSex = [
                { name: 'Laki-Laki', field: 'laki_laki' },
                { name: 'Perempuan', field: 'perempuan' }
            ];

            const tahunan = result
                .filter(x => x.TRIWULAN == 0)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan1 = result
                .filter(x => x.TRIWULAN == 1)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan2 = result
                .filter(x => x.TRIWULAN == 2)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan3 = result
                .filter(x => x.TRIWULAN == 3)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan4 = result
                .filter(x => x.TRIWULAN == 4)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));


            renderBarHorizontal('grafiktopdiagnosarj', seriesSex, tahunan, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarj_1', seriesSex, triwulan1, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarj_2', seriesSex, triwulan2, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarj_3', seriesSex, triwulan3, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarj_4', seriesSex, triwulan4, 'kategori');
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

};

function datarjgeriatri() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/topdiagnosa/datarjgeriatri",
        data      : {selectperiode: selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,

        beforeSend: function () {
            Swal.fire({
                title             : 'Processing',
                html              : 'Please wait while the system displays the requested data.',
                allowOutsideClick : false,
                allowEscapeKey    : false,
                showConfirmButton : false,
                didOpen           : () => Swal.showLoading()
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
            const result      = Array.isArray(response.responResult) ? response.responResult : [];

            globalDataTopDiagnosaRJgeriatri = result;

            const seriesSex = [
                { name: 'Laki-Laki', field: 'laki_laki' },
                { name: 'Perempuan', field: 'perempuan' }
            ];

            const tahunan = result
                .filter(x => x.TRIWULAN == 0)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan1 = result
                .filter(x => x.TRIWULAN == 1)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan2 = result
                .filter(x => x.TRIWULAN == 2)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan3 = result
                .filter(x => x.TRIWULAN == 3)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));

            const triwulan4 = result
                .filter(x => x.TRIWULAN == 4)
                .map(x => ({
                    kategori  : x.DESCRIPTION,
                    laki_laki : Number(x.LAKI_LAKI || 0),
                    perempuan : Number(x.PEREMPUAN || 0)
                }));


            renderBarHorizontal('grafiktopdiagnosarjgeriatri', seriesSex, tahunan, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarjgeriatri_1', seriesSex, triwulan1, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarjgeriatri_2', seriesSex, triwulan2, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarjgeriatri_3', seriesSex, triwulan3, 'kategori');
            renderBarHorizontal('grafiktopdiagnosarjgeriatri_4', seriesSex, triwulan4, 'kategori');
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

function datarjsmf() {

    let selectperiode = $("select[name='selectperiode']").val();

    $.ajax({
        url: url + "index.php/dashboard/topdiagnosa/datarjsmf",
        data: {
            selectperiode: selectperiode
        },
        method: "POST",
        dataType: "JSON",
        cache: false,

        beforeSend: function () {
            Swal.fire({
                title: "Processing",
                html: "Please wait while the system displays the requested data.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function (response) {

            if (response.responCode !== "00") {
                $("#topdiagnosadetailrjsmf").html("");

                Swal.fire({
                    icon: "warning",
                    title: "No Records Found",
                    text: "No records are available for the selected period.",
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            const result = Array.isArray(response.responResult)? response.responResult : [];
            globalDataTopDiagnosaRJSMF = result;

            const seriesSex = [
                {
                    name: "Laki-Laki",
                    field: "laki_laki"
                },
                {
                    name: "Perempuan",
                    field: "perempuan"
                }
            ];

            // ==========================================
            // LIST SMF / KOLEGIUM
            // ==========================================
            const smfList = [
                ...new Set(
                    result
                        .map(x => x.KOLEGIUM)
                        .filter(x => x)
                )
            ];

            // ==========================================
            // BERSIHKAN CONTAINER
            // ==========================================
            $("#topdiagnosadetailrjsmf").empty();

            // ==========================================
            // GENERATE CARD SMF
            // ==========================================
            smfList.forEach((smf, idx) => {

                const id = `smf_${idx}`;

                $("#topdiagnosadetailrjsmf").append(`
                    <div class="col-xl-6 mb-5">

                        <div class="card card-flush">

                            <div class="card-header pt-5">

                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bolder fs-3 mb-1">
                                        ${smf}
                                    </span>
                                    <span class="text-muted mt-1 fw-bold fs-7">
                                        Top 10 Diagnosis Rawat Jalan
                                    </span>
                                </h3>

                                <div class="card-toolbar m-0">

                                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder">

                                        <li class="nav-item">
                                            <a class="nav-link active"
                                               data-bs-toggle="tab"
                                               href="#${id}">
                                                ALL
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               data-bs-toggle="tab"
                                               href="#${id}_1">
                                                Q1
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               data-bs-toggle="tab"
                                               href="#${id}_2">
                                                Q2
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               data-bs-toggle="tab"
                                               href="#${id}_3">
                                                Q3
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                               data-bs-toggle="tab"
                                               href="#${id}_4">
                                                Q4
                                            </a>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                            <div class="card-body py-3">

                                <div class="tab-content">

                                    <div id="${id}" class="tab-pane fade show active">
                                        <div class="card-rounded-bottom" id="grafik_${id}"></div>
                                    </div>

                                    <div id="${id}_1" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_1"></div>
                                    </div>

                                    <div id="${id}_2" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_2"></div>
                                    </div>

                                    <div id="${id}_3" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_3"></div>
                                    </div>

                                    <div id="${id}_4" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_4"></div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                `);

            });

            // ==========================================
            // RENDER CHART PER SMF
            // ==========================================
            smfList.forEach((smf, idx) => {

                const id = `smf_${idx}`;

                const dataSmf = result.filter(
                    x => x.KOLEGIUM === smf
                );

                const tahunan = dataSmf
                    .filter(x => Number(x.TRIWULAN) === 0)
                    .map(x => ({
                        kategori: x.DESCRIPTION,
                        laki_laki: Number(x.LAKI_LAKI || 0),
                        perempuan: Number(x.PEREMPUAN || 0)
                    }));

                const triwulan1 = dataSmf
                    .filter(x => Number(x.TRIWULAN) === 1)
                    .map(x => ({
                        kategori: x.DESCRIPTION,
                        laki_laki: Number(x.LAKI_LAKI || 0),
                        perempuan: Number(x.PEREMPUAN || 0)
                    }));

                const triwulan2 = dataSmf
                    .filter(x => Number(x.TRIWULAN) === 2)
                    .map(x => ({
                        kategori: x.DESCRIPTION,
                        laki_laki: Number(x.LAKI_LAKI || 0),
                        perempuan: Number(x.PEREMPUAN || 0)
                    }));

                const triwulan3 = dataSmf
                    .filter(x => Number(x.TRIWULAN) === 3)
                    .map(x => ({
                        kategori: x.DESCRIPTION,
                        laki_laki: Number(x.LAKI_LAKI || 0),
                        perempuan: Number(x.PEREMPUAN || 0)
                    }));

                const triwulan4 = dataSmf
                    .filter(x => Number(x.TRIWULAN) === 4)
                    .map(x => ({
                        kategori: x.DESCRIPTION,
                        laki_laki: Number(x.LAKI_LAKI || 0),
                        perempuan: Number(x.PEREMPUAN || 0)
                    }));

                renderBarHorizontal(
                    `grafik_${id}`,
                    seriesSex,
                    tahunan,
                    "kategori"
                );

                renderBarHorizontal(
                    `grafik_${id}_1`,
                    seriesSex,
                    triwulan1,
                    "kategori"
                );

                renderBarHorizontal(
                    `grafik_${id}_2`,
                    seriesSex,
                    triwulan2,
                    "kategori"
                );

                renderBarHorizontal(
                    `grafik_${id}_3`,
                    seriesSex,
                    triwulan3,
                    "kategori"
                );

                renderBarHorizontal(
                    `grafik_${id}_4`,
                    seriesSex,
                    triwulan4,
                    "kategori"
                );

            });

        },

        complete: function () {
            Swal.close();
        },

        error: function () {

            $("#topdiagnosadetailrjsmf").html("");

            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });

        }
    });

}

function datarismf() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url: url + "index.php/dashboard/topdiagnosa/datarismf",
        data: {
            selectperiode: selectperiode
        },
        method: "POST",
        dataType: "JSON",
        cache: false,

        beforeSend: function () {
            Swal.fire({
                title: "Processing",
                html: "Please wait while the system displays the requested data.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        },

        success: function (response) {

            if (response.responCode !== "00") {

                $("#topdiagnosadetailrismf").html("");

                Swal.fire({
                    icon: "warning",
                    title: "No Records Found",
                    text: "No records are available for the selected period.",
                    showConfirmButton: false,
                    timer: 2000
                });

                return;
            }

            const result = Array.isArray(response.responResult) ? response.responResult : [];

            globalDataTopDiagnosaRISMF = result;

            const seriesSex = [
                {
                    name: "Laki-Laki",
                    field: "laki_laki"
                },
                {
                    name: "Perempuan",
                    field: "perempuan"
                }
            ];

            // ==========================================
            // LIST SMF
            // ==========================================
            const smfList = [
                ...new Set(
                    result
                        .map(x => x.KOLEGIUM)
                        .filter(Boolean)
                )
            ];

            // ==========================================
            // BERSIHKAN CONTAINER
            // ==========================================
            $("#topdiagnosadetailrismf").empty();

            // ==========================================
            // GENERATE CARD
            // ==========================================
            smfList.forEach((smf, idx) => {

                const id = `rismf_${idx}`;

                $("#topdiagnosadetailrismf").append(`

                    <div class="col-xl-6 mb-5">

                        <div class="card card-flush">

                            <div class="card-header pt-5">

                                <h3 class="card-title align-items-start flex-column">

                                    <span class="card-label fw-bolder fs-3 mb-1">
                                        ${smf}
                                    </span>

                                    <span class="text-muted mt-1 fw-bold fs-7">
                                        Top 10 Diagnosis Rawat Inap
                                    </span>

                                </h3>

                                <div class="card-toolbar m-0">

                                    <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bolder">

                                        <li class="nav-item">
                                            <a class="nav-link active"
                                                data-bs-toggle="tab"
                                                href="#${id}">
                                                ALL
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                                data-bs-toggle="tab"
                                                href="#${id}_1">
                                                Q1
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                                data-bs-toggle="tab"
                                                href="#${id}_2">
                                                Q2
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                                data-bs-toggle="tab"
                                                href="#${id}_3">
                                                Q3
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link"
                                                data-bs-toggle="tab"
                                                href="#${id}_4">
                                                Q4
                                            </a>
                                        </li>

                                    </ul>

                                </div>

                            </div>

                            <div class="card-body py-3">

                                <div class="tab-content">

                                    <div id="${id}" class="tab-pane fade show active">
                                        <div class="card-rounded-bottom" id="grafik_${id}"></div>
                                    </div>

                                    <div id="${id}_1" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_1"></div>
                                    </div>

                                    <div id="${id}_2" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_2"></div>
                                    </div>

                                    <div id="${id}_3" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_3"></div>
                                    </div>

                                    <div id="${id}_4" class="tab-pane fade">
                                        <div class="card-rounded-bottom" id="grafik_${id}_4"></div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                `);

            });

            // ==========================================
            // RENDER CHART
            // ==========================================
            smfList.forEach((smf, idx) => {

                const id = `rismf_${idx}`;

                const dataSmf = result.filter(x => x.KOLEGIUM === smf);

                const getData = triwulan =>
                    dataSmf
                        .filter(x => Number(x.TRIWULAN) === triwulan)
                        .map(x => ({
                            kategori: x.DESCRIPTION,
                            laki_laki: Number(x.LAKI_LAKI || 0),
                            perempuan: Number(x.PEREMPUAN || 0)
                        }));

                renderBarHorizontal(`grafik_${id}`, seriesSex, getData(0), "kategori");
                renderBarHorizontal(`grafik_${id}_1`, seriesSex, getData(1), "kategori");
                renderBarHorizontal(`grafik_${id}_2`, seriesSex, getData(2), "kategori");
                renderBarHorizontal(`grafik_${id}_3`, seriesSex, getData(3), "kategori");
                renderBarHorizontal(`grafik_${id}_4`, seriesSex, getData(4), "kategori");

            });

        },

        complete: function () {
            Swal.close();
        },

        error: function () {

            $("#topdiagnosadetailrismf").html("");

            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });

        }

    });

}

function exportTopDiagnosaRJSMF(data, jenisRawat) {

    if (!Array.isArray(data) || data.length === 0) {
        Swal.fire("Info", "Data belum tersedia", "warning");
        return;
    }

    const workbook = XLSX.utils.book_new();

    const periode = $("select[name='selectperiode']").val() || "ALL";

    const pad = n => String(n).padStart(2, "0");
    const now = new Date();

    const timestamp =
        `${pad(now.getDate())}` +
        `${pad(now.getMonth() + 1)}` +
        `${now.getFullYear()}` +
        `${pad(now.getHours())}` +
        `${pad(now.getMinutes())}` +
        `${pad(now.getSeconds())}`;

    const lastUpdate =
        `${pad(now.getDate())}/${pad(now.getMonth() + 1)}/${now.getFullYear()} ` +
        `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

    const smfList = [...new Set(data.map(x => x.KOLEGIUM).filter(Boolean))];

    smfList.forEach(smf => {

        const rows = [];

        // =====================================================
        // HEADER
        // =====================================================

        rows.push([`TOP 10 DIAGNOSA ${jenisRawat.toUpperCase()} - ${smf}`]);
        rows.push(["Periode", periode]);
        rows.push(["Last Update", lastUpdate]);
        rows.push([]);

        // =====================================================
        // TAHUNAN & TRIWULAN
        // =====================================================

        [0, 1, 2, 3, 4].forEach(q => {

            rows.push([
                q === 0
                    ? "TAHUNAN"
                    : `TRIWULAN ${q}`
            ]);

            rows.push([
                "No",
                "Kode ICD",
                "Diagnosa",
                "Jumlah",
                "Laki-Laki",
                "Perempuan"
            ]);

            const detail = data.filter(item =>
                item.KOLEGIUM === smf &&
                Number(item.TRIWULAN) === q
            );

            detail.forEach((item, index) => {

                rows.push([
                    index + 1,
                    item.ICD10PRIMARY || "",
                    item.DESCRIPTION || "",
                    Number(item.JUMLAH || 0),
                    Number(item.LAKI_LAKI || 0),
                    Number(item.PEREMPUAN || 0)
                ]);

            });

            rows.push([]);
            rows.push([]);

        });

        const ws = XLSX.utils.aoa_to_sheet(rows);

        ws["!cols"] = [
            { wch: 6 },
            { wch: 12 },
            { wch: 60 },
            { wch: 12 },
            { wch: 12 },
            { wch: 12 }
        ];

        XLSX.utils.book_append_sheet(
            workbook,
            ws,
            smf.substring(0, 31)
        );

    });

    const jenisFile = jenisRawat
        .replace(/\s+/g, "_")
        .toUpperCase();

    XLSX.writeFile(
        workbook,
        `Top_Diagnosa_${jenisFile}_SMF_PERIODE_${periode}_TIMESTAMP_${timestamp}.xlsx`
    );

}