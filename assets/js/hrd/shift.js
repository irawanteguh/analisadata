let globaldataperhitunganshift     = [];
let globaldataperhitunganshiftname = [];

perhitunganshift();

$('#selectperiode').on('change', function () {
    perhitunganshift();
});

$("#btndownloaddatashift_table").on("click", function () {

    exportToExcel(
        null,
        null,
        "Perhitungan_Uang_Shifting.xlsx",
        {
            multiSheet: [

                // ==================================================
                // SHEET 1 : DETAIL SHIFT
                // ==================================================
                {
                    name: "Detail Shift",
                    data: globaldataperhitunganshift,

                    formatter: (item, index) => {

                        return {
                            "No"            : index + 1,
                            "Tanggal"       : item.PERIODETGL ?? "",
                            "Hari"          : item.NAMAHARI ?? "",
                            "NIK"            : item.NIK ?? "",
                            "Nama"          : item.NAMAKARYAWAN ?? "",
                            "Unit"          : item.UNIT ?? "",
                            "Sub Unit"      : item.SUB_UNIT ?? "",
                            "Kategori"      : item.JENIS_PEGAWAI ?? "",
                            "Flag"          : item.FLAG ?? "",
                            "Jadwal Masuk"  : item.JAMMASUK ?? "",
                            "Jadwal Pulang" : item.JAMPULANG ?? "",
                            "Jam Masuk"     : item.REALMASUK ?? "",
                            "Jam Pulang"    : item.REALPULANG ?? "",
                            "Uang Shift"    : Number(item.NOMINALUANGSHIFT ?? 0),
                            "Keterangan"    : item.HARILIBUR ?? ""
                        };

                    }
                },


                // ==================================================
                // SHEET 2 : REKAP BY NAME
                // ==================================================
                {
                    name: "By Name",
                    data: globaldataperhitunganshiftname,

                    formatter: (item, index) => {

                        return {
                            "No"                 : index + 1,
                            "NIK"                : item.nik ?? "",
                            "Nama"               : item.nama ?? "",
                            "Unit"               : item.unit ?? "",
                            "Sub Unit"           : item.subunit ?? "",
                            "Kategori"           : item.kategori ?? "",
                            "Jumlah Uang Shift"  : Number(item.total ?? 0)
                        };

                    }
                }

            ]
        }
    );

});

function perhitunganshift() {

    const selectperiode = $("select[name='selectperiode']").val();

    $.ajax({
        url     : url + "index.php/hrd/shift/perhitunganshift",
        type    : "POST",
        dataType: "JSON",
        data    : {
            selectperiode: selectperiode
        },

        beforeSend: function () {

            Swal.fire({
                title: "Processing",
                html: `
                    <div class="mb-3">
                        Please wait while the system retrieves the requested data.
                    </div>

                    <div class="progress" style="height: 20px;">
                        <div id="swalProgress"
                            class="progress-bar progress-bar-striped progress-bar-animated"
                            role="progressbar"
                            style="width: 0%">
                            0%
                        </div>
                    </div>

                    <div class="mt-3 fw-bold" id="swalProgressText">
                        Preparing data...
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $("#resultdataperhitunganshift").empty();
            $("#resultdataperhitunganshiftname").empty();
        },

        success: function (data) {

            if (data.responCode !== "00") {

                Swal.fire({
                    icon: "warning",
                    title: "No Records Found",
                    text: "No records are available for the selected period.",
                    confirmButtonText: "OK"
                });

                return;
            }

            const result = data.responResult || [];
            globaldataperhitunganshift = result;
            const total  = result.length;

            const tbodyDetail  =
                document.getElementById("resultdataperhitunganshift");

            const tbodySummary =
                document.getElementById("resultdataperhitunganshiftname");

            tbodyDetail.innerHTML  = "";
            tbodySummary.innerHTML = "";

            const summary = {};

            let index = 0;
            const batchSize = 200;

            function updateProgress(text, percent) {

                const progressBar =
                    document.getElementById("swalProgress");

                const progressText =
                    document.getElementById("swalProgressText");

                if (progressBar) {

                    progressBar.style.width = percent + "%";
                    progressBar.innerText  = percent + "%";

                }

                if (progressText) {
                    progressText.innerText = text;
                }
            }


            function renderBatch() {

                const fragment =
                    document.createDocumentFragment();

                let count = 0;

                while (
                    index < total &&
                    count < batchSize
                ) {

                    const row = result[index];

                    const tr =
                        document.createElement("tr");

                    tr.innerHTML = `
                        <td class="ps-4">${index + 1}</td>
                        <td>${row.PERIODETGL || ""}</td>
                        <td>${row.NAMAHARI || ""}</td>
                        <td>${row.NIK || ""}</td>
                        <td>${row.NAMAKARYAWAN || ""}</td>
                        <td>${row.UNIT || ""}</td>
                        <td>${row.SUB_UNIT || ""}</td>
                        <td>${row.JENIS_PEGAWAI || ""}</td>
                        <td>${row.FLAG || ""}</td>
                        <td class="text-end">${row.JAMMASUK || ""}</td>
                        <td class="text-end">${row.JAMPULANG || ""}</td>
                        <td class="text-end">${row.REALMASUK || ""}</td>
                        <td class="text-end">${row.REALPULANG || ""}</td>
                        <td class="text-end">
                            ${todesimal(row.NOMINALUANGSHIFT)}
                        </td>
                        <td class="text-end pe-4">
                            ${row.HARILIBUR || ""}
                        </td>
                    `;

                    fragment.appendChild(tr);


                    // ==========================
                    // SUMMARY
                    // ==========================

                    if (!summary[row.NIK]) {

                        summary[row.NIK] = {
                            nik      : row.NIK,
                            nama     : row.NAMAKARYAWAN,
                            unit     : row.UNIT,
                            subunit  : row.SUB_UNIT,
                            kategori : row.JENIS_PEGAWAI,
                            total    : 0
                        };

                    }

                    summary[row.NIK].total +=
                        parseFloat(
                            row.NOMINALUANGSHIFT || 0
                        );

                    index++;
                    count++;
                }


                tbodyDetail.appendChild(fragment);


                // ==========================
                // UPDATE PROGRESS
                // ==========================

                const percent = total > 0
                    ? Math.min(
                        100,
                        Math.round((index / total) * 100)
                    )
                    : 100;

                updateProgress(
                    `Memproses data ${index.toLocaleString()} dari ${total.toLocaleString()}...`,
                    percent
                );


                if (index < total) {

                    requestAnimationFrame(
                        renderBatch
                    );

                } else {

                    buildSummary();
                    globaldataperhitunganshiftname = Object.values(summary);

                }

            }


            function buildSummary() {

                updateProgress(
                    `Membangun rekapitulasi ${Object.keys(summary).length.toLocaleString()} pegawai...`,
                    100
                );


                const fragment =
                    document.createDocumentFragment();

                let no = 1;

                Object.values(summary).forEach(item => {

                    const tr =
                        document.createElement("tr");

                    tr.innerHTML = `
                        <td class="ps-4">${no++}</td>
                        <td>${item.nik}</td>
                        <td>${item.nama}</td>
                        <td>${item.unit}</td>
                        <td>${item.subunit || ""}</td>
                        <td>${item.kategori}</td>
                        <td class="text-end">
                            ${todesimal(item.total)}
                        </td>
                        <td class="pe-4 text-end">
                            <button
                                class="btn btn-sm btn-light-primary btn-detail-shift"
                                data-nik="${item.nik}">
                                Detail
                            </button>
                        </td>
                    `;

                    fragment.appendChild(tr);

                });

                tbodySummary.appendChild(fragment);


                // ==========================
                // DATATABLE
                // ==========================

                updateProgress(
                    "Menyiapkan tabel...",
                    100
                );

                const tableDetail =
                    initDataTable(
                        "#dataperhitunganshift_table",
                        "#searchtable",50
                    );

                const tableSummary =
                    initDataTable(
                        "#dataperhitunganshiftname_table",
                        "#searchtablename",50
                    );


                // ==========================
                // SELESAI
                // ==========================

                setTimeout(() => {

                    Swal.fire({
                        icon: "success",
                        title: "Data Loaded",
                        text:
                            `${total.toLocaleString()} data berhasil diproses.`,
                        timer: 1500,
                        showConfirmButton: false
                    });

                }, 300);

            }


            // Mulai proses
            renderBatch();

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

}

$(document).on("click", ".btn-detail-shift", function () {

    const nik = $(this).data("nik");
    const data = window.globalShiftResult || [];

    let detailHtml = "";
    let totalNominal = 0;
    let no = 1;

    const filtered = data.filter(row => 
        String(row.NIK) === String(nik)
    );

    if (filtered.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Detail Found",
            text: "No detail records available for this employee."
        });
        return;
    }

    filtered.forEach(row => {

        totalNominal += parseFloat(row.NOMINALUANGSHIFT || 0);

        detailHtml += `
            <tr>
                <td class="ps-4">${no++}</td>
                <td>${row.PERIODETGL || ""}</td>
                <td>${row.NAMAHARI || ""}</td>
                <td>${row.FLAG || ""}</td>
                <td>${row.JAMMASUK || ""}</td>
                <td>${row.JAMPULANG || ""}</td>
                <td>${row.REALMASUK || ""}</td>
                <td>${row.REALPULANG || ""}</td>
                <td class="text-end">${todesimal(row.NOMINALUANGSHIFT)}</td>
                <td class="text-end pe-4">${row.HARILIBUR || ""}</td>
            </tr>
        `;
    });

    $("#resultdetailperhitunganshift").html(detailHtml);
    $("#totalnominaldetail").html(todesimal(totalNominal));

    const modal = new bootstrap.Modal(
        document.getElementById('modal_detailshift')
    );
    modal.show();
});