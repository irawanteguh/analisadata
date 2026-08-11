let globaldatapasienreadmisi = [];

datapasienreadmisi();

$('#selectperiode').on('change', function () {
    datapasienreadmisi();
});

$("#btnDownloadExcelReAdmisi").on("click", function () {

    const data = globaldatapasienreadmisi || [];

    const bulan = [
        {kode:"01", nama:"Januari"},
        {kode:"02", nama:"Februari"},
        {kode:"03", nama:"Maret"},
        {kode:"04", nama:"April"},
        {kode:"05", nama:"Mei"},
        {kode:"06", nama:"Juni"},
        {kode:"07", nama:"Juli"},
        {kode:"08", nama:"Agustus"},
        {kode:"09", nama:"September"},
        {kode:"10", nama:"Oktober"},
        {kode:"11", nama:"November"},
        {kode:"12", nama:"Desember"}
    ];

    const rekapBulanan = {};

    bulan.forEach(item => {
        rekapBulanan[item.kode] = 0;
    });

    data.forEach(item => {

        if (item.PERIODE) {

            const kodeBulan = item.PERIODE.substring(5, 7);

            if (rekapBulanan[kodeBulan] !== undefined) {
                rekapBulanan[kodeBulan]++;
            }
        }

    });

    exportToExcel(
        null,
        null,
        "Re_Admisi_InPatient.xlsx",
        {
            multiSheet: [

                // ==========================================
                // SHEET 1 : DETAIL RE ADMISI
                // ==========================================

                {
                    name: "Detail Re Admisi",
                    data: data,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "MR Pasien": item.MRPASIEN ?? "",
                            "Nama Pasien": item.NAMAPASIEN ?? "",
                            "Tanggal Masuk": item.TGLMASUK ?? "",
                            "Tanggal Keluar": item.TGLKELUAR ?? "",
                            "Dokter": item.NAMADOKTER ?? "",
                            "Tanggal Masuk Sebelumnya": item.TGLMASUKLAST ?? "",
                            "Tanggal Keluar Sebelumnya": item.TGLKELUARLAST ?? "",
                            "Dokter Sebelumnya": item.NAMADOKTERLAST ?? "",
                            "Jarak Waktu": Number(item.JARAKWAKTU ?? 0)
                        };

                    }
                },

                // ==========================================
                // SHEET 2 : TOTAL
                // ==========================================

                {
                    name: "Total",
                    data: bulan,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Periode": item.nama,
                            "Total Re Admisi": rekapBulanan[item.kode] || 0
                        };

                    }
                }

            ]
        }
    );

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
            globaldatapasienreadmisi = result;
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

            const table = initDataTable("#datapasienreadmisi_table","#searchtable",50);

            const chartDataBulanan = bulanLengkap.map((b,index)=>({
                periode: namaBulan[index],
                totalValue: bulanan[b] || 0
            }));
            
            renderchartarea("grafikreadmisiaggregate",chartDataBulanan,"Periode Pelayanan","Jumlah Re Admisi",["Transaksi"],["totalValue"],null,"","totalValue","Rata-rata Kunjungan",null);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon             : "error",
                title            : "Request Failed",
                text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
};