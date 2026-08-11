let today                     = new Date().toLocaleDateString('en-CA');  // format YYYY-MM-DD
let startDate                 = today;
let endDate                   = today;
let globaldatajumlahpasien    = [];
let globaldataaktifitasdokter = [];

$("#btndownloaddataaktifitasdokter_table").on("click", function () {

    exportToExcel(
        null,
        null,
        "Rekap_Tindakan_Dokter.xlsx",
        {
            multiSheet: [

                // ==================================================
                // SHEET 1 : JUMLAH PASIEN
                // ==================================================
                {
                    name: "Jumlah Pasien",
                    data: globaldatajumlahpasien || [],

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,

                            "Jenis Episode":
                                item.JENIS_EPISODE === "I"
                                    ? "Rawat Inap"
                                    : "Rawat Jalan",

                            "Tanggal Masuk":
                                item.TGLMASUK ?? "",

                            "Jumlah Kunjungan":
                                Number(item.JML ?? 0)
                        };

                    }
                },

                // ==================================================
                // SHEET 2 : AKTIVITAS DOKTER
                // ==================================================
                {
                    name: "Aktivitas Dokter",
                    data: globaldataaktifitasdokter || [],

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,

                            "Jenis":
                                item.JENIS ?? "",

                            "Nama Pelayanan":
                                item.NAMAPELAYANAN ?? "",

                            "Total Qty":
                                Number(item.TOTAL_QTY ?? 0)
                        };

                    }
                }

            ]
        }
    );

});

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate   : "today",
    onChange  : function (selectedDates, dateStr, instance) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate   = selectedDates[1]  ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    datajumlahpasien(startDate, endDate);
    dataaktifitasdokter(startDate, endDate);
});

function datajumlahpasien(startDate, endDate){
    $.ajax({
        url       : url +"index.php/hrd/rekaptindakan/datajumlahpasien",
        data      : {startDate:startDate,endDate:endDate},
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

            $("#resultdatajumlahpasien").empty();
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

            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatajumlahpasien = result;

            let tableresult = "";
            for (var i in result) {
                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td><span class='badge badge-light-"+(result[i].JENIS_EPISODE === "I" ? "primary" : "success")+"'>"+(result[i].JENIS_EPISODE === "I" ? "Rawat Inap" : "Rawat Jalan")+"</span></td>";
                tableresult += "<td>"+(result[i].TGLMASUK||"")+"</td>";
                tableresult += "<td class='text-end pe-4'>"+(result[i].JML||"")+"</td>";
                tableresult += "</tr>";
            }

            $("#resultdatajumlahpasien").html(tableresult);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve visit data.'
            });
        }
    });
};

function dataaktifitasdokter(startDate, endDate){
    $.ajax({
        url       : url +"index.php/hrd/rekaptindakan/dataaktifitasdokter",
        data      : {startDate:startDate,endDate:endDate},
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

            $("#resultdataaktifitasdokter").empty();
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

            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldataaktifitasdokter = result;

            let tableresult = "";
            for (var i in result) {
                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td><span class='badge badge-light-"+(result[i].JENIS === "TINDAKAN RAWAT INAP / JALAN" ? "primary" : result[i].JENIS === "TINDAKAN ANASTESI" ? "warning" : "success")+"'>"+(result[i].JENIS||"")+"</span></td>";
                tableresult += "<td>"+(result[i].NAMAPELAYANAN||"")+"</td>";
                tableresult += "<td class='text-end pe-4'>"+(result[i].TOTAL_QTY||"")+"</td>";
                tableresult += "</tr>";
            }

            $("#resultdataaktifitasdokter").html(tableresult);

            const table = initDataTable("#dataaktifitasdokter_table","#searchtable");
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve visit data.'
            });
        }
    });
};