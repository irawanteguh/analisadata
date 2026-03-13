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

            let totalResume       = 0;
            let resumekurang48jam = 0;
            let resumelebih48jam  = 0;

            const result = data.responResult || [];

            let tableBulanan = {
                "01":{}, "02":{}, "03":{}, "04":{}, "05":{}, "06":{},
                "07":{}, "08":{}, "09":{}, "10":{}, "11":{}, "12":{}
            };

            if(data.responCode==="00"){    

                const chartDataBulanan = aggregateBulanResume(result,"TGL_KELUAR");
                const chartDataHarian  = aggregateHarianResumeLAST30(result,"TGL_KELUAR");
                const chartDataGlobal  = aggregateResumeGlobal(result);

                renderchartbar(
                    "grafikresumemedis",
                    chartDataBulanan,
                    [
                        { name: "Resume > 48 Jam", field: "lebih48" },
                        { name: "Resume = 48 Jam", field: "kurang48" }
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
                        { name: "Resume <= 48 Jam", field: "kurang48" }
                    ],
                    "Tanggal Pulang Rawat Inap",
                    "Persentase",
                    true
                );

                renderchartpie("grafikresumemedisglobal",chartDataGlobal);

                // LOOP DATA
                for(let i in result){

                    let item = result[i];

                    if(item.TRANSCORESUME !== null && item.TRANSCORESUME !== ""){
                        totalResume++;
                    }else{
                        if(parseInt(item.DURASI) > 2){
                            resumelebih48jam++;
                        }else{
                            resumekurang48jam++;
                        }
                    }

                    // =========================
                    // GROUPING PER TANGGAL
                    // =========================

                    if(!item.TGLKELUAR) continue;

                    let splitTgl;

                    if(item.TGLKELUAR.includes(".")){
                        splitTgl = item.TGLKELUAR.split(".");
                    }else{
                        splitTgl = item.TGLKELUAR.split("-");
                    }

                    let bulan   = splitTgl[1];
                    let tanggal = item.TGLKELUAR;

                    if(!tableBulanan[bulan][tanggal]){
                        tableBulanan[bulan][tanggal] = {
                            total:0,
                            selesai:0,
                            belum:0
                        };
                    }

                    tableBulanan[bulan][tanggal].total++;

                    if(item.TRANSCORESUME != null){
                        tableBulanan[bulan][tanggal].selesai++;
                    }else{
                        tableBulanan[bulan][tanggal].belum++;
                    }

                }

                result.forEach(item => {

                    if(!item.TGLKELUAR) return;

                    let splitTgl = item.TGLKELUAR.includes(".")
                        ? item.TGLKELUAR.split(".")
                        : item.TGLKELUAR.split("-");

                    let bulan   = splitTgl[1];
                    let tanggal = item.TGLKELUAR;

                    if(!tableBulanan[bulan][tanggal]){
                        tableBulanan[bulan][tanggal] = {
                            total:0,
                            selesai:0,
                            belum:0
                        };
                    }

                    tableBulanan[bulan][tanggal].total++;

                    if(item.TRANSCORESUME != null){
                        tableBulanan[bulan][tanggal].selesai++;
                        totalResume++;
                    }else{
                        tableBulanan[bulan][tanggal].belum++;

                        if(parseInt(item.DURASI) > 2){
                            resumelebih48jam++;
                        }else{
                            resumekurang48jam++;
                        }
                    }

                });

                // =========================
                // RENDER TABLE BULANAN
                // =========================

                for(let bulan in tableBulanan){

                    let html = "";
                    let no   = 1;

                    let tanggalSorted = Object.keys(tableBulanan[bulan]).sort((a,b)=>{

                        let da = a.includes(".") ? a.split(".").reverse().join("-") : a;
                        let db = b.includes(".") ? b.split(".").reverse().join("-") : b;

                        return new Date(da) - new Date(db);

                    });

                    tanggalSorted.forEach(tanggal => {

                        let row = tableBulanan[bulan][tanggal];

                        let persen = 0;
                        if(row.total > 0){
                            persen = (row.selesai / row.total) * 100;
                        }

                        html += "<tr>";
                        html += "<td class='text-center'>"+no+"</td>";
                        html += "<td class='text-center'>"+tanggal+"</td>";
                        html += "<td class='text-center'>"+todesimal(row.belum)+"</td>";
                        html += "<td class='text-center'>"+todesimal(row.selesai)+"</td>";
                        html += "<td class='text-center'>"+todesimal(row.total)+"</td>";
                        html += "<td class='text-end pe-4'>"+persen.toFixed(2)+" %</td>";
                        html += "</tr>";

                        no++;
                    });

                    $("#resultdatabln"+bulan).html(html);
                }
            }

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