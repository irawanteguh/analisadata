let today              = new Date().toLocaleDateString('en-CA');
let startDate          = today;
let globaldatahelpdesk = [];

loaddata(startDate);

flatpickr('[name="dateperiode"]', {
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate   : "today",
    onChange  : function (selectedDates, dateStr, instance) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();
    loaddata(startDate);
});

$("#btndownloaddatahelpdesk_table").on("click", function () {

    exportToExcel(
        null,
        null,
        "Data_Antrian_Helpdesk.xlsx",
        {
            multiSheet: [
                {
                    name: "Antrian Helpdesk",
                    data: globaldatahelpdesk,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Tanggal": item.TANGGAL ?? "",
                            "Prioritas / Disabilitas": Number(item.KODE_A ?? 0),
                            "BPJS": Number(item.KODE_B ?? 0),
                            "Help Desk": Number(item.KODE_C ?? 0),
                            "Total": 
                                Number(item.KODE_A ?? 0) +
                                Number(item.KODE_B ?? 0) +
                                Number(item.KODE_C ?? 0)
                        };

                    }
                }
            ]
        }
    );

});

function loaddata(startDate){
    datakunjunganrawatjalan(startDate);
    dataantrianhelpdesk();
    dataagamapasienri();
}

function datakunjunganrawatjalan(startDate){
    $.ajax({
        url     : url + "index.php/dashboard/summary/datakunjunganrawatjalan",
        type    : "POST",
        dataType: "JSON",
        data    : {startDate:startDate},

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

        success: function (response) {

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

            const result = Array.isArray(response.responResult) ? response.responResult : [];


            // =====================================================
            // VALIDASI DATA KOSONG
            // =====================================================

            if (result.length === 0) {

                $('#dutymanager_txt').val('Tidak ada data kunjungan.');
                $('#kainsrj_txt').val('Tidak ada data kunjungan.');

                $("#jmlrawatjalansekarang").text("0");
                $("#jmlrawatjalansebelumnya").text("0 Px");

                $("#presentasirawatjalansekarang")
                    .removeClass("badge-light-success badge-light-danger")
                    .addClass("badge-light-secondary")
                    .html(`<i class="bi bi-dash-circle text-muted me-1"></i><span class="text-muted fw-bold">0%</span>`);

                return;
            }


            const tanggal = [...new Set(result.map(x => x.TANGGAL))]
                .sort((a,b)=>{
                    const da = a.split(".").reverse().join("");
                    const db = b.split(".").reverse().join("");
                    return db.localeCompare(da);
                });


            let   persentase  = 0;
            const currentDate = tanggal[0] || "";
            const lastDate    = tanggal[1] || currentDate;

            const totalKunjungan = result.filter(x => x.TANGGAL === currentDate).reduce((total,item)=>total + Number(item.JUMLAH || 0),0);
            const totalLast      = result.filter(x => x.TANGGAL === lastDate).reduce((total,item)=>total + Number(item.JUMLAH || 0),0);


            if(totalLast > 0){
                persentase = ((totalKunjungan - totalLast) / totalLast) * 100;
            }


            const naik       = totalKunjungan >= totalLast;
            const textClass  = naik ? "text-success" : "text-danger";
            const badgeClass = naik ? "badge-light-success" : "badge-light-danger";
            const iconClass  = naik ? "bi-caret-up-fill" : "bi-caret-down-fill";


            $("#jmlrawatjalansekarang").text(totalKunjungan.toLocaleString("id-ID"));
            $("#jmlrawatjalansebelumnya").text(`${totalLast.toLocaleString("id-ID")} Px`);
            $("#presentasirawatjalansekarang").removeClass("badge-light-success badge-light-danger").addClass(badgeClass).html(`<i class="bi ${iconClass} ${textClass} me-1"></i><span class="${textClass} fw-bold">${Math.abs(persentase).toFixed(2)}%</span>`);


            // =====================================================
            // DATA TANGGAL TERAKHIR UNTUK KAINS & DUTY MANAGER
            // =====================================================

            const resultCurrent  = result.filter(x => x.TANGGAL === currentDate);
            const dataPoli       = {};
            let   totalExecutive = 0;



            resultCurrent.forEach(item=>{
                const poli         = toTitleCase(item.POLI || '');
                const qty          = Number(item.JUMLAH || 0);
                const rekanan      = (item.REKANAN || 'LAINNYA').trim();
                const rekananUpper = rekanan.toUpperCase();
                const rekananId    = item.REKANAN_ID || '';



                if(!dataPoli[poli]){
                    dataPoli[poli]={

                        total:0,
                        BPJS:0,
                        UMUM:0,
                        LAIN:0,
                        EXECUTIVE:0,
                        rekanan:{}

                    };
                }

                if(rekananId === "EXECU0000000001"){
                    dataPoli[poli].EXECUTIVE += qty;
                    totalExecutive += qty;
                    if(!dataPoli[poli].rekanan["Executive"]){
                        dataPoli[poli].rekanan["Executive"]=0;
                    }
                    dataPoli[poli].rekanan["Executive"] += qty;
                }else{
                    dataPoli[poli].total += qty;
                    if(rekananUpper === "BPJS"){
                        dataPoli[poli].BPJS += qty;
                    }
                    else if(rekananUpper === "UMUM"){
                        dataPoli[poli].UMUM += qty;
                    }
                    else{
                        dataPoli[poli].LAIN += qty;
                    }

                    if(!dataPoli[poli].rekanan[rekanan]){
                        dataPoli[poli].rekanan[rekanan]=0;
                    }

                    dataPoli[poli].rekanan[rekanan]+=qty;
                }
            });

            const kainsGroup={};
            resultCurrent.forEach(item=>{

                const poli      = toTitleCase(item.POLI || '');
                const qty       = Number(item.JUMLAH || 0);
                const rekananId = item.REKANAN_ID || '';
                let   rekanan   = item.REKANAN || 'LAINNYA';
                
                if(rekananId === "EXECU0000000001"){
                    rekanan="Executive";
                }else{
                    rekanan=toTitleCase(rekanan);
                }

                if(!kainsGroup[rekanan]){
                    kainsGroup[rekanan]={

                        total:0,
                        poli:{}

                    };
                }

                kainsGroup[rekanan].total += qty;

                if(!kainsGroup[rekanan].poli[poli]){
                    kainsGroup[rekanan].poli[poli]=0;
                }

                kainsGroup[rekanan].poli[poli]+=qty;
            });




            const reportKains=[];

            reportKains.push(`*1. Total Jumlah Kunjungan Rawat Jalan : ${totalKunjungan.toLocaleString("id-ID")} Pasien*`);
            reportKains.push('');

            Object.keys(kainsGroup)
            .sort((a,b)=>
                kainsGroup[b].total - kainsGroup[a].total
            )
            .forEach((rekanan,index)=>{
                reportKains.push(`${String.fromCharCode(65+index)}. ${rekanan} : ${kainsGroup[rekanan].total.toLocaleString("id-ID")} Pasien`);
                reportKains.push('');

                Object.keys(kainsGroup[rekanan].poli)
                .sort((a,b)=>
                    kainsGroup[rekanan].poli[b] - kainsGroup[rekanan].poli[a]
                )
                .forEach(poli=>{
                    reportKains.push(`- ${poli.padEnd(45," ")} : ${String(kainsGroup[rekanan].poli[poli]).padStart(4," ")} Pasien`);
                });

                reportKains.push('');
            });

            $("#kainsrj_txt").val(reportKains.join("\n"));

            const reportDM=[];


            reportDM.push(`*1. Total Jumlah Kunjungan Rawat Jalan : ${totalKunjungan.toLocaleString("id-ID")} Pasien*`);
            reportDM.push('');
            reportDM.push(`A. Poli Reguler : ${(totalKunjungan-totalExecutive).toLocaleString("id-ID")} Pasien`);
            reportDM.push('');

            Object.keys(dataPoli)
            .sort()
            .forEach(poli=>{

                const r=dataPoli[poli];

                if(r.total > 0){
                    const kategori=[];

                    if(r.BPJS>0)
                        kategori.push(`BPJS ${r.BPJS}`);
                    if(r.UMUM>0)
                        kategori.push(`Umum ${r.UMUM}`);
                    if(r.LAIN>0)
                        kategori.push(`Lain-Lain ${r.LAIN}`);

                    reportDM.push(`${poli.padEnd(35," ")} : ${String(r.total).padStart(3," ")} Pasien ( ${kategori.join(", ")} )`);
                }

            });

            reportDM.push('');
            reportDM.push(`B. Poli Executive : ${totalExecutive.toLocaleString("id-ID")} Pasien`);
            reportDM.push('');

            Object.keys(dataPoli)
            .sort()
            .forEach(poli=>{
                const jumlah = dataPoli[poli].EXECUTIVE;

                if(jumlah>0){
                    reportDM.push(`${poli.padEnd(35," ")} : ${jumlah} Pasien`);
                }
            });

            $("#dutymanager_txt").val(reportDM.join("\n"));

        },
        complete:function(){
            Swal.close();
        },
        error:function(){
            Swal.fire({
                icon             : "error",
                title            : "Request Failed",
                text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
}

function dataantrianhelpdesk(){
    $.ajax({
        url       : url +"index.php/dashboard/summary/dataantrianhelpdesk",
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

            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatahelpdesk = result;
            const chartMap = {};

            result.forEach(item => {

                const tanggal = item.TANGGAL;

                if (!tanggal) return;

                if (!chartMap[tanggal]) {
                    chartMap[tanggal] = {
                        periode: tanggal,
                        value_1: 0,
                        value_2: 0,
                        value_3: 0
                    };
                }

                chartMap[tanggal].value_1 += Number(item.KODE_A || 0);
                chartMap[tanggal].value_2 += Number(item.KODE_B || 0);
                chartMap[tanggal].value_3 += Number(item.KODE_C || 0);

            });

            const chartDataHarian = Object.values(chartMap);

            renderChartStackedBar(
                "grafikantrianhelpdesk",
                chartDataHarian.map(item => item.periode),
                [
                    {
                        name: "PRIORITAS / DISABILITAS",
                        data: chartDataHarian.map(item => item.value_1)
                    },
                    {
                        name: "BPJS",
                        data: chartDataHarian.map(item => item.value_2)
                    },
                    {
                        name: "HELP DESK",
                        data: chartDataHarian.map(item => item.value_3)
                    }
                ],
                "Tanggal Kunjungan",
                "Jumlah"
            );
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Request Failed",
                text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                confirmButtonText: "OK"
            });
        }
    });
};

function dataagamapasienri(){
    $.ajax({
        url      : url + "index.php/dashboard/summary/dataagamapasienri",
        type     : "POST",
        dataType : "JSON",
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

        success: function (response) {

            if (response.responCode !== "00") {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Data Available',
                    text: 'No outpatient visit data found.'
                });
                return;
            }

            const result = response.responResult || [];
            const jml = result.length;

            let reportTransit =
                `*2. Agama Pasien Rawat Inap : ${jml} Pasien*\n\n`;

            const groupAgama = {};

            result.forEach(item => {

                const agama = (item.AGAMA || "LAINNYA").trim().toUpperCase();
                const ruangid = (item.RUANGID || "TANPA RUANG").trim();

                if (!groupAgama[agama]) {
                    groupAgama[agama] = {};
                }

                if (!groupAgama[agama][ruangid]) {
                    groupAgama[agama][ruangid] = [];
                }

                groupAgama[agama][ruangid].push(item);
            });


            Object.keys(groupAgama)
                .sort()
                .forEach(agama => {

                    reportTransit += `*${agama}*\n\n`;

                    Object.keys(groupAgama[agama])
                        .sort()
                        .forEach(ruangid => {

                            reportTransit += `Kamar : *${ruangid}*\n`;

                            groupAgama[agama][ruangid].forEach((item, index) => {

                                reportTransit +=
                                    `${index + 1}. ${item.MRPAS || ""} / ` +
                                    `${item.NAMAPASIEN || ""} / ` +
                                    `${item.RUANGRWT_ID || ""}\n`;
                            });

                            reportTransit += `\n`;
                        });
                });

            let existing = $('#dutymanager_txt').val() || '';

            reportTransit = toTitleCase(reportTransit);

            $('#dutymanager_txt').val(
                existing + '\n\n' + reportTransit
            );
        },

        complete: function () {
            Swal.close();
        },

        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve emergency visit data.'
            });
        }
    });
};

function toTitleCase(str) {
    return str.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
}