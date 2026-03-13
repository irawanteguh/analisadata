let tagify;
let startDate, endDate;

datakunjungan();

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

function initTagifyDiagnosa(diagnosaList) {

    if (tagify) tagify.destroy();

    tagify = new Tagify(document.querySelector('#filterDIAG'), {
        whitelist: diagnosaList,
        enforceWhitelist: true,

        // 🔥 INI KUNCI NYA
        delimiters: null, // <-- jangan pecah koma

        dropdown: {
            enabled       : 0,
            closeOnSelect : false,
            classname     : 'diagnosa-dropdown',
            highlightFirst: true
        },

        templates: {
            dropdownItem(tagData) {
                return `
                    <div class="tagify__dropdown__item">
                        <span>${tagData.value}</span>
                    </div>
                `;
            }
        }
    });

    // toggle checkbox behaviour
    tagify.on('dropdown:select', e => {
        const value = e.detail.data.value;
        const exists = tagify.value.some(v => v.value === value);

        exists ? tagify.removeTag(value) : tagify.addTags([value]);
    });

    tagify.on('change', filterByDiagnosa);
}

function filterByDiagnosa(){

    let selected = tagify.value.map(v => v.value);

    if (selected.length === 0) {
        $('#tableanalisaklpcm tbody tr').show();
        return;
    }

    $('#tableanalisaklpcm tbody tr').each(function () {
        let diag = $(this).data('diagnosa') || '';
        let match = selected.some(s => diag.includes(s));
        $(this).toggle(match);
    });
}

function datakunjungan(){
    let dokterid = $("select[name='dokterid']").val();
    let poliid   = $("select[name='poliid']").val();

    $.ajax({
        url        : url + "index.php/outpatient/kunjungan/datakunjungan",
        data       : { startdate:startDate, endate:endDate, dokterid:dokterid, poliid:poliid },
        method     : "POST",
        dataType   : "JSON",
        cache      : false,
        beforeSend : function () {
            Swal.fire({
                title: 'Processing',
                html : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            $("#resultdatakunjungan").html("");
        },
        success: function (data) {

            if (data.responCode !== "00") {
                Swal.fire({
                    icon : 'info',
                    title: 'Information',
                    text : data.responDesc
                });
                return;
            }

            let result      = data.responResult;
            let tableresult = "";
            let allDiagnosa = new Set();

            for (let i in result) {

                let diagPlain = [];

                if (result[i].DIAG) {
                    result[i].DIAG.split(';').forEach(d => {
                        let val = d.split('/batasjenis')[0].trim();
                        diagPlain.push(val);
                        allDiagnosa.add(val);
                    });
                }

                tableresult += `
                    <tr data-diagnosa="${diagPlain.join('|')}">
                        <td class="ps-4 text-start">${result[i].MRPASIEN}</td>
                        <td>${result[i].NAMAPASIEN}</td>
                        <td>${result[i].JENISKELAMIN}</td>
                        <td>${result[i].POLITUJUAN}</td>
                        <td>${result[i].NAMADOKTER}</td>
                        <td>${result[i].EPISODEID}</td>
                        <td>${result[i].PROVIDER}</td>
                        <td>${result[i].TGLMASUK}</td>
                        <td>${result[i].TGLKELUAR}</td>
                        <td>
                `;

                if (result[i].DIAG) {
                    result[i].DIAG.split(';').forEach(d => {
                        let x = d.trim().split('/batasjenis');
                        let badge = x[1] === "1"
                            ? "<span class='badge badge-light-primary'>Primary</span>"
                            : "<span class='badge badge-light-info'>Secondary</span>";
                        tableresult += `<div>${x[0]} ${badge}</div>`;
                    });
                } else {
                    tableresult += `<div class="text-muted fst-italic">Tidak ada diagnosa</div>`;
                }

                tableresult += "</td></tr>";
            }

            $("#resultdatakunjungan").html(tableresult);

            initTagifyDiagnosa([...allDiagnosa].sort());
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

