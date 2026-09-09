let today               = new Date().toLocaleDateString('en-CA');  // format YYYY-MM-DD
let startDate           = today;
let endDate             = today;
let globaldatakunjungan = [];

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate   : "today",
    onChange  : function (selectedDates, dateStr, instance) {
        startDate     = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate       = selectedDates[1]  ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    datakunjungan(startDate, endDate);
});

$("#btndownloaddataberkas_table").on("click", function () {

    exportToExcel(
        globaldatakunjungan,
        "Berkas Pasien",
        "Berkas_Pasien.xlsx",
        {
            formatter: (item, index) => ({
                "No": index + 1,
                "MR Pasien": item.MRPASIEN || "",
                "Nama Pasien": item.NAMAPASIEN || "",
                "Tanggal Masuk": item.TGLMASUK || "",
                "Tanggal Keluar": item.TGLKELUAR || "",
                "Ruang": item.RUANGRWT_ID || "",
                "Provider": item.PROVIDER || "",
                "Nama Dokter": item.NAMADOKTER || "",
                "Cara Pulang": item.CARAPULANG || "",
                "Tanggal Kembali": item.TGLKEMBALI || "",
                "Selisih Hari": item.SELISIHHARI || 0,
                "Status": Number(item.SELISIHHARI || 0) > 2 ? "Tidak Tepat Waktu" : "Tepat Waktu"
            })
        }
    );

});

function datakunjungan(startDate,endDate){
    $.ajax({
        url       : url+"index.php/rekammedis/berkas/datakunjungan",
        data      : {startdate:startDate,endate:endDate},
        method    : "POST",
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

            $("#resultdataberkas").empty();
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
            globaldatakunjungan = result;

            let jmlpasien          = result.length;
            let jmltidaktepatwaktu = result.filter(x => parseFloat(x.SELISIHHARI) > 2).length;
            let jmltepatwaktu      = result.filter(x => parseFloat(x.SELISIHHARI) <= 2).length;
            let persentidak        = jmlpasien > 0 ? ((jmltidaktepatwaktu / jmlpasien) * 100).toFixed(2) : 0;
            let persentepat        = jmlpasien > 0 ? ((jmltepatwaktu / jmlpasien) * 100).toFixed(2) : 0;

            $("#jmlpasien").text(jmlpasien);
            $("#jmltidaktepatwaktu").text(jmltidaktepatwaktu + " (" + persentidak + "%)");
            $("#jmltepatwaktu").text(jmltepatwaktu + " (" + persentepat + "%)");

            let tableresult = "";
            for (var i in result) {
                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult +="<td class='ps-4'>"+result[i].MRPASIEN+"</td>";
                tableresult +="<td>"+result[i].NAMAPASIEN+"</td>";
                tableresult +="<td class='text-center'>"+result[i].TGLMASUK+"</td>";
                tableresult +="<td class='text-center'>"+result[i].TGLKELUAR+"</td>";
                tableresult +="<td>"+result[i].RUANGRWT_ID+"</td>";
                tableresult +="<td>"+result[i].PROVIDER+"</td>";
                tableresult +="<td>"+result[i].NAMADOKTER+"</td>";
                tableresult +="<td>"+result[i].CARAPULANG+"</td>";
                tableresult +="<td><div>"+(result[i].TGLKEMBALI || "")+"</div><div>"+(result[i].SELISIHHARI ? result[i].SELISIHHARI + " Hari" : "")+"</div></td>";
                tableresult +="<td class='pe-4 text-end'>" + (result[i].SELISIHHARI > 2 ? "<span class='badge bg-danger'>Tidak Tepat Waktu</span>" : "<span class='badge bg-success'>Tepat Waktu</span>") + "</td>";
                tableresult += "</tr>";
            }

            $("#resultdataberkas").html(tableresult);

            const table = initDataTable("#databerkas_table","#searchtable");
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
    return false;
};