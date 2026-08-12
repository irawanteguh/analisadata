let globaldatachannelpendaftaran = [];

datachannelpendaftaranrj();

$('#selectperiode').on('change', function () {
    datachannelpendaftaranrj();
});

$("#btndownloaddatachannelpendaftaranrj_table").on("click", function () {

    exportToExcel(
        globaldatachannelpendaftaran,
        "Channel Pendaftaran Rawat Jalan",
        "Channel_Pendaftaran_Rawat_Jalan.xlsx",
        {
            formatter: (item, index) => ({
                "No"       : index + 1,
                "Channel"  : item.CHANNEL || "",
                "Januari"  : Number(item.JAN || 0),
                "Februari" : Number(item.FEB || 0),
                "Maret"    : Number(item.MAR || 0),
                "April"    : Number(item.APR || 0),
                "Mei"      : Number(item.MEI || 0),
                "Juni"     : Number(item.JUN || 0),
                "Juli"     : Number(item.JUL || 0),
                "Agustus"  : Number(item.AGU || 0),
                "September": Number(item.SEP || 0),
                "Oktober"  : Number(item.OKT || 0),
                "November" : Number(item.NOV || 0),
                "Desember" : Number(item.DES || 0)
            })
        }
    );

});

function datachannelpendaftaranrj(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/outpatient/channelpendaftaran/datachannelpendaftaranrj",
        data      : {selectperiode: selectperiode},
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
        },
        success: function(response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Records Found',
                    text: 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer: 2000
                });
                return;
            }

            const result = Array.isArray(response.responResult)
                ? response.responResult
                : [];

            globaldatachannelpendaftaran = result;

            const bulan = [
                "JAN",
                "FEB",
                "MAR",
                "APR",
                "MEI",
                "JUN",
                "JUL",
                "AGU",
                "SEP",
                "OKT",
                "NOV",
                "DES"
            ];

            const series = result.map(item => ({
                name: item.CHANNEL,
                data: bulan.map(bulan => Number(item[bulan] || 0))
            }));

            renderChartStackedBar(
                "grafikchannelpendaftaranrj",
                bulan,
                series,
                "Bulan",
                "Jumlah Pendaftaran"
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