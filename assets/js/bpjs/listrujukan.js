let startDate, endDate;

flatpickr('[name="dateperiode"]', {
    mode: "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate: "today",
    onChange: function (selectedDates, dateStr, instance) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate   = selectedDates[1]  ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    listrujukabydate(startDate, endDate);
});

function listrujukabydate(startDate, endDate){
    $.ajax({
        url        : url+"index.php/bpjs/listrujukan/listrujukabydate",
        data       : {startDate:startDate,endDate:endDate},
        method     : "POST",
        dataType   : "JSON",
        cache      : false,
        processData: true,
        beforeSend : function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data...',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultrujukankeluar").html("");
        },
        success:function(data){
            var tableresult = "";


            if(data.responCode==="00"){
                var result = data.responResult;
                if(result.metaData && result.metaData.code === "200"){
                    
                    for (var i in result.response.list) {
                        tableresult += "<tr>";
                        tableresult += "<td class='ps-4'>" + (parseInt(i) + 1) + "</td>";
                        tableresult += "<td>" + result.response.list[i].noKartu + "</td>";
                        tableresult += "<td>" + result.response.list[i].nama + "</td>";
                        tableresult += "<td>" + result.response.list[i].poliAsal + "</td>";
                        tableresult += "<td>" + result.response.list[i].dokterPerujuk + "</td>";
                        tableresult += "<td>" + result.response.list[i].noSep + "</td>";
                        tableresult += "<td>" + result.response.list[i].noRujukan + "</td>";
                        tableresult += "<td>" + result.response.list[i].icdxcode + "</td>";
                        tableresult += "<td>" + result.response.list[i].icdxdesc + "</td>";
                        tableresult += "<td>" + result.response.list[i].catatan + "</td>";
                        tableresult += "<td class='text-center'>" + result.response.list[i].tglRujukan + "</td>";
                        tableresult += "<td>" + result.response.list[i].namapolitujuan + "</td>";
                        tableresult += "<td class='text-end pe-4'><div>"+result.response.list[i].ppkDirujuk+"</div><div>"+result.response.list[i].namaPpkDirujuk +"</div></td>";
                        tableresult += "</tr>";
                    }

                    Swal.close();
                }else{
                    const message = result.metaData.message;

                    Swal.fire({
                        icon             : 'error',
                        title            : 'Informasi BPJS',
                        html             : `<div class="text-center">ERROR<br>${message}</div>`,
                        timer            : 4000,
                        timerProgressBar : true,
                        showConfirmButton: false,
                        didOpen          : () => {
                            Swal.showLoading();
                        }
                    });
                }

            }

            $("#resultrujukankeluar").html(tableresult);
        },
        complete: function () {
            //
        },
        error: function () {
            Swal.fire({
                icon             : 'error',
                title            : 'Error',
                text             : 'Unable to retrieve visit data.',
                timer            : 4000,
                timerProgressBar : true,
                showConfirmButton: false,
                didOpen          : () => {
                    Swal.showLoading();
                }
            });
        }
    });
    return false;
};