datarjgeriatri();
datarj();

$('#selectperiode').on('change', function () {
    datarjgeriatri();
    datarj();
});

function datarjgeriatri(){
    let selectperiode   = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/diagnosa/topdiagnosa/datarjgeriatri",
        data      : {selectperiode:selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
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
        success: function (data) {
            let rawData = [];
            if (data && Array.isArray(data.responResult)) {
                rawData = data.responResult;
            }

            const chartDataDiagnosa = aggregateketerangan(rawData);
            renderbarhorizontal("grafiktopdiagnosarjgeriatri", chartDataDiagnosa);
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

function datarj(){
    let selectperiode   = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/diagnosa/topdiagnosa/datarj",
        data      : {selectperiode:selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
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
        success: function (data) {
            let rawData = [];
            if (data && Array.isArray(data.responResult)) {
                rawData = data.responResult;
            }

            const chartDataDiagnosa = aggregateketerangan(rawData);
            renderbarhorizontal("grafiktopdiagnosarj", chartDataDiagnosa);
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