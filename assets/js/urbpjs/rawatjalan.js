load();

$("#modal_upload_txt_eklaim").on("show.bs.modal", function() {
    $("#filetxteklaim").val("");
    $("#jmlDataEklaim").text("0");
    $("#totalNilaiEklaim").text("Rp 0");
    $("#totalTarifRSEklaim").text("Rp 0");
    $("#selisihTarifEklaim").text("Rp 0");
    $("#headerPreviewtxtEklaim").empty();
    $("#resultpreviewtxteklaim").empty();
    window.dataTxtEklaim = [];
    window.headerTxtEklaim = [];
});

$("#filetxteklaim").on("change", function() {
    const input = this, file = input.files[0];
    if (!file) return;

    $("#jmlDataEklaim").text("0");
    $("#totalNilaiEklaim").text("Rp 0");
    $("#totalTarifRSEklaim").text("Rp 0");
    $("#selisihTarifEklaim").text("Rp 0");
    $("#headerPreviewtxtEklaim").empty();
    $("#resultpreviewtxteklaim").empty();
    window.dataTxtEklaim = [];
    window.headerTxtEklaim = [];

    const extension = file.name.split(".").pop().toLowerCase();

    if (!["txt", "xlsx", "xls"].includes(extension)) {
        Swal.fire({ icon: "warning", title: "Format File Tidak Sesuai", text: "Silakan pilih file TXT, Microsoft Excel Worksheet (.xlsx), atau Excel 97-2003 (.xls)." });
        $(input).val("");
        return;
    }

    setTimeout(function() { input.blur(); }, 0);

    Swal.fire({
        title: "Membaca File",
        text: "Sedang membaca data E-Klaim...",
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: function() { Swal.showLoading(); }
    });

    function prosesDataEklaim(headers, data, invalidRows) {
        window.headerTxtEklaim = headers;

        const indexSEP = headers.indexOf("SEP"), indexTarif = headers.indexOf("TARIF_INACBG"), indexTarifRS = headers.indexOf("TARIF_RS");
        const requiredHeaders = [{ name: "SEP", index: indexSEP }, { name: "TARIF_INACBG", index: indexTarif }, { name: "TARIF_RS", index: indexTarifRS }];
        const missingHeaders = requiredHeaders.filter(function(item) { return item.index === -1; }).map(function(item) { return item.name; });

        if (missingHeaders.length > 0) {
            Swal.fire({ icon: "error", title: "Kolom Tidak Lengkap", html: "Kolom berikut tidak ditemukan pada file:<br><br><strong>" + missingHeaders.join(", ") + "</strong>" });
            window.headerTxtEklaim = [];
            window.dataTxtEklaim = [];
            return;
        }

        const validData = [], filteredRows = [];

        data.forEach(function(row, index) {
            const sep = String(row[indexSEP] || "").trim();

            if (sep === "") {
                filteredRows.push({ line: index + 2, reason: "SEP kosong" });
                return;
            }

            validData.push(row);
        });

        data = validData;
        invalidRows = invalidRows.concat(filteredRows);

        if (data.length === 0) {
            Swal.fire({ icon: "warning", title: "Data Tidak Valid", text: "Tidak terdapat data E-Klaim yang valid untuk diproses." });
            window.headerTxtEklaim = [];
            window.dataTxtEklaim = [];
            return;
        }

        let headerHtml = "<tr class='fw-bolder'>";

        headers.forEach(function(header, index) {
            let className = "bg-dark text-white text-nowrap";
            if (index === 0) className += " ps-4";
            if (index === headers.length - 1) className += " pe-4";
            headerHtml += "<th class='" + className + "'>" + escapeHtml(header) + "</th>";
        });

        headerHtml += "</tr>";
        $("#headerPreviewtxtEklaim").html(headerHtml);
        window.dataTxtEklaim = data;
        $("#jmlDataEklaim").text(data.length.toLocaleString("id-ID"));

        let totalTarif = 0, totalTarifRS = 0;

        data.forEach(function(row) {
            totalTarif += parseNominalEklaim(row[indexTarif]);
            totalTarifRS += parseNominalEklaim(row[indexTarifRS]);
        });

        const selisihTarif = totalTarif - totalTarifRS;
        const warnaSelisih = selisihTarif >= 0 ? "text-success" : "text-danger";

        $("#totalNilaiEklaim").text("Rp " + totalTarif.toLocaleString("id-ID"));
        $("#totalTarifRSEklaim").text("Rp " + totalTarifRS.toLocaleString("id-ID"));
        $("#selisihTarifEklaim").removeClass("text-success text-danger").addClass(warnaSelisih).text("Rp " + selisihTarif.toLocaleString("id-ID"));

        let bodyHtml = "";

        data.slice(0, 100).forEach(function(row) {
            bodyHtml += "<tr>";

            headers.forEach(function(header, columnIndex) {
                const value = row[columnIndex] === null || row[columnIndex] === undefined ? "" : row[columnIndex];
                bodyHtml += "<td class='text-nowrap'>" + escapeHtml(value) + "</td>";
            });

            bodyHtml += "</tr>";
        });

        $("#resultpreviewtxteklaim").html(bodyHtml);
        Swal.close();

        if (invalidRows.length > 0) {
            setTimeout(function() {
                Swal.fire({
                    icon: "warning",
                    title: "Data Berhasil Dibaca",
                    html: "Data valid: <strong>" + data.length.toLocaleString("id-ID") + "</strong><br>Data dilewati: <strong>" + invalidRows.length.toLocaleString("id-ID") + "</strong>",
                    confirmButtonText: "OK"
                });
            }, 300);
        }
    }

    function parseNominalEklaim(value) {
        if (value === null || value === undefined || value === "") return 0;
        if (typeof value === "number") return isFinite(value) ? value : 0;

        value = String(value).trim();
        if (value === "") return 0;

        value = value.replace(/[^\d,.-]/g, "");

        if (value.indexOf(",") !== -1 && value.indexOf(".") !== -1) {
            const lastComma = value.lastIndexOf(","), lastDot = value.lastIndexOf(".");
            value = lastComma > lastDot ? value.replace(/\./g, "").replace(",", ".") : value.replace(/,/g, "");
        } else if (value.indexOf(",") !== -1) {
            const parts = value.split(",");
            value = parts.length === 2 && parts[1].length <= 2 ? value.replace(",", ".") : value.replace(/,/g, "");
        } else if (value.indexOf(".") !== -1) {
            const parts = value.split(".");
            if (!(parts.length === 2 && parts[1].length <= 2)) value = value.replace(/\./g, "");
        }

        const result = parseFloat(value);
        return isNaN(result) ? 0 : result;
    }

    if (extension === "txt") {
        const reader = new FileReader();

        reader.onload = function(e) {
            try {
                let text = e.target.result || "";
                text = text.replace(/^\uFEFF/, "").replace(/\r\n/g, "\n").replace(/\r/g, "\n");

                const lines = text.split("\n").filter(function(line) { return line.trim() !== ""; });

                if (lines.length < 2) {
                    Swal.fire({ icon: "warning", title: "Data Tidak Ditemukan", text: "File TXT tidak memiliki data E-Klaim." });
                    return;
                }

                const headers = lines[0].replace(/^\uFEFF/, "").split("\t").map(function(header) { return header.trim(); });
                const indexSEP = headers.indexOf("SEP"), data = [], invalidRows = [];

                if (indexSEP === -1) {
                    Swal.fire({ icon: "error", title: "Kolom Tidak Ditemukan", text: "Kolom SEP tidak ditemukan pada file TXT E-Klaim." });
                    return;
                }

                for (let i = 1; i < lines.length; i++) {
                    const row = lines[i].split("\t");

                    if (row.length !== headers.length) {
                        invalidRows.push({ line: i + 1, column: row.length, reason: "Jumlah kolom tidak sesuai" });
                        continue;
                    }

                    const sep = String(row[indexSEP] || "").trim();

                    if (sep === "") {
                        invalidRows.push({ line: i + 1, column: row.length, reason: "SEP kosong" });
                        continue;
                    }

                    data.push(row);
                }

                prosesDataEklaim(headers, data, invalidRows);
            } catch (error) {
                console.error("ERROR READ TXT E-KLAIM:", error);
                Swal.fire({ icon: "error", title: "Gagal Membaca File", text: "Terjadi kesalahan saat membaca file TXT E-Klaim." });
                window.dataTxtEklaim = [];
                window.headerTxtEklaim = [];
            }
        };

        reader.onerror = function() {
            Swal.fire({ icon: "error", title: "Gagal Membaca File", text: "File TXT tidak dapat dibaca." });
            window.dataTxtEklaim = [];
            window.headerTxtEklaim = [];
        };

        reader.readAsText(file);
        return;
    }

    const readerExcel = new FileReader();

    readerExcel.onload = function(e) {
        try {
            if (typeof XLSX === "undefined") {
                Swal.fire({ icon: "error", title: "Library Excel Tidak Ditemukan", text: "Library SheetJS XLSX belum dimuat pada halaman." });
                return;
            }

            const workbook = XLSX.read(e.target.result, { type: "array", cellDates: false, cellNF: false, cellText: true });

            if (!workbook.SheetNames || workbook.SheetNames.length === 0) {
                Swal.fire({ icon: "warning", title: "Sheet Tidak Ditemukan", text: "File Excel tidak memiliki worksheet." });
                return;
            }

            const worksheet = workbook.Sheets[workbook.SheetNames[0]];
            const rows = XLSX.utils.sheet_to_json(worksheet, { header: 1, defval: "", raw: true });

            if (!rows || rows.length < 2) {
                Swal.fire({ icon: "warning", title: "Data Tidak Ditemukan", text: "File Excel tidak memiliki data E-Klaim." });
                return;
            }

            const headers = rows[0].map(function(header) {
                return String(header === null || header === undefined ? "" : header).trim();
            });

            const indexSEP = headers.indexOf("SEP"), data = [], invalidRows = [];

            if (indexSEP === -1) {
                Swal.fire({ icon: "error", title: "Kolom Tidak Ditemukan", text: "Kolom SEP tidak ditemukan pada file Excel E-Klaim." });
                return;
            }

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];

                if (!row || row.every(function(value) {
                    return String(value === null || value === undefined ? "" : value).trim() === "";
                })) continue;

                if (row.length > headers.length) {
                    invalidRows.push({ line: i + 1, column: row.length, reason: "Jumlah kolom tidak sesuai" });
                    continue;
                }

                const normalizedRow = [];

                for (let j = 0; j < headers.length; j++) {
                    const value = row[j];
                    normalizedRow.push(value === null || value === undefined ? "" : value);
                }

                const sep = String(normalizedRow[indexSEP] || "").trim();

                if (sep === "") {
                    invalidRows.push({ line: i + 1, column: normalizedRow.length, reason: "SEP kosong" });
                    continue;
                }

                data.push(normalizedRow);
            }

            prosesDataEklaim(headers, data, invalidRows);
        } catch (error) {
            console.error("ERROR READ EXCEL E-KLAIM:", error);
            Swal.fire({ icon: "error", title: "Gagal Membaca File", text: "File Excel tidak dapat dibaca." });
            window.dataTxtEklaim = [];
            window.headerTxtEklaim = [];
        }
    };

    readerExcel.onerror = function() {
        Swal.fire({ icon: "error", title: "Gagal Membaca File", text: "File Excel tidak dapat dibaca." });
        window.dataTxtEklaim = [];
        window.headerTxtEklaim = [];
    };

    readerExcel.readAsArrayBuffer(file);
});

$("#btnImportTxtEklaim").on("click", function() {
    const data = Array.isArray(window.dataTxtEklaim) ? window.dataTxtEklaim : [];

    if (data.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Data Available",
            text: "Please select and preview a TXT E-Klaim file before proceeding."
        });
        return;
    }

    Swal.fire({
        title: "Import Data E-Klaim",
        html: "Are you sure you want to import <strong>" + data.length.toLocaleString("id-ID") + "</strong> records?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Import",
        cancelButtonText: "Cancel",
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#6c757d"
    }).then(function(result) {
        if (result.isConfirmed) prosesImportTxtEklaim(data);
    });
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

function prosesImportTxtEklaim(data) {
    data = Array.isArray(data) ? data : [];
    const headers = Array.isArray(window.headerTxtEklaim) ? window.headerTxtEklaim : [];
    const total = data.length;
    let index = 0;
    const batchSize = 500;
    const startTime = Date.now();

    if (total === 0) {
        Swal.fire({ icon: "warning", title: "No Data Available", text: "There is no TXT E-Klaim data to import." });
        return;
    }

    if (headers.length === 0) {
        Swal.fire({ icon: "error", title: "Header Tidak Ditemukan", text: "Header file E-Klaim tidak tersedia." });
        return;
    }

    Swal.fire({
        title: "Importing TXT E-Klaim Data",
        html: `
            <div class="text-center">
                <div class="fs-5 mb-3">Please wait...</div>
                <div class="fs-3 fw-bold text-primary"><span id="importProgressTxtEklaim">0</span> / ${total.toLocaleString("id-ID")}</div>
                <div class="progress mt-3" style="height:10px;"><div id="importProgressBarTxtEklaim" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width:0%"></div></div>
                <div class="mt-3 small text-muted">
                    <div>Processing Speed : <span id="importSpeedTxtEklaim">0</span> records/sec</div>
                    <div>Estimated Time Remaining : <span id="importEtaTxtEklaim">Calculating...</span></div>
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

    function kirimBatchTxtEklaim() {
        const batch = data.slice(index, index + batchSize);
        if (batch.length === 0) return;

        $.ajax({
            url: url + "index.php/urbpjs/syncurbpjsrj/importtxteklaim",
            type: "POST",
            dataType: "JSON",
            data: { headers: JSON.stringify(headers), data: JSON.stringify(batch) },
            success: function(res) {
                if (res.responCode !== "00") {
                    Swal.fire({ icon: "error", title: "Import Failed", text: res.responMsg || "Failed to import TXT E-Klaim data." });
                    return;
                }

                index += batch.length;
                $("#importProgressTxtEklaim").text(index.toLocaleString("id-ID"));

                const percent = total > 0 ? (index / total) * 100 : 0;
                $("#importProgressBarTxtEklaim").css("width", percent + "%").attr("aria-valuenow", percent);

                const elapsed = (Date.now() - startTime) / 1000;
                const speed = elapsed > 0 ? index / elapsed : 0;
                $("#importSpeedTxtEklaim").text(speed.toFixed(2));

                const remaining = total - index;
                const eta = speed > 0 ? remaining / speed : 0;
                $("#importEtaTxtEklaim").text(remaining > 0 ? formatDuration(eta) : "Completed");

                if (index < total) {
                    kirimBatchTxtEklaim();
                    return;
                }

                $("#importProgressBarTxtEklaim").css("width", "100%");

                setTimeout(function() {
                    const result = res.responResult || {};
                    const totalResult = Number(result.total || total);
                    const successResult = Number(result.success || 0);
                    const failedResult = Number(result.failed || 0);

                    Swal.fire({
                        icon: failedResult > 0 ? "warning" : "success",
                        title: failedResult > 0 ? "Import Completed with Warning" : "Import Completed",
                        html: `Total Data : <strong>${totalResult.toLocaleString("id-ID")}</strong><br>Berhasil : <strong>${successResult.toLocaleString("id-ID")}</strong><br>Gagal : <strong>${failedResult.toLocaleString("id-ID")}</strong>`,
                        confirmButtonColor: "#009EF7",
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(function() {
                        window.dataTxtEklaim = [];
                        window.headerTxtEklaim = [];
                        $("#filetxteklaim").val("");
                        $("#jmlDataEklaim").text("0");
                        $("#totalNilaiEklaim").text("Rp 0");
                        $("#totalTarifRSEklaim").text("Rp 0");
                        $("#selisihTarifEklaim").text("Rp 0");
                        $("#headerPreviewtxtEklaim").empty();
                        $("#resultpreviewtxteklaim").empty();
                        $("#modal_upload_txt_eklaim").modal("hide");
                    });
                }, 200);
            },
            error: function(xhr, status, error) {
                console.error("IMPORT TXT E-KLAIM ERROR:", error);
                console.error(xhr.responseText);

                Swal.fire({
                    icon: "error",
                    title: "Request Failed",
                    text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
                    confirmButtonText: "OK"
                });
            }
        });
    }
};

function load(){
    datarrjdetail();
    quadrantdokter();
    quadrantsmf();
    quadrantresource();
    datadetailtidakadasep();
    datadetailungrouping();
};

function datarrjdetail(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/urbpjs/rawatjalan/datarrjdetail",
        type     : "POST",
        dataType : "JSON",
        data     : {selectperiode:selectperiode},

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
                    icon             : 'warning',
                    title            : 'No Records Found',
                    text             : 'No records are available for the selected period.',
                    showConfirmButton: false,
                    timer            : 2000
                });
                return;
            }

            const result             = Array.isArray(response.responResult) ? response.responResult : [];
            const bulanLengkap       = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan          = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];
            const mapkunjungan       = {};
            const maptarifrs         = {};
            const mapinacbg          = {};
            const mapkasuspending    = {};
            const mapnilaipending    = {};
            const mapkasustidaklayak = {};
            const mapnilaitidaklayak = {};

            var tableresult            = "";
            let totaljmlkunjungan      = 0;
            let totaljmlunsep          = 0;
            let totaljmlsep            = 0;
            let totaljmlgrouping       = 0;
            let totaljmlungrouping     = 0;
            let totaljmlbahvpending    = 0;
            let totaljmlbahvlayak      = 0;
            let totaljmlbahvtidaklayak = 0;

            let totalnilaitarifrs        = 0;
            let totalnilaiunsep          = 0;
            let totalnilaiungrouping     = 0;
            let totalnilaigrouping       = 0;
            let totalnilaiabd            = 0;
            let totalnilaifarmasi        = 0;
            let totalselisih             = 0;
            let totalnilaibahvpending    = 0;
            let totalnilaibahvlayak      = 0;
            let totalnilaibahvtidakLayak = 0;

            for (var i in result) {
                let jmlkunjungan   = Number(result[i].JUMLAH_KUNJUNGAN) || 0;
                let jmlunsep       = Number(result[i].JUMLAH_UN_SEP) || 0;
                let jmlsep         = Number(result[i].JUMLAH_SEP) || 0;
                let jmlgrouping    = Number(result[i].JUMLAH_GROUPING) || 0;
                let jmlungrouping  = Number(result[i].JUMLAH_UN_GROUPING) || 0;
                let bahvpending    = Number(result[i].JUMLAH_BAHV_N) || 0;
                let bahvlayak      = Number(result[i].JUMLAH_BAHV_Y) || 0;
                let bahvtidaklayak = Number(result[i].JUMLAH_BAHV_T) || 0;

                let nilaitarifrs        = Number(result[i].TOTAL_TARIF_RS) || 0;
                let nilaiunsep          = Number(result[i].NILAI_UN_SEP) || 0;
                let nilaiungrouping     = Number(result[i].NILAI_UN_GROUPING) || 0;
                let nilaigrouping       = Number(result[i].NILAI_GROUPING) || 0;
                let nilaiabd            = Number(result[i].NILAI_ABD) || 0;
                let nilaifarmasi        = Number(result[i].NILAI_FARMASI) || 0;
                let nilaibahvpending    = Number(result[i].NILAI_BAHV_N) || 0;
                let nilaibahvlayak      = Number(result[i].NILAI_BAHV_Y) || 0;
                let nilaibahvtidaklayak = Number(result[i].NILAI_BAHV_T) || 0;

                let selisih    = (nilaigrouping+nilaiabd+nilaifarmasi)-nilaitarifrs;
                let clsSelisih = selisih < 0 ? "text-danger fw-bold" : "text-success fw-bold";

                totaljmlkunjungan      += jmlkunjungan;
                totaljmlunsep          += jmlunsep;
                totaljmlsep            += jmlsep;
                totaljmlgrouping       += jmlgrouping;
                totaljmlungrouping     += jmlungrouping;
                totaljmlbahvpending    += bahvpending;
                totaljmlbahvlayak      += bahvlayak;
                totaljmlbahvtidaklayak += bahvtidaklayak;

                totalnilaitarifrs        += nilaitarifrs;
                totalnilaiunsep          += nilaiunsep;
                totalnilaiungrouping     += nilaiungrouping;
                totalnilaigrouping       += nilaigrouping;
                totalnilaiabd            += nilaiabd;
                totalnilaifarmasi        += nilaifarmasi;
                totalnilaibahvpending    += nilaibahvpending;
                totalnilaibahvlayak      += nilaibahvlayak;
                totalnilaibahvtidakLayak += nilaibahvtidaklayak;

                totalselisih    += selisih;

                mapkunjungan[result[i].BULAN]       = jmlkunjungan;
                maptarifrs[result[i].BULAN]         = nilaitarifrs;
                mapinacbg[result[i].BULAN]          = nilaigrouping+nilaiabd+nilaifarmasi;
                mapkasuspending[result[i].BULAN]    = bahvpending;
                mapnilaipending[result[i].BULAN]    = nilaibahvpending;
                mapkasustidaklayak[result[i].BULAN] = bahvtidaklayak;
                mapnilaitidaklayak[result[i].BULAN] = nilaibahvtidaklayak;

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>" + (result[i].BULAN_LAYANAN || "") + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(jmlkunjungan) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaitarifrs) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaigrouping) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaiabd) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaifarmasi) + "</td>";
                tableresult += "<td class='text-end'><span class='" + clsSelisih + "'>" + todesimal(selisih) + "</span></td>";
                tableresult += "<td class='text-end'>" + todesimal(bahvpending) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaibahvpending) + "</td>";
                tableresult += "<td class='text-end'>" + (nilaitarifrs > 0 ? ((nilaibahvpending / nilaitarifrs) * 100).toFixed(4) : "0.00") + "%</td>";
                tableresult += "<td class='text-end'>" + todesimal(bahvlayak) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaibahvlayak) + "</td>";
                tableresult += "<td class='text-end'>" + (nilaitarifrs > 0 ? ((nilaibahvlayak / nilaitarifrs) * 100).toFixed(4) : "0.00") + "%</td>";
                tableresult += "<td class='text-end'>" + todesimal(bahvtidaklayak) + "</td>";
                tableresult += "<td class='text-end'>" + todesimal(nilaibahvtidaklayak) + "</td>";
                tableresult += "<td class='text-end'>" + (nilaitarifrs > 0 ? ((nilaibahvtidaklayak / nilaitarifrs) * 100).toFixed(4) : "0.00") + "%</td>";
                tableresult += "</tr>";
            }

            let coverage = totalnilaitarifrs > 0 ? (((totalnilaigrouping+totalnilaiabd+totalnilaifarmasi) / totalnilaitarifrs) * 100) : 0;

            $("#resultdataurrjdetail").html(tableresult);

            //ROW 1
            $("#jmlkunjungan").text(todesimal(totaljmlkunjungan));
            $("#jmlsepbelum").text(todesimal(totaljmlunsep)+" / "+todesimal(totalnilaiunsep));
            $("#jmlsep").text(todesimal(totaljmlsep));
            $("#jmlbelumgrouping").text(todesimal(totaljmlungrouping)+" / "+todesimal(totalnilaiungrouping));
            $("#jmlgrouping").text(todesimal(totaljmlgrouping));
            

            //ROW 2
            $("#totaltarifrs").text(todesimal(totalnilaitarifrs));
            $("#totalinacbg").text(todesimal(totalnilaigrouping));
            $("#totalabd").text(todesimal(totalnilaiabd));
            $("#totalfarmasi").text(todesimal(totalnilaifarmasi));
            $("#totalselisih").text(todesimal(totalselisih)).removeClass("text-success text-danger").addClass(totalselisih < 0 ? "text-danger" : "text-success");
            $("#coverage").text(coverage.toFixed(4));

            //ROW 3
            let presentasipending    = totalnilaigrouping > 0 ? ((totalnilaibahvpending / totalnilaigrouping) * 100) : 0;
            let presentasilayak      = totalnilaigrouping > 0 ? ((totalnilaibahvlayak / totalnilaigrouping) * 100) : 0;
            let presentasitidaklayak = totalnilaigrouping > 0 ? ((totalnilaibahvtidakLayak / totalnilaigrouping) * 100) : 0;

            $("#pending_kasus").text(todesimal(totaljmlbahvpending));
            $("#pending_nilai").text(todesimal(totalnilaibahvpending));
            $("#pending_persen").text(presentasipending.toFixed(2));

            $("#layak_kasus").text(todesimal(totaljmlbahvlayak));
            $("#layak_nilai").text(todesimal(totalnilaibahvlayak));
            $("#layak_persen").text(presentasilayak.toFixed(2));

            $("#tidaklayak_kasus").text(todesimal(totaljmlbahvtidaklayak));
            $("#tidaklayak_nilai").text(todesimal(totalnilaibahvtidakLayak));
            $("#tidaklayak_persen").text(presentasitidaklayak.toFixed(2));

            const chartdatakunjungan = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : mapkunjungan[b] ?? 0
            }));

            const chartdatatarif = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : maptarifrs[b] || 0,
                value2 : mapinacbg[b] || 0
            }));

            const chartdatapending = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : mapkasuspending[b] || 0,
                value2 : mapnilaipending[b] || 0
            }));

            const chartdatatidaklayak = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                value1 : mapkasustidaklayak[b] || 0,
                value2 : mapnilaitidaklayak[b] || 0
            }));

            renderchartarea("trenkunjungan",chartdatakunjungan,"Periode Pelayanan","Jumlah Kunjungan",["Jumlah Kunjungan"],["value1"],null,"","value1","Rata-rata Kunjungan",null);
            renderChartbarline("perbadingantarif",chartdatatarif,"Periode Pelayanan","Nilai Billing (Rp)","Nilai Klaim (Rp)","Nilai Billing","Nilai Klaim (Rp)");
            renderChartbarline("klaimpending",chartdatapending,"Periode Pelayanan","Jumlah Kasus","Nilai Klaim (Rp)","Jumlah Kasus","Nilai Klaim (Rp)");
            renderChartbarline("klaimtidaklayak",chartdatatidaklayak,"Periode Pelayanan","Jumlah Kasus","Nilai Klaim (Rp)","Jumlah Kasus","Nilai Klaim (Rp)");
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

function datadetailtidakadasep(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/urbpjs/rawatjalan/datadetailtidakadasep",
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

            $("#resultdatadetailtidakadasep").empty();
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
            globaldatacarakeluar = result;

            var tableresult    = "";
            for (var i in result) {

                let btnaction = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].MRPAS||"")+"</td>";
                tableresult += "<td>"+(result[i].EPISODE_ID||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMAPASIEN||"")+"</td>";
                tableresult += "<td>"+(result[i].POLIKLINIK||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMADOKTER||"")+"</td>";
                tableresult += "<td class='text-center'>"+(result[i].TGLMASUK||"")+"</td>";
                tableresult += "<td>" + ((result[i].LASTUPDATE || "") === "MJKN-TOLOP" ? '<span class="badge badge-light-success">Tol-Ops</span>' : ("" || "")) + "</td>";
                tableresult += "<td class='fw-bold text-end'>";
                    tableresult += "<div class='btn-group'>";
                    tableresult += "<button type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown'>Actions</button>";
                    tableresult += "<div class='dropdown-menu'>";
                    tableresult += btnaction;
                    tableresult += "</div></div>";
                tableresult +="</td>";
                tableresult += "</tr>";
            }

            $("#resultdatadetailtidakadasep").html(tableresult);
            const table = initDataTable("#datadetailtidakadasep_table","#searchtable");
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

function datadetailungrouping(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/urbpjs/rawatjalan/datadetailungrouping",
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

            $("#resultdatadetailbelumgrouping").empty();
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
            globaldatacarakeluar = result;

            var tableresult    = "";
            for (var i in result) {

                let btnaction = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].MRPAS||"")+"</td>";
                tableresult += "<td>"+(result[i].EPISODE_ID||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMAPASIEN||"")+"</td>";
                tableresult += "<td>"+(result[i].POLIKLINIK||"")+"</td>";
                tableresult += "<td>"+(result[i].NAMADOKTER||"")+"</td>";
                tableresult += "<td class='text-center'>"+(result[i].TGLMASUK||"")+"</td>";
                tableresult += "<td>" + ((result[i].LASTUPDATE || "") === "MJKN-TOLOP" ? '<span class="badge badge-light-success">Tol-Ops</span>' : ("" || "")) + "</td>";
                tableresult += "<td class='fw-bold text-end'>";
                    tableresult += "<div class='btn-group'>";
                    tableresult += "<button type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown'>Actions</button>";
                    tableresult += "<div class='dropdown-menu'>";
                    tableresult += btnaction;
                    tableresult += "</div></div>";
                tableresult +="</td>";
                tableresult += "</tr>";
            }

            $("#resultdatadetailbelumgrouping").html(tableresult);
            const table = initDataTable("#datadetailbelumgrouping_table","#searchtable");
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