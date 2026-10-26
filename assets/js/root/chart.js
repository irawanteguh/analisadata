const chartInstances = {};

function renderChartArea(config = {}) {
    const defaults = {
        chart: {
            elementId: '',
            id: '',
            type: 'area',
            height: 350,
            toolbar: true,
            zoom: false,
            animations: true
        },

        data: {
            categories: [],
            series: []
        },

        series: {
            colors: [
                '#008FFB', '#00E396', '#FEB019', '#775DD0',
                '#00B8D9', '#26A69A', '#A5978B', '#546E7A',
                '#FF9800', '#5C6BC0'
            ],
            curve: 'smooth',
            strokeWidth: 3,
            fillOpacity: 0.18,
            showMarkers: true,
            markerSize: 3,
            showDataLabels: true
        },

        title: {
            text: '',
            subtitle: '',
            align: 'left'
        },

        xAxis: {
            title: 'Tanggal',
            rotate: -45
        },

        yAxes: [
            {
                title: '',
                min: 0,
                max: undefined,
                decimals: 0,
                prefix: '',
                suffix: '',
                tickAmount: 5,
                headroom: 0.10,
                opposite: false
            }
        ],

        tooltip: {
            enabled: true,
            shared: true,
            decimals: undefined,
            prefix: '',
            suffix: ''
        },

        legend: {
            show: true,
            position: 'bottom',
            horizontalAlign: 'center'
        },

        trendLine: {
            show       : false,
            name       : 'Simple Linear Regression',
            color      : '#7C3AED',
            strokeWidth: 2,
            dashArray  : 6
        },

        targetLine: {
            show: false,
            value: null,
            name: 'Target',
            color: '#E53935',
            dashArray: 5,
            labelColor: '#FFFFFF',
            labelBackground: '#E53935',
            yAxisIndex: 0,
            suffix: ''
        },

        display: {
            noDataText: 'Tidak ada data',
            responsive: true
        }
    };

    // Deep merge; array dari konfigurasi baru menggantikan array default.
    function mergeConfig(base, custom) {
        const result = { ...base };

        Object.keys(custom || {}).forEach(key => {
            const b = base[key];
            const c = custom[key];

            if (
                b && c &&
                typeof b === 'object' &&
                typeof c === 'object' &&
                !Array.isArray(b) &&
                !Array.isArray(c)
            ) {
                result[key] = mergeConfig(b, c);
            } else {
                result[key] = c;
            }
        });

        return result;
    }

    const cfg = mergeConfig(defaults, config);

    if (!Array.isArray(cfg.yAxes) || cfg.yAxes.length === 0) {
        cfg.yAxes = defaults.yAxes.map(axis => ({ ...axis }));
    }

    // Validasi konfigurasi axis.
    cfg.yAxes = cfg.yAxes.map(axis => ({
        ...defaults.yAxes[0],
        ...(axis || {})
    }));

    const element = document.getElementById(cfg.chart.elementId);

    if (!element) {
        console.warn(
            `Chart element #${cfg.chart.elementId} tidak ditemukan.`
        );
        return;
    }

    if (element._apexChart) {
        element._apexChart.destroy();
        element._apexChart = null;
    }

    const categories = Array.isArray(cfg.data.categories)
        ? cfg.data.categories
        : [];

    const inputSeries = Array.isArray(cfg.data.series)
        ? cfg.data.series
        : [];

    // Normalisasi data series.
    const series = inputSeries.map((item, index) => ({
        name      : item.name || `Series ${index + 1}`,
        type      : item.type || cfg.chart.type || 'area',
        yAxisIndex: Number.isInteger(Number(item.yAxisIndex)) ? Math.max(0, Number(item.yAxisIndex)): 0,
        data      : (Array.isArray(item.data) ? item.data : []).map(value => {if (value === null || value === undefined || value === '') {return null;} const number = Number(value); return Number.isFinite(number) ? number : null;})
    }));

    // Pastikan indeks axis series valid.
    series.forEach(item => {
        if (item.yAxisIndex >= cfg.yAxes.length) {
            console.warn(
                `Axis ${item.yAxisIndex} tidak tersedia untuk series "${item.name}".`
            );
            item.yAxisIndex = 0;
        }
    });

    // Garis tren regresi linear.
    let trendSeriesName = null;

    if (cfg.trendLine.show) {
        const sourceIndex = series.findIndex(
            item => item.type === 'area' || item.type === 'line'
        );

        if (sourceIndex !== -1) {
            const source = series[sourceIndex];

            const points = source.data
                .map((y, x) => ({ x, y }))
                .filter(p => p.y !== null && Number.isFinite(p.y));

            if (points.length >= 2) {
                const n = points.length;

                const sumX = points.reduce((s, p) => s + p.x, 0);
                const sumY = points.reduce((s, p) => s + p.y, 0);
                const sumXY = points.reduce((s, p) => s + p.x * p.y, 0);
                const sumXX = points.reduce((s, p) => s + p.x * p.x, 0);

                const denominator = n * sumXX - sumX * sumX;

                const slope = denominator !== 0
                    ? (n * sumXY - sumX * sumY) / denominator
                    : 0;

                const intercept = (sumY - slope * sumX) / n;

                const trendData = source.data.map((_, index) =>
                    Number((slope * index + intercept).toFixed(4))
                );

                trendSeriesName = cfg.trendLine.name;

                // Hindari nama trend yang sama dengan series lain.
                if (series.some(item => item.name === trendSeriesName)) {
                    trendSeriesName = `${cfg.trendLine.name} (Linear)`;
                }

                series.push({
                    name      : trendSeriesName,
                    type      : 'line',
                    yAxisIndex: source.yAxisIndex,
                    data      : trendData,
                    isTrend   : true
                });
            } else {
                console.warn(
                    'Garis tren membutuhkan minimal 2 data numerik.'
                );
            }
        }
    }

    // Target SLA.
    const isTargetEnabled =
        cfg.targetLine.show &&
        cfg.targetLine.value !== null &&
        cfg.targetLine.value !== '' &&
        Number.isFinite(Number(cfg.targetLine.value));

    const targetValue = isTargetEnabled
        ? Number(cfg.targetLine.value)
        : null;

    const targetAxisIndex = Number.isInteger(
        Number(cfg.targetLine.yAxisIndex)
    )
        ? Math.max(0, Number(cfg.targetLine.yAxisIndex))
        : 0;

    if (isTargetEnabled && targetAxisIndex >= cfg.yAxes.length) {
        console.warn('Target SLA menggunakan yAxisIndex yang tidak tersedia.');
    }

    const validTarget = isTargetEnabled &&
        targetAxisIndex < cfg.yAxes.length;

    // Anotasi target SLA.
    const targetAnnotations = validTarget
        ? {
            yaxis: [{
                y: targetValue,
                yAxisIndex: targetAxisIndex,
                borderColor: cfg.targetLine.color,
                strokeDashArray: cfg.targetLine.dashArray,
                borderWidth: 2,
                label: {
                    text: `${cfg.targetLine.name} (${targetValue}${cfg.targetLine.suffix ? ' ' + cfg.targetLine.suffix : ''})`,
                    position: 'right',
                    borderColor: cfg.targetLine.color,
                    style: {
                        color: cfg.targetLine.labelColor,
                        background: cfg.targetLine.labelBackground,
                        fontSize: '11px',
                        fontWeight: 600
                    }
                }
            }]
        }
        : {};

    // Hitung batas setiap axis berdasarkan series dan target.
    const axisConfigs = cfg.yAxes.map((axis, axisIndex) => {
        const axisSeries = series.filter(
            item => item.yAxisIndex === axisIndex
        );

        const values = axisSeries.flatMap(item =>
            item.data.filter(value =>
                value !== null && Number.isFinite(value)
            )
        );

        const dataMin = values.length ? Math.min(...values) : 0;
        const dataMax = values.length ? Math.max(...values) : 0;

        const axisTarget = validTarget && axisIndex === targetAxisIndex
            ? targetValue
            : null;

        const lowest = Math.min(
            dataMin,
            axisTarget !== null ? axisTarget : dataMin
        );

        const highest = Math.max(
            dataMax,
            axisTarget !== null ? axisTarget : dataMax
        );

        const configuredMin = axis.min !== undefined
            ? Number(axis.min)
            : undefined;

        const minValue = configuredMin !== undefined
            ? Math.min(configuredMin, lowest)
            : lowest;

        const range = Math.max(
            highest - minValue,
            Math.abs(highest) * 0.1,
            1
        );

        const rawMax = highest + range * (axis.headroom ?? 0.10);
        const decimals = Math.max(0, Number(axis.decimals) || 0);
        const factor = Math.pow(10, decimals);

        const calculatedMax = Math.ceil(rawMax * factor) / factor;

        const axisMax = axis.max !== undefined
            ? Math.max(Number(axis.max), calculatedMax)
            : calculatedMax;

        const axisSeriesNames = axisSeries.map(item => item.name);

        return {
            seriesName     : axisSeriesNames.length ? axisSeriesNames: undefined,
            min            : minValue,
            max            : axisMax,
            opposite       : axis.opposite ?? axisIndex > 0,
            forceNiceScale : true,
            tickAmount     : axis.tickAmount ?? 5,
            decimalsInFloat: decimals,
            title          : {text: axis.title || undefined},
            labels         : {
                formatter: value =>
                    (axis.prefix || '') +
                    Number(value).toLocaleString('id-ID', {
                        maximumFractionDigits: decimals
                    }) +
                    (axis.suffix || '')
            }
        };
    });

    // Warna dipetakan berdasarkan urutan series asli.
    const colors = series.map((item, index) => {
        if (item.name === trendSeriesName) {
            return cfg.trendLine.color || '#7C3AED';
        }

        if (item.type === 'line') {
            return '#64748B';
        }

        const areaIndex = series
            .slice(0, index + 1)
            .filter(s => s.type !== 'line').length - 1;

        const palette = cfg.series.colors;

        return Array.isArray(palette) && palette.length
            ? palette[areaIndex % palette.length]
            : '#008FFB';
    });

    // Gaya garis per series.
    const strokeWidths = series.map(item =>
        item.name === trendSeriesName
            ? (Number(cfg.trendLine.strokeWidth) || 3)
            : item.type === 'line'
                ? 2
                : (Number(cfg.series.strokeWidth) || 3)
    );

    const dashArrays = series.map(item =>
        item.name === trendSeriesName
            ? Number(cfg.trendLine.dashArray ?? 6)
            : 0
    );

    const markerSizes = series.map(item =>
        item.name === trendSeriesName || !cfg.series.showMarkers
            ? 0
            : (Number(cfg.series.markerSize) || 0)
    );

    const fillTypes = series.map(item =>
        item.type === 'line' ? 'solid' : 'gradient'
    );

    const fillOpacities = series.map(item =>
        item.type === 'line'
            ? 1
            : (Number(cfg.series.fillOpacity) || 0.18)
    );

    const dataLabelsSeries = series
        .map((item, index) =>
            item.name === trendSeriesName ? -1 : index
        )
        .filter(index => index >= 0);

    const chartOptions = {
        chart: {
            id: cfg.chart.id || cfg.chart.elementId,
            type: 'line',
            height: cfg.chart.height,
            toolbar: {
                show: cfg.chart.toolbar
            },
            zoom: {
                enabled: cfg.chart.zoom
            },
            animations: {
                enabled: cfg.chart.animations
            }
        },

        series,

        colors,

        stroke: {
            curve    : cfg.series.curve || 'smooth',
            width    : strokeWidths,
            dashArray: dashArrays,
            lineCap  : 'round'
        },

        grid: {
            borderColor: '#E0E0E0',
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            }
        },

        fill: {
            type    : fillTypes,
            opacity : fillOpacities,
            gradient: {
                shade         : 'light',
                type          : 'vertical',
                shadeIntensity: 0,
                inverseColors : false,
                opacityFrom   : 0.9,
                opacityTo     : 0.1,
                stops         : [0, 100]
            }
        },

        markers: {
            size: markerSizes,
            strokeWidth: 3,
            hover: {
                sizeOffset: 3
            }
        },

        dataLabels: {
            enabled: cfg.series.showDataLabels,
            enabledOnSeries: dataLabelsSeries,
            formatter: function (value) {
                if (
                    value === null ||
                    value === undefined ||
                    !Number.isFinite(Number(value))
                ) {
                    return '';
                }

                const number = Number(value);

                return number.toLocaleString('id-ID', {
                    minimumFractionDigits: Number.isInteger(number) ? 0 : 2,
                    maximumFractionDigits: 2
                });
            }
        },

        ...(cfg.title.text ? {
            title: {
                text: cfg.title.text,
                align: cfg.title.align
            }
        } : {}),

        ...(cfg.title.subtitle ? {
            subtitle: {
                text: cfg.title.subtitle,
                align: cfg.title.align
            }
        } : {}),

        xaxis: {
            categories,
            title: {
                text: cfg.xAxis.title || undefined
            },
            labels: {
                rotate: cfg.xAxis.rotate,
                rotateAlways: false
            }
        },

        yaxis: axisConfigs,

        annotations: targetAnnotations,

        tooltip: {
            enabled: cfg.tooltip.enabled,
            shared: cfg.tooltip.shared,
            y: {
                formatter: function (value, { seriesIndex }) {
                    if (
                        value === null ||
                        value === undefined ||
                        !Number.isFinite(Number(value))
                    ) {
                        return '-';
                    }

                    const decimals = cfg.tooltip.decimals ??
                        cfg.yAxes[series[seriesIndex]?.yAxisIndex ?? 0]?.decimals ??
                        0;

                    const suffix = Array.isArray(cfg.tooltip.suffix)
                        ? (cfg.tooltip.suffix[seriesIndex] ?? '')
                        : (cfg.tooltip.suffix ?? '');

                    const prefix = Array.isArray(cfg.tooltip.prefix)
                        ? (cfg.tooltip.prefix[seriesIndex] ?? '')
                        : (cfg.tooltip.prefix ?? '');

                    return prefix +
                        Number(value).toLocaleString('id-ID', {
                            maximumFractionDigits: decimals,
                            minimumFractionDigits: decimals
                        }) +
                        suffix;
                }
            }
        },

        legend: {
            show: cfg.legend.show,
            position: cfg.legend.position,
            horizontalAlign: cfg.legend.horizontalAlign
        },

        noData: {
            text: cfg.display.noDataText
        },

        responsive: cfg.display.responsive
            ? [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 300
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
            : []
    };

    const chart = new ApexCharts(element, chartOptions);
    element._apexChart = chart;

    chart.render();

    return chart;
}

function renderchartheatmap(
    name,
    data,
    title = "Inpatient Discharges by Hour",
    titleX = "Hour of Discharge",
    titleY = "Discharge Date",
    dateField = "PERIODE",
    hourPrefix = "JAM_"
) {

    if (chartInstances[name]) {
        chartInstances[name].destroy();
        chartInstances[name] = null;
    }

    const chartEl = document.getElementById(name);

    if (!chartEl) {
        console.error("Chart element tidak ditemukan:", name);
        return;
    }

    if (!Array.isArray(data) || data.length === 0) {
        chartEl.innerHTML = "";
        return;
    }

    // Parse tanggal DD.MM.YYYY
    const parseDate = value => {
        const [day, month, year] = String(value).split(".");

        return new Date(
            Number(year),
            Number(month) - 1,
            Number(day)
        ).getTime();
    };

    // Urutkan tanggal dari terlama ke terbaru
    const sortedData = [...data].sort(
        (a, b) => parseDate(a[dateField]) - parseDate(b[dateField])
    );

    // Label jam 00 sampai 23
    const jamLabels = Array.from(
        { length: 24 },
        (_, i) => String(i).padStart(2, "0")
    );

    // Bentuk data heatmap
    const series = sortedData.map(row => ({
        name: row[dateField],
        data: jamLabels.map(jam => ({
            x: jam,
            y: Math.max(
                0,
                Number(row[hourPrefix + jam]) || 0
            )
        }))
    }));

    // Ambil nilai maksimum seluruh data
    const values = series.flatMap(item =>
        item.data.map(point => point.y)
    );

    const maxValue = Math.max(1, ...values);

    // Batas rentang warna
    const q1 = Math.ceil(maxValue * 0.25);
    const q2 = Math.ceil(maxValue * 0.50);
    const q3 = Math.ceil(maxValue * 0.75);

    // Skala warna hijau sampai merah
    const ranges = [
        {
            from: 0,
            to: 0,
            color: "#F1F5F9",
            name: "0 - Tidak ada pasien"
        },
        {
            from: 1,
            to: q1,
            color: "#22C55E",
            name: "Rendah"
        },
        {
            from: q1 + 1,
            to: q2,
            color: "#FACC15",
            name: "Sedang"
        },
        {
            from: q2 + 1,
            to: q3,
            color: "#F97316",
            name: "Tinggi"
        },
        {
            from: q3 + 1,
            to: maxValue,
            color: "#DC2626",
            name: "Sangat tinggi"
        }
    ].filter(range => range.from <= range.to);

    const options = {
        series: series,

        chart: {
            type: "heatmap",
            height: Math.max(400, sortedData.length * 24),
            toolbar: {
                show: true
            },
            animations: {
                enabled: false
            },
            fontFamily: "inherit"
        },

        // title: {
        //     text: title,
        //     align: "left",
        //     style: {
        //         fontSize: "16px",
        //         fontWeight: 600
        //     }
        // },

        plotOptions: {
            heatmap: {
                shadeIntensity: 0.2,
                radius: 2,
                useFillColorAsStroke: false,
                colorScale: {
                    ranges: ranges
                }
            }
        },

        dataLabels: {
            enabled: true,
            style: {
                fontSize: "10px",
                colors: ["#0F172A"]
            },
            formatter: function (value) {
                return value === 0 ? "" : value;
            }
        },

        stroke: {
            width: 1,
            colors: ["#FFFFFF"]
        },

        xaxis: {
            type: "category",
            position: "top",
            title: {
                text: titleX
            },
            labels: {
                rotate: 0,
                style: {
                    fontSize: "10px"
                }
            },
            axisBorder: {
                show: true
            },
            axisTicks: {
                show: true
            }
        },

        yaxis: {
            title: {
                text: titleY
            },
            labels: {
                style: {
                    fontSize: "11px"
                }
            }
        },

        tooltip: {
            y: {
                formatter: function (value) {
                    return value + " patient(s)";
                },
                title: {
                    formatter: function () {
                        return "Discharges: ";
                    }
                }
            },
            x: {
                formatter: function (value) {
                    return "Hour: " + value + ":00–" + value + ":59";
                }
            }
        },

        legend: {
            show: true,
            position: "bottom",
            horizontalAlign: "center"
        },

        grid: {
            padding: {
                top: 5,
                right: 10,
                bottom: 5,
                left: 10
            }
        },

        noData: {
            text: "No data available",
            align: "center",
            verticalAlign: "middle"
        },

        responsive: [
            {
                breakpoint: 768,
                options: {
                    chart: {
                        height: Math.max(400, sortedData.length * 22)
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        labels: {
                            style: {
                                fontSize: "9px"
                            }
                        }
                    }
                }
            }
        ]
    };

    chartInstances[name] = new ApexCharts(chartEl, options);

    chartInstances[name].render();
}