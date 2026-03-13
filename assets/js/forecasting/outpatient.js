forecastingoutpatient();

$('#selectperiode').on('change', function () {
    forecastingoutpatient();
});

function forecastingoutpatient() {

    let selectperiode = $("select[name='selectperiode']").val();

    $.ajax({
        url      : url + "index.php/forecasting/outpatient/forecastingoutpatient",
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

            $("#grafikforecastingoutatient").html("");

        },

        success: function (response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon: 'warning',
                    title: 'Forecast Data Not Found',
                    text: 'Forecasting data is not available. Do you want to run simulation forecasting now?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Run Simulation',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33'
                }).then((result) => {

                    if (result.isConfirmed) {

                        simulationforecasting();

                    }

                });

                return;
            }

            const result = response.responResult || [];

            if (result.length === 0) return;

            const data = result[0];

            const namaBulan = [
                "Jan","Feb","Mar","Apr","Mei","Jun",
                "Jul","Agu","Sep","Okt","Nov","Des"
            ];

            const chartData = [];

            for (let i = 1; i <= 12; i++) {

                const idx = String(i).padStart(2, "0");

                chartData.push({
                    periode  : namaBulan[i-1],
                    real     : parseInt(data["REAL_" + idx]) || 0,
                    prediksi : parseInt(data["FORECAST_" + idx]) || 0
                });

            }


            // ======================
            // RENDER CHART
            // ======================

            renderchartarea(
                "grafikforecastingoutatient",
                chartData,
                "Periode Pelayanan",
                "Jumlah Kunjungan",
                ["Real","Forecast"],
                ["real","prediksi"]
            );


            // ======================
            // VALIDATION METRICS
            // ======================

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

function simulationforecasting(){

    let selectperiode = $("select[name='selectperiode']").val();

    $.ajax({
        url      : url + "index.php/forecasting/outpatient/simulationforecasting",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

        beforeSend: function(){

            Swal.fire({
                title: "AI Forecasting",
                html: "AI Engine sedang melakukan simulasi forecasting...",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

        },

        success: function(response){

            if(response.responCode !== "00"){

                Swal.fire({
                    icon : "warning",
                    title: "Warning",
                    text : response.responDesc
                });

                return;
            }


            Swal.fire({
                icon : "success",
                title: "Simulation Complete",
                text : "Forecasting berhasil dijalankan."
            });

            forecastingoutpatient();

        },

        complete:function(){

            Swal.close();

        },

        error:function(){

            Swal.fire({
                icon : "error",
                title: "Connection Error",
                text : "Tidak dapat terhubung ke API forecasting"
            });

        }

    });

}