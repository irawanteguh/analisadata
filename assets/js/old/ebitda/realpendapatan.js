let startDate, endDate;

flatpickr('[name="dateperiode"]', {
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate: "today",
    defaultDate: "today",
    onChange: function (selectedDates) {
        startDate = selectedDates[0]
            ? selectedDates[0].toLocaleDateString('en-CA') // YYYY-MM-DD
            : null;

        endDate = startDate; // samakan karena 1 tanggal
    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    dataactual(startDate,endDate);
});


function dataactual(startDate, endDate) {
    $.ajax({
        url: url + "index.php/ebitda/realpendapatan/dataactual",
        data: { startdate: startDate, endate: endDate },
        type: "POST",
        dataType: "JSON",
        beforeSend: function () {
            Swal.fire({
                title: 'Processing',
                html: 'Please wait...',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            $("#resultdataactualpendapatan").html("");
            $(".totalactualpendapatan").html("Rp. 0");
        },
        success: function (data) {
            let tableresult = "";
            let grandTotal = 0;

            if (data.responCode === "00") {
                let grouped = {};
                let rows = data.responResult || [];

                // ===== GROUPING =====
                rows.forEach(r => {
                    let key = r.LAYAN_ID + "_" + r.HARGA_SATUAN;
                    if (!grouped[key]) {
                        grouped[key] = {
                            NAMAPELAYANAN: r.NAMAPELAYANAN,
                            HARGA_SATUAN: parseFloat(r.HARGA_SATUAN) || 0,
                            QTY: 0,
                            TOTAL: 0,
                            DETAIL: []
                        };
                    }
                    grouped[key].QTY += parseFloat(r.QTY) || 0;
                    grouped[key].TOTAL += parseFloat(r.HARGA_TOTAL) || 0;
                    grouped[key].DETAIL.push(r);
                });

                // ===== RENDER =====
                let no = 1;
                Object.values(grouped).forEach(v => {
                    let detailId = "detail_row_" + no;
                    grandTotal += v.TOTAL;

                    tableresult += `
                        <tr>
                            <td class="ps-4">${no}</td>
                            <td>
                                <a href="javascript:void(0)" onclick="toggleDetail('${detailId}')">
                                    ${v.NAMAPELAYANAN}
                                </a>
                            </td>
                            <td class="text-end">${v.QTY}</td>
                            <td></td>
                            <td class="text-end">${todesimal(v.HARGA_SATUAN)}</td>
                            <td class="text-end">${todesimal(v.TOTAL)}</td>
                            <td></td>
                            <td></td>
                        </tr>

                        <tr id="${detailId}" style="display:none">
                            <td></td>
                            <td colspan="5" class="p-3">
                                ${renderDetailTable(v.DETAIL)}
                            </td>
                        </tr>
                    `;
                    no++;
                });
            }

            $("#resultdataactualpendapatan").html(tableresult);
            $(".totalactualpendapatan").html("Rp. " + todesimal(grandTotal));
        },
        complete: function () {
            Swal.close();
        }
    });
}

function toggleDetail(id) {
    const row = document.getElementById(id);
    row.style.display = (row.style.display === "none") ? "" : "none";
}



function renderDetailTable(data) {
    let html = `
        <table class="table table-sm table-bordered fs-8 mb-0">
            <thead class="align-middle">
                <tr class="fw-bolder text-white bg-info">
                    <th class="ps-4 rounded-start text-start">No</th>
                    <th>MR</th>
                    <th>Nama Pasien</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Harga</th>
                    <th class="text-end">Total</th>
                    <th class="pe-4 rounded-end text-end">Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-bold">
    `;

    data.forEach((r, i) => {
        html += `
            <tr>
                <td class="ps-4 text-start">${i + 1}</td>
                <td>${r.MRPASIEN}</td>
                <td class="text-start">${r.NAMAPASIEN}</td>
                <td class="text-end">${r.QTY}</td>
                <td class="text-end">${todesimal(r.HARGA_SATUAN)}</td>
                <td class="text-end">${todesimal(r.HARGA_TOTAL)}</td>
                <td class="pe-4 text-end">${r.CREATEDDATE}</td>
            </tr>
        `;
    });

    html += `</tbody></table>`;
    return html;
}
