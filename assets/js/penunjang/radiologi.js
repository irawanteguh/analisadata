datatransaksi();

$('#selectperiode').on('change', function () {
    datatransaksi();
});

$(document).on("keyup", "#fieldsearch", function () {
    let keyword = $(this).val().toLowerCase();
    $("#resultaggregatedataradiologi tr").each(function () {
        let row = $(this);
        let rowText = row.text().toLowerCase();
        if(rowText.indexOf(keyword) > -1){
            row.show();
        }else{
            row.hide();
        }
    });
});


function datatransaksi(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/penunjang/radiologi/datatransaksi",
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

            $("#resultaggregatedataradiologi").html("");
        },
        success:function(data){
            var   tableresult      = "";
            const result           = data.responResult || [];

            if(data.responCode === "00"){
                for (var i in result) {
                    let total =
                        parseFloat(result[i].JAN || 0) +
                        parseFloat(result[i].FEB || 0) +
                        parseFloat(result[i].MAR || 0) +
                        parseFloat(result[i].APR || 0) +
                        parseFloat(result[i].MEI || 0) +
                        parseFloat(result[i].JUN || 0) +
                        parseFloat(result[i].JUL || 0) +
                        parseFloat(result[i].AGU || 0) +
                        parseFloat(result[i].SEP || 0) +
                        parseFloat(result[i].OKT || 0) +
                        parseFloat(result[i].NOV || 0) +
                        parseFloat(result[i].DES || 0);

                    tableresult += "<tr>";
                    tableresult += "<td class='ps-4'>" + (parseInt(i) + 1) + "</td>";
                    tableresult += "<td>" + result[i].NAMA_LAYAN1 + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].JAN) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].FEB) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].MAR) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].APR) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].MEI) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].JUN) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].JUL) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].AGU) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].SEP) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].OKT) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].NOV) + "</td>";
                    tableresult += "<td class='text-end'>" + todesimal(result[i].DES) + "</td>";
                    tableresult += "<td class='fw-bold text-end pe-4'>" + todesimal(total) + "</td>";
                    tableresult += "</tr>";
                }
            }


            $("#resultaggregatedataradiologi").html(tableresult);
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