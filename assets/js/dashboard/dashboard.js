let globaldatakunjunganrjpoliklinik = [];
let globaldatakunjunganrjdokter     = [];
let globaldatakunjunganrjprovider   = [];

loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});

$("#datapoliklinik_table_downloadexcel").on("click", function () {
    exportToExcel(
        null,
        null,
        "Kunjungan_Rawat_Jalan_Non_Executive_dan_Executive.xlsx",
        {
            multiSheet: [
                {
                    name: "Poliklinik",
                    data: globaldatakunjunganrjpoliklinik,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No"        : index + 1,
                            "Poliklinik": item.POLIKLINIK ?? "",
                            "Jan"       : jan,
                            "Feb"       : feb,
                            "Mar"       : mar,
                            "Apr"       : apr,
                            "Mei"       : mei,
                            "Jun"       : jun,
                            "Jul"       : jul,
                            "Agu"       : agu,
                            "Sep"       : sep,
                            "Okt"       : okt,
                            "Nov"       : nov,
                            "Des"       : des,
                            "Total"     : jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Dokter",
                    data: globaldatakunjunganrjdokter,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Nama Dokter": item.NAMADOKTER ?? "",
                            "SMF": item.KOLEGIUM ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Provider",
                    data: globaldatakunjunganrjprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                }
            ]
        }
    );

});

function loaddata(){
    datakunjunganigd();
    datakunjunganrj();
    datakunjunganrjnonexe();
    datakunjunganrjexe();
    datakunjunganrjpoliklinik();
    datakunjunganrjdokter();
    datakunjunganrjprovider();
    datakunjunganmcu();
    datakunjunganri();
};

function datakunjunganigd(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganigd",
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
                        Value1: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.JML) || 0

                    monthlyData[bulan] = {
                        bulan,
                        Value1
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
                    elementId: 'grafikkunjunganigd',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Jumlah Pasien',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        }            
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Jumlah Pasien',
                        min: 0,
                        suffix: ' px',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' px']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : false,
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

function datakunjunganrj(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganrj",
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
                        Value1: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.JML) || 0

                    monthlyData[bulan] = {
                        bulan,
                        Value1
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
                    elementId: 'grafikkunjunganrjall',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Jumlah Pasien',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        }            
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Jumlah Pasien',
                        min: 0,
                        suffix: ' px',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' px']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : false,
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

function datakunjunganrjnonexe(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganrjnonexe",
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
                        Value1: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.JML) || 0

                    monthlyData[bulan] = {
                        bulan,
                        Value1
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
                    elementId: 'grafikkunjunganrjnonexecutive',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Jumlah Pasien',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        }            
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Jumlah Pasien',
                        min: 0,
                        suffix: ' px',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' px']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : false,
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

function datakunjunganrjpoliklinik() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganrjpoliklinik",
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

            $("#resultdatapoliklinik").empty();
            $("#footerdatapoliklinik").empty();
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
            globaldatakunjunganrjpoliklinik = result;

            let tableresult = "";
            let totalJan    = 0;
            let totalFeb    = 0;
            let totalMar    = 0;
            let totalApr    = 0;
            let totalMei    = 0;
            let totalJun    = 0;
            let totalJul    = 0;
            let totalAug    = 0;
            let totalSep    = 0;
            let totalOkt    = 0;
            let totalNov    = 0;
            let totalDes    = 0;
            let grandTotal  = 0;

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
                        <td>${result[i].POLIKLINIK}</td>
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

            $("#resultdatapoliklinik").html(tableresult);
            $("#footerdatapoliklinik").html(footer);

            initDataTable("#datapoliklinik_table","#searchtable");

            
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

function datakunjunganrjdokter() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganrjdokter",
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

            $("#resultdatadokter").empty();
            $("#footerdatadokter").empty();
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
            globaldatakunjunganrjdokter = result;

            let tableresult = "";
            let totalJan    = 0;
            let totalFeb    = 0;
            let totalMar    = 0;
            let totalApr    = 0;
            let totalMei    = 0;
            let totalJun    = 0;
            let totalJul    = 0;
            let totalAug    = 0;
            let totalSep    = 0;
            let totalOkt    = 0;
            let totalNov    = 0;
            let totalDes    = 0;
            let grandTotal  = 0;

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
                        <td>${result[i].NAMADOKTER}</td>
                        <td>${result[i].KOLEGIUM || ""}</td>
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
                    <td colspan="3" class="text-center">
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

            $("#resultdatadokter").html(tableresult);
            $("#footerdatadokter").html(footer);

            initDataTable("#datadokter_table","#searchtable");

            
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

function datakunjunganrjprovider() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganrjprovider",
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

            $("#resultdataprovider").empty();
            $("#footerdataprovider").empty();
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
            globaldatakunjunganrjprovider = result;

            let tableresult = "";
            let totalJan    = 0;
            let totalFeb    = 0;
            let totalMar    = 0;
            let totalApr    = 0;
            let totalMei    = 0;
            let totalJun    = 0;
            let totalJul    = 0;
            let totalAug    = 0;
            let totalSep    = 0;
            let totalOkt    = 0;
            let totalNov    = 0;
            let totalDes    = 0;
            let grandTotal  = 0;

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
                        <td>${result[i].PROVIDER}</td>
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

            $("#resultdataprovider").html(tableresult);
            $("#footerdataprovider").html(footer);

            initDataTable("#dataprovider_table","#searchtable");

            
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

function datakunjunganrjexe(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganrjexe",
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
                        Value1: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.JML) || 0

                    monthlyData[bulan] = {
                        bulan,
                        Value1
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
                    elementId: 'grafikkunjunganexecutive',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Jumlah Pasien',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        }            
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Jumlah Pasien',
                        min: 0,
                        suffix: ' px',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' px']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : false,
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

function datakunjunganmcu(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganmcu",
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
                        Value1: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.JML) || 0

                    monthlyData[bulan] = {
                        bulan,
                        Value1
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
                    elementId: 'grafikkunjunganmcu',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Jumlah Pasien',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        }            
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Jumlah Pasien',
                        min: 0,
                        suffix: ' px',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' px']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : false,
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

function datakunjunganri(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganri",
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
                        Value1: 0
                    };
                });

                result.forEach(item => {
                    const bulan = String(item.BULAN || '').padStart(2, '0');

                    if (!monthlyData[bulan]) return;

                    const Value1 = Number(item.JML) || 0

                    monthlyData[bulan] = {
                        bulan,
                        Value1
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
                    elementId: 'grafikkunjunganri',
                    height: 350
                },

                data: {
                    categories: chartgrafikmonthlykpi.map(item => item.periode),
                    series: [
                        {
                            name: 'Jumlah Pasien',
                            type: 'area',
                            yAxisIndex: 0,
                            data: chartgrafikmonthlykpi.map(item => item.Value1)
                        }            
                    ]
                },

                xAxis: {
                    title: 'Admission Date'
                },

                yAxes: [
                    {
                        title: 'Jumlah Pasien',
                        min: 0,
                        suffix: ' px',
                        decimals: 0,
                        opposite: false,
                        headroom: 0.10
                    }
                ],

                tooltip: {
                    suffix: [' px']
                },

                trendLine: {
                    show: true
                },

                targetLine: {
                    show      : false,
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