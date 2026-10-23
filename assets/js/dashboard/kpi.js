loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});


function loaddata(){
    rawatjalanwaktutunggu();
    rawatjalanlamapelayanan();
    radiologi();
    rawatinap();
    rawatinapdaily();
    rawatinapdailydetail();
    pembatalanoperasielektif();
};

function radiologi(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/radiologi",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        pemeriksaan        : 0,
                        tercapai           : 0,
                        tidakTercapai      : 0,
                        persenTercapai     : 0,
                        persenTidakTercapai: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || "").padStart(2, "0");
                    if (!monthlyData[bulan]) return;

                    monthlyData[bulan] = {
                        bulan,
                        pemeriksaan        : Number(item.JML_PEMERIKSAAN) || 0,
                        tercapai           : Number(item.JML_TERCAPAI) || 0,
                        tidakTercapai      : Number(item.JML_TIDAK_TERCAPAI) || 0,
                        persenTercapai     : Number(item.PERSEN_TERCAPAI) || 0,
                        persenTidakTercapai: Number(item.PERSEN_TIDAK_TERCAPAI) || 0
                    };
                });

                return bulanLengkap.map(bulan => {
                    const item = monthlyData[bulan];

                    return {
                        periode: namaBulan[Number(bulan) - 1],
                        Value1: item.persenTercapai,
                        Value2: item.persenTidakTercapai,
                        Value3: item.pemeriksaan,
                        Value4: item.tercapai,
                        Value5: item.tidakTercapai
                    };
                });
            })();

            renderchartarea(
                "grafikradiologi",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                ["Persentase Tercapai", "Jml Tercapai", "Jml Tidak Tercapai"],
                ["Value1", "Value4","Value5"],
                true,
                "Jumlah Pemeriksaan",
                "Value3",
                "KPI Waktu Pelayanan ≤ 60 Menit",
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

function rawatjalanwaktutunggu(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/rawatjalanwaktutunggu",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        jmlall: 0,
                        jmlsebelum60all: 0,
                        jmlsesudah60all: 0,
                        presentasiall: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || "").padStart(2, "0");
                    if (!monthlyData[bulan]) return;

                    monthlyData[bulan] = {
                        bulan,
                        jmlall         : Number(item.TOTAL_PASIEN) || 0,
                        jmlsebelum60all: Number(item.JUMLAH_PASIEN_KURANG_SAMA_60_MENIT) || 0,
                        jmlsesudah60all: Number(item.JUMLAH_PASIEN_LEBIH_60_MENIT) || 0,
                        presentasiall  : Number(item.PERSENTASE_PASIEN_KURANG_SAMA_60_MENIT) || 0
                    };
                });

                return bulanLengkap.map(bulan => {
                    const item = monthlyData[bulan];

                    return {
                        periode: namaBulan[Number(bulan) - 1],

                        Value1: item.jmlall,
                        Value2: item.jmlsebelum60all,
                        Value3: item.jmlsesudah60all,
                        Value4: item.presentasiall
                    };
                });
            })();

            renderchartarea(
                "kpirawatjalanwaktutunggu",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                [
                    "Persentase <= 60 Menit",
                    "Jml <= 60 Menit",
                    "Jml > 60 Menit"
                ],
                ["Value4", "Value2", "Value3"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value2"]
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

function rawatjalanlamapelayanan(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/rawatjalanlamapelayanan",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        jmlall: 0,
                        jmlsebelum60all: 0,
                        jmlsesudah60all: 0,
                        presentasiall: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || "").padStart(2, "0");
                    if (!monthlyData[bulan]) return;

                    monthlyData[bulan] = {
                        bulan,
                        jmlall         : Number(item.TOTAL_PASIEN) || 0,
                        jmlsebelum60all: Number(item.JUMLAH_PASIEN_KURANG_SAMA_120_MENIT) || 0,
                        jmlsesudah60all: Number(item.JUMLAH_PASIEN_LEBIH_120_MENIT) || 0,
                        presentasiall  : Number(item.PERSENTASE_PASIEN_KURANG_SAMA_120_MENIT) || 0
                    };
                });

                return bulanLengkap.map(bulan => {
                    const item = monthlyData[bulan];

                    return {
                        periode: namaBulan[Number(bulan) - 1],

                        Value1: item.jmlall,
                        Value2: item.jmlsebelum60all,
                        Value3: item.jmlsesudah60all,
                        Value4: item.presentasiall
                    };
                });
            })();

            renderchartarea(
                "kpirawatjalanwaktupelayanan",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                [
                    "Persentase <= 60 Menit",
                    "Jml <= 60 Menit",
                    "Jml > 60 Menit"
                ],
                ["Value4", "Value2", "Value3"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value2"]
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

function rawatinap(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/rawatinap",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        jmlall: 0,
                        jmlsebelum12all: 0,
                        jmlsesudah12all: 0,
                        presentasiall: 0,
                        jmltutd: 0,
                        jmlsebelum12tutd: 0,
                        jmlsesudah12tutd: 0,
                        presentasitutd: 0,
                        jmlnontutd: 0,
                        jmlsebelum12nontutd: 0,
                        jmlsesudah12nontutd: 0,
                        presentasinontutd: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || "").padStart(2, "0");
                    if (!monthlyData[bulan]) return;

                    monthlyData[bulan] = {
                        bulan,
                        jmlall: Number(item.JUMLAHALL) || 0,
                        jmlsebelum12all: Number(item.SEBELUM12ALL) || 0,
                        jmlsesudah12all: Number(item.SESUDAH12ALL) || 0,
                        presentasiall: Number(item.PRESENTASIALL) || 0,

                        jmltutd: Number(item.JMLTUTD) || 0,
                        jmlsebelum12tutd: Number(item.SEBELUM12TUTD) || 0,
                        jmlsesudah12tutd: Number(item.SESUDAH12TUTD) || 0,
                        presentasitutd: Number(item.PRESENTASITUTD) || 0,

                        jmlnontutd: Number(item.JMLNONTUTD) || 0,
                        jmlsebelum12nontutd: Number(item.SEBELUM12NONTUTD) || 0,
                        jmlsesudah12nontutd: Number(item.SESUDAH12NONTUTD) || 0,
                        presentasinontutd: Number(item.PRESENTASINONTUTD) || 0
                    };
                });

                return bulanLengkap.map(bulan => {
                    const item = monthlyData[bulan];

                    return {
                        periode: namaBulan[Number(bulan) - 1],

                        Value1: item.jmlall,
                        Value2: item.jmlsebelum12all,
                        Value3: item.jmlsesudah12all,
                        Value4: item.presentasiall,

                        Value5: item.jmltutd,
                        Value6: item.jmlsebelum12tutd,
                        Value7: item.jmlsesudah12tutd,
                        Value8: item.presentasitutd,

                        Value9 : item.jmlnontutd,
                        Value10: item.jmlsebelum12nontutd,
                        Value11: item.jmlsesudah12nontutd,
                        Value12: item.presentasinontutd
                    };
                });
            })();

            renderchartarea(
                "kpirawatinapall",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                [
                    "Persentase Pulang Sebelum Jam 12:00",
                    "Jml Sebelum Jam 12:00",
                    "Jml Sesudah Jam 12:00"
                ],
                ["Value4", "Value2", "Value3"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value2"]
            );

            renderchartarea(
                "kpirawatinaptutd",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                ["Persentase Pulang Sebelum Jam 12:00", "Jml Sebelum Jam 12:00", "Jml Sesudah Jam 12:00"],
                ["Value8", "Value6", "Value7"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value6"]
            );

            renderchartarea(
                "kpirawatinapnontutd",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                ["Persentase Pulang Sebelum Jam 12:00", "Jml Sebelum Jam 12:00", "Jml Sesudah Jam 12:00"],
                ["Value12", "Value10", "Value11"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value10"]
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

function rawatinapdaily(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/rawatinapdaily",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];

            const DailyData = {};

            result.forEach(item => {
                if (!item.PERIODE) return;

                const [tanggal, bulan, tahun] = item.PERIODE.split(".");
                if (!tanggal || !bulan || !tahun) return;

                const key = `${tanggal}.${bulan}.${tahun}`;

                DailyData[key] = {
                    periode          : key,

                    jumlahAll        : Number(item.JUMLAHALL) || 0,
                    sebelum12        : Number(item.SEBELUM12ALL) || 0,
                    sesudah12        : Number(item.SESUDAH12ALL) || 0,
                    presentasiAll    : Number(item.PRESENTASIALL) || 0,

                    jmlTUTD          : Number(item.JMLTUTD) || 0,
                    tutdSebelum12    : Number(item.SEBELUM12TUTD) || 0,
                    tutdSesudah12    : Number(item.SESUDAH12TUTD) || 0,
                    presentasiTUTD   : Number(item.PRESENTASITUTD) || 0,

                    jmlNonTUTD       : Number(item.JMLNONTUTD) || 0,
                    nonTUTDSebelum12 : Number(item.SEBELUM12NONTUTD) || 0,
                    nonTUTDSesudah12 : Number(item.SESUDAH12NONTUTD) || 0,
                    presentasiNonTUTD: Number(item.PRESENTASINONTUTD) || 0
                };
            });

            const chartgrafikdailykpi = result.map(item => {
                const jumlahAll          = Number(item.JUMLAHALL) || 0;
                const sebelum12          = Number(item.SEBELUM12ALL) || 0;
                const sesudah12           = Number(item.SESUDAH12ALL) || 0;
                const presentasiAll      = Number(item.PRESENTASIALL) || 0;

                const jmlTUTD            = Number(item.JMLTUTD) || 0;
                const tutdSebelum12      = Number(item.SEBELUM12TUTD) || 0;
                const tutdSesudah12      = Number(item.SESUDAH12TUTD) || 0;
                const presentasiTUTD     = Number(item.PRESENTASITUTD) || 0;

                const jmlNonTUTD         = Number(item.JMLNONTUTD) || 0;
                const nonTUTDSebelum12   = Number(item.SEBELUM12NONTUTD) || 0;
                const nonTUTDSesudah12   = Number(item.SESUDAH12NONTUTD) || 0;
                const presentasiNonTUTD  = Number(item.PRESENTASINONTUTD) || 0;

                return {
                    periode: item.PERIODE,
                    Value1: jumlahAll,
                    Value2: sebelum12,
                    Value3: sesudah12,
                    Value4: presentasiAll,
                    Value5: jmlTUTD,
                    Value6: tutdSebelum12,
                    Value7: tutdSesudah12,
                    Value8: presentasiTUTD,
                    Value9: jmlNonTUTD,
                    Value10: nonTUTDSebelum12,
                    Value11: nonTUTDSesudah12,
                    Value12: presentasiNonTUTD
                };
            })
            .sort((a, b) => {
                const [dayA, monthA, yearA] = a.periode.split('.').map(Number);
                const [dayB, monthB, yearB] = b.periode.split('.').map(Number);

                return new Date(yearA, monthA - 1, dayA) -
                    new Date(yearB, monthB - 1, dayB);
            });

            renderchartarea(
                "kpirawatinapalldaily",
                chartgrafikdailykpi,
                "Tanggal",
                "Persentase (%)",
                ["Persentase Pulang Sebelum Jam 12:00", "Jml Sebelum Jam 12:00", "Jml Sesudah Jam 12:00"],
                ["Value4", "Value2", "Value3"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value2"]
            );

            renderchartarea(
                "kpirawatinaptutddaily",
                chartgrafikdailykpi,
                "Tanggal",
                "Persentase (%)",
                ["Persentase Pulang Sebelum Jam 12:00", "Jml Sebelum Jam 12:00", "Jml Sesudah Jam 12:00"],
                ["Value8", "Value6", "Value7"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value6"]
            );

            renderchartarea(
                "kpirawatinapnontutddaily",
                chartgrafikdailykpi,
                "Tanggal",
                "Persentase (%)",
                ["Persentase Pulang Sebelum Jam 12:00", "Jml Sebelum Jam 12:00", "Jml Sesudah Jam 12:00"],
                ["Value12", "Value10", "Value11"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value10"]
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

function rawatinapdailydetail(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/dashboard/kpi/rawatinapdailydetail",
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

            const result       = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        jmlall: 0,
                        jmlsebelum12all: 0,
                        jmlsesudah12all: 0,
                        presentasiall: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || "").padStart(2, "0");
                    if (!monthlyData[bulan]) return;

                    monthlyData[bulan] = {
                        bulan,
                        jmlall         : Number(item.TOTAL) || 0,
                        jmlsebelum12all: Number(item.REGISTER) || 0,
                        jmlsesudah12all: Number(item.BATAL) || 0,
                        presentasiall  : Number(item.PRESENTASI) || 0
                    };
                });

                return bulanLengkap.map(bulan => {
                    const item = monthlyData[bulan];

                    return {
                        periode: namaBulan[Number(bulan) - 1],

                        Value1: item.jmlall,
                        Value2: item.jmlsebelum12all,
                        Value3: item.jmlsesudah12all,
                        Value4: item.presentasiall
                    };
                });
            })();

            renderchartarea(
                "grafikkpiokmountly",
                chartgrafikmonthlykpi,
                "Bulan",
                "Persentase (%)",
                [
                    "Persentase Batal Tindakan",
                    "Register",
                    "Batal"
                ],
                ["Value4", "Value2", "Value3"],
                true,
                "Jumlah",
                null,
                "",
                null,
                ["Value2"]
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