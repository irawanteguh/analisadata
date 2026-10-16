let globaldatawaktutunggurawatjalan = [];
let globalpemabatalanoperasielektif = [];

loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});

$("#btnDownloadgrafikkpiwaktutunggurj").on("click", function () {

    exportToExcel(
        globaldatawaktutunggurawatjalan,
        "Waktu Tunggu Rawat Jalan",
        "Waktu_Tunggu_Rawat_Jalan.xlsx",
        {
            formatter: (item, index) => ({

                "No": index + 1,
                "Bulan": item.BULAN || "",

                "Jumlah Pasien": item.JML || 0,
                "Jumlah Sudah Diperiksa": item.JML_SUDAH_PERIKSA || 0,
                "Jumlah ≤ 60 Menit": item.JML_DIBAWAH_60 || 0,

                "% Pasien Diperiksa ≤ 60 Menit":
                    item.PERSEN_DIBAWAH_60 || 0,

                "Rata-rata Waktu Tunggu (Menit)":
                    item.AVG_WAKTU_TUNGGU || 0

            })
        }
    );

});

$("#btnDownloadgrafikkpiwaktutunggurjcheckinanam").on("click", function () {

    exportToExcel(
        globaldatawaktutunggurawatjalan,
        "Check-in Mulai Anamnesa",
        "Checkin_Mulai_Anamnesa.xlsx",
        {
            formatter: (item, index) => ({

                "No": index + 1,
                "Bulan": item.BULAN || "",

                "Jumlah Pasien": item.JML || 0,
                "Jumlah ≤ 20 Menit": item.JML_DIBAWAH_20_ANAM || 0,

                "% Pasien Memulai Anamnesa ≤ 20 Menit":
                    item.PERSEN_DIBAWAH_20_ANAM || 0,

                "Rata-rata Waktu Check-in - Mulai Anamnesa (Menit)":
                    item.AVG_WAKTU_ANAM || 0

            })
        }
    );

});

$("#btnDownloadgrafikkpiwaktutunggurjselesaianam").on("click", function () {

    exportToExcel(
        globaldatawaktutunggurawatjalan,
        "Waktu Selesai Anamnesa",
        "Waktu_Selesai_Anamnesa.xlsx",
        {
            formatter: (item, index) => ({

                "No": index + 1,
                "Bulan": item.BULAN || "",

                "Jumlah Pasien": item.JML || 0,
                "Jumlah ≤ 10 Menit": item.JML_ANAM_SELESAI_10 || 0,

                "% Pasien Selesai Anamnesa ≤ 10 Menit":
                    item.PERSEN_ANAM_SELESAI_10 || 0,

                "Rata-rata Waktu Anamnesa (Menit)":
                    item.AVG_WAKTU_SELESAI_ANAM || 0

            })
        }
    );

});

$("#btnDownloadgrafikkpiwaktutunggurjmulaidokter").on("click", function () {

    exportToExcel(
        globaldatawaktutunggurawatjalan,
        "Mulai Diperiksa Dokter",
        "Mulai_Diperiksa_Dokter.xlsx",
        {
            formatter: (item, index) => ({

                "No": index + 1,
                "Bulan": item.BULAN || "",

                "Jumlah Pasien": item.JML || 0,
                "Jumlah ≤ 30 Menit": item.JML_ANAM_DOKTER_30 || 0,

                "% Pasien Mulai Diperiksa Dokter ≤ 30 Menit":
                    item.PERSEN_ANAM_DOKTER_30 || 0,

                "Rata-rata Waktu Selesai Anamnesa - Dokter (Menit)":
                    item.AVG_WAKTU_ANAM_DOKTER || 0

            })
        }
    );

});

$("#btnDownloadoperasi").on("click", function () {

    exportToExcel(
        globalpemabatalanoperasielektif,
        "Pembatalan Operasi Elektif",
        "Pembatalan_Operasi_Elektif.xlsx",
        {
            formatter: (item, index) => ({

                "No": index + 1,
                "Tahun": item.TAHUN || "",
                "Bulan": item.BULAN || "",

                "Total Operasi": item.TOTAL || 0,
                "Jumlah Pembatalan": item.BATAL || 0,
                "% Pembatalan Operasi Elektif":
                    item.PERSENTASE_BATAL || 0,

                "Last Update": item.LAST_UPDATE || ""

            })
        }
    );

});

function loaddata(){
    datajampulangpasienbln();
    datajampulangharian();
    datawaktutunggurawatjalan();
    pemabatalanoperasielektif();
    datapasienpulanglebihjam12();
};

function pemabatalanoperasielektif(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/pemabatalanoperasielektif",
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
            globalpemabatalanoperasielektif = result;

            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMap = {};

            result.forEach(item => {
                dataMap[item.BULAN] = {
                    val1: parseFloat(item.PERSENTASE_BATAL) || 0,
                    val2: parseFloat(item.BATAL) || 0
                };
            });

            const chartData = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                Value1: dataMap[b]?.val1 ?? 0,
                Value2: dataMap[b]?.val2 ?? 0
            }));

            renderchartarea(
                "grafikkpioperasi",
                chartData,
                "Periode",
                "% Pembatalan Operasi Elektif",
                ["% Pembatalan", "Jumlah Pembatalan"],
                ["Value1", "Value2"],
                true,
                "Jumlah Pembatalan Operasi",
                "Value1",
                "Jumlah Pembatalan Operasi",
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
            globaldatawaktutunggurawatjalan = result;

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

function datajampulangpasienbln(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/datajampulangpasienbln",
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
                    val1: item.PERSENTASE,
                    val2: item.BIAYA_MAKAN
                };
            });


            const chartData = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                Value1 : dataMap[b]?.val1 ?? 0,
                Value2 : dataMap[b]?.val2 ?? 0
            }));

            renderchartarea("grafikkpipasienpulang",chartData,"Periode Pelayanan","% Pulang < Pukul 12:00",["Presentasi","Biaya Makan"],["Value1","Value2"],true,"Biaya Makan","Value1","Avg % Pulang < Pukul 12:00",null);
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

function datajampulangharian(){
    $.ajax({
        url      : url + "index.php/dashboard/kpi/datajampulangharian",
        type     : "POST",
        dataType : "JSON",
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

            const result       = response.responResult || [];

            const chartData = result.map(item => ({
                periode: item.PERIODE,
                Value1 : item.PERSENTASE ?? 0,
                Value2 : item.BIAYA_MAKAN ?? 0,
                Value3 : item.SEBELUM_12 ?? 0,
                Value4 : item.SESUDAH_12 ?? 0,
                Value5 : parseInt(item.SEBELUM_12 ?? 0, 10) + parseInt(item.SESUDAH_12 ?? 0, 10)
            }));


            renderchartarea("grafikkpipasienpulangharian",chartData,"Periode Pelayanan","% Pulang < Pukul 12:00",["Presentasi","Jml TUD","< Jam 12:00",">= Jam 12:00"],["Value1","Value5","Value3","Value4"],true,"Jumlah Pasien","Value1","Avg % Pulang < Pukul 12:00",null);
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

function datapasienpulanglebihjam12(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/dashboard/kpi/datapasienpulanglebihjam12",
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

            $("#resultdatapasienpulanglebihjam12").empty();
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

            let jumlahKelas1 = 0;
            let jumlahKelas2 = 0;
            let jumlahKelas3 = 0;

            result.forEach(function(item) {
                const kelas = parseInt(item.KELAS_ID, 10);
                const status = parseInt(item.STATUS, 10);

                if (status === 1 || status === 2) {
                    if (kelas === 1) {
                        jumlahKelas1++;
                    } else if (kelas === 2) {
                        jumlahKelas2++;
                    } else if (kelas === 3) {
                        jumlahKelas3++;
                    }
                }
            });

            $("#jml_discharge_kelas1").text(jumlahKelas1.toLocaleString("id-ID"));
            $("#jml_discharge_kelas2").text(jumlahKelas2.toLocaleString("id-ID"));
            $("#jml_discharge_kelas3").text(jumlahKelas3.toLocaleString("id-ID"));

            var tableresult    = "";
            for (var i in result) {
                let btnaction = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";


                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td><div>"+(result[i].MRPASIEN||"")+"</div><div>"+(result[i].NAMAPASIEN||"")+"</div></td>";
                tableresult += "<td>"+(result[i].NAMADOKTER||"")+"</td>";
                tableresult += "<td>"+(result[i].RUANGRWT_ID||"")+"</td>";
                tableresult += "<td>Kelas "+(result[i].KELAS_ID||"")+"</td>";
                tableresult += "<td>"+(result[i].TGLKELUAR||"")+"</td>";
                tableresult += "<td>"+(result[i].STATUS == 1 ? "<span class='badge badge-light-danger'>TUD</span> <span class='badge badge-light-info'>Belum Pulang</span>" : result[i].STATUS == 2 ? "<span class='badge badge-light-info'>Belum Pulang</span>" : result[i].STATUS == 3 ? "<span class='badge badge-light-success'>Sudah Pulang</span>" : "")+"</td>";
                tableresult += "<td class='text-end'>";
                tableresult += "<div class='btn-group'>";
                tableresult += "<button type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown'>Actions</button>";
                tableresult += "<div class='dropdown-menu'>";
                tableresult += btnaction;
                tableresult += "</div></div></td>";
                tableresult += "</tr>";
                tableresult += "</tr>";
            }

            $("#resultdatapasienpulanglebihjam12").html(tableresult);
            initDataTable("#datapasienpulanglebihjam12_table","#searchtable");
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