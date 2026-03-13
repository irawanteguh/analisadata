let startDate, endDate;

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate   : "today",
    onChange  : function (selectedDates) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate   = selectedDates[1] ? selectedDates[1].toLocaleDateString('en-CA') : null;
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    datakunjungan(startDate, endDate);
});

$(document).on("keyup", "#fieldsearch", function () {
    let keyword = $(this).val().toLowerCase();
    $("#resultrawdatarawatinap tr").each(function () {
        let row = $(this);
        let rowText = row.text().toLowerCase();
        if(rowText.indexOf(keyword) > -1){
            row.show();
        }else{
            row.hide();
        }
    });
});

function datakunjungan(startDate,endDate){
    $.ajax({
        url       : url + "index.php/inpatient/kunjungan/datakunjungan",
        data    : {startdate:startDate,endate:endDate},
        type      : "POST",
        dataType  : "JSON",
        cache     : false,
        beforeSend: function () {            
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data...',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultrawdatarawatinap").html("");
        },

        success: function (data) {

            if(data.responCode !== "00"){
                Swal.fire('Info','Data tidak ditemukan','info');
                return;
            }

            const result = data.responResult || [];
            if(result.length === 0){
                Swal.fire('Info','Data kosong','info');
                return;
            }

            // const chartDataDiagnosa         = aggregatediagnosa(result, 10);
            // const chartDataDiagnosageriatri = aggregatediagnosageriatri(result, 10);
            // const chartDataPoliklinik       = aggregatepoliklinik(result, 10);
            // const chartDataDokter           = aggregatedokter(result, 10);
            // const chartDataProvider         = aggregateprovider(result, 10);

            // renderbarhorizontal("grafiktopdiagnosarj", chartDataDiagnosa);
            // renderbarhorizontal("grafiktopdiagnosarjgeriatri", chartDataDiagnosageriatri);
            // renderbarhorizontal("grafikrjpoli", chartDataPoliklinik);
            // renderbarhorizontal("grafikdokter", chartDataDokter);
            // renderbarhorizontal("grafikprovider", chartDataProvider);

            // renderDiagnosaSummary("resultrawdatarawatjalandiagnosa",result);
            // renderDiagnosaSummarygeriatri("resultrawdatarawatjalandiagnosageriatri",result);
            // renderPoliklinik("resultrawdatarawatjalanpoli",result);
            // renderDokter("resultrawdatarawatjalandokter",result);
            // renderProvider("resultrawdatarawatjalanprovider",result);

            renderKunjunganChunk(result);
            
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

function renderKunjunganChunk(result){
    Swal.close();

    const target    = document.getElementById("resultrawdatarawatinap");
    const chunkSize = 300;
    let index       = 0;

    // tampilkan toastr awal
    const toast = toastr.info(
        `Rendering data... <b>0</b> / ${result.length}`,
        "Processing"
    );

    function render(){
        let html = "";

        for(let i = index; i < index + chunkSize && i < result.length; i++){
            html += buildRow(result[i], i);
        }

        target.insertAdjacentHTML("beforeend", html);
        index += chunkSize;

        // update isi toastr
        toast.find(".toast-message").html(
            `Rendering data... <b>${index}</b> / ${result.length}`
        );

        if(index < result.length){
            requestAnimationFrame(render);
        } else {
            toastr.clear(); // selesai → tutup toastr
        }
    }

    render();
}

function buildRow(row, i){
    const sexLabel      = row.SEX_ID   === 'L' ? 'Laki-laki' : row.SEX_ID === 'P' ? 'Perempuan' : '';
    const geriatriLabel = row.GERIATRI === 'Y' ? 'Geriatri' : '';

    return `
        <tr>
            <td class="ps-4">${i + 1}</td>
            <td>${row.MRPASIEN || ''}</td>
            <td>${row.NAMAPASIEN || ''}</td>
            <td>${sexLabel}</td>
            <td>${geriatriLabel}</td>
            <td>${row.TEMPAT_LAHIR_TXT || ''}</td>
            <td>${row.TGLLAHIR || ''}</td>
            <td>${row.UMURSAATINI || ''}</td>
            <td>${row.NAMADOKTER || ''}</td>
            <td>${row.TGLMASUK || ''}</td>
            <td>${row.TGLKELUAR || ''}</td>
            <td>${row.UMURSAATPELAYANAN || ''}</td>
            <td>${buildDiagnosa(row.DIAG)}</td>
            <td class="text-end"></td>
        </tr>
    `;
}

function buildDiagnosa(diag){

    if(!diag) return '';

    return diag.split(";").map(d => {

        const parts = d.split("/batasjenis");
        const text  = parts[0] || '';
        const jenis = parts[1] || '';

        const cls = jenis === '1'
            ? 'badge bg-light-primary text-primary'
            : 'badge bg-light-info text-info';

        return `<span class="${cls}">${text}</span>`;

    }).join("<br>");
}

function buildObat(obat){

    if(!obat) return '';

    return obat
        .split(";")
        .filter(o => o.trim() !== '')
        .map(o => `<div>${o}</div>`)
        .join("");
}