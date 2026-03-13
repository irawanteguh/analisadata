const chartInstances = {};

// function renderbarhorizontal(name, data) {

//     // 🔥 DESTROY chart lama jika ada
//     if (chartInstances[name]) {
//         chartInstances[name].destroy();
//         chartInstances[name] = null;
//     }

//     const labelHeight  = 28;
//     const paddingChart = 80;
//     const minHeight    = 220;

//     let calculatedHeight = (data.length * labelHeight) + paddingChart;
//     if (calculatedHeight < minHeight) calculatedHeight = minHeight;

//     $("#" + name).css("height", calculatedHeight + "px");

//     const categories = data.map(d => d.keterangan);
//     const values     = data.map(d => d.jumlah);

//     const maxValue = Math.max(...values);
//     const minValue = Math.min(...values.filter(v => v > 0));
//     const minScale = minValue > 0 ? Math.max(0, minValue * 0.5) : 0;

//     const options = {
//         chart: {
//             type: 'bar',
//             height: calculatedHeight,
//             toolbar: { show: false }
//         },
//         series: [{ name: 'Jumlah', data: values }],
//         plotOptions: {
//             bar: {
//                 horizontal: true,
//                 barHeight: '80%',
//                 borderRadius: 3,
//                 minHeight: 6,
//                 dataLabels: {
//                     position: 'bottom'
//                 }
//             }
//         },
//         xaxis: {
//             categories,
//             min: 0,
//             max: maxValue * 1.1,
//             tickAmount: Math.min(maxValue, 6),
//             labels: {
//                 formatter: val =>
//                     Math.round(val).toLocaleString('id-ID')
//             }
//         },
//         yaxis: { labels: { show: false } },
//         dataLabels: {
//             enabled: true,
//             textAnchor: 'start', 
//             offsetX: 10,            
//             formatter: function (val, opts) {
//                 const label = opts.w.globals.labels[opts.dataPointIndex];
//                 return `${label}: ${val.toLocaleString('id-ID')}`;
//             },
//             style: {
//                 colors: ['#000000'], // putih biar kontras di dalam bar
//                 fontSize: '11px',
//                 fontWeight: 500
//             }
//         },
//         grid: {
//             yaxis: {
//                 lines: {
//                     show: false
//                 }
//             },
//             padding: { left: 0, right: 0 }
//         }
//     };

//     // 🔥 SIMPAN INSTANCE
//     chartInstances[name] = new ApexCharts(
//         document.querySelector(`#${name}`),
//         options
//     );
//     chartInstances[name].render();
// }

// function renderchartarea(name, data, titleX, titleY) {
//     let options = {

//         chart: {
//             type   : "area",
//             height : 350,
//             toolbar: { show: false },
//             zoom   : { enabled: false }
//         },

//         series: [{
//             name: 'Kunjungan',
//             data: data.map(item => item.total)
//         }],

//         xaxis: {
//             categories   : data.map(item => item.periode),
//             title        : { text: titleX },
//             tickPlacement: 'on',
//             axisBorder   : { show: true },
//             axisTicks    : { show: true }
//         },

//         yaxis: {
//             title         : { text: titleY },
//             min           : 0,
//             forceNiceScale: true,
//             labels        : {formatter: val => val.toLocaleString()}
//         },

//         stroke: {
//             curve: 'smooth',
//             width: 3
//         },

//         fill: {
//             type: 'gradient',
//             gradient: {
//                 shadeIntensity: 1,
//                 opacityFrom: 0.35,
//                 opacityTo: 0.05,
//                 stops: [50, 90, 100]
//             }
//         },

//         grid: {
//             xaxis: { lines: { show: false } },
//             yaxis: { lines: { show: true } } 
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: function (val) {return val.toLocaleString('id-ID');}
//         },

//         tooltip: {
//             y: {formatter: val => val.toLocaleString()}
//         }
//     };

//     document.querySelector(`#${name}`).innerHTML = "";
//     new ApexCharts(document.querySelector(`#${name}`), options).render();
// }



// function renderchartpie(name, data){

//     let labels = [];
//     let series = [];

//     data.forEach(function(item){

//         labels.push(item.LABEL);
//         series.push(parseInt(item.TOTAL));

//     });

//     let options = {

//         chart: {
//             type: "donut",
//             height: 350
//         },

//         series: series,
//         labels: labels,

//         legend: {
//             position: "bottom"
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: function (val) {
//                 return val.toFixed(1) + "%";
//             }
//         },

//         tooltip: {
//             y: {
//                 formatter: function (val) {
//                     return val.toLocaleString("id-ID") + " Pasien";
//                 }
//             }
//         }

//     };

//     document.querySelector(`#${name}`).innerHTML = "";

//     new ApexCharts(
//         document.querySelector(`#${name}`),
//         options
//     ).render();
// }

// function renderchartareaforecasting(name, data, titleX, titleY) {
//     // Pastikan data memiliki format: {bulan, real, prediksi}
//     const series = [
//         {
//             name: 'Real',
//             data: data.map(item => item.real)
//         },
//         {
//             name: 'Prediksi',
//             data: data.map(item => item.prediksi)
//         }
//     ];

//     const options = {
//         chart: {
//             type: "area",
//             height: 350,
//             toolbar: { show: false },
//             zoom: { enabled: false }
//         },

//         series: series,

//         xaxis: {
//             categories: data.map(item => item.bulan),
//             title: { text: titleX },
//             tickPlacement: 'on',
//             axisBorder: { show: true },
//             axisTicks: { show: true }
//         },

//         yaxis: {
//             title: { text: titleY },
//             min: 0,
//             forceNiceScale: true,
//             labels: {
//                 formatter: val => val.toLocaleString('id-ID')
//             }
//         },

//         stroke: {
//             curve: 'smooth',
//             width: 3
//         },

//         fill: {
//             type: 'gradient',
//             gradient: {
//                 shadeIntensity: 1,
//                 opacityFrom: 0.35,
//                 opacityTo: 0.05,
//                 stops: [50, 90, 100]
//             }
//         },

//         grid: {
//             xaxis: { lines: { show: false } },
//             yaxis: { lines: { show: true } } 
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: val => val.toLocaleString('id-ID')
//         },

//         tooltip: {
//             shared: true,
//             intersect: false,
//             y: {
//                 formatter: val => val.toLocaleString('id-ID')
//             }
//         },

//         legend: {
//             position: 'top',
//             horizontalAlign: 'right'
//         }
//     };

//     // Kosongkan container sebelum render
//     document.querySelector(`#${name}`).innerHTML = "";
//     new ApexCharts(document.querySelector(`#${name}`), options).render();
// }

// function renderGrafikUmur(name, data) {
//     const categories    = data.map(item => item.RANGE_UMUR);
//     const lakiData      = data.map(item => -parseInt(item.LAKI_LAKI || 0));
//     const perempuanData = data.map(item =>  parseInt(item.PEREMPUAN || 0));

//     const options = {
//         chart: {
//             type: 'bar',
//             height: 500,
//             stacked: true,
//             toolbar: { show: false }
//         },

//         plotOptions: {
//             bar: {
//                 borderRadius           : 5,
//                 borderRadiusApplication: 'end',
//                 borderRadiusWhenStacked: 'all',
//                 horizontal             : true,
//                 barHeight              : '80%'
//             },
//         },

//         series: [
//             {name: 'Laki-laki',data: lakiData},
//             {name: 'Perempuan',data: perempuanData}
//         ],

//         xaxis: {
//             categories: categories,
//             labels: {
//                 formatter: function(val) {
//                     return Math.abs(val).toLocaleString('id-ID');
//                 }
//             }
//         },

//         yaxis: {
//             stepSize: 1,
//             title: { text: 'Umur' }
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: function(val) {
//                 return Math.abs(val).toLocaleString('id-ID');
//             }
//         },

//         tooltip: {
//             shared: true,
//             intersect: false,
//             y: {
//                 formatter: function(val) {
//                     return Math.abs(val).toLocaleString('id-ID') + " Pasien";
//                 }
//             }
//         },

//         stroke: {
//             width: 1,
//             colors: ['#fff']
//         },

//         grid: {
//             xaxis: {
//                 lines: { show: false }
//             }
//         },

//         legend: {
//             position: 'bottom'
//         },

//         colors: ['#3b82f6', '#ec4899']
//     };

//     document.querySelector(`#${name}`).innerHTML = "";
//     new ApexCharts(document.querySelector(`#${name}`), options).render();
// }

// function renderchartareaSPM(name, data, titleX, titleY, seriesName, fieldName, slaValue, slaLabel) {

//     const options = {

//         chart: {
//             type   : "area",
//             height : 380,
//             toolbar: { show: false },
//             zoom   : { enabled: false }
//         },

//         series: [{
//             name: seriesName,
//             data: data.map(item => parseFloat(item[fieldName]))
//         }],

//         xaxis: {
//             categories   : data.map(item => item.periode),
//             title        : { text: titleX },
//             tickPlacement: 'on',
//             axisBorder   : { show: true },
//             axisTicks    : { show: true }
//         },

//         yaxis: {
//             title         : { text: titleY },
//             min           : 0,
//             forceNiceScale: true,
//             labels: {
//                 formatter: function (val) {
//                     return val.toFixed(2) + " Menit";
//                 }
//             }
//         },

//         stroke: {
//             curve: 'smooth',
//             width: 3
//         },

//         fill: {
//             type: 'gradient',
//             gradient: {
//                 shadeIntensity: 1,
//                 opacityFrom: 0.35,
//                 opacityTo: 0.05,
//                 stops: [0, 90, 100]
//             }
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: function (val) {
//                 return val.toFixed(2);
//             }
//         },

//         tooltip: {
//             y: {
//                 formatter: function (val) {
//                     return val.toFixed(2);
//                 }
//             }
//         },

//         grid: {
//             borderColor: '#f1f1f1',
//             strokeDashArray: 4
//         },

//         legend: {
//             position: 'top'
//         },

//         // ===== SLA LINE =====
//         annotations: {
//             yaxis: [{
//                 y: slaValue,
//                 borderColor: '#FF0000',
//                 strokeDashArray: 3,
//                 label: {
//                     text: slaLabel,
//                     style: {
//                         background: '#FF0000',
//                         color: '#fff',
//                         fontSize: '12px'
//                     }
//                 }
//             }]
//         }

//     };

//     document.querySelector(`#${name}`).innerHTML = "";
//     new ApexCharts(document.querySelector(`#${name}`), options).render();
// }

// function renderPyramidChart(name, data, seriesConfig) {

//     if (chartInstances[name]) {
//         chartInstances[name].destroy();
//         chartInstances[name] = null;
//     }
    
//     const categories = data.map(item => item.RANGE_UMUR);

//     const series = seriesConfig.map(config => {
//         const isNegative = config.negative === true;

//         return {
//             name: config.name,
//             data: data.map(item => {
//                 let value = parseInt(item[config.field] || 0);
//                 return isNegative ? -value : value;
//             })
//         };
//     });

//     const options = {

//         chart: {
//             type: 'bar',
//             height: 500,
//             stacked: true,
//             toolbar: { show: false }
//         },

//         plotOptions: {
//             bar: {
//                 horizontal: true,
//                 barHeight: '80%',
//                 borderRadius: 5,
//                 borderRadiusApplication: 'end',
//                 borderRadiusWhenStacked: 'all'
//             }
//         },

//         series: series,

//         xaxis: {
//             categories: categories,
//             labels: {
//                 formatter: function (val) {
//                     return Math.abs(val).toLocaleString('id-ID');
//                 }
//             }
//         },

//         yaxis: {
//             title: { text: 'Kategori' }
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: function (val) {
//                 return Math.abs(val).toLocaleString('id-ID');
//             }
//         },

//         tooltip: {
//             shared: true,
//             intersect: false,
//             y: {
//                 formatter: function (val) {
//                     return Math.abs(val).toLocaleString('id-ID') + " Pasien";
//                 }
//             }
//         },

//         stroke: {
//             width: 1,
//             colors: ['#fff']
//         },

//         legend: {
//             position: 'bottom'
//         },

//         colors: seriesConfig.map(s => s.color || null)
//     };

//     document.querySelector(`#${name}`).innerHTML = "";
//     new ApexCharts(document.querySelector(`#${name}`), options).render();
// }

// function renderchartarea(name, data, titleX, titleY, seriesName, fieldName, slaValue, slaLabel){

//     if (chartInstances[name]) {
//         chartInstances[name].destroy();
//         chartInstances[name] = null;
//     }

//     let series = [];

//     // ===== Jika Multiple Series =====
//     if (Array.isArray(seriesName) && Array.isArray(fieldName)) {

//         series = seriesName.map((name, index) => ({
//             name: name,
//             data: data.map(item =>
//                 parseFloat(item[fieldName[index]] || 0)
//             )
//         }));

//     } else {
//         // ===== Single Series (cara lama tetap jalan) =====
//         series = [{
//             name: seriesName,
//             data: data.map(item =>
//                 parseFloat(item[fieldName] || 0)
//             )
//         }];
//     }

//     const options = {

//         chart: {
//             type: "area",
//             height: 300,
//             toolbar: { show: false },
//             zoom: { enabled: false }
//         },

//         series: series,

//         xaxis: {
//             categories: data.map(item => item.periode),
//             title: { text: titleX },
//             tickPlacement: 'on',
//             axisBorder: { show: true },
//             axisTicks: { show: true }
//         },

//         yaxis: {
//             title: { text: titleY },
//             min: 0,
//             forceNiceScale: true,
//             labels: {
//                 formatter: function (val) {
//                     return val.toLocaleString();
//                 }
//             }
//         },

//         stroke: {
//             curve: 'smooth',
//             width: 3
//         },

//         fill: {
//             type: 'gradient',
//             gradient: {
//                 shadeIntensity: 1,
//                 opacityFrom: 0.75,
//                 opacityTo: 0.25,
//                 stops: [0, 100]
//             }
//         },

//         dataLabels: {
//             enabled: true,
//             formatter: function (val) {
//                 return val.toLocaleString();
//             }
//         },

//         tooltip: {
//             y: {
//                 formatter: function (val) {
//                     return val.toLocaleString();
//                 }
//             }
//         },

//         grid: {
//             strokeDashArray: 4
//         },

//         legend: {
//             position: 'top'
//         },

//         annotations: slaValue ? {
//             yaxis: [{
//                 y: slaValue,
//                 borderColor: '#FF0000',
//                 strokeDashArray: 3,
//                 label: {
//                     text: slaLabel,
//                     style: {
//                         background: '#FF0000',
//                         color: '#fff',
//                         fontSize: '12px'
//                     }
//                 }
//             }]
//         } : {}

//     };

//     document.querySelector(`#${name}`).innerHTML = "";
//     new ApexCharts(document.querySelector(`#${name}`), options).render();
// }

function renderchartbar(name, data, seriesConfig, titleX, titleY, showLegend = false) {

    let series = seriesConfig.map(cfg => {
        return {
            name: cfg.name,
            data: data.map(item => item[cfg.field] || 0)
        };
    });

    let options = {

        chart: {
            type: "bar",
            height: 300,
            stacked: true,
            stackType: "100%",
            toolbar: { show: false },
            zoom: { enabled: false }
        },

        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '85%'
            }
        },

        series: series,

        xaxis: {
            categories: data.map(item => item.periode),
            title: { text: titleX }
        },

        yaxis: {
            title: { text: titleY },
            min: 0,
            max: 100,
            labels: {
                formatter: val => val.toFixed(0) + "%"
            }
        },

        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(0) + "%";
            }
        },

        tooltip: {
            y: {
                formatter: val => val.toLocaleString()
            }
        },

        legend: {
            show: showLegend
        }

    };

    document.querySelector(`#${name}`).innerHTML = "";
    new ApexCharts(document.querySelector(`#${name}`), options).render();
}

function renderchartarea(
    name,
    data,
    titleX,
    titleY,
    seriesName,
    fieldName,
    slaValue,
    slaLabel,
    rightAxisIndex = null,
    rightAxisLabel = ""
){

    if (chartInstances[name]) {
        chartInstances[name].destroy();
        chartInstances[name] = null;
    }

    let series = [];

    if (Array.isArray(seriesName) && Array.isArray(fieldName)) {

        series = seriesName.map((nm, index) => ({

            name: nm,
            data: data.map(item =>
                parseFloat(item[fieldName[index]] || 0)
            ),

            yAxisIndex: (rightAxisIndex !== null && index === rightAxisIndex) ? 1 : 0

        }));

    } else {

        series = [{
            name: seriesName,
            data: data.map(item =>
                parseFloat(item[fieldName] || 0)
            )
        }];
    }


    const options = {

        chart: {
            type: "area",
            height: 300,
            toolbar: { show: false },
            zoom: { enabled: false }
        },

        series: series,

        xaxis: {
            categories: data.map(item => item.periode),
            title: { text: titleX },
            tickPlacement: 'on',
            axisBorder: { show: true },
            axisTicks: { show: true }
        },

        yaxis: rightAxisIndex !== null ? [
            {
                title: { text: titleY },
                min: 0,
                forceNiceScale: true,
                labels: {
                    formatter: function (val) {
                        return val.toLocaleString();
                    }
                }
            },
            {
                opposite: true,
                title: { text: rightAxisLabel },
                labels: {
                    formatter: val => val.toLocaleString()
                }
            }
        ] : {
            title: { text: titleY },
            min: 0,
            forceNiceScale: true,
            labels: {
                formatter: function (val) {
                    return val.toLocaleString();
                }
            }
        },

        stroke: {
            curve: 'smooth',
            width: 3
        },

        markers: {
            size: 4
        },

        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.75,
                opacityTo: 0.25,
                stops: [0, 100]
            }
        },

        dataLabels: {
            enabled: true,
            formatter: val => val.toLocaleString()
        },

        tooltip: {
            y: {
                formatter: val => val.toLocaleString()
            }
        },

        grid: {
            strokeDashArray: 4
        },

        legend: {
            position: 'top'
        },

        annotations: slaValue ? {
            yaxis: [{
                y: slaValue,
                borderColor: '#FF0000',
                strokeDashArray: 3,
                label: {
                    text: slaLabel,
                    style: {
                        background: '#FF0000',
                        color: '#fff'
                    }
                }
            }]
        } : {}

    };

    document.querySelector(`#${name}`).innerHTML = "";

    chartInstances[name] = new ApexCharts(
        document.querySelector(`#${name}`),
        options
    );

    chartInstances[name].render();
}

function renderchartpie(name, data){

    let labels = [];
    let series = [];

    data.forEach(function(item){

        labels.push(item.LABEL);
        series.push(parseInt(item.TOTAL));

    });

    let options = {

        chart: {
            type: "donut",
            height: 350
        },

        series: series,
        labels: labels,

        legend: {
            position: "bottom"
        },

        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%";
            }
        },

        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString("id-ID") + " Pasien";
                }
            }
        }

    };

    document.querySelector(`#${name}`).innerHTML = "";

    new ApexCharts(
        document.querySelector(`#${name}`),
        options
    ).render();
}

function renderPyramidChart(name, data, seriesConfig) {

    if (chartInstances[name]) {
        chartInstances[name].destroy();
        chartInstances[name] = null;
    }
    
    const categories = data.map(item => item.RANGE_UMUR);

    const series = seriesConfig.map(config => {
        const isNegative = config.negative === true;

        return {
            name: config.name,
            data: data.map(item => {
                let value = parseInt(item[config.field] || 0);
                return isNegative ? -value : value;
            })
        };
    });

    const options = {

        chart: {
            type: 'bar',
            height: 500,
            stacked: true,
            toolbar: { show: false }
        },

        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '80%',
                borderRadius: 5,
                borderRadiusApplication: 'end',
                borderRadiusWhenStacked: 'all'
            }
        },

        series: series,

        xaxis: {
            categories: categories,
            labels: {
                formatter: function (val) {
                    return Math.abs(val).toLocaleString('id-ID');
                }
            }
        },

        yaxis: {
            title: { text: 'Kategori' }
        },

        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return Math.abs(val).toLocaleString('id-ID');
            }
        },

        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (val) {
                    return Math.abs(val).toLocaleString('id-ID') + " Pasien";
                }
            }
        },

        stroke: {
            width: 1,
            colors: ['#fff']
        },

        legend: {
            position: 'bottom'
        },

        colors: seriesConfig.map(s => s.color || null)
    };

    document.querySelector(`#${name}`).innerHTML = "";
    new ApexCharts(document.querySelector(`#${name}`), options).render();
}