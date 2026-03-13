daftarlayanan();

$('#modal_addplan').on('hidden.bs.modal', function () {
    datarencanapendapatan();
});


function daftarlayanan(){
    $.ajax({
        url     : url +"index.php/ebitda/rencanapendapatan/daftarlayanan",
        type    : "POST",
        dataType: "JSON",
        beforeSend : function () {
            // Swal.fire({
            //     title            : 'Processing',
            //     html             : 'Please wait while the system displays the requested data.',
            //     allowOutsideClick: false,
            //     allowEscapeKey   : false,
            //     showConfirmButton: false,
            //     didOpen          : () => Swal.showLoading()
            // });

            $("#resultdatadaftarlayanan").html("");
        },
        success:function(data){
            var tableresult = "";

            if(data.responCode==="00"){
                var result = data.responResult;
                
                for(var i in result){
                    tableresult += "<tr>";
                    tableresult += "<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult += "<td><div>"+(result[i].NAMAPELAYANAN || "")+"</div><div class='badge badge-light-info me-4'>Kelas "+(result[i].KELAS_ID || "")+"</div><div class='badge badge-light-primary'>Kelas "+(result[i].KATEGORI || "")+"</div></td>";
                    tableresult += "<td class='text-end'>"+(todesimal(result[i].HARGA) || "")+"</td>";
                    if(result[i].QTY!=null){
                        tableresult += `<td class='text-end'><input class='form-control form-control-sm text-end' id='qty_${result[i].LAYAN_ID}' data-kelas='${result[i].KELAS_ID}' data-harga='${result[i].HARGA}' value='${todesimal(result[i].QTY)}' onchange='simpandata(this)'></td>`;
                    }else{
                        tableresult += `<td class='text-end'><input class='form-control form-control-sm text-end' id='qty_${result[i].LAYAN_ID}' data-kelas='${result[i].KELAS_ID}' data-harga='${result[i].HARGA}' onchange='simpandata(this)'></td>`;
                    }
                    
                    tableresult += "</tr>";
                }
            }

            $("#resultdatadaftarlayanan").html(tableresult);
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

function simpandata(input){
    
    const layanid  = input.id.split("_")[1];
    const qty      = parseFloat(input.value);
    const kelas_id = input.dataset.kelas;
    const harga    = input.dataset.harga;

    if (isNaN(qty) || input.value.trim() === "") {
        showAlert(
            "I'm Sorry",
            "Masukkan nilai numerik yang valid!",
            "error",
            "Please Try Again",
            "btn btn-danger"
        );
        input.value = "";
        return;
    }

    $.ajax({
        url       : url+"index.php/ebitda/rencanapendapatan/addplan",
        method    : "POST",
        dataType  : "JSON",
        data      : {
            layanid : layanid,
            qty     : qty,
            kelas_id: kelas_id,
            harga   : harga
        },
        beforeSend: function () {
            // Swal.fire({
            //     title            : 'Processing',
            //     html             : 'Please wait processing add plan',
            //     allowOutsideClick: false,
            //     allowEscapeKey   : false,
            //     showConfirmButton: false,
            //     didOpen          : () => Swal.showLoading()
            // });
        },
        success: function (data) {
            daftarlayanan();
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