// function datakunjungan(){
//     $.ajax({
//         url        : url + "index.php/outpatient/kunjunganaggregate/datakunjungan",
//         method     : "POST",
//         dataType   : "JSON",
//         cache      : false,
//         beforeSend : function () {
//             Swal.fire({
//                 title: 'Processing',
//                 html : 'Please wait while the system displays the requested data.',
//                 allowOutsideClick: false,
//                 allowEscapeKey   : false,
//                 showConfirmButton: false,
//                 didOpen: () => Swal.showLoading()
//             });
//         },
//         success: function (data) {
//             let chartData = data.responResult.map(item => ({
//                 periode : item.PERIODE,
//                 VALUE1  : parseInt(item.VALUE1) || 0,
//                 VALUE2  : parseInt(item.VALUE2) || 0
//             }));

//             renderChart("grafikkunjunganaggregate", chartData);
//         },
//         complete: function () {
//             Swal.close();
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

datakunjungan();

$('#selectperiode').on('change', function () {
    datakunjungan();
});

function datakunjungan(){
    let selectperiode   = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/outpatientaggregate/kunjunganaggregate/datakunjungan",
        data      : {selectperiode:selectperiode},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
        beforeSend: function () {            
            $("#grafikkunjunganaggregate").html("");
            $("#grafikkunjunganaggregateprovider").html("");
            $("#grafikkunjunganaggregatepoli").html("");
            $("#grafikkunjunganaggregatedokter").html("");

            $("#tablerawprovider").empty();
            $("#tablerawpoli").empty();
            $("#tablerawdokter").empty();
            
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });
        },
        success: function (data) {
            let rawData = [];
            if (data && Array.isArray(data.responResult)) {
                rawData = data.responResult;
            }

            const chartDataBulanan  = aggregatePerBulan(rawData);
            const chartDataProvider = aggregateProvider(rawData);
            const chartDataPoli     = aggregatePoli(rawData);
            const chartDataDokter   = aggregateDokter(rawData);

            renderchartarea("grafikkunjunganaggregate",chartDataBulanan,"Periode Pelayanan","Jumlah Kunjungan");
            renderBarHorizontal("grafikkunjunganaggregateprovider", chartDataProvider);
            renderBarHorizontal("grafikkunjunganaggregatepoli", chartDataPoli);
            renderBarHorizontal("grafikkunjunganaggregatedokter", chartDataDokter);

            renderTableRaw("tablerawprovider", rawData, "PROVIDER");
            renderTableRaw("tablerawpoli", rawData, "POLIKLINIK");
            renderTableRaw("tablerawdokter", rawData, "NAMADOKTER");
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

function aggregatePerBulan(rawData) {

    const monthOrder = [
        { num: "01", label: "JAN" },
        { num: "02", label: "FEB" },
        { num: "03", label: "MAR" },
        { num: "04", label: "APR" },
        { num: "05", label: "MEI" },
        { num: "06", label: "JUN" },
        { num: "07", label: "JUL" },
        { num: "08", label: "AGU" },
        { num: "09", label: "SEP" },
        { num: "10", label: "OKT" },
        { num: "11", label: "NOV" },
        { num: "12", label: "DES" }
    ];

    // Inisialisasi semua bulan = 0 (biar urut & konsisten)
    const result = {};
    monthOrder.forEach(m => {
        result[m.num] = {
            periode: m.label,
            total: 0
        };
    });

    // Agregasi data
    rawData.forEach(item => {
        if (!item.TGLMASUK) return;

        const month = item.TGLMASUK.substring(5, 7); // YYYY-MM-DD
        if (result[month]) {
            result[month].total++;
        }
    });

    // Return sesuai urutan bulan
    return monthOrder.map(m => result[m.num]);
}

function aggregateProvider(data) {

    const map = {};

    data.forEach(item => {

        // amankan nama field (backend kadang uppercase)
        const provider =
            item.provider ||
            item.PROVIDER ||
            'LAINNYA';

        if (!map[provider]) {
            map[provider] = {
                keterangan: provider,
                jumlah: 0
            };
        }

        map[provider].jumlah++;
    });

    // ubah ke array + sorting
    return Object.values(map).sort((a, b) => b.jumlah - a.jumlah);
}

function aggregatePoli(data) {

    const map = {};

    data.forEach(item => {
        const poliklinik =
            item.poliklinik ||
            item.POLIKLINIK ||
            'LAINNYA';

        if (!map[poliklinik]) {
            map[poliklinik] = {
                keterangan: poliklinik,
                jumlah: 0
            };
        }

        map[poliklinik].jumlah++;
    });

    // ubah ke array + sorting
    return Object.values(map).sort((a, b) => b.jumlah - a.jumlah);
}

function aggregateDokter(data) {

    const map = {};

    data.forEach(item => {
        const namadokter =
            item.namadokter ||
            item.NAMADOKTER ||
            'LAINNYA';

        if (!map[namadokter]) {
            map[namadokter] = {
                keterangan: namadokter,
                jumlah: 0
            };
        }

        map[namadokter].jumlah++;
    });

    // ubah ke array + sorting
    return Object.values(map).sort((a, b) => b.jumlah - a.jumlah);
}

function renderTableRaw(divId, rawData, groupByField) {
    const tableBody = $("#" + divId);
    tableBody.empty(); // Kosongkan isi tabel sebelumnya

    const groupMap = {};

    // 1. Kelompokkan data berdasarkan groupByField dan Bulan
    rawData.forEach(item => {
        const groupName = item[groupByField] || 'LAINNYA';
        
        // Ambil index bulan (asumsi fieldnya 'TGL_MASUK' atau 'bulan')
        let monthIdx = -1;
        if (item.TGLMASUK) {
            monthIdx = new Date(item.TGLMASUK).getMonth(); // 0-11
        } else if (item.bulan) {
            monthIdx = parseInt(item.bulan) - 1; // Jika dari DB formatnya 1-12
        }

        if (!groupMap[groupName]) {
            groupMap[groupName] = {
                name: groupName,
                monthly: new Array(12).fill(0),
                total: 0
            };
        }

        if (monthIdx >= 0 && monthIdx <= 11) {
            groupMap[groupName].monthly[monthIdx]++;
            groupMap[groupName].total++;
        }
    });

    // 2. Ubah map menjadi array dan urutkan berdasarkan total terbanyak
    const sortedData = Object.values(groupMap).sort((a, b) => b.total - a.total);

    // 3. Generate HTML baris tabel
    let html = "";
    sortedData.forEach(row => {
        html += `
            <tr>
                <td class="ps-4">
                    <div class="text-muted fw-bolder fs-8">${row.name}</div>
                </td>`;
        
        // Render kolom JAN sampai DES
        row.monthly.forEach(val => {
            html += `<td class="text-end"><span class="text-muted fw-bold fs-8">${val.toLocaleString()}</span></td>`;
        });

        // Render kolom TOTAL
        html += `
                <td class="pe-4 text-end">
                    <span class="badge badge-light-success fs-8 fw-bolder">${row.total.toLocaleString()}</span>
                </td>
            </tr>`;
    });

    tableBody.append(html);
}
