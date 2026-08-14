let globaldatacarakeluar = [];

datacarakeluar();

$('#selectperiode').on('change', function () {
    datacarakeluar();
});

$("#btndownloaddatacarakeluar_table").on("click", function () {
    exportToExcel(
        globaldatacarakeluar,
        "Cara Keluar Pasien IGD",
        "Cara_Keluar_Pasien_IGD.xlsx",
        {
            formatter: (item, index) => ({
                "No"           : index + 1,
                "No. RM"       : item.MRPAS || "",
                "Nama Pasien"  : item.NAMAPASIEN || "",
                "Tanggal"      : item.TGLMASUK || "",
                "Ruangan"      : item.RUANGRWT_ID || "",
                "Jenis Episode": item.JENIS_EPISODE === "I" ? (String(item.RUANGRWT_ID || "").toUpperCase().startsWith("TRANSIT") ? "Transit" : "Rawat Inap") : "IGD",
                "Tindak Lanjut": item.TINDAKLANJUT || "",
                "Cara Keluar"  : item.CARAKELUAR || "",
                "Catatan"      : ""
            })
        }
    );
});

function datacarakeluar(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/igd/carakeluar/datacarakeluar",
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

            $("#resultdatacarakeluar").empty();
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
            globaldatacarakeluar = result;

            var tableresult    = "";
            for (var i in result) {

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td><div>"+(result[i].MRPAS||"")+"</div><div>"+(result[i].NAMAPASIEN||"")+"</div></td>";
                tableresult += "<td>"+(result[i].TGLMASUK||"")+"</td>";
                tableresult += "<td>"+(result[i].TINDAKLANJUT||"")+"</td>";
                tableresult += "<td>"+(result[i].CARAKELUAR||"")+"</td>";
                tableresult += "<td class='text-end pe-4'>"+(result[i].JENIS_EPISODE === "O" ? "IGD" : "Transit") +"</td>";
                tableresult += "</tr>";
            }

            $("#resultdatacarakeluar").html(tableresult);
            const table = initDataTable("#datacarakeluar_table","#searchtable");

            const totaltindaklanjut = aggregateSeries(result, "PERIODE", "TINDAKLANJUT");
            const totalcarapulang   = aggregateSeries(result, "PERIODE", "CARAKELUAR");
            const bulanLengkap      = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan         = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMapTL         = {};
            const dataMapCaraKeluar = {};

            totaltindaklanjut.forEach(item => {
                dataMapTL[item.periode] = item;
            });

            totalcarapulang.forEach(item => {
                dataMapCaraKeluar[item.periode] = item;
            });

            const seriesKeysTL = [
                ...new Set(
                    totaltindaklanjut.flatMap(item =>
                        Object.keys(item).filter(key => key !== "periode")
                    )
                )
            ].sort();

            const seriesKeysCaraKeluar = [
                ...new Set(
                    totalcarapulang.flatMap(item =>
                        Object.keys(item).filter(key => key !== "periode")
                    )
                )
            ].sort();

            const seriesTL = seriesKeysTL.map(key => ({
                name: key,
                data: bulanLengkap.map(bulan => dataMapTL[bulan]?.[key] || 0)
            }));

            const seriesCaraKeluar = seriesKeysCaraKeluar.map(key => ({
                name: key,
                data: bulanLengkap.map(bulan => dataMapCaraKeluar[bulan]?.[key] || 0)
            }));


            renderChartStackedBar(
                "trentindaklanjutigd",
                namaBulan,
                seriesTL,
                "Periode Pelayanan",
                "Jumlah Pasien"
            );

            renderChartStackedBar(
                "trencarakeluarigd",
                namaBulan,
                seriesCaraKeluar,
                "Periode Pelayanan",
                "Jumlah Pasien"
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