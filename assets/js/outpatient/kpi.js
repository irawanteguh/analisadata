let globaldetailwwaktutunggu = [];

datawaktutunggurawatjalan();
detailwwaktutunggu();

$('#selectperiode').on('change', function () {
    datawaktutunggurawatjalan();
});

$("#btndownloaddatawaktutunggu_table").on("click", function () {

    exportToExcel(
        globaldetailwwaktutunggu,
        "Waktu Tunggu Rawat Jalan",
        "Waktu_Tunggu_Rawat_Jalan.xlsx",
        {
            formatter: (item, index) => ({

                "No": index + 1,
                "MR": item.MRPAS || "",
                "Nama Pasien": item.NAMAPASIEN || "",
                "Booking ID": item.BOOKINGID || "",
                "Tanggal Masuk": item.TGLMASUK || "",
                "Poliklinik": item.POLIKLINIK || "",
                "Dokter": item.NAMADOKTER || "",

                "Jam Check-in": item.JAM_CHECKIN || "",
                "Jam Mulai Anamnesa": item.JAM_MULAI_ANAM || "",
                "Jam Selesai Anamnesa": item.JAM_SELESAI_ANAM || "",
                "Jam Periksa Dokter": item.JAM_PERIKSA_DOKTER || "",

                "Waktu Tunggu Anamnesa (Menit)": item.WAKTU_TUNGGU_ANAM_MENIT || 0,
                "Waktu Anamnesa (Menit)": item.WAKTU_ANAM_MENIT || 0,
                "Waktu Tunggu Dokter (Menit)": item.WAKTU_TUNGGU_DOKTER_MENIT || 0,
                "Waktu Tunggu Check-in - Dokter (Menit)": item.WAKTU_TUNGGU_MENIT || 0,

                "Waktu Tunggu Check-in - Dokter": item.WAKTU_TUNGGU || ""

            })
        }
    );

});

function datawaktutunggurawatjalan(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/datawaktutunggurawatjalan",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

        beforeSend: function () {
            Swal.fire({
                title: 'Processing',
                html : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMap = {};

            result.forEach(item => {
                dataMap[item.BULAN] = {
                    val1: parseFloat(item.PERSEN_DIBAWAH_60) || 0,
                    val2: parseFloat(item.AVG_WAKTU_TUNGGU) || 0,
                    val3: parseFloat(item.PERSEN_DIBAWAH_20_ANAM) || 0,
                    val4: parseFloat(item.AVG_WAKTU_ANAM) || 0,
                    val5: parseFloat(item.PERSEN_ANAM_SELESAI_10) || 0,
                    val6: parseFloat(item.AVG_WAKTU_SELESAI_ANAM) || 0,
                    val7: parseFloat(item.PERSEN_ANAM_DOKTER_30) || 0,
                    val8: parseFloat(item.AVG_WAKTU_ANAM_DOKTER) || 0
                };
            });

            const chartData = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                Value1: dataMap[b]?.val1 ?? 0,
                Value2: dataMap[b]?.val2 ?? 0,
                Value3: dataMap[b]?.val3 ?? 0,
                Value4: dataMap[b]?.val4 ?? 0,
                Value5: dataMap[b]?.val5 ?? 0,
                Value6: dataMap[b]?.val6 ?? 0,
                Value7: dataMap[b]?.val7 ?? 0,
                Value8: dataMap[b]?.val8 ?? 0
            }));

            renderchartarea(
                "grafikkpiwaktutunggurj",
                chartData,
                "Periode Pelayanan",
                "% Pasien Diperiksa ≤ 60 Menit",
                ["% ≤ 60 Menit", "Rata-rata Waktu Tunggu (Menit)"],
                ["Value1", "Value2"],
                true,
                "Rata-rata Waktu Tunggu",
                "Value1",
                "Rata-rata Waktu Tunggu (Menit)",
                null
            );

            renderchartarea(
                "grafikkpiwaktutunggurjcheckinanam",
                chartData,
                "Periode Pelayanan",
                "% Pasien Memulai Anamnesa ≤ 20 Menit",
                ["% ≤ 20 Menit", "Rata-rata Waktu Tunggu"],
                ["Value3", "Value4"],
                true,
                "Rata-rata Waktu Tunggu",
                "Value3",
                "Rata-rata Waktu Tunggu (Menit)",
                null
            );

            renderchartarea(
                "grafikkpiwaktutunggurjselesaianam",
                chartData,
                "Periode Pelayanan",
                "% Pasien Selesai Anamnesa ≤ 10 Menit",
                ["% ≤ 10 Menit", "Rata-rata Waktu Anamnesa"],
                ["Value5","Value6"],
                true,
                "Rata-rata Waktu Tunggu",
                "Value5",
                "Rata-rata Waktu Tunggu (Menit)",
                null
            );

            renderchartarea(
                "grafikkpiwaktutunggurjmulaidokter",
                chartData,
                "Periode Pelayanan",
                "% Pasien Mulai Diperiksa Dokter ≤ 30 Menit Setelah Anamnesa",
                ["% ≤ 30 Menit", "Rata-rata Waktu Tunggu (Menit)"],
                ["Value7", "Value8"],
                true,
                "Rata-rata Waktu Tunggu",
                "Value7",
                "Rata-rata Waktu Tunggu (Menit)",
                null
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

function detailwwaktutunggu(){
    $.ajax({
        url       : url +"index.php/outpatient/kpi/detailwwaktutunggu",
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

            $("#resultdatawaktutunggu").empty();
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
            globaldetailwwaktutunggu = result;

            var tableresult    = "";
            for (var i in result) {

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].MRPAS||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMAPASIEN||"")+"</td>";
                tableresult += "<td>"+(result[i].BOOKINGID||"")+"</td>";
                tableresult += "<td>"+(result[i].TGLMASUK||"")+"</td>";
                tableresult += "<td>"+(result[i].POLIKLINIK||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMADOKTER||"")+"</td>";
                tableresult += "<td>"+(result[i].JAM_CHECKIN||"")+"</td>";
                tableresult += "<td>"+(result[i].JAM_MULAI_ANAM||"")+"</td>";
                tableresult += "<td>"+(result[i].JAM_SELESAI_ANAM||"")+"</td>";
                tableresult += "<td>"+(result[i].JAM_PERIKSA_DOKTER||"")+"</td>";
                var waktuTunggu = parseInt(result[i].WAKTU_TUNGGU_MENIT); tableresult += "<td class='text-end pe-4 fw-bold'>" + (isNaN(waktuTunggu) ? "<span class='badge badge-light-warning'>-</span>" : waktuTunggu > 60 ? "<span class='badge badge-light-danger'>" + result[i].WAKTU_TUNGGU + "</span>" : "<span class='badge badge-light-success'>" + result[i].WAKTU_TUNGGU + "</span>") + "</td>";
                tableresult += "</tr>";
            }

            $("#resultdatawaktutunggu").html(tableresult);
            const table = initDataTable("#datawaktutunggu_table","#searchtable",50);
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