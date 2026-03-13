function aggregatepoliklinik(data, limit = 10) {
    const map = {};

    data.forEach(item => {
        const keterangan = item.POLITUJUAN;

        if (!map[keterangan]) {
            map[keterangan] = {
                keterangan: keterangan,
                jumlah: 0
            };
        }

        map[keterangan].jumlah++;
    });

    return Object.values(map).sort((a, b) => b.jumlah - a.jumlah).slice(0, limit);
};

function aggregatedokter(data, limit = 10) {
    const map = {};

    data.forEach(item => {
        const keterangan = item.NAMADOKTER;

        if (!map[keterangan]) {
            map[keterangan] = {
                keterangan: keterangan,
                jumlah: 0
            };
        }

        map[keterangan].jumlah++;
    });

    return Object.values(map).sort((a, b) => b.jumlah - a.jumlah).slice(0, limit);
};

function aggregateprovider(data, limit = 10) {
    const map = {};

    data.forEach(item => {
        const keterangan = item.PROVIDER;

        if (!map[keterangan]) {
            map[keterangan] = {
                keterangan: keterangan,
                jumlah: 0
            };
        }

        map[keterangan].jumlah++;
    });

    return Object.values(map).sort((a, b) => b.jumlah - a.jumlah).slice(0, limit);
};

function aggregatediagnosa(data, limit = 10) {

    const map = {};

    if (!Array.isArray(data)) return [];

    data.forEach(item => {

        let diagString = item?.DIAG;
        if (!diagString || typeof diagString !== "string") return;

        // 🔥 Bersihkan tag XML jika masih ada
        diagString = diagString.replace(/<\/?E>/gi, '');

        diagString
            .split(';')
            .map(d => d.trim())
            .filter(Boolean)
            .forEach(d => {

                // Ambil sebelum /batasjenis
                let cleanDiagnosa = d.split('/batasjenis')[0].trim();
                if (!cleanDiagnosa) return;

                // Normalisasi spasi ganda
                cleanDiagnosa = cleanDiagnosa.replace(/\s+/g, ' ');

                // Ambil kode ICD dalam []
                const match = cleanDiagnosa.match(/\[(.*?)\]/);
                const kode  = match ? match[1].toUpperCase() : "";

                // 🔥 Exclude ICD awalan Z dan R
                if (kode && /^[ZR]/.test(kode)) return;

                // Gunakan key berdasarkan kode saja supaya tidak double beda penulisan
                const key = kode || cleanDiagnosa;

                if (!map[key]) {
                    map[key] = {
                        kode: kode,
                        keterangan: cleanDiagnosa,
                        jumlah: 0
                    };
                }

                map[key].jumlah++;
            });

    });

    return Object.values(map)
        .sort((a, b) => b.jumlah - a.jumlah)
        .slice(0, limit);
}

function aggregatediagnosageriatri(data, limit = 10) {

    const map = {};

    if (!Array.isArray(data)) return [];

    data.forEach(item => {

        if (!item || item.GERIATRI !== "Y") return;

        let diagString = item.DIAG;
        if (!diagString || typeof diagString !== "string") return;

        // 🔥 Bersihkan tag XML jika masih ada
        diagString = diagString.replace(/<\/?E>/gi, '');

        diagString
            .split(';')
            .map(d => d.trim())
            .filter(Boolean)
            .forEach(d => {

                let cleanDiagnosa = d.split('/batasjenis')[0].trim();
                if (!cleanDiagnosa) return;

                // Normalisasi spasi
                cleanDiagnosa = cleanDiagnosa.replace(/\s+/g, ' ');

                // Ambil kode ICD dalam []
                const match = cleanDiagnosa.match(/\[(.*?)\]/);
                const kode  = match ? match[1].toUpperCase() : "";

                // 🔥 Exclude ICD awalan Z & R
                if (kode && /^[ZR]/.test(kode)) return;

                // Gunakan kode sebagai key supaya tidak double
                const key = kode || cleanDiagnosa;

                if (!map[key]) {
                    map[key] = {
                        kode: kode,
                        keterangan: cleanDiagnosa,
                        jumlah: 0
                    };
                }

                map[key].jumlah++;
            });

    });

    return Object.values(map)
        .sort((a, b) => b.jumlah - a.jumlah)
        .slice(0, limit);
}

function aggregateBulan(data, fieldName = "PERIODE") {
    const monthOrder = [
        { num: 0, label: "JAN" },
        { num: 1, label: "FEB" },
        { num: 2, label: "MAR" },
        { num: 3, label: "APR" },
        { num: 4, label: "MEI" },
        { num: 5, label: "JUN" },
        { num: 6, label: "JUL" },
        { num: 7, label: "AGU" },
        { num: 8, label: "SEP" },
        { num: 9, label: "OKT" },
        { num: 10, label: "NOV" },
        { num: 11, label: "DES" }
    ];

    // inisialisasi total per bulan
    const result = {};
    monthOrder.forEach(m => {
        result[m.num] = {
            periode: m.label,
            total: 0
        };
    });

    // loop data
    data.forEach(item => {
        if (!item[fieldName]) return;

        let month = null;
        try {
            const dt = new Date(item[fieldName]);
            if (!isNaN(dt)) {
                month = dt.getMonth(); // 0 = Jan, 11 = Des
            }
        } catch(e) {
            // abaikan jika tidak bisa parse
        }

        if (month !== null && result[month]) {
            result[month].total++;
        }
    });

    return monthOrder.map(m => result[m.num]);
}

function aggregateBulanSPRI(data) {

    const monthLabel = [
        "JAN","FEB","MAR","APR","MEI","JUN",
        "JUL","AGU","SEP","OKT","NOV","DES"
    ];

    const result = {};

    data.forEach(item => {

        if (!item.PERIODE) return;

        const parts = item.PERIODE.split(".");
        if (parts.length < 2) return;

        const monthIndex = parseInt(parts[1], 10) - 1;
        if (isNaN(monthIndex)) return;

        if (!result[monthIndex]) {
            result[monthIndex] = {
                periode: monthLabel[monthIndex],
                totalIGD_SPRI: 0,
                totalRANAP_TRANSFER: 0,
                countIGD_SPRI: 0,
                countRANAP_TRANSFER: 0
            };
        }

        // IGD → SPRI
        const igd = parseTanggal(item.MASUK_IGD);
        const spri = parseTanggal(item.BUAT_SPRI);

        if (igd && spri) {
            const diffJam = (spri - igd) / (1000 * 60 * 60);
            if (!isNaN(diffJam) && diffJam >= 0) {
                result[monthIndex].totalIGD_SPRI += diffJam;
                result[monthIndex].countIGD_SPRI++;
            }
        }

        // RANAP → TRANSFER
        const ranap = parseTanggal(item.MASUK_RANAP);
        const transfer = parseTanggal(item.BUAT_TRANSFER);

        if (ranap && transfer) {
            const diffJam = (transfer - ranap) / (1000 * 60 * 60);
            if (!isNaN(diffJam) && diffJam >= 0) {
                result[monthIndex].totalRANAP_TRANSFER += diffJam;
                result[monthIndex].countRANAP_TRANSFER++;
            }
        }

    });

    return Object.values(result).map(item => ({
        periode: item.periode,
        avgIGD_SPRI: item.countIGD_SPRI
            ? (item.totalIGD_SPRI / item.countIGD_SPRI).toFixed(2)
            : 0,
        avgRANAP_TRANSFER: item.countRANAP_TRANSFER
            ? (item.totalRANAP_TRANSFER / item.countRANAP_TRANSFER).toFixed(2)
            : 0
    }));
}

function aggregateBulanResume(data, fieldName = "TGL_KELUAR") {

    const monthOrder = [
        { num: 0, label: "Jan" },
        { num: 1, label: "Feb" },
        { num: 2, label: "Mar" },
        { num: 3, label: "Apr" },
        { num: 4, label: "Mei" },
        { num: 5, label: "Jun" },
        { num: 6, label: "Jul" },
        { num: 7, label: "Agu" },
        { num: 8, label: "Sep" },
        { num: 9, label: "Okt" },
        { num: 10, label: "Nov" },
        { num: 11, label: "Des" }
    ];

    const result = {};

    monthOrder.forEach(m => {
        result[m.num] = {
            periode: m.label,
            selesai: 0,
            kurang48: 0,
            lebih48: 0
        };
    });

    data.forEach(item => {

        if (!item[fieldName]) return;

        let dt = new Date(item[fieldName]);
        if (isNaN(dt)) return;

        let month = dt.getMonth();

        if (!result[month]) return;

        if(item.TRANSCORESUME !== null && item.TRANSCORESUME !== ""){
            result[month].selesai++;
            if(parseInt(item.DURASI) > 2){
                result[month].lebih48++;
            } else {
                result[month].kurang48++;
            }
        }
    });

    return monthOrder.map(m => result[m.num]);
}

function aggregateHarianResume(data, fieldName = "TGL_KELUAR") {

    const result = {};

    data.forEach(item => {

        if (!item[fieldName]) return;

        let dt = new Date(item[fieldName]);
        if (isNaN(dt)) return;

        let dateKey = dt.toISOString().slice(0,10); // YYYY-MM-DD

        if (!result[dateKey]) {
            result[dateKey] = {
                periode: dateKey,
                selesai: 0,
                kurang48: 0,
                lebih48: 0
            };
        }

        if(item.TRANSCORESUME !== null && item.TRANSCORESUME !== ""){

            result[dateKey].selesai++;

            if(parseInt(item.DURASI) > 2){
                result[dateKey].lebih48++;
            } else {
                result[dateKey].kurang48++;
            }

        }

    });

    return Object.values(result).sort((a,b) => 
        new Date(a.periode) - new Date(b.periode)
    );
}

function aggregateHarianResumeLAST30(data, fieldName = "TGL_KELUAR") {

    const result = {};

    const today = new Date();
    const startDate = new Date();
    startDate.setDate(today.getDate() - 60);

    data.forEach(item => {

        if (!item[fieldName]) return;

        let dt = new Date(item[fieldName]);
        if (isNaN(dt)) return;

        // Filter hanya 30 hari terakhir
        if (dt < startDate || dt > today) return;

        let dateKey = dt.toISOString().slice(0,10);

        if (!result[dateKey]) {
            result[dateKey] = {
                periode: dateKey,
                selesai: 0,
                kurang48: 0,
                lebih48: 0
            };
        }

        if(item.TRANSCORESUME !== null && item.TRANSCORESUME !== ""){

            result[dateKey].selesai++;

            if(parseInt(item.DURASI) > 2){
                result[dateKey].lebih48++;
            } else {
                result[dateKey].kurang48++;
            }

        }

    });

    return Object.values(result).sort((a,b) => 
        new Date(a.periode) - new Date(b.periode)
    );
}

function aggregateResumeGlobal(data) {

    let kurang48 = 0;
    let lebih48  = 0;

    data.forEach(item => {

        if(item.TRANSCORESUME !== null && item.TRANSCORESUME !== ""){
            
            if(parseInt(item.DURASI) > 2){
                lebih48++;
            } else {
                kurang48++;
            }

        }

    });

    return [
        {
            LABEL: "≤ 48 Jam",
            TOTAL: kurang48
        },
        {
            LABEL: "> 48 Jam",
            TOTAL: lebih48
        }
    ];
}