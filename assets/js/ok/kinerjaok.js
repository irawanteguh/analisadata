datakunjungan();

$('#selectperiode').on('change', function () {
    datakunjungan();
});

function datakunjungan(){
    let selectperiode   = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/ok/kinerjaok/datakunjungan",
        data      : {selectperiode:selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
        beforeSend: function(){
            am4core.disposeAllCharts();
            
            Swal.fire({
                title: 'Processing',
                html : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
        },
        success: function (data) {
            if (data.responCode !== "00") {
                Swal.fire("Info", "Data tidak ditemukan", "info");
                return;
            }

            const rawData           = data.responResult || [];
            const chartData         = aggregateKunjunganBulanan(rawData);
            const heatmapData       = aggregateHeatmapOK(rawData);
            const heatmapDataDurasi = aggregateHeatmapOKByJam(rawData);


            console.log(heatmapDataDurasi);

            renderStackedClusteredChart("grafikaggregateok", chartData);
            renderHeatmapOK("grafikaggregateruang", heatmapData);
            renderHeatmapOKByJam("grafikaggregatewaktu", heatmapDataDurasi);
            renderWordCloudAlasanBatal("grafikaggregatereason",rawData);
            
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve visit data.'
            });
        }
    });
}

function aggregateKunjunganBulanan(data) {

    const map = {};

    /* ================= INISIALISASI 12 BULAN ================= */
    for (let i = 1; i <= 12; i++) {
        let bulan = String(i).padStart(2, "0");
        map[bulan] = {
            bulan    : bulan,
            status00 : 0,
            status01 : 0,
            status02 : 0,
            status99 : 0
        };
    }

    /* ================= ISI DATA ================= */
    data.forEach(item => {

        if (!item.TGL_TINDAKAN) return;

        let tgl   = new Date(item.TGL_TINDAKAN.replace(/-/g, " "));
        let bulan = String(tgl.getMonth() + 1).padStart(2, "0");

        if (item.STATUS_ID === "00") map[bulan].status00++;
        if (item.STATUS_ID === "01") map[bulan].status01++;
        if (item.STATUS_ID === "02") map[bulan].status02++;
        if (item.STATUS_ID === "99") map[bulan].status99++;
    });

    /* ================= RETURN TERURUT ================= */
    return Object.values(map).sort((a, b) =>
        a.bulan.localeCompare(b.bulan)
    );
}


function aggregateHeatmapOK(data) {

    const map = {};
    const bulanList = ["01","02","03","04","05","06","07","08","09","10","11","12"];
    const ruangSet  = new Set();

    /* ================= AMBIL DATA ================= */
    data.forEach(item => {

        if (item.STATUS_ID !== "02") return;

        let tgl   = new Date(item.TGL_TINDAKAN.replace(/-/g, " "));
        let bulan = String(tgl.getMonth() + 1).padStart(2, "0");
        let ruang = item.RUANG_OK || "Tidak Diketahui";

        ruangSet.add(ruang);

        let key = ruang + "-" + bulan;

        if (!map[key]) {
            map[key] = { ruang_ok: ruang, bulan, value: 0 };
        }
        map[key].value++;
    });

    /* ================= URUTKAN RUANG OK ================= */
    const ruangList = Array.from(ruangSet).sort((a, b) =>
        a.localeCompare(b, undefined, { numeric: true })
    );

    /* ================= BENTUK DATA FINAL ================= */
    const result = [];

    ruangList.forEach(ruang => {
        bulanList.forEach(bulan => {
            let key = ruang + "-" + bulan;
            result.push(
                map[key] || { ruang_ok: ruang, bulan, value: 0 }
            );
        });
    });

    return result;
}

function aggregateHeatmapOKByJam(data) {

    const JAM_OPERASIONAL_OK = Array.from(
        { length: 24 },
        (_, i) => String(i).padStart(2, "0")
    );

    const map = {};
    const ruangSet = new Set();
    const uniqueTR = new Set(); // ⬅️ PENTING

    data.forEach(item => {

        if (String(item.STATUS_ID) !== "02") return;
        if (!item.JAM_MULAI || !item.RUANG_OK || !item.TRANS_ID) return;

        // 🔒 DEDUPLIKASI BERDASARKAN TRANSAKSI
        if (uniqueTR.has(item.TRANS_ID)) return;
        uniqueTR.add(item.TRANS_ID);

        // 🔒 NORMALISASI JAM
        let jam = item.JAM_MULAI.split(":")[0].padStart(2, "0");
        let ruang = item.RUANG_OK.trim();

        ruangSet.add(ruang);

        let key = ruang + "-" + jam;

        if (!map[key]) {
            map[key] = {
                ruang_ok: ruang,
                jam     : jam,
                value   : 0
            };
        }

        map[key].value++;
    });

    /* ================= ISI JAM KOSONG ================= */
    const result = [];

    Array.from(ruangSet)
        .sort((a, b) => a.localeCompare(b))
        .forEach(ruang => {

            JAM_OPERASIONAL_OK.forEach(jam => {

                let key = ruang + "-" + jam;

                result.push(
                    map[key] || {
                        ruang_ok: ruang,
                        jam     : jam,
                        value   : 0
                    }
                );
            });
        });

    return result;
}


function renderHeatmapOK(divId, data) {
    am4core.useTheme(am4themes_animated);

    let chart = am4core.create(divId, am4charts.XYChart);
    chart.data = data;

    chart.maskBullets = false;

    /* ================= X AXIS (BULAN) ================= */
    let xAxis                                 = chart.xAxes.push(new am4charts.CategoryAxis());
        xAxis.dataFields.category             = "bulan";
        xAxis.title.text                      = "Bulan";
        xAxis.title.fill                      = am4core.color("#6C757D");
        xAxis.renderer.grid.template.disabled = true;
        xAxis.renderer.minGridDistance        = 1;
        xAxis.renderer.labels.template.fill = am4core.color("#6C757D");

    /* ================= Y AXIS (RUANG OK) ================= */
    let yAxis                                 = chart.yAxes.push(new am4charts.CategoryAxis());
        yAxis.dataFields.category             = "ruang_ok";
        yAxis.title.text                      = "Ruang OK";
        yAxis.title.fill                      = am4core.color("#6C757D");
        yAxis.renderer.grid.template.disabled = true;
        yAxis.renderer.labels.template.fill = am4core.color("#6C757D");

    /* ================= SERIES (HEATMAP) ================= */
    let series                                = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.categoryX           = "bulan";
        series.dataFields.categoryY           = "ruang_ok";
        series.dataFields.value               = "value";
        series.sequencedInterpolation         = true;
        series.columns.template.tooltipText   = "[bold]{ruang_ok}[/]\nBulan {bulan}\nJumlah: {value}";
        series.columns.template.width         = am4core.percent(100);
        series.columns.template.height        = am4core.percent(100);
        series.columns.template.strokeOpacity = 0;

    /* ================= HEAT RULE ================= */
    series.heatRules.push({
        target   : series.columns.template,
        property : "fill",
        min      : am4core.color("#F8D7DA"),
        max      : am4core.color("#DC3545")
    });

    /* ================= LABEL DI CELL ================= */
    let labelBullet                     = series.bullets.push(new am4charts.LabelBullet());
        labelBullet.label.text          = "{value}";
        labelBullet.label.fill          = am4core.color("#212529");
        labelBullet.interactionsEnabled = false;

    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "none";

    chart.appear(1000, 100);
}

function renderHeatmapOKByJam(divId, data) {

    am4core.ready(function () {

        am4core.useTheme(am4themes_animated);

        // Hapus chart lama di div ini saja
        if (am4core.registry.baseSprites.length) {
            am4core.registry.baseSprites
                .filter(c => c.htmlContainer && c.htmlContainer.id === divId)
                .forEach(c => c.dispose());
        }

        let chart = am4core.create(divId, am4charts.XYChart);
        chart.data = data;
        chart.maskBullets = false;

        /* ================= X AXIS (JAM) ================= */
        let xAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        xAxis.dataFields.category = "jam";
        xAxis.title.text = "Jam";
        xAxis.renderer.grid.template.disabled = true;
        xAxis.renderer.minGridDistance = 1;

        // Tampilkan format 07:00
        xAxis.renderer.labels.template.adapter.add("text", function (text) {
            return text;
        });

        /* ================= Y AXIS (RUANG OK) ================= */
        let yAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        yAxis.dataFields.category = "ruang_ok";
        yAxis.title.text = "Ruang OK";
        yAxis.renderer.grid.template.disabled = true;

        /* ================= SERIES (HEATMAP) ================= */
        let series = chart.series.push(new am4charts.ColumnSeries());
        series.dataFields.categoryX = "jam";
        series.dataFields.categoryY = "ruang_ok";
        series.dataFields.value     = "value";
        series.sequencedInterpolation = true;

        series.columns.template.width  = am4core.percent(100);
        series.columns.template.height = am4core.percent(100);
        series.columns.template.strokeOpacity = 0;

        series.columns.template.tooltipText =
            "[bold]{ruang_ok}[/]\nJam {jam}:00\nDurasi / Jumlah: {value}";

        /* ================= HEAT RULE ================= */
        series.heatRules.push({
            target   : series.columns.template,
            property : "fill",
            min      : am4core.color("#E9ECEF"), // ringan
            max      : am4core.color("#DC3545")  // padat
        });

        /* ================= LABEL DALAM CELL ================= */
        let labelBullet = series.bullets.push(new am4charts.LabelBullet());
        labelBullet.label.text = "{value}";
        labelBullet.label.fill = am4core.color("#212529");
        labelBullet.label.fontSize = 11;
        labelBullet.interactionsEnabled = false;

        // Sembunyikan label jika 0
        labelBullet.label.adapter.add("text", function (text, target) {
            return target.dataItem.value === 0 ? "" : text;
        });

        /* ================= CURSOR ================= */
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "none";

        chart.appear(800, 100);
    });
}

function renderStackedClusteredChart(divId, data) {
    am4core.useTheme(am4themes_animated);

    let chart      = am4core.create(divId, am4charts.XYChart);
        chart.data = data;

    /* ================= CATEGORY AXIS ================= */
    let categoryAxis                                 = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category             = "bulan";
        categoryAxis.title.text                      = "Bulan";
        categoryAxis.title.fill                      = am4core.color("#6C757D");
        categoryAxis.renderer.grid.template.disabled = true;
        categoryAxis.renderer.minGridDistance        = 1;                                               // Penting agar semua label muncul
        categoryAxis.renderer.labels.template.fill   = am4core.color("#6C757D");

    /* ================= VALUE AXIS ================= */
    let valueAxis                                 = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text                      = "Jumlah Kunjungan";
        valueAxis.title.fill                      = am4core.color("#6C757D");
        valueAxis.min                             = 0;
        valueAxis.calculateTotals                 = true;
        valueAxis.renderer.grid.template.disabled = true;
        valueAxis.renderer.labels.template.fill   = am4core.color("#6C757D");

    /* ================= SERIES ================= */
    createSeries("status02", "Finish");
    createSeries("status99", "Canceled");

    function createSeries(field, name) {
        let series                              = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY            = field;
            series.dataFields.categoryX         = "bulan";
            series.name                         = name;
            series.stacked                      = true;
            series.clustered                    = true;
            series.columns.template.tooltipText = "[bold]{name}[/]\nBulan {categoryX}: {valueY}";

        /* ================= DATA LABEL ================= */
        let labelBullet                     = series.bullets.push(new am4charts.LabelBullet());
            labelBullet.label.text          = "{valueY}";
            labelBullet.label.fill          = am4core.color("#FFFFFF");
            labelBullet.label.fontSize      = 11;
            labelBullet.locationY           = 0.5;
            labelBullet.interactionsEnabled = false;
    }

    chart.legend          = new am4charts.Legend();
    chart.legend.position = "bottom";

    chart.appear(1000, 100);
}

function renderWordCloudAlasanBatal(divId, dataResult){

    am4core.useTheme(am4themes_animated);

    // ====== AGREGASI ======
    let agregat = {};
    dataResult.forEach(item => {
        let alasan = item.ALASAN_BATAL;
        if (alasan && alasan.trim() !== "") {
            agregat[alasan] = (agregat[alasan] || 0) + 1;
        }
    });

    // ====== SORT & AMBIL TOP 50 ======
    let chartData = Object.keys(agregat)
        .map(key => ({
            word  : key,
            count : agregat[key]
        }))
        .sort((a, b) => b.count - a.count)   // terbesar dulu
        .slice(0, 70);                       // ambil 50 teratas

    // Jika tidak ada data
    if (chartData.length === 0) {
        document.getElementById(divId).innerHTML =
            "<div class='text-center text-muted pt-5'>Tidak ada data alasan batal</div>";
        return;
    }

    // ====== WORD CLOUD ======
    let chart = am4core.create(divId, am4plugins_wordCloud.WordCloud);
    chart.fontFamily = "Courier New";
    chart.zoomable   = false;

    let series = chart.series.push(
        new am4plugins_wordCloud.WordCloudSeries()
    );

    series.data = chartData;

    series.dataFields.word  = "word";
    series.dataFields.value = "count";

    // Tampilan
    series.minFontSize       = 10;
    series.maxFontSize       = 70;
    series.randomness        = 0.25;
    series.rotationThreshold = 0.2;

    series.labels.template.tooltipText =
        "[bold]{word}[/]\nJumlah: {value}";

    series.labels.template.fillOpacity = 0.9;

    chart.appear(800, 100);
}