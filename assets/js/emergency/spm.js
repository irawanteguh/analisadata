
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

            const dataMapIGD         = {};
            const dataMapTransfer    = {};
            const dataMapIGDRaw      = {};
            const dataMapTransferRaw = {};
            

            result.forEach(item => {
                dataMapIGD[item.PERIODE]      = parseFloat(item.RATA_IGD_SPRI || 0);
                dataMapTransfer[item.PERIODE] = parseFloat(item.RATA_RANAP_TRANSFER || 0);

                dataMapIGDRaw[item.PERIODE] = {
                    cepat: parseFloat(item.IGD_SPRI_LT_360 || 0),
                    lambat: parseFloat(item.IGD_SPRI_GE_360 || 0),
                    invalid: parseFloat(item.IGD_SPRI_INVALID || 0)
                };

                dataMapTransferRaw[item.PERIODE] = {
                    cepat: parseFloat(item.RANAP_LT_60 || 0),
                    lambat: parseFloat(item.RANAP_GE_60 || 0),
                    invalid: parseFloat(item.RANAP_INVALID || 0)
                };
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

            const chartIGDRaw = bulanLengkap.map((b, index) => {
                const periodeDB = `${tahun}-${b}`;
                const val = dataMapIGDRaw[periodeDB] || {};

                return {
                    periode: namaBulan[index],
                    cepat  : val.cepat || 0,
                    lambat : val.lambat || 0,
                    invalid: val.invalid || 0
                };
            });

            const chartTransferRaw = bulanLengkap.map((b, index) => {
                const periodeDB = `${tahun}-${b}`;
                const val       = dataMapTransferRaw[periodeDB] || {};

                return {
                    periode: namaBulan[index],
                    cepat  : val.cepat || 0,
                    lambat : val.lambat || 0,
                    invalid: val.invalid || 0
                };
            });

            renderchartarea("grafikspmspri",chartDataIGD,"Periode Pelayanan","Waktu Tunggu (Menit)",["IGD - SPRI"],["avgValue"],null,"",null,"Target SLA 6 Jam (360 Menit)",360);
            renderchartarea("grafiktransfer",chartDataTransfer,"Periode Pelayanan","Waktu Tunggu (Menit)",["RANAP - TRANSFER"],["avgValue"],null,"",null,"Target SLA 1 Jam (60 Menit)",60);

            renderChartStackedBar(
                "grafikspmspriraw",
                chartIGDRaw.map(item => item.periode),
                [
                    {
                        name: "<= 60 Menit",
                        data: chartIGDRaw.map(item => item.cepat)
                    },
                    {
                        name: "> 60 Menit",
                        data: chartIGDRaw.map(item => item.lambat)
                    },
                    {
                        name: "Invalid",
                        data: chartIGDRaw.map(item => item.invalid)
                    }
                ],
                "Periode Tanggal Pulang Rawat Inap",
                "Persentase",
                true
            );

            renderChartStackedBar(
                "grafiktransferraw",
                chartTransferRaw.map(item => item.periode),
                [
                    {
                        name: "<= 60 Menit",
                        data: chartTransferRaw.map(item => item.cepat)
                    },
                    {
                        name: "> 60 Menit",
                        data: chartTransferRaw.map(item => item.lambat)
                    },
                    {
                        name: "Invalid",
                        data: chartTransferRaw.map(item => item.invalid)
                    }
                ],
                "Periode Tanggal Pulang Rawat Inap",
                "Persentase",
                true
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