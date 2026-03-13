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

            $("#resultdatapasienreadmisi").html("");
            $("#grafikreadmisiaggregate").html("");
        },
        success:function(data){
            toastr.clear();
            var result      = "";
            var tableresult = "";

            if(data.responCode==="00"){
                result = data.responResult;
                for(var i in result){
                    btnaction  = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";

                    tableresult +="<tr>";
                    tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult +="<td>"+(result[i].MRPASIEN || "")+"</td>";
                    tableresult +="<td>"+(result[i].NAMAPASIEN || "")+"</td>";
                    tableresult +="<td class='text-center'>"+(result[i].TGLMASUK || "")+"</td>";
                    tableresult +="<td class='text-center'>"+(result[i].TGLKELUAR || "")+"</td>";
                    tableresult +="<td>"+(result[i].NAMADOKTER || "")+"</td>";
                    tableresult +="<td class='text-center'>"+(result[i].TGLMASUKLAST || "")+"</td>";
                    tableresult +="<td class='text-center'>"+(result[i].TGLKELUARLAST || "")+"</td>";
                    tableresult +="<td>"+(result[i].NAMADOKTERLAST || "")+"</td>";
                    tableresult +="<td>"+(result[i].JARAKWAKTU || "")+" Hari</td>";
                    tableresult += "<td class='text-end'>";
                        tableresult += "<div class='btn-group' role='group'>";
                            tableresult += "<button id='btnGroupDrop1' type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown' aria-expanded='false'>Actions</button>";
                            tableresult += "<div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>";
                                tableresult += btnaction;
                            tableresult += "</div>";
                        tableresult += "</div>";
                    tableresult += "</td>";
                    tableresult +="</tr>";                    
                }
            }

            $("#resultdatapasienreadmisi").html(tableresult);
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