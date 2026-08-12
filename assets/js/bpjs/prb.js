let today     = new Date().toLocaleDateString('en-CA');  // format YYYY-MM-DD
let startDate = today;
let endDate   = today;

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate   : "today",
    onChange  : function (selectedDates, dateStr, instance) {
        startDate     = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate       = selectedDates[1]  ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    srbbydate(startDate, endDate);
});

function srbbydate(startDate, endDate){
    $.ajax({
        url        : url+"index.php/bpjs/prb/srbbydate",
        data       : {startDate:startDate,endDate:endDate},
        method     : "POST",
        dataType   : "JSON",
        beforeSend : function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataprb").empty();
        },
        success:function(response){

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

            const result = response.responResult;

            let tableresult = "";
            for (var i in result['response']['prb']['list']) {
                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>"+result['response']['prb']['list'][i]['peserta']['noKartu']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['peserta']['nama']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['peserta']['alamat']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['peserta']['noTelepon']+"</td>";
                tableresult += "<td>";
                    tableresult += "<a href='#' data-nosrb='"+result['response']['prb']['list'][i]['noSRB']+"' data-nosep='"+result['response']['prb']['list'][i]['noSEP']+"' data-bs-toggle='modal' data-bs-target='#modal-detailsrb' onclick='detailsrb(this)'>"+result['response']['prb']['list'][i]['noSRB']+"</a>";
                tableresult += "</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['noSEP']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['tglSRB']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['programPRB']['nama']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['keterangan']+"</td>";
                tableresult += "<td>"+result['response']['prb']['list'][i]['saran']+"</td>";
                tableresult += "<td class='text-end pe-4'>"+result['response']['prb']['list'][i]['DPJP']['nama']+"</td>";
                tableresult += "</tr>";
            }

            $("#resultdataprb").html(tableresult);
            const table = initDataTable("#dataprb_table","#searchtable");            
        },
        complete: function () {
            Swal.close();
		},
        error: function(xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
		}
    });
    return false;
};

function detailsrb(btn){
    var nosrb = $(btn).attr("data-nosrb");
    var nosep = $(btn).attr("data-nosep");
    $.ajax({
        url       : url+"index.php/bpjs/prb/detailsrb",
        data      : {nosrb:nosrb,nosep:nosep},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });
        },
        success:function(data){
            var result        = data.responResult;

            $("input[name='modal-detailsrb-nokartu']").val(result['response']['prb']['peserta']['noKartu']);
            $("input[name='modal-detailsrb-namapasien']").val(result['response']['prb']['peserta']['nama']);
            $("input[name='modal-detailsrb-alamat']").val(result['response']['prb']['peserta']['alamat']);
            $("input[name='modal-detailsrb-notlp']").val(result['response']['prb']['peserta']['noTelepon']);
            $("input[name='modal-detailsrb-nosrb']").val(result['response']['prb']['noSRB']);
            $("input[name='modal-detailsrb-nosep']").val(result['response']['prb']['noSEP']);
            $("input[name='modal-detailsrb-tglsrb']").val(result['response']['prb']['tglSRB']);
            $("input[name='modal-detailsrb-program']").val(result['response']['prb']['programPRB']['nama']);
            $("input[name='modal-detailsrb-dpjp']").val(result['response']['prb']['DPJP']['nama']);
            $("input[name='modal-detailsrb-kodefaskes']").val(result['response']['prb']['peserta']['asalFaskes']['kode']);
            $("input[name='modal-detailsrb-namafaskes']").val(result['response']['prb']['peserta']['asalFaskes']['nama']);

            $("textarea[name='modal-detailsrb-keterangan']").val(result['response']['prb']['keterangan']);
            $("textarea[name='modal-detailsrb-saran']").val(result['response']['prb']['saran']);

            var daftarObat = result['response']['prb']['obat']['obat'];  
            var rows = "";
            if (daftarObat && daftarObat.length > 0) {
                $.each(daftarObat, function(i, item){
                    rows += `
                        <tr>
                            <td class="ps-4">${item.kdObat}</td>
                            <td>${item.nmObat}</td>
                            <td>${item.signa1}</td>
                            <td>${item.signa2}</td>
                            <td class="text-end pe-4">${item.jmlObat}</td>
                        </tr>
                    `;
                });
            } else {
                rows = `<tr><td colspan="6" class="text-center text-muted">Tidak ada data obat</td></tr>`;
            }
            $("#resultdaftarobat").html(rows);
        },
        complete: function () {
            Swal.close();
		},
        error: function(xhr, status, error) {
            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
		}
    });
    return false;
};