resumemedis();

$('#selectperiode').on('change', function () {
    resumemedis();
});

$(document).on("keyup", "#fieldsearch", function () {
    filterTableByKeywords("#fieldsearch", "#resultdatapendingresume");
});

function resumemedis(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/inpatient/resumemedis/resumemedis",
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

            $("#resultdatapendingresume").html("");
            $("#totalpasienpulang").html("Total Pasien Pulang Rawat Inap : 0 Px");
            $('#totalresume').html('Total Resume Yang Telah Di Buat : 0 Px');
            $('#pendingresumekurang').html('Pending Resume Medis <= 48 Jam : 0 Px');
            $('#pendingresumelebih').html('Pending Resume > 48 Jam : 0 Px');
        },
        success:function(data){
            let   totalResume       = 0;
            let   resumekurang48jam = 0;
            let   resumelebih48jam  = 0;
            let   tableresult       = "";
            const result            = data.responResult || [];

            if(data.responCode==="00"){    
                
                const chartDataBulanan = aggregateBulanResume(result,"TGL_KELUAR");
                const chartDataHarian  = aggregateHarianResumeLAST30(result,"TGL_KELUAR");
                const chartDataGlobal  = aggregateResumeGlobal(result);


                renderchartbar(
                    "grafikresumemedis",
                    chartDataBulanan,
                    [
                        { name: "Resume > 48 Jam", field: "lebih48" },
                        { name: "Resume ≤ 48 Jam", field: "kurang48" }
                    ],
                    "Periode Tanggal Pulang Rawat Inap",
                    "Persentase",
                    true
                );

                renderchartbar(
                    "grafikresumemedisharian",
                    chartDataHarian,
                    [
                        { name: "Resume > 48 Jam", field: "lebih48" },
                        { name: "Resume ≤ 48 Jam", field: "kurang48" }
                    ],
                    "Tanggal Pulang Rawat Inap",
                    "Persentase",
                    true
                );
                renderchartpie("grafikresumemedisglobal",chartDataGlobal);

                if(data.responCode==="00"){
                    for(var i in result){
                        if(result[i].TRANSCORESUME !== null && result[i].TRANSCORESUME !== ""){
                            totalResume++;
                        }else{
                            if(parseInt(result[i].DURASI) > 2){
                                resumelebih48jam++;
                            } else {
                                resumekurang48jam++;
                            }
                        }

                        btnaction  = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";

                        tableresult +="<tr>";
                        tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                        tableresult +="<td>"+(result[i].MRPAS || "")+"</td>";
                        tableresult +="<td>"+(result[i].NAMAPASIEN || "")+"</td>";
                        tableresult +="<td>"+(result[i].SEXID || "")+"</td>";
                        tableresult +="<td>"+(result[i].RUANGRWT_ID || "")+"</td>";
                        tableresult +="<td>"+(result[i].KELAS_ID || "")+"</td>";
                        tableresult +="<td>"+(result[i].DPJP || "")+"</td>";
                        tableresult +="<td>"+(result[i].TGLMASUK || "")+"</td>";
                        tableresult +="<td>"+(result[i].TGLKELUAR || "")+"</td>";
                        tableresult +="<td>"+(result[i].PROVIDER || "")+"</td>";
                        tableresult +="<td>"+(result[i].CARAPULANG || "")+"</td>";
                        if(result[i].TRANSCORESUME!=null){
                            tableresult +="<td><span class='badge badge-light-success'>Resume Sudah Dibuat</span></td>";
                        }else{
                            if(parseInt(result[i].DURASI) > 2){
                                tableresult +="<td><span class='badge badge-light-danger'>Resume Belum Dibuat > 48 Jam</span></td>";
                            }else{
                                tableresult +="<td><span class='badge badge-light-warning'>Resume Belum Dibuat <= 48 Jam</span></td>";         
                            }
                        }
                        tableresult +="<td>"+(result[i].CREATEDDATERESUME || "")+"</td>";
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
            }

            // Masukkan hasil ke elemen HTML
            $("#resultdatapendingresume").html(tableresult);
            $("#totalpasienpulang").html("Total Pasien Pulang Rawat Inap : " + todesimal(result.length) + " Px");
            $("#totalresume").html("Total Resume Yang Telah Di Buat : " + todesimal(totalResume) + " Px");
            $("#pendingresumekurang").html("Pending Resume Medis <= 48 Jam : " + todesimal(resumekurang48jam) + " Px");
            $("#pendingresumelebih").html("Pending Resume Medis > 48 Jam : " + todesimal(resumelebih48jam) + " Px");
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