dataeklaim();

$('#selectperiode').on('change', function () {
    dataeklaim();
});

function dataeklaim(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/urbpjs/rawatinap/dataeklaim",
        data     : {selectperiode:selectperiode},
        type     : "POST",
        dataType : "JSON",
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

            const result             = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap       = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan          = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            // const jmlkunjungan  = aggregate(result, "count", "PERIODE");
            const jmlkunjungan  = aggregate(result, "count", "PERIODE",["SEP"]);
            const jmlpendapatan = aggregate(result,"sum","PERIODE",["TOTAL_TARIF", "IDRG_TOTAL_TARIF"]);


            const severityMap = {
                "0"  : "Rawat Jalan",
                "I"  : "Severity I",
                "II" : "Severity II",
                "III": "Severity III"
            };

            const mapKunjungan    = {};
            const mapPendapatan   = {};
            const severityCount   = {};
            const severityMonthly = {};

            let totalKunjungan = 0;
            let totalTarifRS   = 0;
            let totalINACBG    = 0;
            let totalIDRG      = 0;

            result.forEach(function (item) {
                totalKunjungan += 1;
                totalTarifRS   += parseFloat(item.TARIF_RS) || 0;
                totalINACBG    += parseFloat(item.TOTAL_TARIF) || 0;
                totalIDRG      += parseFloat(item.IDRG_TOTAL_TARIF) || 0;

                // =========================
                // SEVERITY LEVEL
                // =========================
                const sl      = String(item.SL ?? "").trim();
                const periode = String(item.PERIODE ?? "").trim();

                if (!severityMap[sl]) return;

                severityCount[sl] = (severityCount[sl] || 0) + 1;

                if (!severityMonthly[periode]) {
                    severityMonthly[periode] = {
                        "0": 0,
                        "I": 0,
                        "II": 0,
                        "III": 0
                    };
                }

                severityMonthly[periode][sl] += 1;
            });

            jmlkunjungan.forEach(function (item) {
                mapKunjungan[item.periode] = item;
            });

            jmlpendapatan.forEach(function (item) {
                mapPendapatan[item.periode] = item;
            });

            const chartdatakunjungan = bulanLengkap.map(function (bulan, index) {
                return {
                    periode: namaBulan[index],
                    value1: mapKunjungan[bulan]?.SEP || 0
                };
            });
            
            const chartpendapatan = bulanLengkap.map(function (bulan, index) {
                return {
                    periode: namaBulan[index],
                    value1 : mapPendapatan[bulan]?.TOTAL_TARIF || 0,
                    value2 : mapPendapatan[bulan]?.IDRG_TOTAL_TARIF || 0
                };
            });

            const chartseveritylevel = Object.keys(severityMap).filter(function (sl) {return severityCount[sl] > 0;}).map(function (sl) {
                return {
                    category: severityMap[sl],
                    value   : severityCount[sl]
                };
            });

            const chartDataSeverityMonthly = bulanLengkap.map(function(bulan, index) {
                const data = severityMonthly[bulan] || {
                    "0": 0,
                    "I": 0,
                    "II": 0,
                    "III": 0
                };

                return {
                    periode: namaBulan[index],
                    value_1: data["0"],
                    value_2: data["I"],
                    value_3: data["II"],
                    value_4: data["III"]
                };
            });

            const selisihRSINACBG = totalINACBG - totalTarifRS;
            const selisihRSIDRG   = totalIDRG - totalTarifRS;


            $("#jmlkunjungan").text(new Intl.NumberFormat("id-ID").format(totalKunjungan));
            $("#jmltarifrs").text(todesimal(totalTarifRS));
            $("#jmlinacbg").text(todesimal(totalINACBG));
            $("#selisihinacbg").text(todesimal(selisihRSINACBG));
            $("#jmlidrg").text(todesimal(totalIDRG));
            $("#selisihidrg").text(todesimal(selisihRSIDRG));


            setStatusSelisih("#selisihinacbg", selisihRSINACBG);
            setStatusSelisih("#selisihidrg", selisihRSIDRG);

            
            renderchartarea("trenkunjungan",chartdatakunjungan,"Admission Date","Jumlah Kunjungan",["Jumlah Kunjungan"],["value1"],null,"","value1","Rata-rata Kunjungan",null);
            renderchartarea("trenpendapatan",chartpendapatan,"Admission Date","Tarif Klaim",["Tarif Ina-Cbg","Tarif IDRG"],["value1","value2"],null,"","value1","Rata-rata Pendapatan",null);
            renderchartpie("severitylevelyear", chartseveritylevel);

            renderChartStackedBar(
                "severitylevelmountly",
                chartDataSeverityMonthly.map(item => item.periode),
                [
                    {
                        name: "Rawat Jalan",
                        data: chartDataSeverityMonthly.map(item => item.value_1)
                    },
                    {
                        name: "Severity I",
                        data: chartDataSeverityMonthly.map(item => item.value_2)
                    },
                    {
                        name: "Severity II",
                        data: chartDataSeverityMonthly.map(item => item.value_3)
                    },
                    {
                        name: "Severity III",
                        data: chartDataSeverityMonthly.map(item => item.value_4)
                    }
                ],
                "Admission Date",
                "Jumlah Kasus"
            );
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

function setStatusSelisih(element, value) {

    const card = $(element).closest(".card");

    // Semua elemen teks di dalam card
    const textElements = card.find(".text-dark, .text-success, .text-danger");
    
    // Reset warna card
    card.removeClass(
        "border-dark border-success border-danger " +
        "bg-light-dark bg-light-success bg-light-danger"
    );

    // Reset warna text
    textElements.removeClass(
        "text-dark text-success text-danger"
    );

    if (value >= 0) {

        // SUCCESS
        card.addClass("border-success bg-light-success");

        textElements.addClass("text-success");

    } else {

        // DANGER
        card.addClass("border-danger bg-light-danger");

        textElements.addClass("text-danger");
    }
}