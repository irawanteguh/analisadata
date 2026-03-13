// let startDate, endDate;

// flatpickr('[name="dateperiode"]', {
//     mode: "range",
//     enableTime: false,
//     dateFormat: "d.m.Y",
//     maxDate: "today",
//     onChange: function (selectedDates, dateStr, instance) {
//         startDate = selectedDates[0] 
//             ? selectedDates[0].toLocaleDateString('en-CA') // Format YYYY-MM-DD
//             : null;
//         endDate = selectedDates[1] 
//             ? selectedDates[1].toLocaleDateString('en-CA') // Format YYYY-MM-DD
//             : null;

//     }
// });

// $(document).on("click", ".btn-apply", function (e) {
//     e.preventDefault();

//     if (!startDate || !endDate) {
//         toastr["warning"]("Please select a valid date range", "Warning");
//         return;
//     }

//     datakunjungan(startDate, endDate);
// });

// function datakunjungan(startDate,endDate){
//     $.ajax({
//         url     : url + "index.php/outpatient/kunjungan/datakunjungan",
//         data    : {startdate:startDate,endate:endDate},
//         type    : "POST",
//         dataType: "JSON",
//         cache   : false,
//         beforeSend: function () {            
//             Swal.fire({
//                 title            : 'Processing',
//                 html             : 'Please wait while the system displays the requested data...',
//                 allowOutsideClick: false,
//                 allowEscapeKey   : false,
//                 showConfirmButton: false,
//                 didOpen          : () => Swal.showLoading()
//             });

//             $("#resultrawdatarawatjalan").html("");
//         },

//         success: function (data) {

//             if(data.responCode !== "00"){
//                 Swal.fire('Info','Data tidak ditemukan','info');
//                 return;
//             }

//             const result = data.responResult || [];
//             if(result.length === 0){
//                 Swal.fire('Info','Data kosong','info');
//                 return;
//             }

//             const chartDataDiagnosa         = aggregatediagnosa(result, 10);
//             const chartDataDiagnosageriatri = aggregatediagnosageriatri(result, 10);
//             const chartDataPoliklinik       = aggregatepoliklinik(result, 10);
//             const chartDataDokter           = aggregatedokter(result, 10);
//             const chartDataProvider         = aggregateprovider(result, 10);

//             renderbarhorizontal("grafiktopdiagnosarj", chartDataDiagnosa);
//             renderbarhorizontal("grafiktopdiagnosarjgeriatri", chartDataDiagnosageriatri);
//             renderbarhorizontal("grafikrjpoli", chartDataPoliklinik);
//             renderbarhorizontal("grafikdokter", chartDataDokter);
//             renderbarhorizontal("grafikprovider", chartDataProvider);

//             renderDiagnosaSummary("resultrawdatarawatjalandiagnosa",result);
//             renderDiagnosaSummarygeriatri("resultrawdatarawatjalandiagnosageriatri",result);
//             renderPoliklinik("resultrawdatarawatjalanpoli",result);
//             renderDokter("resultrawdatarawatjalandokter",result);
//             renderProvider("resultrawdatarawatjalanprovider",result);

//             renderKunjunganChunk(result);
            
//         },
//         error: function () {
//             Swal.fire({
//                 icon : 'error',
//                 title: 'Error',
//                 text : 'Unable to retrieve visit data.'
//             });
//         }
//     });
// }

// function renderKunjunganChunk(result){
//     Swal.close(); // pastikan swal sebelumnya ditutup

//     const target    = document.getElementById("resultrawdatarawatjalan");
//     const chunkSize = 300;
//     let index       = 0;

//     // tampilkan toastr awal
//     const toast = toastr.info(
//         `Rendering data... <b>0</b> / ${result.length}`,
//         "Processing"
//     );

//     function render(){
//         let html = "";

//         for(let i = index; i < index + chunkSize && i < result.length; i++){
//             html += buildRow(result[i], i);
//         }

//         target.insertAdjacentHTML("beforeend", html);
//         index += chunkSize;

//         // update isi toastr
//         toast.find(".toast-message").html(
//             `Rendering data... <b>${index}</b> / ${result.length}`
//         );

//         if(index < result.length){
//             requestAnimationFrame(render);
//         } else {
//             toastr.clear(); // selesai → tutup toastr
//         }
//     }

//     render();
// }

// function renderDiagnosaSummary(targetId, result, limit = 10){

//     const diagnosaMap = {};

//     result.forEach(row => {

//         if(!row.DIAG) return;

//         const diags = row.DIAG.split(";");

//         diags.forEach(d => {

//             const parts    = d.split("/batasjenis");
//             const diagnosa = (parts[0] || '').trim();

//             if(!diagnosa) return;

//             // 🔥 ambil kode dalam tanda []
//             const match = diagnosa.match(/\[(.*?)\]/);
//             const kode  = match ? match[1] : "";

//             // 🔥 exclude kode yang diawali Z atau R
//            if(/^[ZR]/i.test(kode)) return;

//             diagnosaMap[diagnosa] = (diagnosaMap[diagnosa] || 0) + 1;

//         });

        

//     });

//     const diagnosaArr = Object.entries(diagnosaMap)
//         .sort((a, b) => b[1] - a[1])
//         .slice(0, limit);

//     let html = "";
//     let no   = 1;


//     console.log(diagnosaArr);

//     diagnosaArr.forEach(([diagnosa, jumlah]) => {
//         html += `
//             <tr>
//                 <td class="ps-4">${no++}</td>
//                 <td>${diagnosa}</td>
//                 <td class="text-end pe-4">${jumlah.toLocaleString("id-ID")}</td>
//             </tr>
//         `;
//     });

//     $("#" + targetId).html(html);
// }

// function renderDiagnosaSummarygeriatri(targetId, result, limit = 10){

//     const diagnosaMap = {};

//     result.forEach(row => {

//         // ✅ hanya pasien geriatri
//         if(row.GERIATRI !== "Y") return;
//         if(!row.DIAG) return;

//         const diags = row.DIAG.split(";");

//         diags.forEach(d => {

//             const parts    = d.split("/batasjenis");
//             const diagnosa = (parts[0] || '').trim();

//             if(!diagnosa) return;

//             // 🔥 ambil kode ICD dalam []
//             const match = diagnosa.match(/\[(.*?)\]/);
//             const kode  = match ? match[1] : "";

//             // 🔥 exclude Z & R
//             if(/^[ZR]/i.test(kode)) return;

//             diagnosaMap[diagnosa] = (diagnosaMap[diagnosa] || 0) + 1;

//         });
//     });

//     const diagnosaArr = Object.entries(diagnosaMap)
//         .sort((a, b) => b[1] - a[1])
//         .slice(0, limit);

//     let html = "";
//     let no   = 1;

//     diagnosaArr.forEach(([diagnosa, jumlah]) => {
//         html += `
//             <tr>
//                 <td class="ps-4">${no++}</td>
//                 <td>${diagnosa}</td>
//                 <td class="text-end pe-4">${jumlah.toLocaleString("id-ID")}</td>
//             </tr>
//         `;
//     });

//     $("#" + targetId).html(html);
// }

// function renderPoliklinik(targetId, result, limit = 100){

//     const poliMap = {}; // { poliklinik: jumlah }

//     result.forEach(row => {

//         const poli = (row.POLITUJUAN || '').trim();
//         if(!poli) return;

//         poliMap[poli] = (poliMap[poli] || 0) + 1;
//     });

//     const poliArr = Object.entries(poliMap).sort((a, b) => b[1] - a[1]).slice(0, limit); // 🔥 TOP N

//     let html = "";
//     let no   = 1;

//     poliArr.forEach(([poliklinik, jumlah]) => {
//         html += `
//             <tr>
//                 <td class="ps-4">${no++}</td>
//                 <td>${poliklinik}</td>
//                 <td class="text-end pe-4">${jumlah}</td>
//             </tr>
//         `;
//     });

//     $("#" + targetId).html(html);
// }

// function renderDokter(targetId, result, limit = 100){

//     const dokterMap = {}; // { namaDokter: jumlah }

//     result.forEach(row => {

//         const namaDokter = (row.NAMADOKTER || '').trim();
//         if(!namaDokter) return;

//         dokterMap[namaDokter] = (dokterMap[namaDokter] || 0) + 1;
//     });

//     // TOP N dokter berdasarkan jumlah kunjungan
//     const dokterArr = Object.entries(dokterMap)
//         .sort((a, b) => b[1] - a[1])
//         .slice(0, limit);

//     let html = "";
//     let no   = 1;

//     dokterArr.forEach(([namaDokter, jumlah]) => {
//         html += `
//             <tr>
//                 <td class="ps-4">${no++}</td>
//                 <td>${namaDokter}</td>
//                 <td class="text-end pe-4">${jumlah}</td>
//             </tr>
//         `;
//     });

//     $("#" + targetId).html(html);
// }

// function renderProvider(targetId, result, limit = 100){

//     const providerMap = {}; // { provider: jumlah }

//     result.forEach(row => {

//         const provider = (row.PROVIDER || '').trim();
//         if(!provider) return;

//         providerMap[provider] = (providerMap[provider] || 0) + 1;
//     });

//     // TOP N provider berdasarkan jumlah kunjungan
//     const providerArr = Object.entries(providerMap)
//         .sort((a, b) => b[1] - a[1])
//         .slice(0, limit);

//     let html = "";
//     let no   = 1;

//     providerArr.forEach(([provider, jumlah]) => {
//         html += `
//             <tr>
//                 <td class="ps-4">${no++}</td>
//                 <td>${provider}</td>
//                 <td class="text-end pe-4">${jumlah}</td>
//             </tr>
//         `;
//     });

//     $("#" + targetId).html(html);
// }

// function buildRow(row, i){
//     const sexLabel      = row.SEX_ID   === 'L' ? 'Laki-laki' : row.SEX_ID === 'P' ? 'Perempuan' : '';
//     const geriatriLabel = row.GERIATRI === 'Y' ? 'Geriatri' : '';

//     return `
//         <tr>
//             <td class="ps-4">${i + 1}</td>
//             <td>${row.MRPASIEN || ''}</td>
//             <td>${row.NAMAPASIEN || ''}</td>
//             <td>${sexLabel}</td>
//             <td>${geriatriLabel}</td>
//             <td>${row.TEMPAT_LAHIR_TXT || ''}</td>
//             <td>${row.TGLLAHIR || ''}</td>
//             <td>${row.UMURSAATINI || ''}</td>
//             <td>${row.EPISODE_ID || ''}</td>
//             <td>${row.POLITUJUAN || ''}</td>
//             <td>${row.NAMADOKTER || ''}</td>
//             <td>${row.TGLMASUK || ''}</td>
//             <td>${row.TGLKELUAR || ''}</td>
//             <td>${row.UMURSAATPELAYANAN || ''}</td>
//             <td>${row.BB || ''}</td>
//             <td>${row.TB || ''}</td>
//             <td>${row.IMT || ''}</td>
//             <td>${buildDiagnosa(row.DIAG)}</td>
//             <td>${buildObat(row.OBAT)}</td>
//             <td class="text-end"></td>
//         </tr>
//     `;
// }

// function buildDiagnosa(diag){

//     if(!diag) return '';

//     return diag.split(";").map(d => {

//         const parts = d.split("/batasjenis");
//         const text  = parts[0] || '';
//         const jenis = parts[1] || '';

//         const cls = jenis === '1'
//             ? 'badge bg-light-primary text-primary'
//             : 'badge bg-light-info text-info';

//         return `<span class="${cls}">${text}</span>`;

//     }).join("<br>");
// }

// function buildObat(obat){

//     if(!obat) return '';

//     return obat
//         .split(";")
//         .filter(o => o.trim() !== '')
//         .map(o => `<div>${o}</div>`)
//         .join("");
// }

let today = new Date().toLocaleDateString('en-CA'); // format YYYY-MM-DD
let startDate = today;
let endDate   = today;

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    minDate   : "today",
    onChange  : function (selectedDates, dateStr, instance) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate   = selectedDates[1]  ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    databooking(startDate, endDate);
});

databooking(startDate, endDate);

$(document).on("keyup", "#fieldsearch", function () {
    filterTableByKeywords("#fieldsearch", "#resultdatadatabooking");
});

function databooking(startDate,endDate) {
    $.ajax({
        url       : url + "index.php/outpatient/kunjungan/databooking",
        data      : {startDate:startDate,endDate:endDate},
        type      : "POST",
        dataType  : "JSON",
        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatadatabooking").empty();
        },

        success: function (data) {
            var   tableresult = "";
            const result      = data.responResult || [];
            const total       = result.length;

            if (data.responCode !== "00") {
                Swal.fire({
                    icon : 'warning',
                    title: 'No Records Found',
                    text : 'No records are available for the selected period.',
                    showConfirmButton: false
                });
                return;
            }

            Swal.close();

            for(var i in result){
                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i) + 1) + "</td>";
                tableresult += "<td>"+result[i].MRPASIEN+"</td>";
                tableresult += "<td>"+result[i].NAMAPASIEN+"</td>";
                tableresult += "<td>"+result[i].PROVIDER+"</td>";
                tableresult += "<td>"+result[i].POLITUJUAN+"</td>";
                tableresult += "<td>"+result[i].NAMADOKTER+"</td>";
                tableresult += "<td>"+result[i].TGLMASUK+"</td>";
                tableresult += "<td class='text-end'>"+result[i].JAM_MULAI+"</td>";
                tableresult += "<td class='text-end'>"+result[i].JAM_SELESAI+"</td>";
                tableresult += "<td>"+result[i].URUT+"</td>";
                tableresult += "<td>"+result[i].BOOKING_ID+"</td>";
                tableresult += "<td class='text-end pe-4'>"+result[i].NO_SELULAR+"</td>";
                tableresult += "</tr>";
            }

            $("#resultdatadatabooking").html(tableresult);

        },

        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to retrieve shift data.'
            });
        }
    });
}