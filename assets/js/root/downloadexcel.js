function exportToExcel(data, sheetName, fileName, config = {}) {

    // =====================================================
    // VALIDASI DATA
    // =====================================================
    const isMultiSheet = Array.isArray(config.multiSheet);

    const hasData = isMultiSheet
        ? config.multiSheet.some(sheet =>
            Array.isArray(sheet.data) && sheet.data.length > 0
        )
        : Array.isArray(data) && data.length > 0;

    if (!hasData) {
        Swal.fire("Info", "Data belum tersedia", "warning");
        return;
    }

    // =====================================================
    // PERIODE
    // =====================================================
    const periode = $("select[name='selectperiode']").val() || "ALL";

    // =====================================================
    // TIMESTAMP
    // Format : DDMMYYYYHHMMSS
    // =====================================================
    const now = new Date();

    const pad = (n) => String(n).padStart(2, "0");

    const timestamp =
        `${pad(now.getDate())}` +
        `${pad(now.getMonth() + 1)}` +
        `${now.getFullYear()}` +
        `${pad(now.getHours())}` +
        `${pad(now.getMinutes())}` +
        `${pad(now.getSeconds())}`;

    // =====================================================
    // NAMA FILE
    // contoh:
    // Kunjungan_Rawat_Jalan_PERIODE_2026_TIMESTAMP_05082026084300.xlsx
    // =====================================================
    const finalFileName = fileName.includes(".xlsx")
        ? fileName.replace(
            ".xlsx",
            `_PERIODE_${periode}_TIMESTAMP_${timestamp}.xlsx`
        )
        : `${fileName}_PERIODE_${periode}_TIMESTAMP_${timestamp}.xlsx`;

    // =====================================================
    // LAST UPDATE
    // =====================================================
    const lastUpdate =
        `${pad(now.getDate())}/${pad(now.getMonth() + 1)}/${now.getFullYear()} ` +
        `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;

    const workbook = XLSX.utils.book_new();

    // =====================================================
    // AUTO WIDTH
    // =====================================================
    function autoWidth(ws, rows) {

        if (!rows.length) return;

        const cols = Object.keys(rows[0]).map(key => ({
            wch: Math.max(
                key.length,
                ...rows.map(r => String(r[key] ?? "").length)
            ) + 2
        }));

        ws["!cols"] = cols;
    }

    // =====================================================
    // MEMBUAT WORKSHEET
    // =====================================================
    function createWorksheet(sheetData, sheetTitle, formatter) {

        let dataExport = [];

        if (typeof formatter === "function") {

            dataExport = sheetData.map((item, index) =>
                formatter(item, index)
            );

        } else {

            dataExport = sheetData.map((item, index) => ({
                No: index + 1,

                Keterangan:
                    item.KETERANGAN ??
                    item.LABEL ??
                    item.PROVIDER ??
                    item.PENDIDIKAN ??
                    item.DESCRIPTION ??
                    "-",

                Total: Number(
                    item.TOTAL ??
                    item.JUMLAH ??
                    0
                )
            }));

        }

        const worksheet = XLSX.utils.json_to_sheet(
            dataExport,
            {
                origin: "A5"
            }
        );

        // Header
        XLSX.utils.sheet_add_aoa(
            worksheet,
            [
                [sheetTitle],
                ["Periode", periode],
                ["Last Update", lastUpdate],
                []
            ],
            {
                origin: "A1"
            }
        );

        autoWidth(worksheet, dataExport);

        return worksheet;
    }

    // =====================================================
    // MULTI SHEET
    // =====================================================
    if (isMultiSheet) {

        config.multiSheet.forEach(sheet => {

            const worksheet = createWorksheet(
                sheet.data,
                sheet.name,
                sheet.formatter
            );

            XLSX.utils.book_append_sheet(
                workbook,
                worksheet,
                sheet.name
            );

        });

    } else {

        // =====================================================
        // SINGLE SHEET
        // =====================================================
        const worksheet = createWorksheet(
            data,
            sheetName || "Sheet1",
            config.formatter
        );

        XLSX.utils.book_append_sheet(
            workbook,
            worksheet,
            sheetName || "Sheet1"
        );

    }

    // =====================================================
    // DOWNLOAD
    // =====================================================
    XLSX.writeFile(workbook, finalFileName);

}

function exportTableToExcel(tableID, options = {}) {

    // 🔥 support legacy string filename
    if (typeof options === 'string') {
        options = { filename: options };
    }

    const {
        filename = 'export',
        excludeColumns = [],
        excludeClass = '.excludeThisClass',
        removeLastColumn = false,
        onlyVisible = true   // ✔ NEW FEATURE
    } = options;

    // 🔥 validasi table
    if ($("#" + tableID).length === 0) {
        console.error("Table tidak ditemukan:", tableID);
        return;
    }

    let $table = $("#" + tableID).clone();

    // 🔥 FIX UTAMA: hanya ambil row yang visible
    if (onlyVisible) {
        $table.find("tr").each(function () {
            if ($(this).css("display") === "none") {
                $(this).remove();
            }
        });
    }

    // 🔹 Hapus kolom tertentu
    if (excludeColumns.length > 0) {
        $table.find("tr").each(function () {
            $(this).find("th, td").each(function (i) {
                if (excludeColumns.includes(i)) {
                    $(this).remove();
                }
            });
        });
    }

    // 🔹 Hapus berdasarkan class
    if (excludeClass) {
        $table.find(excludeClass).remove();
    }

    // 🔹 Hapus kolom terakhir
    if (removeLastColumn) {
        $table.find("tr").each(function () {
            $(this).find("td:last, th:last").remove();
        });
    }

    // 🔹 bersihkan UI element
    $table.find("button, .dropdown-menu, .no-export, .filtered-hidden").remove();

    // 🔹 sanitize filename
    const safeFilename = filename.replace(/[\\/:*?"<>|]/g, '_');

    // 🔥 EXPORT
    $table.table2excel({
        exclude: excludeClass,
        name: "Worksheet",
        filename: safeFilename + ".xls",
        preserveColors: false
    });
}