const chartInstances = {};

const resourceConfig = {
    "REGISTRASI": {
        x: 1,
        color: "#0d6efd"
    },
    "JASA DOKTER": {
        x: 2,
        color: "#6610f2"
    },
    "OBAT": {
        x: 3,
        color: "#20c997"
    },
    "LABORATORIUM": {
        x: 4,
        color: "#fd7e14"
    },
    "RADIOLOGI": {
        x: 5,
        color: "#dc3545"
    },
    "RADIOTERAPI": {
        x: 6,
        color: "#6f42c1"
    },
    "TINDAKAN": {
        x: 7,
        color: "#198754"
    },
    "AMBULAN": {
        x: 8,
        color: "#d63384"
    }
};

function renderchartarea(name, data, titleX, titleY, seriesName, fieldName, rightAxisIndex = null, rightAxisLabel = "", avgField = null, avgLabel = "Rata-rata", annotationValue = null) {

    if (chartInstances[name]) {
        chartInstances[name].destroy();
        chartInstances[name] = null;
    }

    let series = [];

    if (Array.isArray(seriesName) && Array.isArray(fieldName)) {
        series = seriesName.map((nm, index) => ({
            name: nm,
            data: data.map(item => parseFloat(item[fieldName[index]] || 0)),
            yAxisIndex: (rightAxisIndex !== null && index === rightAxisIndex) ? 1 : 0
        }));
    } else {
        series = [{
            name: seriesName,
            data: data.map(item => parseFloat(item[fieldName] || 0))
        }];
    }

    let avgValue = null;

    if (avgField) {
        let total = 0;
        let count = 0;

        data.forEach(item => {
            let val = parseFloat(item[avgField]);
            if (!isNaN(val) && val > 0) {
                total += val;
                count++;
            }
        });

        avgValue = count > 0 ? total / count : 0;
    }

    let finalAnnotation = null;
    let finalLabel = "";

    if (annotationValue !== null) {
        finalAnnotation = annotationValue;
        finalLabel      = avgLabel;
    } else if (avgValue !== null) {
        finalAnnotation = avgValue;
        finalLabel      = `${avgLabel} (${Math.round(avgValue).toLocaleString()})`;
    }

    const options = {
        chart : { type: "area", height: 350, toolbar: { show: false }, zoom: { enabled: false } },
        series: series,
        xaxis : {
            categories   : data.map(item => item.periode),
            title        : { text: titleX },
            tickPlacement: 'on',
            axisBorder   : { show: true },
            axisTicks    : { show: true }
        },
        yaxis: rightAxisIndex !== null ? [
            {
                title         : { text: titleY },
                min           : 0,
                forceNiceScale: true,
                labels        : { formatter: val => val.toLocaleString() }
            },
            {
                opposite: true,
                title   : { text: rightAxisLabel },
                labels  : { formatter: val => val.toLocaleString() }
            }
        ] : {
            title         : { text: titleY },
            min           : 0,
            forceNiceScale: true,
            labels        : { formatter: val => val.toLocaleString() }
        },
        stroke : { curve: 'smooth', width: 3 },
        markers: { size: 4 },
        fill   : {
            type    : 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.75, opacityTo: 0.25, stops: [0, 100] }
        },
        dataLabels: { enabled: true, formatter: val => val.toLocaleString() },
        tooltip   : { y: { formatter: val => val.toLocaleString() } },
        grid      : { strokeDashArray: 4 },
        legend    : { position: 'top' },
        annotations: finalAnnotation !== null ? {
            yaxis: [{
                y: finalAnnotation,
                borderColor: '#FF0000',
                strokeDashArray: 3,
                label: {
                    text: finalLabel,
                    style: { background: '#FF0000', color: '#fff' }
                }
            }]
        } : {}
    };

    const chartEl = document.querySelector(`#${name}`);
    chartEl.innerHTML = "";
    chartInstances[name] = new ApexCharts(chartEl, options);
    chartInstances[name].render();
}

function renderChartStackedBar(elementId,categories,series,titleX = "",titleY = "",options = {}){

    const chartElement = document.getElementById(elementId);

    if (!chartElement) return;

    if (chartElement.chart) {
        chartElement.chart.destroy();
    }

    const config = {

        chart: {
            type: "bar",
            height: options.height || 350,
            stacked: true,
            toolbar: {
                show: false
            },
            fontFamily: "inherit"
        },

        series: series,

        xaxis: {
            categories: categories,
            title: {
                text: titleX
            }
        },

        yaxis: {
            title: {
                text: titleY
            }
        },

        plotOptions: {
            bar: {
                horizontal: options.horizontal || false,
                columnWidth: options.columnWidth || "55%",
                borderRadius: options.borderRadius || 4,
                borderRadiusApplication: "end"
            }
        },

        stroke: {
            width: 1,
            colors: ["#fff"]
        },

        fill: {
            opacity: 1
        },

        dataLabels: {
            enabled: options.dataLabels ?? false
        },

        legend: {
            position: options.legendPosition || "top",
            horizontalAlign: options.legendAlign || "left"
        },

        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toLocaleString("id-ID");
                }
            }
        },

        grid: {
            borderColor: "#e5e5e5",
            strokeDashArray: 4
        },

        noData: {
            text: "Tidak ada data"
        }

    };

    Object.assign(config, options);

    chartElement.chart = new ApexCharts(chartElement, config);
    chartElement.chart.render();

}

function renderquadrant(container, data, revenueLine = 0, patientLine) {

    $(container).empty();

    //==================================================
    // REVENUE SCALE
    //==================================================
    const revenueStep = 50000000;

    const maxPositive = Math.max(
        0,
        ...data.filter(d => d.x > 0).map(d => d.x)
    );

    const revenuePadding = maxPositive * 0.05;

    const revenueMax = maxPositive + revenuePadding;
    const revenueMin = -(maxPositive + revenuePadding);

    const tickAmount = Math.ceil(
        (revenueMax - revenueMin) / revenueStep
    );

    //==================================================
    // PREPARE DATA
    //==================================================
    const chartData = data.map(item => {

        const clippedX = Math.max(
            revenueMin,
            Math.min(item.x, revenueMax)
        );

        return {
            ...item,
            realRevenue: item.x,
            x: clippedX,
            label: (item.nama || "")
                .replace(/^DRG?\.\s*/i, "")
                .replace(/^DR\s*/i, "")
                .replace(/^drg?\.\s*/i, "")
                .replace(/^dr\s*/i, "")
                .split(",")[0]
        };

    });

    //==================================================
    // PATIENT SCALE
    //==================================================
    const minPatient = Math.min(...chartData.map(i => i.y));
    const maxPatient = Math.max(...chartData.map(i => i.y));

    const patientPadding = (maxPatient - minPatient) * 0.05;

    const yMin = Math.max(
        0,
        Math.floor(minPatient - patientPadding)
    );

    const yMax = Math.ceil(
        maxPatient + patientPadding
    );

    //==================================================
    // QUADRANT
    //==================================================
    const q1 = [];
    const q2 = [];
    const q3 = [];
    const q4 = [];

    chartData.forEach(item => {

        if (item.realRevenue >= revenueLine && item.y >= patientLine) {
            q1.push(item);
        }
        else if (item.realRevenue >= revenueLine && item.y < patientLine) {
            q2.push(item);
        }
        else if (item.realRevenue < revenueLine && item.y < patientLine) {
            q3.push(item);
        }
        else {
            q4.push(item);
        }

    });

    //==================================================
    // CHART
    //==================================================
    const options = {

        chart: {
            type: "scatter",
            height: 700,
            zoom: {
                enabled: true
            },
            toolbar: {
                show: false
            }
        },

        colors: [
            "#28a745",
            "#0d6efd",
            "#dc3545",
            "#fd7e14"
        ],

        series: [
            {
                name: "Q1 (Revenue + | High Patient)",
                data: q1
            },
            {
                name: "Q2 (Revenue + | Low Patient)",
                data: q2
            },
            {
                name: "Q3 (Revenue - | Low Patient)",
                data: q3
            },
            {
                name: "Q4 (Revenue - | High Patient)",
                data: q4
            }
        ],

        markers: {
            size: 7,
            hover: {
                size: 10
            }
        },

        dataLabels: {

            enabled: true,

            offsetY: -10,

            background: {
                enabled: false
            },

            formatter: function(val, opts) {

                return opts.w.config.series[
                    opts.seriesIndex
                ].data[
                    opts.dataPointIndex
                ].label;

            }

        },

        xaxis: {

            min: revenueMin,
            max: revenueMax,

            tickAmount: tickAmount,

            forceNiceScale: false,

            decimalsInFloat: 0,

            title: {
                text: "Revenue (INA-CBG - Tarif RS)"
            },

            labels: {

                formatter: function(val) {

                    if (Math.abs(val) < 1000000) {
                        return "0";
                    }

                    return Math.round(val / 1000000) + " Jt";

                }

            }

        },

        yaxis: {

            min: yMin,
            max: yMax,

            tickAmount: 8,

            forceNiceScale: true,

            title: {
                text: "Jumlah Kunjungan"
            },

            labels: {

                formatter: function(val) {
                    return todesimal(Math.round(val));
                }

            }

        },

        annotations: {

            xaxis: [{
                x: revenueLine,
                borderColor: "#dc3545",
                strokeDashArray: 5,
                label: {
                    text: "Revenue",
                    style: {
                        background: "#dc3545",
                        color: "#fff"
                    }
                }
            }],

            yaxis: [{
                y: patientLine,
                borderColor: "#0d6efd",
                strokeDashArray: 5,
                label: {
                    text: "Kunjungan",
                    style: {
                        background: "#0d6efd",
                        color: "#fff"
                    }
                }
            }]

        },

        legend: {
            position: "bottom"
        },

        tooltip: {

            custom: function({ seriesIndex, dataPointIndex, w }) {

                const d = w.config.series[seriesIndex].data[dataPointIndex];

                return `
                    <div style="padding:10px;min-width:260px">

                        <div style="font-size:14px;font-weight:bold">
                            ${d.nama}
                        </div>

                        <hr>

                        <table style="width:100%">

                            <tr>
                                <td>Kunjungan</td>
                                <td align="right">${todesimal(d.y)}</td>
                            </tr>

                            <tr>
                                <td>Tarif RS</td>
                                <td align="right">
                                    Rp ${todesimal(d.tarifrs)}
                                </td>
                            </tr>

                            <tr>
                                <td>INA-CBG</td>
                                <td align="right">
                                    Rp ${todesimal(d.inacbg)}
                                </td>
                            </tr>

                            <tr>
                                <td><b>Revenue</b></td>
                                <td align="right">
                                    <b>Rp ${todesimal(d.realRevenue)}</b>
                                </td>
                            </tr>

                        </table>

                    </div>
                `;

            }

        }

    };

    new ApexCharts(
        document.querySelector(container),
        options
    ).render();

}

function renderscatterresource(container, data) {
    $(container).empty();

    const series = Object.entries(resourceConfig).map(([resourceName, config]) => {
        const resourceData = data.filter(d => d.resourceTertinggi === resourceName);
        const totalPoint = resourceData.length;

        return {
            name: resourceName,
            color: config.color,
            data: resourceData.map((d, index) => {
                let offset = 0;

                if (totalPoint > 1) {
                    const spread = 0.32;
                    offset = -spread + ((index / (totalPoint - 1)) * (spread * 2));
                }

                return {
                    ...d,
                    x: config.x + offset,
                    y: Number(d.persentaseResource) || 0
                };
            })
        };
    });

    const resourceXAnnotations = [];
    const resourcePointAnnotations = [];

    Object.entries(resourceConfig).forEach(([resourceName, config]) => {
        resourceXAnnotations.push({
            x: config.x,
            borderColor: config.color,
            strokeDashArray: 4,
            borderWidth: 1
        });

        resourcePointAnnotations.push({
            x: config.x,
            y: 0,
            marker: {
                size: 0
            },
            label: {
                text: resourceName,
                borderColor: config.color,
                offsetY: 28,
                style: {
                    background: config.color,
                    color: "#fff",
                    fontSize: "9px",
                    fontWeight: 600,
                    padding: {
                        left: 5,
                        right: 5,
                        top: 3,
                        bottom: 3
                    }
                }
            }
        });
    });

    const options = {
        chart: {
            type: "scatter",
            height: 650,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: true
            }
        },

        series: series,

        plotOptions: {
            scatter: {
                jitter: {
                    enabled: true,
                    x: 0.35,
                    y: 0
                }
            }
        },

        markers: {
            size: 6,
            strokeWidth: 2,
            strokeColors: "#fff",
            hover: {
                size: 9
            }
        },

        fill: {
            opacity: 0.85
        },

        dataLabels: {
            enabled: false
        },

        xaxis: {
            type: "numeric",
            min: 0.5,
            max: 8.5,
            tickAmount: 8,
            forceNiceScale: false,
            crosshairs: {
                show: false
            },
            title: {
                text: "Jenis Resource",
                style: {
                    fontSize: "12px",
                    fontWeight: 600
                }
            },
            labels: {
                formatter: function() {
                    return "";
                }
            }
        },

        yaxis: {
            min: 0,
            max: 100,
            tickAmount: 10,
            forceNiceScale: false,
            title: {
                text: "Persentase Resource",
                style: {
                    fontSize: "12px",
                    fontWeight: 600
                }
            },
            labels: {
                formatter: function(val) {
                    return Math.round(val) + "%";
                }
            }
        },

        annotations: {
            xaxis: resourceXAnnotations,
            points: resourcePointAnnotations
        },

        legend: {
            position: "bottom",
            horizontalAlign: "center"
        },

        tooltip: {
            shared: false,
            intersect: true,
            custom: function({ seriesIndex, dataPointIndex, w }) {
                const d = w.config.series[seriesIndex].data[dataPointIndex];

                return `
                    <div style="padding:10px;min-width:300px;">
                        <div style="font-size:14px;font-weight:bold;margin-bottom:8px;">
                            ${d.nama || "-"}
                        </div>
                        <table style="width:100%;font-size:12px;">
                            <tr>
                                <td>Resource Dominan</td>
                                <td align="right">
                                    <b>${d.resourceTertinggi}</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Persentase</td>
                                <td align="right">
                                    <b>${d.persentaseResource.toFixed(2)}%</b>
                                </td>
                            </tr>
                            <tr>
                                <td>Nilai Resource</td>
                                <td align="right">
                                    Rp ${todesimal(d.nilaiResourceTertinggi)}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Resource</td>
                                <td align="right">
                                    Rp ${todesimal(d.totalResource)}
                                </td>
                            </tr>
                        </table>
                    </div>
                `;
            }
        },

        grid: {
            borderColor: "#e5e7eb",
            padding: {
                top: 20,
                right: 20,
                bottom: 55,
                left: 20
            }
        }
    };

    new ApexCharts(
        document.querySelector(container),
        options
    ).render();
}

function renderChartbarline(container,data,xTitle,yTitleBar,yTitleLine,seriesBar,seriesLine){

    $("#" + container).empty();

    const categories = data.map(x => x.periode);
    const barData = data.map(x => Number(x.value1) || 0);
    const lineData = data.map(x => Number(x.value2) || 0);

    var options = {

        chart: {
            height: 350,
            type: 'line',
            toolbar: {
                show: false
            }
        },

        series: [
            {
                name: seriesBar,
                type: 'column',
                data: barData
            },
            {
                name: seriesLine,
                type: 'line',
                data: lineData
            }
        ],

        colors: [
            "#0d6efd", // Tarif RS
            "#28a745"  // Total Klaim
        ],

        stroke: {
            width: [0,4],
            curve: 'smooth'
        },

        plotOptions: {
            bar: {
                columnWidth: '80%',
                borderRadius: 5
            }
        },

        markers: {
            size: 5
        },

        dataLabels: {
            enabled: false
        },

        xaxis: {
            categories: categories,
            title: {
                text: xTitle
            }
        },

        yaxis: [

            // Sumbu kiri = Jumlah Kasus
            {
                title: {
                    text: yTitleBar
                },
                forceNiceScale: true,
                labels: {
                    formatter: function(val){
                        return todesimal(Math.round(val));
                    }
                }
            },

            // Sumbu kanan = Rupiah
            {
                opposite: true,
                title: {
                    text: yTitleLine
                },
                forceNiceScale: true,
                labels: {
                    formatter: function(val){
                        return "Rp " + todesimal(val);
                    }
                }
            }

        ],

        tooltip: {
            shared: true,
            intersect: false,
            y: [

                {
                    formatter: function(val){
                        return todesimal(val);
                    }
                },

                {
                    formatter: function(val){
                        return "Rp " + todesimal(val);
                    }
                }

            ]
        },

        legend: {
            position: 'bottom',
            horizontalAlign: 'left'
        },

        grid: {
            borderColor: '#e5e7eb',
            strokeDashArray: 4
        }

    };

    new ApexCharts(
        document.querySelector("#" + container),
        options
    ).render();

}

function renderBarHorizontal(name, seriesConfig, data, categoryField = 'kategori') {
    // Hapus chart sebelumnya jika ada
    if (chartInstances[name]) {
        chartInstances[name].destroy();
        chartInstances[name] = null;
    }

    const categories = data.map(item => item[categoryField]);

    const series = seriesConfig.map(s => ({
        name: s.name,
        data: data.map(item => Number(item[s.field] || 0))
    }));

    const options = {
        series,

        chart: {
            type: 'bar',
            stacked: true,
            height: Math.max(350, data.length * 35),
            toolbar: {
                show: false
            }
        },

        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 3,
                barHeight: '70%',

                dataLabels: {
                    total: {
                        enabled: true,
                        offsetX: 10,

                        style: {
                            fontSize: '12px',
                            fontWeight: 700
                        },

                        formatter: function (val) {
                            return val.toLocaleString('id-ID');
                        }
                    }
                }
            }
        },

        stroke: {
            width: 1,
            colors: ['#fff']
        },

        fill: {
            opacity: 1
        },

        xaxis: {
            min: 0,
            categories,

            labels: {
                formatter: function (val) {
                    return Number(val).toLocaleString('id-ID');
                }
            }
        },

        yaxis: {
            title: {
                text: undefined
            }
        },

        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Number(val).toLocaleString('id-ID');
            },
            style: {
                fontSize: '11px'
            }
        },

        tooltip: {
            shared: true,
            intersect: false,

            y: {
                formatter: function (val) {
                    return val.toLocaleString('id-ID');
                }
            }
        },

        legend: {
            position: 'top',
            horizontalAlign: 'left'
        }
    };

    const chartEl = document.querySelector(`#${name}`);

    if (!chartEl) {
        // console.warn(`Element #${name} tidak ditemukan.`);
        return;
    }

    chartEl.innerHTML = '';

    chartInstances[name] = new ApexCharts(chartEl, options);
    chartInstances[name].render();
}

function renderchartpie(name, data) {

    if (chartInstances[name]) {
        chartInstances[name].destroy();
        chartInstances[name] = null;
    }

    const labels = [];
    const series = [];

    data.forEach(item => {

        labels.push(
            item.label ??
            item.LABEL ??
            item.PROVIDER ??
            "-"
        );

        series.push(
            Number(
                item.value ??
                item.VALUE ??
                item.TOTAL ??
                0
            )
        );

    });

    const options = {

        chart: {
            type: "donut",
            height: 395
        },

        labels: labels,

        series: series,

        legend: {
            position: "bottom"
        },

        dataLabels: {
            enabled: true,
            formatter: function(val){
                return val.toFixed(1) + "%";
            }
        },

        tooltip: {
            y: {
                formatter: function(val){
                    return val.toLocaleString("id-ID") + " Pasien";
                }
            }
        }

    };

    const chartContainer = document.querySelector(`#${name}`);

    if (!chartContainer) {
        // console.warn(`Element #${name} tidak ditemukan.`);
        return;
    }

    chartContainer.innerHTML = "";

    chartInstances[name] = new ApexCharts(chartContainer, options);
    chartInstances[name].render();

}