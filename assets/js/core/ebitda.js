datarencanapendapatan();

function datarencanapendapatan(){
    $.ajax({
        url     : url +"index.php/ebitda/rencanapendapatan/datarencanapendapatan",
        type    : "POST",
        dataType: "JSON",
        beforeSend : function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatarencanapendapatan").html("");
            $(".totalrencanapendapatan").html("Rp. 0");
        },
        success:function(data){
            var tableresult = "";
            var grandTotal  = 0; // <-- total footer

            if(data.responCode==="00"){
                var result = data.responResult;
                
                for(var i in result){

                    var total = parseFloat(result[i].TOTAL) || 0;
                    grandTotal += total;

                    tableresult += "<tr>";
                    tableresult += "<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult += "<td>"+(result[i].NAMAPELAYANAN || "")+"</td>";
                    tableresult += "<td class='text-end'>"+(result[i].QTY || "")+"</td>";
                    tableresult += "<td>Per Pemeriksaan</td>";
                    tableresult += "<td class='text-end'>"+(todesimal(result[i].HARGA_SATUAN) || "")+"</td>";
                    tableresult += "<td class='text-end'>"+todesimal(total)+"</td>";
                    tableresult += "<td class='text-end'><div>"+(result[i].DIBUATOLEH || "")+"</div><div>"+(result[i].CREATEDDATE || "")+"</div></td>";
                    tableresult += "<td></td>";
                    tableresult += "</tr>";
                }
            }

            $("#resultdatarencanapendapatan").html(tableresult);
            $(".totalrencanapendapatan").html("Rp. " + todesimal(grandTotal));
            notification(grandTotal);
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

function notification(grandTotal) {
    let targetHarian = window.targetHarian || 0;

    grandTotal   = parseFloat(grandTotal) || 0;
    targetHarian = parseFloat(targetHarian) || 0;

    if (grandTotal < targetHarian) {

        let selisih = targetHarian - grandTotal;

        $("#noticeTargetKurang").removeClass("d-none");
        $("#noticeTargetKurang .fs-6").html(`
            Rencana target pendapatan harian masih kurang 
            <span class="fw-bolder text-danger">
                Rp. ${todesimal(selisih)}
            </span>
            dari target pendapatan harian. <a href="../ebitda/rencanapendapatan" class="fw-bolder me-1">Tambahkan Detail Rencana Pendapatan</a>
        `);

    } else {
        $("#noticeTargetKurang").addClass("d-none");
    }
}