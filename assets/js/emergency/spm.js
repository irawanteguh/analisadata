
spmigd();

$('#selectperiode').on('change', function () {
    spmigd();
});

function spmigd(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/emergency/spm/spmigd",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",
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
        success: function (res) {

            const result       = res.responResult || [];
            const tahun        = selectperiode;
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMapIGD      = {};
            const dataMapTransfer = {};

            result.forEach(item => {
                dataMapIGD[item.PERIODE]      = parseFloat(item.RATA_MENIT_IGD_SPRI || 0);
                dataMapTransfer[item.PERIODE] = parseFloat(item.RATA_MENIT_RANAP_TRANSFER || 0);
            });

            // ===== Chart IGD → SPRI =====
            const chartDataIGD = bulanLengkap.map((b, index) => {
                const periodeDB = `${tahun}-${b}`;
                return {
                    periode: namaBulan[index],
                    avgValue: dataMapIGD[periodeDB] ?? 0
                };
            });

            // ===== Chart RANAP → TRANSFER =====
            const chartDataTransfer = bulanLengkap.map((b, index) => {
                const periodeDB = `${tahun}-${b}`;
                return {
                    periode: namaBulan[index],
                    avgValue: dataMapTransfer[periodeDB] ?? 0
                };
            });

            // Render Chart IGD → SPRI
            renderchartarea(
                "grafikspmspri",
                chartDataIGD,
                "Periode Pelayanan",
                "Rata-rata Waktu (Menit)",
                "IGD → SPRI",
                "avgValue",
                360,
                "Target SLA 6 Jam (360 Menit)"
            );

            // Render Chart RANAP → TRANSFER
            renderchartarea(
                "grafiktransfer",
                chartDataTransfer,
                "Periode Pelayanan",
                "Rata-rata Waktu (Menit)",
                "RANAP → TRANSFER",
                "avgValue",
                60, // misal SLA 1 jam
                "Target SLA 1 Jam (60 Menit)"
            );
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve data.'
            });
        }
    });
}