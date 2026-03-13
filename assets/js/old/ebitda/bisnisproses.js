databisnisproses();

function databisnisproses(){
    $.ajax({
        url     : url +"index.php/ebitda/bisnisproses/databisnisproses",
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

            $("#resultdatabisnisproses").html("");
        },
        success:function(data){
            toastr.clear();
            var result       = "";
            var tableresult  = "";

            if(data.responCode==="00"){
                result = data.responResult;
                
                for(var i in result){
                    getvariabel = " data-prosesid='"+result[i].PROSES_ID+"'";

                    btnaction = "<a class='dropdown-item btn btn-sm' data-bs-toggle='modal' data-bs-target='#modal_ebitda_editbisnisproses' "+getvariabel+"><i class='bi bi-pencil-square text-primary'></i>Edit</a>";
                    btnaction += "<a class='dropdown-item btn btn-sm' data-bs-toggle='modal' data-bs-target='#modal_ebitda_editbisnisproses' "+getvariabel+"><i class='bi bi-x-circle text-info'></i>Non Active</a>";
                    btnaction += "<a class='dropdown-item btn btn-sm' data-bs-toggle='modal' data-bs-target='#modal_ebitda_editbisnisproses' "+getvariabel+"><i class='bi bi-trash text-danger'></i>Deleted</a>";

                    tableresult +="<tr>";
                    tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult +="<td>"+(result[i].LOKASI || "")+"</td>";
                    tableresult +="<td>"+(result[i].OWNER || "")+"</td>";
                    tableresult +="<td>"+(result[i].PROSES || "")+"</td>";
                    tableresult +="<td><div>"+(result[i].DIBUATOLEH || "")+"</div><div>"+(result[i].CREATEDDATE || "")+"</div></td>";

                    tableresult += "<td class='text-end'>";
                        tableresult += "<div class='btn-group' role='group'>";
                            tableresult += "<button id='btnGroupDrop1' type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown' aria-expanded='false'>Action</button>";
                            tableresult += "<div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>";
                                tableresult += btnaction;
                            tableresult += "</div>";
                        tableresult += "</div>";
                    tableresult += "</td>";
                    tableresult +="</tr>";                    
                }
            }
            $("#resultdatabisnisproses").html(tableresult);
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