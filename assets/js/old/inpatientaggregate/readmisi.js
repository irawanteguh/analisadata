datapasienreadmisi();

$('#selectperiode').on('change', function () {
    datapasienreadmisi();
});


function datapasienreadmisi(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/inpatientraw/readmisi/datapasienreadmisi",
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

            $("#grafikreadmisiaggregate").html("");
        },
        success:function(data){
            let rawData     = [];

            if (data && Array.isArray(data.responResult)) {
                rawData = data.responResult;
            }
                    
            const chartDataBulanan  = aggregatePerBulan(rawData);
            console.log(chartDataBulanan);

            renderchartarea("grafikreadmisiaggregate",chartDataBulanan,"Periode Pelayanan","Jumlah Re Admisi");
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
};