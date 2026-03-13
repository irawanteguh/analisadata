datapasienreadmisi();

$('#selectperiode').on('change', function () {
    datapasienreadmisi();
});

$(document).on("keyup", "#fieldsearch", function () {
    filterTableByKeywords("#fieldsearch", "#resultdatapasienreadmisi");
});

function datapasienreadmisi(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/inpatient/readmisi/datapasienreadmisi",
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
            var   tableresult  = "";
            const result       = data.responResult || [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            let bulanan = {};

            

            if(data.responCode==="00"){
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
                    
                    if(result[i].PERIODE){
                        let bulan = result[i].PERIODE.substring(5,7);
                        if(!bulanan[bulan]){
                            bulanan[bulan] = 0;
                        }
                        bulanan[bulan]++;
                    }
                }
            }

            $("#resultdatapasienreadmisi").html(tableresult);

            const chartDataBulanan = bulanLengkap.map((b,index)=>({
                periode: namaBulan[index],
                totalValue: bulanan[b] || 0
            }));

            // ===== hitung rata-rata =====
            const bulanAktif = chartDataBulanan.filter(x => x.totalValue > 0);
            const totalAll   = bulanAktif.reduce((sum,x)=>sum + x.totalValue,0);
            const avgValue   = bulanAktif.length > 0 ? totalAll / bulanAktif.length : 0;

            // ===== render chart =====
            renderchartarea(
                "grafikreadmisiaggregate",
                chartDataBulanan,
                "Periode Pelayanan",
                "Jumlah Re Admisi",
                "Re Admisi Pasien",
                "totalValue",
                avgValue,
                "Rata-rata: " + Math.round(avgValue)
            );

            // renderchartarea("grafikreadmisiaggregate",chartDataBulanan,"Periode Pelayanan","Jumlah Re Admisi");
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