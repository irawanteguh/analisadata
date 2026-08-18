let globaldatautilisasiruangok = [];
let globaldatautilisasialkes   = [];


loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});

$("#btndownloaddatautilisasiruangok_table").on("click", function () {
    exportToExcel(
        null,
        null,
        "Utilisasi_Ruang_Operasi.xlsx",
        {
            multiSheet: [
                {
                    name: "Utilisasi Ruang OK",
                    data: globaldatautilisasiruangok,

                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const aug = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        const total =
                            jan + feb + mar + apr + mei + jun +
                            jul + aug + sep + okt + nov + des;

                        return {
                            "No"          : index + 1,
                            "Nama Ruangan": item.NAMAOK ?? "",
                            "Jan"         : jan,
                            "Feb"         : feb,
                            "Mar"         : mar,
                            "Apr"         : apr,
                            "Mei"         : mei,
                            "Jun"         : jun,
                            "Jul"         : jul,
                            "Agu"         : aug,
                            "Sep"         : sep,
                            "Okt"         : okt,
                            "Nov"         : nov,
                            "Des"         : des,
                            "Total"       : total
                        };
                    }
                }
            ]
        }
    );

});

$("#btndownloaddatautilisasialkes_table").on("click", function () {
    exportToExcel(
        null,
        null,
        "Utilisasi_Alkes.xlsx",
        {
            multiSheet: [
                {
                    name: "Utilisasi Alat Kesehatan",
                    data: globaldatautilisasialkes,

                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const aug = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        const total =
                            jan + feb + mar + apr + mei + jun +
                            jul + aug + sep + okt + nov + des;

                        return {
                            "No"          : index + 1,
                            "Nama Ruangan": item.DEVICE_NAME ?? "",
                            "Jan"         : jan,
                            "Feb"         : feb,
                            "Mar"         : mar,
                            "Apr"         : apr,
                            "Mei"         : mei,
                            "Jun"         : jun,
                            "Jul"         : jul,
                            "Agu"         : aug,
                            "Sep"         : sep,
                            "Okt"         : okt,
                            "Nov"         : nov,
                            "Des"         : des,
                            "Total"       : total
                        };
                    }
                }
            ]
        }
    );

});

$(document).on("click", ".btn-mapping", function (e) {
    e.preventDefault();

    const layanid = $(this).data("layanid");
    const namapelayanan = decodeURIComponent($(this).attr("data-namapelayanan"));

    $("#mapping_layanid").val(layanid);
    $("#mapping_namapelayanan").val(namapelayanan);

    $("#modal_mappingalkes").modal("show");
});

$(document).on("click", "#btnsimpanmapping", function () {
    simpanMapping();
});

function loaddata(){
    datautilisasiruangok();
    datautilisasialkes();
    datamasterlayan();
}

function datautilisasiruangok() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/utilisasi/datautilisasiruangok",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatautilisasiruangok").empty();
            $("#footerdatautilisasiruangok").empty();
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

            let totalJan = 0;
            let totalFeb = 0;
            let totalMar = 0;
            let totalApr = 0;
            let totalMei = 0;
            let totalJun = 0;
            let totalJul = 0;
            let totalAug = 0;
            let totalSep = 0;
            let totalOkt = 0;
            let totalNov = 0;
            let totalDes = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatautilisasiruangok = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].NAMAOK}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdatautilisasiruangok").html(tableresult);
            $("#footerdatautilisasiruangok").html(footer);

            const table = initDataTable("#datautilisasiruangok_table","#searchtable");

            const namaBulan = ["Jan", "Feb", "Mar", "Apr","Mei", "Jun", "Jul", "Agu","Sep", "Okt", "Nov", "Des"];

            const series = result.map(function (item) {
                return {
                    name: item.NAMAOK ?? "Ruang Operasi",
                    data: [
                        Number(item.JAN ?? 0),
                        Number(item.FEB ?? 0),
                        Number(item.MAR ?? 0),
                        Number(item.APR ?? 0),
                        Number(item.MEI ?? 0),
                        Number(item.JUN ?? 0),
                        Number(item.JUL ?? 0),
                        Number(item.AGU ?? 0),
                        Number(item.SEP ?? 0),
                        Number(item.OKT ?? 0),
                        Number(item.NOV ?? 0),
                        Number(item.DES ?? 0)
                    ]
                };
            });

            renderChartStackedBar(
                "grafikutilisasiok",
                namaBulan,
                series,
                "Periode Pelayanan",
                "Jumlah Tindakan"
            );

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

function datautilisasialkes() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/utilisasi/datautilisasialkes",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatautilisasialkes").empty();
            $("#footerdatautilisasialkes").empty();
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

            let totalJan   = 0;
            let totalFeb   = 0;
            let totalMar   = 0;
            let totalApr   = 0;
            let totalMei   = 0;
            let totalJun   = 0;
            let totalJul   = 0;
            let totalAug   = 0;
            let totalSep   = 0;
            let totalOkt   = 0;
            let totalNov   = 0;
            let totalDes   = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatautilisasialkes = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].DEVICE_NAME}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdatautilisasialkes").html(tableresult);
            $("#footerdatautilisasialkes").html(footer);

            // const table1 = initDataTable("#datautilisasialkes_table", "#searchtable");
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

function datamasterlayan() {
    $.ajax({
        url       : url + "index.php/dashboard/utilisasi/datamasterlayan",
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            if ($.fn.DataTable.isDataTable("#datamapping_table")) {
                $("#datamapping_table").DataTable().clear().destroy();
            }
            
            $("#resultdatamapping").empty();
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

            let tableresult = "";
            for (let i in result) {
                btnaction = "<a class='dropdown-item btn btn-sm btn-mapping' href='#' data-layanid='" + result[i].LAYAN_ID + "' data-namapelayanan='" + result[i].NAMA_LAYAN1 + "'><i class='bi bi-link-45deg text-primary pe-4'></i>Mapping</a>";

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].NAMA_LAYAN1||"")+"</td>";
                tableresult += "<td>"+(result[i].DEVICE_NAME||"")+"</td>";
                tableresult += "<td class='text-end'>";
                    tableresult += "<div class='btn-group' role='group'>";
                        tableresult += "<button id='btnGroupDrop1' type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown' aria-expanded='false'>Actions</button>";
                        tableresult += "<div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>";
                            tableresult += btnaction;
                        tableresult += "</div>";
                    tableresult += "</div>";
                tableresult += "</td>";
                tableresult += "</tr>";
                
            }

            $("#resultdatamapping").html(tableresult);

            const table1 = initDataTable("#datamapping_table", "#searchtable");
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

function simpanMapping() {

    const layanid  = $("#mapping_layanid").val();
    const deviceid = $("#mapping_deviceid").val();

    if (!layanid) {
        Swal.fire({
            icon: "warning",
            title: "Data Tidak Valid",
            text: "Layanan tidak ditemukan.",
            confirmButtonText: "OK"
        });
        return;
    }

    if (!deviceid) {
        Swal.fire({
            icon: "warning",
            title: "Pilih Alat Kesehatan",
            text: "Silakan pilih alat kesehatan terlebih dahulu.",
            confirmButtonText: "OK"
        });
        return;
    }

    $.ajax({
        url     : url + "index.php/dashboard/utilisasi/mappingtindakanalkes",
        type    : "POST",
        dataType: "JSON",
        data    : {layanid:layanid,deviceid:deviceid},

        beforeSend: function () {
            Swal.fire({
                title: "Processing",
                html: "Menyimpan mapping...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: function () {
                    Swal.showLoading();
                }
            });
        },

        success: function (response) {
            if (response.responCode === "00") {
                $("#modal_mappingalkes").modal("hide");
                datamasterlayan();
            }
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
}