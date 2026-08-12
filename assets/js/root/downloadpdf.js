function exportLaporanKinerjaDokterPDF() {

    const { jsPDF } = window.jspdf;

    const doc = new jsPDF({
        orientation: "portrait",
        unit: "mm",
        format: "a4"
    });

    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    const marginLeft = 15;
    const marginRight = 15;

    const dokter = typeof globalnamauser !== "undefined"
        ? globalnamauser
        : "";

    const periode = typeof globalperiode !== "undefined"
        ? globalperiode
        : "";

    const dataAktifitas = Array.isArray(globaldataaktifitasdokter)
        ? globaldataaktifitasdokter
        : [];

    const dataJumlahPasien = Array.isArray(globaldatajumlahpasien)
        ? globaldatajumlahpasien
        : [];


    // =========================================================
    // HEADER
    // =========================================================

    doc.setFont("helvetica", "bold");
    doc.setFontSize(16);

    doc.text(
        "RSUD PASAR MINGGU",
        pageWidth / 2,
        14,
        {
            align: "center"
        }
    );


    doc.setFont("helvetica", "normal");
    doc.setFontSize(13);

    doc.text(
        "LAPORAN KINERJA PELAYANAN DOKTER",
        pageWidth / 2,
        22,
        {
            align: "center"
        }
    );


    doc.setLineWidth(0.7);

    doc.line(
        marginLeft,
        27,
        pageWidth - marginRight,
        27
    );


    // =========================================================
    // INFORMASI DOKTER
    // =========================================================

    doc.setFontSize(9);
    doc.setFont("helvetica", "bold");

    doc.text(
        "Nama Dokter",
        marginLeft + 1,
        38
    );

    doc.text(
        ":",
        40,
        38
    );

    doc.text(
        dokter,
        46,
        38
    );


    doc.text(
        "Periode",
        marginLeft + 1,
        46
    );

    doc.text(
        ":",
        40,
        46
    );


    doc.setFont("helvetica", "normal");

    doc.text(
        periode,
        46,
        46
    );


    let currentY = 57;


    // =========================================================
    // SECTION 1
    // =========================================================

    doc.setFillColor(240, 240, 240);

    doc.rect(
        marginLeft,
        currentY,
        pageWidth - marginLeft - marginRight,
        10,
        "F"
    );


    doc.setFillColor(50, 50, 50);

    doc.rect(
        marginLeft,
        currentY,
        2,
        10,
        "F"
    );


    doc.setFont("helvetica", "bold");
    doc.setFontSize(10);

    doc.text(
        "1. Aktivitas Dokter Jenis Pelayanan",
        marginLeft + 5,
        currentY + 6.5
    );


    currentY += 13;


    // =========================================================
    // DATA AKTIVITAS
    // =========================================================

    const aktivitasRows = dataAktifitas.map((item, index) => [
        index + 1,
        item.JENIS ?? "",
        dokter,
        item.NAMAPELAYANAN ?? "",
        Number(item.TOTAL_QTY ?? 0)
    ]);


    let totalTindakan = 0;

    dataAktifitas.forEach(item => {

        totalTindakan += Number(
            item.TOTAL_QTY ?? 0
        );

    });


    doc.autoTable({

        startY: currentY,

        head: [[
            "No",
            "Jenis Pelayanan",
            "Nama Dokter",
            "Nama Tindakan / Pelayanan",
            "Total Qty"
        ]],

        body: aktivitasRows,

        theme: "grid",

        margin: {
            left: marginLeft,
            right: marginRight
        },

        styles: {
            font: "helvetica",
            fontSize: 8,
            cellPadding: 2,
            lineColor: [0, 0, 0],
            lineWidth: 0.2,
            textColor: [20, 20, 20],
            valign: "middle"
        },

        headStyles: {
            fillColor: [220, 220, 220],
            textColor: [20, 20, 20],
            fontStyle: "bold",
            halign: "center",
            valign: "middle"
        },

        columnStyles: {

            0: {
                cellWidth: 12,
                halign: "center"
            },

            1: {
                cellWidth: 55
            },

            2: {
                cellWidth: 55
            },

            3: {
                cellWidth: "auto"
            },

            4: {
                cellWidth: 22,
                halign: "center"
            }

        },

        didDrawPage: function () {

            addFooter(doc);

        }

    });


    currentY = doc.lastAutoTable.finalY;


    // =========================================================
    // TOTAL TINDAKAN
    // =========================================================

    doc.setFont("helvetica", "bold");
    doc.setFontSize(8);


    doc.text(
        "TOTAL TINDAKAN:",
        pageWidth - marginRight - 35,
        currentY + 6,
        {
            align: "right"
        }
    );


    doc.text(
        totalTindakan.toLocaleString("id-ID"),
        pageWidth - marginRight - 3,
        currentY + 6,
        {
            align: "right"
        }
    );


    doc.setLineWidth(0.2);

    doc.line(
        pageWidth - 60,
        currentY + 8,
        pageWidth - marginRight,
        currentY + 8
    );


    currentY += 17;


    // =========================================================
    // CEK HALAMAN
    // =========================================================

    if (currentY > pageHeight - 45) {

        doc.addPage();

        currentY = 15;

    }


    // =========================================================
    // SECTION 2
    // =========================================================

    doc.setFillColor(240, 240, 240);

    doc.rect(
        marginLeft,
        currentY,
        pageWidth - marginLeft - marginRight,
        10,
        "F"
    );


    doc.setFillColor(50, 50, 50);

    doc.rect(
        marginLeft,
        currentY,
        2,
        10,
        "F"
    );


    doc.setFont("helvetica", "bold");
    doc.setFontSize(10);

    doc.text(
        "2. Rekap Jumlah Pasien by DPJP",
        marginLeft + 5,
        currentY + 6.5
    );


    currentY += 13;


    // =========================================================
    // DATA JUMLAH PASIEN
    // =========================================================

    const pasienRows = dataJumlahPasien.map((item, index) => [

        index + 1,

        item.JENIS_EPISODE === "I"
            ? "RAWAT INAP"
            : "RAWAT JALAN",

        item.TGLMASUK ?? "",

        Number(item.JML ?? 0)

    ]);


    doc.autoTable({

        startY: currentY,

        head: [[
            "No",
            "Jenis Pelayanan",
            "Periode",
            "Total Kunjungan"
        ]],

        body: pasienRows,

        theme: "grid",

        margin: {
            left: marginLeft,
            right: marginRight
        },

        styles: {
            font: "helvetica",
            fontSize: 8,
            cellPadding: 2,
            lineColor: [0, 0, 0],
            lineWidth: 0.2,
            textColor: [20, 20, 20],
            valign: "middle"
        },

        headStyles: {
            fillColor: [220, 220, 220],
            textColor: [20, 20, 20],
            fontStyle: "bold",
            halign: "center",
            valign: "middle"
        },

        columnStyles: {

            0: {
                cellWidth: 12,
                halign: "center"
            },

            1: {
                cellWidth: 65
            },

            2: {
                cellWidth: 55,
                halign: "center"
            },

            3: {
                cellWidth: 48,
                halign: "center"
            }

        },

        didDrawPage: function () {

            addFooter(doc);

        }

    });


    // =========================================================
    // FOOTER
    // =========================================================

    function addFooter(doc) {

        const pageNumber =
            doc.internal.getNumberOfPages();


        doc.setFont("helvetica", "normal");
        doc.setFontSize(7);


        doc.text(
            "RSUD Pasar Minggu",
            marginLeft,
            pageHeight - 7
        );


        doc.text(
            "Halaman " + pageNumber,
            pageWidth - marginRight,
            pageHeight - 7,
            {
                align: "right"
            }
        );

    }


    // =========================================================
    // NAMA FILE
    // =========================================================

    const now = new Date();


    const pad = (num) => {

        return String(num).padStart(2, "0");

    };


    const timestamp =
        now.getFullYear() +
        pad(now.getMonth() + 1) +
        pad(now.getDate()) +
        pad(now.getHours()) +
        pad(now.getMinutes()) +
        pad(now.getSeconds());


    // Bersihkan nama dokter agar aman sebagai nama file
    const namaDokterFile = String(dokter || "DOKTER")
        .trim()
        .replace(/[\\/:*?"<>|]/g, "_")
        .replace(/\s+/g, "_");


    // Ambil periode dari globalperiode
    // Contoh:
    // "01.08.2026 - 12.08.2026"
    //
    // Hasil:
    // "01.08.2026-12.08.2026"
    const periodeFile = String(periode || "PERIODE")
        .trim()
        .replace(/\s+/g, "")
        .replace(/[\\/:*?"<>|]/g, "_");


    const namaFile =
        "Laporan_Aktivitas_" +
        namaDokterFile +
        "_PERIODE_" +
        periodeFile +
        "_TIMESTAMP_" +
        timestamp +
        ".pdf";


    // =========================================================
    // SAVE PDF
    // =========================================================

    doc.save(namaFile);

}