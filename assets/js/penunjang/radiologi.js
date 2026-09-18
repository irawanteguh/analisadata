let globaldatatindakanrad = [];

$("#btndownloaddatarad_table").on("click", function () {

    // =========================================
    // DATA SOURCE
    // =========================================
    const result = Array.isArray(globaldatatindakanrad)
        ? globaldatatindakanrad
        : [];

    if (result.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Records Found",
            text: "No radiology data available for export.",
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    // =========================================
    // TIPE DISTINCT
    // =========================================
    const tipe = [...new Set(
        result
            .map(function (item) {
                return item.TIPE;
            })
            .filter(function (value) {
                return value !== null &&
                       value !== undefined &&
                       value !== "";
            })
    )].sort(function (a, b) {
        return String(a).localeCompare(String(b));
    });

    // =========================================
    // KATEGORI DISTINCT
    // =========================================
    const kategori = [...new Set(
        result.map(function (item) {

            return item.NAMAGROUP !== null &&
                   item.NAMAGROUP !== undefined &&
                   item.NAMAGROUP !== ""
                ? item.NAMAGROUP
                : "LAIN-LAIN";

        })
    )].sort(function (a, b) {
        return String(a).localeCompare(String(b));
    });

    // =========================================
    // PEMERIKSAAN DISTINCT
    // =========================================
    const pemeriksaan = [...new Set(
        result
            .map(function (item) {
                return item.NAMA_PEMERIKSAAN;
            })
            .filter(function (value) {
                return value !== null &&
                       value !== undefined &&
                       value !== "";
            })
    )].sort(function (a, b) {
        return String(a).localeCompare(String(b));
    });

    // =========================================
    // SHEET 1 - KATEGORI
    // =========================================
    const dataKategori = kategori.map(function (namaKategori, index) {

        var total = 0;

        var row = {
            "No": index + 1,
            "Kategori": namaKategori
        };

        $.each(tipe, function (i, tipeValue) {

            var jumlah = result.filter(function (item) {

                var group =
                    item.NAMAGROUP !== null &&
                    item.NAMAGROUP !== undefined &&
                    item.NAMAGROUP !== ""
                        ? item.NAMAGROUP
                        : "LAIN-LAIN";

                return group === namaKategori &&
                       item.TIPE === tipeValue;

            }).length;

            row[tipeValue] = jumlah;
            total += jumlah;

        });

        row["TOTAL"] = total;

        return row;
    });

    // =========================================
    // SHEET 2 - NAMA PEMERIKSAAN
    // =========================================
    const dataPemeriksaan = pemeriksaan.map(function (namaPemeriksaan, index) {

        var total = 0;

        var row = {
            "No": index + 1,
            "Nama Pemeriksaan": namaPemeriksaan
        };

        $.each(tipe, function (i, tipeValue) {

            var jumlah = result.filter(function (item) {

                return item.NAMA_PEMERIKSAAN === namaPemeriksaan &&
                       item.TIPE === tipeValue;

            }).length;

            row[tipeValue] = jumlah;
            total += jumlah;

        });

        row["TOTAL"] = total;

        return row;
    });

    // =========================================
    // SHEET 3 - DETAIL PASIEN
    // =========================================
    const dataDetail = result.map(function (item, index) {

        return {
            "No": index + 1,
            "Tanggal Order": item.TANGGAL_ORDER || "",
            "Tanggal Approve": item.TANGGAL_APPROVE || "",
            "No RM": item.NO_RM || "",
            "Nama Pasien": item.NAMA_PASIEN || "",
            "No Register": item.NO_REGISTER || "",
            "No Rontgen": item.NO_RONTGEN || "",
            "ID Detail Radiologi": item.ID_DETAIL_RADIOLOGI || "",
            "Kode Pemeriksaan": item.KODE_PEMERIKSAAN || "",
            "Nama Pemeriksaan": item.NAMA_PEMERIKSAAN || "",
            "Kategori": item.NAMAGROUP || "LAIN-LAIN",
            "Kode Ruangan": item.KODE_RUANGAN_PENGIRIM || "",
            "Ruangan Pengirim": item.RUANGAN_PENGIRIM || "",
            "Tipe": item.TIPE || ""
        };

    });

    // =========================================
    // EXPORT MULTI SHEET
    // =========================================
    exportToExcel(
        null,
        "Tindakan Radiologi",
        "Tindakan_Radiologi.xlsx",
        {
            multiSheet: [
                {
                    name: "Kategori",
                    data: dataKategori,
                    formatter: function (item) {
                        return item;
                    }
                },
                {
                    name: "Nama Pemeriksaan",
                    data: dataPemeriksaan,
                    formatter: function (item) {
                        return item;
                    }
                },
                {
                    name: "Detail Pasien",
                    data: dataDetail,
                    formatter: function (item) {
                        return item;
                    }
                }
            ]
        }
    );

});

flatpickr('[name="dateperiode"]', {
    mode      : "range",
    enableTime: true,
    dateFormat: "d.m.Y H:i",
    maxDate   : "today",
    time_24hr : true,

    onChange: function (selectedDates, dateStr, instance) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleString('sv-SE').replace(' ', 'T') : null;
        endDate = selectedDates[1] ? selectedDates[1].toLocaleString('sv-SE').replace(' ', 'T') : null;
    }
});


$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();
    datapemeriksaan(startDate,endDate)
});

function datapemeriksaan(startDate,endDate){
    $.ajax({
        url       : url +"index.php/penunjang/radiologi/datapemeriksaan",
        data      : {startDate: startDate,endDate: endDate},
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

            $("#datadatapemeriksaandetail_table").empty();
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

            const result      = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatatindakanrad = result;

            // TIPE DISTINCT
            const tipe = [...new Set(
                result
                    .map(function(item) {
                        return item.TIPE;
                    })
                    .filter(function(value) {
                        return value !== null &&
                            value !== undefined &&
                            value !== "";
                    })
            )];

            // NAMA PEMERIKSAAN DISTINCT
            const pemeriksaan = [...new Set(
                result
                    .map(function(item) {
                        return item.NAMA_PEMERIKSAAN;
                    })
                    .filter(function(value) {
                        return value !== null &&
                            value !== undefined &&
                            value !== "";
                    })
            )].sort(function(a, b) {
                return a.localeCompare(b);
            });

            const kategori = [...new Set(
                result.map(function(item) {
                    return item.NAMAGROUP !== null &&
                        item.NAMAGROUP !== undefined &&
                        item.NAMAGROUP !== ""
                        ? item.NAMAGROUP
                        : "LAIN-LAIN";
                })
            )].sort(function(a, b) {
                return a.localeCompare(b);
            });

            var tablekategori = "";

            // =========================
            // THEAD
            // =========================
            tablekategori += "<thead class='align-middle'>";
            tablekategori += "<tr class='fw-bolder text-muted bg-light'>";

            tablekategori += "<th class='ps-4 rounded-start'>";
            tablekategori += "Kategori";
            tablekategori += "</th>";

            $.each(tipe, function(index, value) {

                tablekategori += "<th class='text-center'>";
                tablekategori += value;
                tablekategori += "</th>";

            });

            tablekategori += "<th class='pe-4 rounded-end text-end'>";
            tablekategori += "Total";
            tablekategori += "</th>";

            tablekategori += "</tr>";
            tablekategori += "</thead>";

            // =========================
            // TBODY
            // =========================
            tablekategori += "<tbody class='text-gray-600 fw-bold'>";

            $.each(kategori, function(index, namaKategori) {

                var totalKategori = 0;

                tablekategori += "<tr>";

                tablekategori += "<td class='ps-4'>";
                tablekategori += namaKategori;
                tablekategori += "</td>";

                $.each(tipe, function(index, tipeValue) {

                    var jumlahKategori = result.filter(function(item) {

                        var group = item.NAMAGROUP !== null &&
                                    item.NAMAGROUP !== undefined &&
                                    item.NAMAGROUP !== ""
                                ? item.NAMAGROUP
                                : "LAIN-LAIN";

                        return group === namaKategori &&
                            item.TIPE === tipeValue;

                    }).length;

                    totalKategori += jumlahKategori;

                    tablekategori += "<td class='text-center'>";
                    tablekategori += todesimal(jumlahKategori);
                    tablekategori += "</td>";

                });

                tablekategori += "<td class='pe-4 text-end fw-bold'>";
                tablekategori += todesimal(totalKategori);
                tablekategori += "</td>";

                tablekategori += "</tr>";

            });

            tablekategori += "</tbody>";

            var tableresult = "";

            // =========================
            // THEAD
            // =========================
            tableresult += "<thead class='align-middle'>";
            tableresult += "<tr class='fw-bolder text-muted bg-light'>";

            tableresult += "<th class='ps-4 rounded-start'>";
            tableresult += "Nama Pemeriksaan";
            tableresult += "</th>";

            $.each(tipe, function(index, value) {

                tableresult += "<th class='text-center'>";
                tableresult += value;
                tableresult += "</th>";

            });

            tableresult += "<th class='pe-4 rounded-end text-end'>";
            tableresult += "Total";
            tableresult += "</th>";

            tableresult += "</tr>";
            tableresult += "</thead>";

            // =========================
            // TBODY
            // =========================
            tableresult += "<tbody class='text-gray-600 fw-bold'>";

            $.each(pemeriksaan, function(index, namaPemeriksaan) {

                var total = 0;

                tableresult += "<tr>";

                // Nama Pemeriksaan
                tableresult += "<td class='ps-4'>";
                tableresult += namaPemeriksaan;
                tableresult += "</td>";

                // Per TIPE
                $.each(tipe, function(index, tipeValue) {

                    var jumlah = result.filter(function(item) {

                        return item.NAMA_PEMERIKSAAN === namaPemeriksaan &&
                            item.TIPE === tipeValue;

                    }).length;

                    total += jumlah;

                    tableresult += "<td class='text-center'>";
                    tableresult += todesimal(jumlah);
                    tableresult += "</td>";

                });

                // Total
                tableresult += "<td class='pe-4 text-end fw-bold'>";
                tableresult += todesimal(total);
                tableresult += "</td>";

                tableresult += "</tr>";

            });

            tableresult += "</tbody>";

            $("#datadatakategori_table").html(tablekategori);
            $("#datadatapemeriksaandetail_table").html(tableresult);

            const table1 = initDataTable("#datadatakategori_table","#searchtable",50);
            const table2 = initDataTable("#datadatapemeriksaandetail_table","#searchtable",50);
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