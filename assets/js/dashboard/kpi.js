loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});


function loaddata(){
    kpipasienrawatinappulangagregate();
    kpipasienrawatinappulangdetail();
    pembatalanoperasielektif();
};

function kpipasienrawatinappulangagregate(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/kpipasienrawatinappulangagregate",
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

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                const tahunData = [
                    ...new Set(
                        result
                            .filter(item => item.PERIODE)
                            .map(item => item.PERIODE.split(".")[2])
                            .filter(Boolean)
                    )
                ];

                tahunData.forEach(tahun => {
                    bulanLengkap.forEach(bulan => {
                        const key = `${bulan}.${tahun}`;

                        monthlyData[key] = {
                            periode: key,
                            bulan,
                            tahun,
                            sebelum12: 0,
                            sesudah12: 0,
                            tutdSebelum12: 0,
                            tutdSesudah12: 0
                        };
                    });
                });

                result.forEach(item => {
                    if (!item.PERIODE) return;

                    const [tanggal, bulan, tahun] = item.PERIODE.split(".");
                    if (!bulan || !tahun) return;

                    const key = `${bulan}.${tahun}`;

                    if (!monthlyData[key]) {
                        monthlyData[key] = {
                            periode: key,
                            bulan,
                            tahun,
                            sebelum12: 0,
                            sesudah12: 0,
                            tutdSebelum12: 0,
                            tutdSesudah12: 0
                        };
                    }

                    monthlyData[key].sebelum12 += Number(item.SEBELUM_12) || 0;
                    monthlyData[key].sesudah12 += Number(item.SESUDAH_12) || 0;
                    monthlyData[key].tutdSebelum12 += Number(item.TUTD_SEBELUM_12) || 0;
                    monthlyData[key].tutdSesudah12 += Number(item.TUTD_SESUDAH_12) || 0;
                });

                return Object.values(monthlyData)
                    .sort((a, b) =>
                        Number(a.tahun) - Number(b.tahun) ||
                        Number(a.bulan) - Number(b.bulan)
                    )
                    .map(item => {
                        const total = item.sebelum12 + item.sesudah12;
                        const totalTUTD = item.tutdSebelum12 + item.tutdSesudah12;

                        return {
                            periode: namaBulan[Number(item.bulan) - 1] || item.bulan,

                            Value1: item.sebelum12,
                            Value2: item.sesudah12,
                            Value3: item.tutdSebelum12,
                            Value4: item.tutdSesudah12,

                            Value5: total > 0
                                ? Number(((item.sebelum12 / total) * 100).toFixed(2))
                                : 0,

                            Value6: totalTUTD > 0
                                ? Number(((item.tutdSebelum12 / totalTUTD) * 100).toFixed(2))
                                : 0
                        };
                    });
            })();

            const chartgrafikdailykpi = result.slice(0, 60).reverse().map(item => {
                const sebelum12      = parseInt(item.SEBELUM_12, 10) || 0;
                const sesudah12      = parseInt(item.SESUDAH_12, 10) || 0;
                const tutdSebelum12  = parseInt(item.TUTD_SEBELUM_12, 10) || 0;
                const tutdSesudah12  = parseInt(item.TUTD_SESUDAH_12, 10) || 0;

                return {
                    periode: item.PERIODE,
                    Value1 : sebelum12,
                    Value2 : sesudah12,
                    Value3 : tutdSebelum12,
                    Value4 : tutdSesudah12,
                    Value5 : (sebelum12 + sesudah12) > 0 ? parseFloat(((sebelum12 / (sebelum12 + sesudah12)) * 100).toFixed(2)) : 0,
                    Value6 : (tutdSebelum12 + tutdSesudah12) > 0 ? parseFloat(((tutdSebelum12 / (tutdSebelum12 + tutdSesudah12)) * 100).toFixed(2)) : 0
                };
            });


            renderchartarea(
                "grafikkpitutdmountly",
                chartgrafikmonthlykpi,
                "Discharge Date",
                "Persentase (%)",
                ["Persentase", "Sebelum Jam 12:00", "Sesudah Jam 12:00"],
                ["Value6", "Value3", "Value4"],
                true,
                "Jumlah Pasien",
                "",
                "",
                null
            );

            renderchartarea(
                "grafikkpinontutdmountly",
                chartgrafikmonthlykpi,
                "Discharge Date",
                "Persentase (%)",
                ["Persentase", "Sebelum Jam 12:00", "Sesudah Jam 12:00"],
                ["Value5", "Value1", "Value2"],
                true,
                "Jumlah Pasien",
                "",
                "",
                null
            );
            

            renderchartarea(
                "grafikkpitutd",
                chartgrafikdailykpi,
                "Discharge Date",
                "Persentase (%)",
                ["Persentase", "Sebelum Jam 12:00", "Sesudah Jam 12:00"],
                ["Value6", "Value3", "Value4"],
                true,
                "Jumlah Pasien",
                null,
                "Rata-rata",
                null
            );

            renderchartarea(
                "grafikkpinontutd",
                chartgrafikdailykpi,
                "Discharge Date",
                "Persentase (%)",
                ["Persentase", "Sebelum Jam 12:00", "Sesudah Jam 12:00"],
                ["Value5", "Value1", "Value2"],
                true,
                "Jumlah Pasien",
                null,
                "Rata-rata",
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

function kpipasienrawatinappulangdetail(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/dashboard/kpi/kpipasienrawatinappulangdetail",
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
                const jenis = item.JENIS;

                if (jenis === "TUTD" || jenis === "NONTUTD") {
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
                tableresult += "<td class='text-center'>"+(result[i].KELAS_ID||"")+"</td>";
                tableresult += "<td>"+(result[i].TGLKELUAR||"")+"</td>";
                tableresult += "<td>"+(result[i].JENIS == "TUTD" ? "<span class='badge badge-light-danger'>TUTD</span></br><span class='badge badge-light-info'>Belum Pulang</span>" : result[i].JENIS == "NONTUTD" ? "<span class='badge badge-light-danger'>NON TUTD</span></br><span class='badge badge-light-info'>Belum Pulang</span>" : result[i].JENIS == "CLOSSING" ? "<span class='badge badge-light-success'>Sudah Pulang</span>" : "")+"</td>";
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

function pembatalanoperasielektif(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/pembatalanoperasielektif",
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

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                const tahunData = [
                    ...new Set(
                        result
                            .filter(item => item.PERIODE)
                            .map(item => item.PERIODE.split(".")[2])
                            .filter(Boolean)
                    )
                ];

                tahunData.forEach(tahun => {
                    bulanLengkap.forEach(bulan => {
                        const key = `${bulan}.${tahun}`;

                        monthlyData[key] = {
                            periode: key,
                            bulan,
                            tahun,
                            register: 0,
                            batal: 0
                        };
                    });
                });

                result.forEach(item => {
                    if (!item.PERIODE) return;

                    const [tanggal, bulan, tahun] = item.PERIODE.split(".");
                    if (!bulan || !tahun) return;

                    const key = `${bulan}.${tahun}`;

                    if (!monthlyData[key]) {
                        monthlyData[key] = {
                            periode: key,
                            bulan,
                            tahun,
                            register: 0,
                            batal: 0
                        };
                    }

                    monthlyData[key].register += Number(item.REGISTER) || 0;
                    monthlyData[key].batal += Number(item.BATAL) || 0;
                });

                return Object.values(monthlyData)
                    .sort((a, b) =>
                        Number(a.tahun) - Number(b.tahun) ||
                        Number(a.bulan) - Number(b.bulan)
                    )
                    .map(item => {
                        const total = item.register + item.batal;

                        return {
                            periode: namaBulan[Number(item.bulan) - 1] || item.bulan,
                            Value1: item.register,
                            Value2: item.batal,
                            Value3: total > 0
                                ? Number(((item.batal / total) * 100).toFixed(2))
                                : 0
                        };
                    });
            })();

            const chartgrafikdailykpi = result.slice(0, 30).reverse().map(item => {
                const register = parseInt(item.REGISTER, 10) || 0;
                const batal    = parseInt(item.BATAL, 10) || 0;

                return {
                    periode: item.PERIODE,
                    Value1 : register,
                    Value2 : batal,
                    Value3 : (register + batal) > 0 ? parseFloat(((batal / (register + batal)) * 100).toFixed(2)) : 0
                };
            });


            renderchartarea(
                "grafikkpiokmountly",
                chartgrafikmonthlykpi,
                "Procedure Date",
                "Persentase (%)",
                ["Persentase", "Jumlah Batal Operasi"],
                ["Value3", "Value2"],
                true,
                "Jumlah Operasi",
                "Value3",
                "Avg Pembatalan Operasi",
                null
            );

            renderchartarea(
                "grafikkpiokdaily",
                chartgrafikdailykpi,
                "Procedure Date",
                "Persentase (%)",
                ["Persentase", "Jumlah Batal Operasi"],
                ["Value3", "Value2"],
                true,
                "Jumlah Operasi",
                "Value3",
                "Avg Pembatalan Operasi",
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