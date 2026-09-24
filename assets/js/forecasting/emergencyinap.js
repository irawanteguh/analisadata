forecastingoutpatient();

$('#selectperiode').on('change', function () {
    forecastingoutpatient();
});

function forecastingoutpatient() {
    let selectperiode = $("select[name='selectperiode']").val();

    $.ajax({
        url      : url + "index.php/forecasting/emergencyinap/forecastingoutpatient",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

        beforeSend: function () {
            Swal.fire({
                title: 'Processing',
                html: 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
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

            const result = response.responResult || [];

            if (result.length === 0) return;

            const data      = result[0];
            const namaBulan = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            const chartData = [];

            for (let i = 1; i <= 12; i++) {

                const idx = String(i).padStart(2, "0");

                chartData.push({
                    periode  : namaBulan[i-1],
                    real     : parseInt(data["REAL_" + idx]) || 0,
                    prediksi : parseInt(data["FORECAST_" + idx]) || 0
                });

            }

            renderchartarea("grafikforecastingoutatient",chartData,"Periode Pelayanan","Jumlah Kunjungan",["Real","Forecast"],["real","prediksi"],null,"",null,"",null);


            $("#forecast_mae").html(
                Math.round(parseFloat(data.MAE)).toLocaleString()
            );

            $("#forecast_rmse").html(
                Math.round(parseFloat(data.RMSE)).toLocaleString()
            );

            $("#forecast_mape").html(
                parseFloat(data.MAPE).toFixed(2) + "%"
            );

            Swal.close();

        },

        complete: function () {

            

        },

        error: function () {

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to retrieve forecasting data.'
            });

        }

    });

}