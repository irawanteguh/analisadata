let startDate, endDate;

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate   : "today",
    onChange  : function (selectedDates) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate   = selectedDates[1] ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    waktutungguranap(startDate, endDate);
});

waktutungguranap(startDate, endDate);

$(document).on("keyup", "#fieldsearch", function () {
    let keyword = $(this).val().toLowerCase();
    $("#resultrawdatawaktutungguranap tr").each(function () {
        let row = $(this);
        let rowText = row.text().toLowerCase();
        if(rowText.indexOf(keyword) > -1){
            row.show();
        }else{
            row.hide();
        }
    });
});

function waktutungguranap(startDate,endDate){
    $.ajax({
        url       : url +"index.php/inpatient/waktutunggu/waktutungguranap",
        data      : {startdate:startDate,endate:endDate},
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

            $("#resultrawdatawaktutungguranap").html("");
        },
        success:function(data){
            var   tableresult      = "";
            const result           = data.responResult || [];

            if(data.responCode==="00"){
                for(var i in result){
                    let   classtransit    = "";
                    let   classranap      = "";
                    let   ruangTransit    = (result[i].RUANGTRANSIT_MASUK || "");
                    let   ruangRanap      = (result[i].RUANG_MASUK || "");
                    const timerId         = "timer_" + i;
                    const timerIdSpri     = "spri_" + i;
                    const timerIdTransfer = "transfer_" + i;
                    
                    if(ruangTransit !== ""){
                        classtransit ="badge badge-light-info";
                    }

                    if(ruangRanap !== ""){
                        classranap ="badge badge-light-info";
                    }else{
                        classranap ="badge badge-light";
                    }

                    tableresult +="<tr>";
                    tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult +="<td>"+(result[i].MRPASIEN || "")+"</td>";
                    tableresult +="<td>"+(result[i].NAMAPASIEN || "")+"</td>";
                    tableresult +="<td>"+(result[i].REGISIGDBY || "")+"</td>";
                    tableresult +="<td class='text-end'>"+(result[i].IGD_MASUK_JAM || "")+"</td>";

                    tableresult +="<td>"+(result[i].SPRI_BY || "")+"</td>";
                    tableresult +="<td class='text-end'>"+(result[i].SPRITGLJAM || "")+"</td>";
                    tableresult +="<td><span class='badge fw-bold' id='" + timerIdSpri + "'>Loading...</span></td>";  

                    tableresult +="<td><div class='"+classtransit+"'>"+(result[i].RUANGTRANSIT_MASUK || "")+" "+(result[i].BEDTRANSIT_MASUK || "")+"</div></td>";
                    tableresult +="<td>"+(result[i].REGISTRANSITBY || "")+"</td>";
                    tableresult +="<td class='text-end'>"+(result[i].RUANGTRANSIT_MASUK_JAM || "")+"</td>";
                    

                    tableresult +="<td><div class='badge badge-light-info'>"+(result[i].RUANG_MASUK || "")+" "+(result[i].BEDRUANG_MASUK || "")+"</div></td>";
                    tableresult +="<td>"+(result[i].REGISRANAPTBY || "")+"</td>";
                    tableresult +="<td class='text-end'>"+(result[i].RUANG_MASUK_JAM || "")+"</td>";

                    tableresult +="<td>"+(result[i].TRANSFER_BY || "")+"</td>";
                    tableresult +="<td class='text-end'>"+(result[i].CREATEDDATETRANSFER || "")+"</td>";
                    tableresult +="<td><span class='badge fw-bold' id='" + timerIdTransfer + "'>Loading...</span></td>";

                    tableresult +="<td>"+(result[i].TRANSFERRUANG_BY || "")+"</td>";
                    tableresult +="<td class='text-end'>"+(result[i].TRANSFERRUANG_JAM || "")+"</td>";
                    tableresult +="<td><span class='badge fw-bold' id='" + timerId + "'>Loading...</span></td>";                    
                    
                    tableresult +="</tr>";                    
                }
            }

            $("#resultrawdatawaktutungguranap").html(tableresult);

            for(var i in result){
                const timerId         = "timer_" + i;
                const timerIdSpri     = "spri_" + i;
                const timerIdTransfer = "transfer_" + i;

                let   transidtransferruang = (result[i].TRANSIDTRANSFERRUANG || "");

                if(transidtransferruang !== ""){
                    setDurasiSLA(result[i].RUANG_MASUK_JAM, result[i].TRANSFERRUANG_JAM, timerId, 1);
                    setDurasiSLA(result[i].RUANG_MASUK_JAM, result[i].CREATEDDATETRANSFER, timerIdTransfer, 1);
                }

                if(result[i].POLIASAL==="UGD01"){
                    setDurasiSLA(result[i].IGD_MASUK_JAM, result[i].SPRITGLJAM, timerIdSpri, 6);
                }
                
            }
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