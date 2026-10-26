loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});


function loaddata(){
    radiologi();
    rawatinap();
    rawatinapdaily();
    rawatinapheatmap();
    rawatinapdailydetail();
    pembatalanoperasielektif();
    lamalayananigd();
    waktutunggumasukranap();
    rawatjalanwaktutunggu();
    rawatjalanlamapelayanan();
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

            const MonthlyData = {};

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        Value1: 0,
                        Value2: 0,
                        Value3: 0,
                        Value4: 0,
                        Value5: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.PERSEN_TERCAPAI) || 0;
                    const Value2 = Number(item.PERSEN_TIDAK_TERCAPAI) || 0;
                    const Value3 = Number(item.JML_PEMERIKSAAN) || 0;
                    const Value4 = Number(item.JML_TERCAPAI) || 0;
                    const Value5 = Number(item.JML_TIDAK_TERCAPAI) || 0;

                    monthlyData[bulan] = {
                        bulan,
                        Value1,
                        Value2,
                        Value3,
                        Value4,
                        Value5
                    };
                });

                Object.assign(MonthlyData, monthlyData);

                return bulanLengkap.map(bulan => ({
                    periode: namaBulan[Number(bulan) - 1],
                    ...Object.fromEntries(
                        Object.entries(monthlyData[bulan])
                            .filter(([key]) => key.startsWith('Value'))
                    )
                }));
            })();

            renderChartArea({
                chart: {
                    elementId: 'grafikradiologi',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        },
                        {
                            name: '≤ 60 Menit',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value4)
                        },
                        {
                            name: '> 60 Menit',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value5)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Order Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 100,
                    name      : 'Target Pencapaian',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

            const MonthlyData = {};

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        Value1: 0,
                        Value2: 0,
                        Value3: 0,
                        Value4: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.TOTAL_PASIEN) || 0;
                    const Value2 = Number(item.JUMLAH_PASIEN_KURANG_SAMA_60_MENIT) || 0;
                    const Value3 = Number(item.JUMLAH_PASIEN_LEBIH_60_MENIT) || 0;
                    const Value4 = Number(item.PERSENTASE_PASIEN_KURANG_SAMA_60_MENIT) || 0;

                    monthlyData[bulan] = {
                        bulan,
                        Value1,
                        Value2,
                        Value3,
                        Value4
                    };
                });

                Object.assign(MonthlyData, monthlyData);

                return bulanLengkap.map(bulan => ({
                    periode: namaBulan[Number(bulan) - 1],
                    ...Object.fromEntries(
                        Object.entries(monthlyData[bulan])
                            .filter(([key]) => key.startsWith('Value'))
                    )
                }));
            })();

            renderChartArea({
                chart: {
                    elementId: 'kpirawatjalanwaktutunggu',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value4)
                        },
                        {
                            name: '≤ 60 Menit',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value2)
                        },
                        {
                            name: '> 60 Menit',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value3)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 80,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

            renderChartArea({
                chart: {
                    elementId: 'kpirawatjalanwaktupelayanan',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value4)
                        },
                        {
                            name: '≤ 60 Menit',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value2)
                        },
                        {
                            name: '> 60 Menit',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value3)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 80,
                    name      : 'Target Pencapaian ≥',
                    suffix    : 'Menit',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

            const MonthlyData = {};

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        Value1: 0,
                        Value2: 0,
                        Value3: 0,
                        Value4: 0,
                        Value5: 0,
                        Value6: 0,
                        Value7: 0,
                        Value8: 0,
                        Value9: 0,
                        Value10: 0,
                        Value11: 0,
                        Value12: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1  = Number(item.JUMLAHALL) || 0;
                    const Value2  = Number(item.SEBELUM12ALL) || 0;
                    const Value3  = Number(item.SESUDAH12ALL) || 0;
                    const Value4  = Number(item.PRESENTASIALL) || 0;
                    const Value5  = Number(item.JMLTUTD) || 0;
                    const Value6  = Number(item.SEBELUM12TUTD) || 0;
                    const Value7  = Number(item.SESUDAH12TUTD) || 0;
                    const Value8  = Number(item.PRESENTASITUTD) || 0;
                    const Value9  = Number(item.JMLNONTUTD) || 0;
                    const Value10 = Number(item.SEBELUM12NONTUTD) || 0;
                    const Value11 = Number(item.SESUDAH12NONTUTD) || 0;
                    const Value12 = Number(item.PRESENTASINONTUTD) || 0;

                    monthlyData[bulan] = {
                        bulan,
                        Value1,
                        Value2,
                        Value3,
                        Value4,
                        Value5,
                        Value6,
                        Value7,
                        Value8,
                        Value9,
                        Value10,
                        Value11,
                        Value12
                    };
                });

                Object.assign(MonthlyData, monthlyData);

                return bulanLengkap.map(bulan => ({
                    periode: namaBulan[Number(bulan) - 1],
                    ...Object.fromEntries(
                        Object.entries(monthlyData[bulan])
                            .filter(([key]) => key.startsWith('Value'))
                    )
                }));
            })();

            renderChartArea({
                chart: {
                    elementId: 'kpirawatinapall',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value4)
                        },
                        {
                            name: '< Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value2)
                        },
                        {
                            name: '≥ Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value3)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Discharge Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 90,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });

            renderChartArea({
                chart: {
                    elementId: 'kpirawatinaptutd',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value8)
                        },
                        {
                            name: '< Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value6)
                        },
                        {
                            name: '≥ Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value7)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Discharge Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 90,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });

            renderChartArea({
                chart: {
                    elementId: 'kpirawatinapnontutd',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value12)
                        },
                        {
                            name: '< Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value10)
                        },
                        {
                            name: '≥ Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value11)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Discharge Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 90,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

            const chartgrafikdailykpi = (() => {
                const dailyData = {};

                result.forEach(item => {
                    if (!item.PERIODE) return;

                    const [tanggal, bulan, tahun] = item.PERIODE.split('.');
                    if (!tanggal || !bulan || !tahun) return;

                    const periode = item.PERIODE;

                    const Value1  = Number(item.JUMLAHALL) || 0;
                    const Value2  = Number(item.SEBELUM12ALL) || 0;
                    const Value3  = Number(item.SESUDAH12ALL) || 0;
                    const Value4  = Number(item.PRESENTASIALL) || 0;
                    const Value5  = Number(item.JMLTUTD) || 0;
                    const Value6  = Number(item.SEBELUM12TUTD) || 0;
                    const Value7  = Number(item.SESUDAH12TUTD) || 0;
                    const Value8  = Number(item.PRESENTASITUTD) || 0;
                    const Value9  = Number(item.JMLNONTUTD) || 0;
                    const Value10 = Number(item.SEBELUM12NONTUTD) || 0;
                    const Value11 = Number(item.SESUDAH12NONTUTD) || 0;
                    const Value12 = Number(item.PRESENTASINONTUTD) || 0;

                    dailyData[periode] = {
                        periode,
                        Value1,
                        Value2,
                        Value3,
                        Value4,
                        Value5,
                        Value6,
                        Value7,
                        Value8,
                        Value9,
                        Value10,
                        Value11,
                        Value12,
                        _sortDate: new Date(
                            Number(tahun),
                            Number(bulan) - 1,
                            Number(tanggal)
                        ).getTime()
                    };
                });

                Object.assign(DailyData, dailyData);

                return Object.values(dailyData)
                    .sort((a, b) => a._sortDate - b._sortDate)
                    .map(({ _sortDate, ...item }) => item);
            })();

            renderChartArea({
                chart: {
                    elementId: 'kpirawatinapalldaily',
                    height: 350
                },

                data: {
                    categories: chartgrafikdailykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikdailykpi.map(item => item.Value4)
                        },
                        {
                            name: '< Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikdailykpi.map(item => item.Value2)
                        },
                        {
                            name: '≥ Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikdailykpi.map(item => item.Value3)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Discharge Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 90,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });

            renderChartArea({
                chart: {
                    elementId: 'kpirawatinaptutddaily',
                    height: 350
                },

                data: {
                    categories: chartgrafikdailykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikdailykpi.map(item => item.Value8)
                        },
                        {
                            name: '< Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikdailykpi.map(item => item.Value6)
                        },
                        {
                            name: '≥ Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikdailykpi.map(item => item.Value7)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Discharge Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 90,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });

            renderChartArea({
                chart: {
                    elementId: 'kpirawatinapnontutddaily',
                    height: 350
                },

                data: {
                    categories: chartgrafikdailykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikdailykpi.map(item => item.Value12)
                        },
                        {
                            name: '< Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikdailykpi.map(item => item.Value10)
                        },
                        {
                            name: '≥ Jam 12:00',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikdailykpi.map(item => item.Value11)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Discharge Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 90,
                    name      : 'Target Pencapaian ≥',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

function rawatinapheatmap(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/rawatinapheatmap",
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

            renderchartheatmap(
                "kpirawatinapallheatmap",
                result,
                "Inpatient Discharges by Hour",
                "Hour of Discharge",
                "Discharge Date",
                "PERIODE",
                "JAM_"
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

            const MonthlyData = {};

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        Value1: 0,
                        Value2: 0,
                        Value3: 0,
                        Value4: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.TOTAL) || 0;
                    const Value2 = Number(item.REGISTER) || 0;
                    const Value3 = Number(item.BATAL) || 0;
                    const Value4 = Number(item.PRESENTASI) || 0;

                    monthlyData[bulan] = {
                        bulan,
                        Value1,
                        Value2,
                        Value3,
                        Value4
                    };
                });

                Object.assign(MonthlyData, monthlyData);

                return bulanLengkap.map(bulan => ({
                    periode: namaBulan[Number(bulan) - 1],
                    ...Object.fromEntries(
                        Object.entries(monthlyData[bulan])
                            .filter(([key]) => key.startsWith('Value'))
                    )
                }));
            })();

            renderChartArea({
                chart: {
                    elementId: 'grafikkpiokmountly',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Presentasi',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value4)
                        },
                        {
                            name: 'Finish',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value2)
                        },
                        {
                            name: 'Canceled',
                            type: 'area',
                            yAxisIndex: 1,
                            data: chartgrafikmonthlykpi.map(item => item.Value3)
                        }              
                    ]
                },

                xAxis: {
                    title: 'Procedure Date'
                },

                yAxes: [
                    {
                        title: 'Presentasi (%)',
                        min: 0,
                        suffix: ' %',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    },
                    {
                        title: 'Jml Pasien',
                        min: 0,
                        suffix: ' Px',
                        decimals: 0,
                        opposite: true,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' %']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 3,
                    name      : 'Target Pencapaian ≤',
                    suffix    : '%',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

function lamalayananigd(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/lamalayananigd",
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

            const MonthlyData = {};

            const chartgrafikmonthlykpi = (() => {
                const monthlyData = {};

                bulanLengkap.forEach(bulan => {
                    monthlyData[bulan] = {
                        bulan,
                        Value1: 0,
                        Value2: 0,
                        Value3: 0,
                        Value4: 0,
                        Value5: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.TOTAL) || 0;
                    const Value2 = Number(item.JUMLAH_PASIEN_KURANG_SAMA_240_MENIT) || 0;
                    const Value3 = Number(item.JUMLAH_PASIEN_LEBIH_240_MENIT) || 0;
                    const Value4 = Number(item.PERSENTASE_PASIEN_KURANG_SAMA_240_MENIT) || 0;
                    const Value5 = Number(item.AVG_DURASI_MENIT) || 0;

                    monthlyData[bulan] = {
                        bulan,
                        Value1,
                        Value2,
                        Value3,
                        Value4,
                        Value5
                    };
                });

                Object.assign(MonthlyData, monthlyData);

                return bulanLengkap.map(bulan => ({
                    periode: namaBulan[Number(bulan) - 1],
                    ...Object.fromEntries(
                        Object.entries(monthlyData[bulan])
                            .filter(([key]) => key.startsWith('Value'))
                    )
                }));
            })();
            
            
            renderChartArea({
                chart: {
                    elementId: 'kpiigdlayalayan',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Avg Lama Layanan',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value5)
                        }                 
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Avg Lama Layanan',
                        min: 0,
                        suffix: ' Minute',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' Minute']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 240,
                    name      : 'Target Pencapaian ≤',
                    suffix    : 'Menit',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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

function waktutunggumasukranap(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/waktutunggumasukranap",
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
                        jmlall         : 0,
                        jmlsebelum12all: 0,
                        jmlsesudah12all: 0,
                        presentasiall  : 0,
                        avgmenit       : 0,
                        presentasiavg  : 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || "").padStart(2, "0");
                    if (!monthlyData[bulan]) return;

                    monthlyData[bulan] = {
                        bulan,
                        jmlall         : Number(item.TOTAL) || 0,
                        jmlsebelum12all: Number(item.JUMLAH_PASIEN_KURANG_SAMA_60_MENIT) || 0,
                        jmlsesudah12all: Number(item.JUMLAH_PASIEN_LEBIH_60_MENIT) || 0,
                        presentasiall  : Number(item.PERSENTASE_PASIEN_KURANG_SAMA_60_MENIT) || 0,
                        avgmenit       : Number(item.AVG_DURASI_MENIT) || 0
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
                        Value5: item.avgmenit
                    };
                });
            })();

            renderChartArea({
                chart: {
                    elementId: 'grafikkpiwaktumasukranap',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Avg Waktu Tunggu Rawat Inap',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value5)
                        }                 
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Avg Waktu Tunggu Rawat Inap',
                        min: 0,
                        suffix: ' Minute',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' Minute']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : true,
                    value     : 60,
                    name      : 'Target Pencapaian ≤',
                    suffix    : 'Menit',
                    color     : '#E53935',
                    dashArray : 5,
                    yAxisIndex: 0
                }
            });
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