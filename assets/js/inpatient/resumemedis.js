let globaldatapendingresume = [];

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

    rawresumemedis(startDate, endDate);
});

resumemedis();

$('#selectperiode').on('change', function () {
    resumemedis();
});

$("#btndownloaddatapendingresume_table").on("click", function () {
    exportToExcel(
        globaldatapendingresume,
        "Resume Medis",
        "Monitoring_Penyelesaian_Resume_Medis.xlsx",
        {
            formatter: (item, index) => {
                let durasi    = parseInt(item.DURASI) || 0;
                let adaResume = item.PENDINGRESUMELEBIH48;

                let status = "";

                if (adaResume === "N") {

                    if (durasi > 2) {
                        status = "Resume Sudah Dibuat > 48 Jam";
                    } else {
                        status = "Resume Sudah Dibuat <= 48 Jam";
                    }

                } else {

                    if (adaResume === ">48") {
                        status = "Resume Belum Dibuat > 48 Jam";
                    } else {
                        status = "Resume Belum Dibuat <= 48 Jam";
                    }

                }

                return {
                    "No"            : index + 1,
                    "No. RM"        : item.MRPAS || "",
                    "Nama Pasien"   : item.NAMAPASIEN || "",
                    "Sex"           : item.SEXID || "",
                    "Ruangan"       : item.RUANGRWT_ID || "",
                    "Kelas"         : item.KELAS_ID || "",
                    "Nama Dokter"   : item.DPJP || "",
                    "Tgl Masuk"     : item.TGLMASUK || "",
                    "Tgl Keluar"    : item.TGLKELUAR || "",
                    "Provider"      : item.PROVIDER || "",
                    "Cara Pulang"   : item.CARAPULANG || "",
                    "Status"        : status,
                    "Tanggal Resume": item.CREATEDDATERESUME || ""
                };
            }
        }
    );
});

function rawresumemedis(startDate, endDate){
    $.ajax({
        url       : url +"index.php/inpatient/resumemedis/rawresumemedis",
        data      : {startDate:startDate,endDate:endDate},
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

            $("#resultdatapendingresume").empty();
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

            const result            = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatapendingresume = result;
            let   totalResume       = 0;
            let   resumekurang48jam = 0;
            let   resumelebih48jam  = 0;
            let   noDokter          = 1;
            let   groupDokter       = {};

            var tableresult = "";            
            for (var i in result) {
                let durasi        = parseInt(result[i].DURASI) || 0;
                let adaResume     = result[i].PENDINGRESUMELEBIH48;

                if(adaResume === "N"){
                    totalResume++;
                }else{
                    if(adaResume === ">48"){
                        resumelebih48jam++;
                    }else{
                        resumekurang48jam++;
                    }
                }
                
                let btnaction = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].MRPAS || "")+"</td>";
                tableresult += "<td>"+(result[i].NAMAPASIEN || "")+"</td>";
                tableresult += "<td>"+(result[i].SEXID || "")+"</td>";
                tableresult += "<td>"+(result[i].RUANGRWT_ID || "")+"</td>";
                tableresult += "<td>"+(result[i].KELAS_ID || "")+"</td>";
                tableresult += "<td>"+(result[i].DPJP || "")+"</td>";
                tableresult += "<td>"+(result[i].TGLMASUK || "")+"</td>";
                tableresult += "<td>"+(result[i].TGLKELUAR || "")+"</td>";
                tableresult += "<td>"+(result[i].PROVIDER || "")+"</td>";
                tableresult += "<td>"+(result[i].CARAPULANG || "")+"</td>";

                if(adaResume === "N"){
                    if(durasi > 2){
                        tableresult += "<td><span class='badge badge-light-info'>Resume Sudah Dibuat > 48 Jam</span></td>";
                    }else{
                        tableresult += "<td><span class='badge badge-light-success'>Resume Sudah Dibuat <= 48 Jam</span></td>";
                    }
                }else{
                    if(adaResume === ">48"){
                        tableresult += "<td><span class='badge badge-light-danger'>Resume Belum Dibuat > 48 Jam</span></td>";
                    }else{
                        tableresult += "<td><span class='badge badge-light-warning'>Resume Belum Dibuat <= 48 Jam</span></td>";         
                    }
                }

                tableresult += "<td>"+(result[i].CREATEDDATERESUME || "")+"</td>";

                tableresult += "<td class='text-end'>";
                tableresult += "<div class='btn-group'>";
                tableresult += "<button type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown'>Actions</button>";
                tableresult += "<div class='dropdown-menu'>";
                tableresult += btnaction;
                tableresult += "</div></div></td>";
                tableresult += "</tr>";
            }

            $("#resultdatapendingresume").html(tableresult);
            const table = initDataTable("#datapendingresume_table","#searchtable",100);
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

            const result            = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatapendingresume = result;
            let   totalResume       = 0;
            let   resumekurang48jam = 0;
            let   resumelebih48jam  = 0;
            let   noDokter          = 1;
            let   groupDokter       = {};

            var htmlDokter  = "";
            
            for (var i in result) {
                let durasi        = parseInt(result[i].DURASI) || 0;
                let adaResume     = result[i].PENDINGRESUMELEBIH48;

                if(adaResume === "N"){
                    totalResume++;
                }else{
                    if(adaResume === ">48"){
                        resumelebih48jam++;
                    }else{
                        resumekurang48jam++;
                    }

                    let dokter = result[i].DPJP || "TIDAK DIKETAHUI";

                    if(!groupDokter[dokter]){
                        groupDokter[dokter] = 0;
                    }

                    groupDokter[dokter]++;
                }
            }

            let dokterSorted = Object.keys(groupDokter).sort((a,b)=>{
                return groupDokter[b] - groupDokter[a];
            });

            dokterSorted.forEach(namaDokter => {

                let jumlah = groupDokter[namaDokter];

                let btnaction = `
                                    <a class="dropdown-item btn btn-sm" href="javascript:void(0)" 
                                    onclick="filterDokter('${namaDokter.replace(/'/g, "\\'")}')">
                                        <i class="bi bi-search text-primary pe-2"></i> Detail
                                    </a>
                                `;

                htmlDokter += "<tr>";
                htmlDokter += "<td class='ps-4'>"+noDokter+"</td>";
                htmlDokter += "<td>"+namaDokter+"</td>";
                htmlDokter += "<td class='text-end pe-4'>"+todesimal(jumlah)+"</td>";
                htmlDokter += "</tr>";

                noDokter++;
            });

            $("#resultdatapendingresumedokter").html(htmlDokter);
            const table = initDataTable("#datapendingresumedokter_table","#searchtable",10);

            $("#totalpasienpulang").html(todesimal(result.length) + " Px");
            $("#totalresume").html(todesimal(totalResume) + " Px");
            $("#pendingresumekurang").html(todesimal(resumekurang48jam) + " Px");
            $("#pendingresumelebih").html(todesimal(resumelebih48jam) + " Px");

            const namaBulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            const chartMap = {};

            for (let i = 1; i <= 12; i++) {
                const bulan = String(i).padStart(2, "0");
                chartMap[bulan] = {
                    periode: namaBulan[i - 1],
                    value_1: 0,
                    value_2: 0,
                    value_3: 0
                };
            }

            result.forEach(item => {
                const tanggal = item.TGLKELUAR;
                if (!tanggal) return;
                const [, bulan] = String(tanggal).split(".");
                if (!chartMap[bulan]) return;

                chartMap[bulan].value_1 += Number(item.STATUSKURANG || 0);
                chartMap[bulan].value_2 += Number(item.STATUSLEBIH || 0);
                chartMap[bulan].value_3 += Number(item.STATUSBELUMBUAT || 0);

            });

            const chartDataBulanan = Object.keys(chartMap).sort((a, b) => Number(a) - Number(b)).map(bulan => chartMap[bulan]);

            renderChartStackedBar(
                "grafikresumemedis",
                chartDataBulanan.map(item => item.periode),
                [
                    {
                        name: "Resume <= 48 Jam",
                        data: chartDataBulanan.map(item => item.value_1)
                    },
                    {
                        name: "Resume > 48 Jam",
                        data: chartDataBulanan.map(item => item.value_2)
                    },
                    {
                        name: "Belum Buat Resume",
                        data: chartDataBulanan.map(item => item.value_3)
                    }
                ],
                "Periode Bulan Pulang Rawat Inap",
                "Jumlah Pasien"
            );

            result
                .filter(item => item.TGLKELUAR)
                .forEach(item => {
                    const tanggal = item.TGLKELUAR;

                    if (!chartMap[tanggal]) {
                        chartMap[tanggal] = {
                            periode: tanggal,
                            value_1: 0,
                            value_2: 0
                        };
                    }

                    chartMap[tanggal].value_1 += Number(item.STATUSKURANG || 0);
                    chartMap[tanggal].value_2 += Number(item.STATUSLEBIH || 0);
                });

            const chartDataHarian = Object.values(chartMap)
                .sort((a, b) => {
                    const dateA = a.periode.split(".").reverse().join("-");
                    const dateB = b.periode.split(".").reverse().join("-");
                    return new Date(dateA) - new Date(dateB);
                })
                .slice(-60);

            renderChartStackedBar(
                "grafikresumemedisharian",
                chartDataHarian.map(item => item.periode),
                [
                    {
                        name: "Resume <= 48 Jam",
                        data: chartDataHarian.map(item => item.value_1)
                    },
                    {
                        name: "Resume > 48 Jam",
                        data: chartDataHarian.map(item => item.value_2)
                    }
                ],
                "60 Hari Terakhir",
                "Jumlah Pasien"
            );

            const dataGlobalResume = [
                { label: "Resume <= 48 Jam", value: 0 },
                { label: "Resume > 48 Jam", value: 0 },
                { label: "Belum Buat Resume", value: 0 }
            ];

            result.forEach(item => {
                dataGlobalResume[0].value += Number(item.STATUSKURANG || 0);
                dataGlobalResume[1].value += Number(item.STATUSLEBIH || 0);
                dataGlobalResume[2].value += Number(item.STATUSBELUMBUAT || 0);
            });

            renderchartpie("grafikresumemedisglobal", dataGlobalResume);
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
