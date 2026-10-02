$("#modal_upload_txt_eklaim").on("show.bs.modal", function() {

    $("#filetxteklaim").val("");

    // Reset Summary
    $("#jmlDataEklaim").text("0");
    $("#totalTarifRSEklaim").text("Rp 0");
    $("#totalNilaiEklaim").text("Rp 0");
    $("#selisihTarifEklaim").text("Rp 0");

    // IDRG
    $("#totalTarifIDRGEklaim").text("Rp 0");
    $("#selisihTarifIDRGEklaim").text("Rp 0");

    // Reset Preview
    $("#headerPreviewtxtEklaim").empty();
    $("#resultpreviewtxteklaim").empty();

    // Reset Data
    window.dataTxtEklaim = [];
    window.headerTxtEklaim = [];

});

$("#filetxteklaim").on("change", function() {

    const input = this;
    const file = input.files[0];

    if (!file) return;

    // =========================================================
    // RESET SUMMARY
    // =========================================================
    $("#jmlDataEklaim").text("0");
    $("#totalNilaiEklaim").text("Rp 0");
    $("#totalTarifRSEklaim").text("Rp 0");
    $("#selisihTarifEklaim").text("Rp 0");

    $("#totalTarifIDRGEklaim").text("Rp 0");
    $("#selisihTarifIDRGEklaim").text("Rp 0");

    // =========================================================
    // RESET PREVIEW
    // =========================================================
    $("#headerPreviewtxtEklaim").empty();
    $("#resultpreviewtxteklaim").empty();

    window.dataTxtEklaim = [];
    window.headerTxtEklaim = [];

    // =========================================================
    // EXTENSION
    // =========================================================
    const extension = file.name.split(".").pop().toLowerCase();

    if (!["txt", "xlsx", "xls"].includes(extension)) {

        Swal.fire({
            icon: "warning",
            title: "Format File Tidak Sesuai",
            text: "Silakan pilih file TXT, Microsoft Excel Worksheet (.xlsx), atau Excel 97-2003 (.xls)."
        });

        $(input).val("");

        return;
    }

    // =========================================================
    // LOADING
    // =========================================================
    Swal.fire({
        title: "Membaca File",
        text: "Sedang membaca data E-Klaim...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });

    // =========================================================
    // PROSES DATA
    // =========================================================
    function prosesDataEklaim(headers, data, invalidRows) {

        window.headerTxtEklaim = headers;

        // =====================================================
        // INDEX KOLOM
        // =====================================================
        const indexSEP = headers.indexOf("SEP");
        const indexTarif = headers.indexOf("TARIF_INACBG");
        const indexTarifRS = headers.indexOf("TARIF_RS");
        const indexTarifIDRG = headers.indexOf("IDRG_TOTAL_TARIF");

        // =====================================================
        // REQUIRED HEADER
        // =====================================================
        const requiredHeaders = [
            {
                name: "SEP",
                index: indexSEP
            },
            {
                name: "TARIF_INACBG",
                index: indexTarif
            },
            {
                name: "TARIF_RS",
                index: indexTarifRS
            },
            {
                name: "IDRG_TOTAL_TARIF",
                index: indexTarifIDRG
            }
        ];

        const missingHeaders = requiredHeaders
            .filter(function(item) {
                return item.index === -1;
            })
            .map(function(item) {
                return item.name;
            });

        if (missingHeaders.length > 0) {

            Swal.fire({
                icon: "error",
                title: "Kolom Tidak Lengkap",
                html:
                    "Kolom berikut tidak ditemukan pada file:" +
                    "<br><br>" +
                    "<strong>" +
                    missingHeaders.join(", ") +
                    "</strong>"
            });

            window.headerTxtEklaim = [];
            window.dataTxtEklaim = [];

            return;
        }

        // =====================================================
        // FILTER DATA VALID
        // =====================================================
        const validData = [];
        const filteredRows = [];

        data.forEach(function(row, index) {

            const sep = String(row[indexSEP] || "").trim();

            if (sep === "") {

                filteredRows.push({
                    line: index + 2,
                    reason: "SEP kosong"
                });

                return;
            }

            validData.push(row);
        });

        data = validData;
        invalidRows = invalidRows.concat(filteredRows);

        // =====================================================
        // TIDAK ADA DATA VALID
        // =====================================================
        if (data.length === 0) {

            Swal.fire({
                icon: "warning",
                title: "Data Tidak Valid",
                text: "Tidak terdapat data E-Klaim yang valid untuk diproses."
            });

            window.headerTxtEklaim = [];
            window.dataTxtEklaim = [];

            return;
        }

        // =====================================================
        // HEADER PREVIEW
        // =====================================================
        let headerHtml = "<tr class='fw-bolder'>";

        headers.forEach(function(header, index) {

            let className = "bg-dark text-white text-nowrap";

            if (index === 0) {
                className += " ps-4";
            }

            if (index === headers.length - 1) {
                className += " pe-4";
            }

            headerHtml +=
                "<th class='" +
                className +
                "'>" +
                escapeHtml(header) +
                "</th>";
        });

        headerHtml += "</tr>";

        $("#headerPreviewtxtEklaim").html(headerHtml);

        // =====================================================
        // SIMPAN DATA
        // =====================================================
        window.dataTxtEklaim = data;

        $("#jmlDataEklaim").text(
            data.length.toLocaleString("id-ID")
        );

        // =====================================================
        // TOTAL
        // =====================================================
        let totalTarifINACBG = 0;
        let totalTarifRS = 0;
        let totalTarifIDRG = 0;

        data.forEach(function(row) {

            totalTarifINACBG += parseNominalEklaim(
                row[indexTarif]
            );

            totalTarifRS += parseNominalEklaim(
                row[indexTarifRS]
            );

            totalTarifIDRG += parseNominalEklaim(
                row[indexTarifIDRG]
            );

        });

        // =====================================================
        // SELISIH
        //
        // RS - INA-CBG
        // RS - IDRG
        // =====================================================
        const selisihTarifINACBG =
            totalTarifINACBG - totalTarifRS;

        const selisihTarifIDRG =
            totalTarifIDRG - totalTarifRS;

        // =====================================================
        // TAMPILKAN TOTAL
        // =====================================================
        $("#totalNilaiEklaim").text(
            "Rp " +
            totalTarifINACBG.toLocaleString("id-ID")
        );

        $("#totalTarifRSEklaim").text(
            "Rp " +
            totalTarifRS.toLocaleString("id-ID")
        );

        $("#totalTarifIDRGEklaim").text(
            "Rp " +
            totalTarifIDRG.toLocaleString("id-ID")
        );

        // =====================================================
        // WARNA SELISIH INA-CBG
        // =====================================================
        const warnaSelisihINACBG =
            selisihTarifINACBG >= 0
                ? "text-success"
                : "text-danger";

        $("#selisihTarifEklaim")
            .removeClass("text-success text-danger")
            .addClass(warnaSelisihINACBG)
            .text(
                "Rp " +
                selisihTarifINACBG.toLocaleString("id-ID")
            );

        // =====================================================
        // WARNA SELISIH IDRG
        // =====================================================
        const warnaSelisihIDRG =
            selisihTarifIDRG >= 0
                ? "text-success"
                : "text-danger";

        $("#selisihTarifIDRGEklaim")
            .removeClass("text-success text-danger")
            .addClass(warnaSelisihIDRG)
            .text(
                "Rp " +
                selisihTarifIDRG.toLocaleString("id-ID")
            );

        // =====================================================
        // PREVIEW MAKSIMAL 100 DATA
        // =====================================================
        let bodyHtml = "";

        data.slice(0, 100).forEach(function(row) {

            bodyHtml += "<tr>";

            headers.forEach(function(header, columnIndex) {

                const value =
                    row[columnIndex] === null ||
                    row[columnIndex] === undefined
                        ? ""
                        : row[columnIndex];

                bodyHtml +=
                    "<td class='text-nowrap'>" +
                    escapeHtml(value) +
                    "</td>";
            });

            bodyHtml += "</tr>";
        });

        $("#resultpreviewtxteklaim").html(bodyHtml);

        Swal.close();

        // =====================================================
        // WARNING DATA YANG DILEWATI
        // =====================================================
        if (invalidRows.length > 0) {

            setTimeout(function() {

                Swal.fire({

                    icon: "warning",

                    title: "Data Berhasil Dibaca",

                    html:
                        "Data valid: <strong>" +
                        data.length.toLocaleString("id-ID") +
                        "</strong>" +
                        "<br>" +
                        "Data dilewati: <strong>" +
                        invalidRows.length.toLocaleString("id-ID") +
                        "</strong>",

                    confirmButtonText: "OK"
                });

            }, 300);
        }
    }

    // =========================================================
    // PARSE NOMINAL
    // =========================================================
    function parseNominalEklaim(value) {

        if (
            value === null ||
            value === undefined ||
            value === ""
        ) {
            return 0;
        }

        if (typeof value === "number") {
            return isFinite(value) ? value : 0;
        }

        value = String(value).trim();

        if (value === "") {
            return 0;
        }

        value = value.replace(/[^\d,.-]/g, "");

        if (
            value.indexOf(",") !== -1 &&
            value.indexOf(".") !== -1
        ) {

            const lastComma = value.lastIndexOf(",");
            const lastDot = value.lastIndexOf(".");

            if (lastComma > lastDot) {

                value = value
                    .replace(/\./g, "")
                    .replace(",", ".");

            } else {

                value = value.replace(/,/g, "");
            }

        } else if (value.indexOf(",") !== -1) {

            const parts = value.split(",");

            if (
                parts.length === 2 &&
                parts[1].length <= 2
            ) {

                value = value.replace(",", ".");

            } else {

                value = value.replace(/,/g, "");
            }

        } else if (value.indexOf(".") !== -1) {

            const parts = value.split(".");

            if (
                !(
                    parts.length === 2 &&
                    parts[1].length <= 2
                )
            ) {

                value = value.replace(/\./g, "");
            }
        }

        const result = parseFloat(value);

        return isNaN(result) ? 0 : result;
    }

    // =========================================================
    // TXT
    // =========================================================
    if (extension === "txt") {

        const reader = new FileReader();

        reader.onload = function(e) {

            try {

                let text = e.target.result || "";

                text = text
                    .replace(/^\uFEFF/, "")
                    .replace(/\r\n/g, "\n")
                    .replace(/\r/g, "\n");

                const lines = text
                    .split("\n")
                    .filter(function(line) {
                        return line.trim() !== "";
                    });

                if (lines.length < 2) {

                    Swal.fire({
                        icon: "warning",
                        title: "Data Tidak Ditemukan",
                        text: "File TXT tidak memiliki data E-Klaim."
                    });

                    return;
                }

                const headers = lines[0]
                    .replace(/^\uFEFF/, "")
                    .split("\t")
                    .map(function(header) {
                        return header.trim();
                    });

                const indexSEP = headers.indexOf("SEP");
                const data = [];
                const invalidRows = [];

                if (indexSEP === -1) {

                    Swal.fire({
                        icon: "error",
                        title: "Kolom Tidak Ditemukan",
                        text: "Kolom SEP tidak ditemukan pada file TXT E-Klaim."
                    });

                    return;
                }

                for (let i = 1; i < lines.length; i++) {

                    const row = lines[i].split("\t");

                    if (row.length !== headers.length) {

                        invalidRows.push({
                            line: i + 1,
                            column: row.length,
                            reason: "Jumlah kolom tidak sesuai"
                        });

                        continue;
                    }

                    const sep =
                        String(row[indexSEP] || "").trim();

                    if (sep === "") {

                        invalidRows.push({
                            line: i + 1,
                            column: row.length,
                            reason: "SEP kosong"
                        });

                        continue;
                    }

                    data.push(row);
                }

                prosesDataEklaim(
                    headers,
                    data,
                    invalidRows
                );

            } catch (error) {

                console.error(
                    "ERROR READ TXT E-KLAIM:",
                    error
                );

                Swal.fire({
                    icon: "error",
                    title: "Gagal Membaca File",
                    text: "Terjadi kesalahan saat membaca file TXT E-Klaim."
                });

                window.dataTxtEklaim = [];
                window.headerTxtEklaim = [];
            }
        };

        reader.onerror = function() {

            Swal.fire({
                icon: "error",
                title: "Gagal Membaca File",
                text: "File TXT tidak dapat dibaca."
            });

            window.dataTxtEklaim = [];
            window.headerTxtEklaim = [];
        };

        reader.readAsText(file);

        return;
    }

    // =========================================================
    // EXCEL
    // =========================================================
    const readerExcel = new FileReader();

    readerExcel.onload = function(e) {

        try {

            if (typeof XLSX === "undefined") {

                Swal.fire({
                    icon: "error",
                    title: "Library Excel Tidak Ditemukan",
                    text: "Library SheetJS XLSX belum dimuat pada halaman."
                });

                return;
            }

            const workbook = XLSX.read(
                e.target.result,
                {
                    type: "array",
                    cellDates: false,
                    cellNF: false,
                    cellText: true
                }
            );

            if (
                !workbook.SheetNames ||
                workbook.SheetNames.length === 0
            ) {

                Swal.fire({
                    icon: "warning",
                    title: "Sheet Tidak Ditemukan",
                    text: "File Excel tidak memiliki worksheet."
                });

                return;
            }

            const worksheet =
                workbook.Sheets[
                    workbook.SheetNames[0]
                ];

            const rows =
                XLSX.utils.sheet_to_json(
                    worksheet,
                    {
                        header: 1,
                        defval: "",
                        raw: true
                    }
                );

            if (!rows || rows.length < 2) {

                Swal.fire({
                    icon: "warning",
                    title: "Data Tidak Ditemukan",
                    text: "File Excel tidak memiliki data E-Klaim."
                });

                return;
            }

            const headers = rows[0].map(function(header) {

                return String(
                    header === null ||
                    header === undefined
                        ? ""
                        : header
                ).trim();

            });

            const indexSEP = headers.indexOf("SEP");
            const data = [];
            const invalidRows = [];

            if (indexSEP === -1) {

                Swal.fire({
                    icon: "error",
                    title: "Kolom Tidak Ditemukan",
                    text: "Kolom SEP tidak ditemukan pada file Excel E-Klaim."
                });

                return;
            }

            for (let i = 1; i < rows.length; i++) {

                const row = rows[i];

                if (
                    !row ||
                    row.every(function(value) {

                        return String(
                            value === null ||
                            value === undefined
                                ? ""
                                : value
                        ).trim() === "";

                    })
                ) {
                    continue;
                }

                if (row.length > headers.length) {

                    invalidRows.push({
                        line: i + 1,
                        column: row.length,
                        reason: "Jumlah kolom tidak sesuai"
                    });

                    continue;
                }

                const normalizedRow = [];

                for (
                    let j = 0;
                    j < headers.length;
                    j++
                ) {

                    const value = row[j];

                    normalizedRow.push(
                        value === null ||
                        value === undefined
                            ? ""
                            : value
                    );
                }

                const sep =
                    String(
                        normalizedRow[indexSEP] || ""
                    ).trim();

                if (sep === "") {

                    invalidRows.push({
                        line: i + 1,
                        column: normalizedRow.length,
                        reason: "SEP kosong"
                    });

                    continue;
                }

                data.push(normalizedRow);
            }

            prosesDataEklaim(
                headers,
                data,
                invalidRows
            );

        } catch (error) {

            console.error(
                "ERROR READ EXCEL E-KLAIM:",
                error
            );

            Swal.fire({
                icon: "error",
                title: "Gagal Membaca File",
                text: "File Excel tidak dapat dibaca."
            });

            window.dataTxtEklaim = [];
            window.headerTxtEklaim = [];
        }
    };

    readerExcel.onerror = function() {

        Swal.fire({
            icon: "error",
            title: "Gagal Membaca File",
            text: "File Excel tidak dapat dibaca."
        });

        window.dataTxtEklaim = [];
        window.headerTxtEklaim = [];
    };

    readerExcel.readAsArrayBuffer(file);

});

function escapeHtml(text) {
    return String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
};

function formatDuration(seconds) {
    seconds = Math.max(0, Math.round(seconds));

    let h = Math.floor(seconds / 3600);
    let m = Math.floor((seconds % 3600) / 60);
    let s = seconds % 60;

    if (h > 0) {return h + " hour " + m + " minute " + s + " second";}
    if (m > 0) {return m + " minute " + s + " second";}
    return s + " second";
};

$("#btnImportTxtEklaim").on("click", function() {
    const data = Array.isArray(window.dataTxtEklaim) ? window.dataTxtEklaim : [];

    if (data.length === 0) {
        Swal.fire({
            icon : "warning",
            title: "No Data Available",
            text : "Please select and preview a TXT E-Klaim file before proceeding."
        });
        return;
    }

    Swal.fire({
        title             : "Import Data E-Klaim",
        html              : "Are you sure you want to import <strong>" + data.length.toLocaleString("id-ID") + "</strong> records?",
        icon              : "question",
        showCancelButton  : true,
        confirmButtonText : "Yes, Import",
        cancelButtonText  : "Cancel",
        confirmButtonColor: "#0d6efd",
        cancelButtonColor : "#6c757d"
    }).then(function(result) {
        if (result.isConfirmed) prosesImportTxtEklaim(data);
    });
});

function prosesImportTxtEklaim(data) {

    data = Array.isArray(data) ? data : [];

    const headers = Array.isArray(window.headerTxtEklaim)
        ? window.headerTxtEklaim
        : [];

    const total = data.length;
    let index = 0;

    const batchSize = 500;
    const startTime = Date.now();

    // =========================================================
    // AKUMULASI HASIL SELURUH BATCH
    // =========================================================
    let totalSuccess = 0;
    let totalFailed  = 0;

    // =========================================================
    // VALIDASI DATA
    // =========================================================
    if (total === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Data Available",
            text: "There is no TXT E-Klaim data to import."
        });
        return;
    }

    if (headers.length === 0) {
        Swal.fire({
            icon: "error",
            title: "Header Tidak Ditemukan",
            text: "Header file E-Klaim tidak tersedia."
        });
        return;
    }

    // =========================================================
    // RESET PROGRESS
    // =========================================================
    $("#importProgressTxtEklaim").text("0");
    $("#importSpeedTxtEklaim").text("0");
    $("#importEtaTxtEklaim").text("Calculating...");

    // =========================================================
    // PROGRESS MODAL
    // =========================================================
    Swal.fire({

        title: "Importing TXT E-Klaim Data",

        html: `
            <div class="text-center">

                <div class="fs-5 mb-3">
                    Please wait...
                </div>

                <div class="fs-3 fw-bold text-primary">
                    <span id="importProgressTxtEklaim">0</span>
                    /
                    ${total.toLocaleString("id-ID")}
                </div>

                <div class="progress mt-3" style="height:10px;">
                    <div
                        id="importProgressBarTxtEklaim"
                        class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                        role="progressbar"
                        style="width:0%"
                        aria-valuenow="0"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>

                <div class="mt-3 small text-muted">

                    <div>
                        Processing Speed :
                        <span id="importSpeedTxtEklaim">0</span>
                        records/sec
                    </div>

                    <div>
                        Estimated Time Remaining :
                        <span id="importEtaTxtEklaim">
                            Calculating...
                        </span>
                    </div>

                </div>

            </div>
        `,

        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,

        didOpen: function() {

            Swal.showLoading();

            kirimBatchTxtEklaim();
        }
    });

    // =========================================================
    // KIRIM BATCH
    // =========================================================
    function kirimBatchTxtEklaim() {

        const batch = data.slice(
            index,
            index + batchSize
        );

        if (batch.length === 0) {
            return;
        }

        $.ajax({

            url: url + "index.php/urbpjs/syncurbpjsrj/importtxteklaim",

            type: "POST",

            dataType: "JSON",

            data: {
                headers: JSON.stringify(headers),
                data: JSON.stringify(batch)
            },

            success: function(res) {

                // =================================================
                // RESPONSE ERROR
                // =================================================
                if (!res || res.responCode !== "00") {

                    Swal.fire({
                        icon: "error",
                        title: "Import Failed",
                        text: res && res.responMsg
                            ? res.responMsg
                            : "Failed to import TXT E-Klaim data."
                    });

                    return;
                }

                // =================================================
                // HASIL BATCH
                // =================================================
                const batchResult = res.responResult || {};

                const batchSuccess = Number(
                    batchResult.success || 0
                );

                const batchFailed = Number(
                    batchResult.failed || 0
                );

                // =================================================
                // AKUMULASI HASIL
                // =================================================
                totalSuccess += batchSuccess;
                totalFailed  += batchFailed;

                console.log(
                    "BATCH:",
                    index + " - " + (index + batch.length),
                    "SUCCESS:",
                    batchSuccess,
                    "FAILED:",
                    batchFailed,
                    "TOTAL SUCCESS:",
                    totalSuccess,
                    "TOTAL FAILED:",
                    totalFailed
                );

                // =================================================
                // UPDATE INDEX
                // =================================================
                index += batch.length;

                // =================================================
                // UPDATE PROGRESS
                // =================================================
                $("#importProgressTxtEklaim").text(
                    index.toLocaleString("id-ID")
                );

                // =================================================
                // PROGRESS %
                // =================================================
                const percent = total > 0
                    ? (index / total) * 100
                    : 0;

                $("#importProgressBarTxtEklaim")
                    .css("width", percent + "%")
                    .attr("aria-valuenow", percent);

                // =================================================
                // PROCESSING SPEED
                // =================================================
                const elapsed =
                    (Date.now() - startTime) / 1000;

                const speed =
                    elapsed > 0
                        ? index / elapsed
                        : 0;

                $("#importSpeedTxtEklaim").text(
                    speed.toFixed(2)
                );

                // =================================================
                // ETA
                // =================================================
                const remaining =
                    total - index;

                const eta =
                    speed > 0
                        ? remaining / speed
                        : 0;

                $("#importEtaTxtEklaim").text(
                    remaining > 0
                        ? formatDuration(eta)
                        : "Completed"
                );

                // =================================================
                // MASIH ADA DATA
                // =================================================
                if (index < total) {

                    kirimBatchTxtEklaim();

                    return;
                }

                // =================================================
                // IMPORT SELESAI
                // =================================================
                $("#importProgressBarTxtEklaim")
                    .css("width", "100%")
                    .attr("aria-valuenow", 100);

                $("#importProgressTxtEklaim").text(
                    total.toLocaleString("id-ID")
                );

                $("#importEtaTxtEklaim").text(
                    "Completed"
                );

                // =================================================
                // HASIL FINAL
                // =================================================
                setTimeout(function() {
                    const totalResult   = total;
                    const successResult = totalSuccess;
                    const failedResult  = totalFailed;

                    // =================================================
                    // SWAL RESULT
                    // =================================================
                    Swal.fire({
                        icon: failedResult > 0 ? "warning" : "success",
                        title: failedResult > 0 ? "Import Completed with Warnings" : "Import Successfully Completed",
                        html: `
                            <div class="text-center">
                                <div class="text-muted mb-3">
                                    The E-Klaim data import process has been completed.
                                </div>
                                <div class="border rounded p-3 text-start">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">
                                            Total Records
                                        </span>
                                        <strong>
                                            ${totalResult.toLocaleString("en-US")}
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">
                                            Successfully Processed
                                        </span>
                                        <strong class="text-success">
                                            ${successResult.toLocaleString("en-US")}
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">
                                            Failed to Process
                                        </span>
                                        <strong class="text-danger">
                                            ${failedResult.toLocaleString("en-US")}
                                        </strong>
                                    </div>
                                </div>
                                <div class="alert ${failedResult > 0 ? "alert-warning" : "alert-success"} py-2 mt-3 mb-0">
                                    ${failedResult > 0? `<strong>${failedResult.toLocaleString("en-US")}</strong> record(s) could not be processed. Please review the affected data.` : `All records have been successfully processed and saved to the system.`}
                                </div>
                            </div>
                        `,
                        confirmButtonText : "Done",
                        confirmButtonColor: "#009EF7",
                        allowOutsideClick : false,
                        allowEscapeKey    : false,
                        width             : 450,
                        timer             : 3000,
                        timerProgressBar  : true,
                        willClose: function() {
                            window.dataTxtEklaim = [];
                            window.headerTxtEklaim = [];

                            $("#filetxteklaim").val("");
                            $("#jmlDataEklaim").text("0");
                            $("#totalTarifRSEklaim").text("Rp 0");
                            $("#totalNilaiEklaim").text("Rp 0");
                            $("#selisihTarifEklaim").text("Rp 0");
                            $("#totalTarifIDRGEklaim").text("Rp 0");
                            $("#selisihTarifIDRGEklaim").text("Rp 0");
                            $("#headerPreviewtxtEklaim").empty();
                            $("#resultpreviewtxteklaim").empty();
                            $("#modal_upload_txt_eklaim").modal("hide");

                            dataeklaim();
                        }
                    });
                }, 200);
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon             : "error",
                    title            : "Request Failed",
                    text             : "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                    confirmButtonText: "OK"
                });
            }
        });
    }
}
