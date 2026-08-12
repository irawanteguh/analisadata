let globaldatakunjunganrjpoliklinik = [];
let globaldatakunjunganrjdokter     = [];
let globaldatakunjunganrjprovider   = [];
let globaldatakunjunganrjpaketmcu   = [];
let globaldatakunjunganigdprovider  = [];
// let globaldatakunjungandokterigd    = [];
let globaldatakunjunganridokter      = [];
let globaldatakunjunganriprovider    = [];

loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});

$("#btndownloaddatakunjungan_table").on("click", function () {
    const bulanField = ["JAN", "FEB", "MAR", "APR", "MEI", "JUN","JUL", "AGU", "SEP", "OKT", "NOV", "DES"];
    const namaBulan  = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    const formatBulanan = (data) => {
        const result = [];
        bulanField.forEach((field, index) => {
            let total = 0;
            data.forEach(item => {
                total += Number(item[field] || 0);
            });
            result.push({
                BULAN: namaBulan[index],
                TOTAL: total
            });
        });
        return result;
    };


    const formatRawatJalan = (data, jenis) => {
        const result = [];
        bulanField.forEach((field, index) => {
            let total = 0;
            data.forEach(item => {
                const value = Number(item[field] || 0);

                if (jenis === "all") {
                    total += value;
                }

                else if (
                    jenis === "executive" &&
                    item.REKANAN_ID === "EXECU0000000001"
                ){
                    total += value;
                }

                else if (
                    jenis === "nonexecutive" &&
                    item.REKANAN_ID !== "EXECU0000000001"
                ) {

                    total += value;

                }
            });

            result.push({
                BULAN: namaBulan[index],
                TOTAL: total
            });

        });

        return result;
    };

    const formatRawatJalanMCU = (data, jenis) => {
        const result = [];
        bulanField.forEach((field, index) => {
            let total = 0;
            data.forEach(item => {
                const value = Number(item[field] || 0);

                if (item.POLI_ID === "MEDIC0000000000") {
                    total += value;
                }
            });

            result.push({
                BULAN: namaBulan[index],
                TOTAL: total
            });

        });

        return result;
    };

    const dataIGD            = formatBulanan(globaldatakunjunganigdprovider);
    const dataRJAll          = formatRawatJalan(globaldatakunjunganrjprovider,"all");
    const dataRJNonExecutive = formatRawatJalan(globaldatakunjunganrjprovider,"nonexecutive");
    const dataRJExecutive    = formatRawatJalan(globaldatakunjunganrjprovider,"executive");
    const dataRJMCU          = formatRawatJalanMCU(globaldatakunjunganrjpoliklinik);
    const dataRI             = formatBulanan(globaldatakunjunganriprovider);


    exportToExcel(
        null,
        null,
        "Kunjungan_IGD_Rawat_Jalan_Rawat_Inap.xlsx",
        {
            multiSheet: [
                {
                    name: "IGD",
                    data: dataIGD,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Keterangan": item.BULAN,
                            "Total": Number(item.TOTAL || 0)
                        };

                    }
                },
                {
                    name: "Rawat Jalan All Provider",
                    data: dataRJAll,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Keterangan": item.BULAN,
                            "Total": Number(item.TOTAL || 0)
                        };

                    }
                },
                {
                    name: "Rawat Jalan Non Executive",
                    data: dataRJNonExecutive,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Keterangan": item.BULAN,
                            "Total": Number(item.TOTAL || 0)
                        };

                    }
                },
                {
                    name: "Rawat Jalan Executive",
                    data: dataRJExecutive,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Keterangan": item.BULAN,
                            "Total": Number(item.TOTAL || 0)
                        };

                    }
                },
                {
                    name: "Medical Check Up",
                    data: dataRJMCU,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Keterangan": item.BULAN,
                            "Total": Number(item.TOTAL || 0)
                        };

                    }
                },
                {
                    name: "Rawat Inap",
                    data: dataRI,

                    formatter: (item, index) => {

                        return {
                            "No": index + 1,
                            "Keterangan": item.BULAN,
                            "Total": Number(item.TOTAL || 0)
                        };

                    }
                }

            ]
        }
    );

});

$("#btndownloaddatakunjunganprovider_table").on("click", function () {
    exportToExcel(
        null,
        null,
        "Kunjungan_IGD_Rawat_Jalan_Rawat_Inap_Provider.xlsx",
        {
            multiSheet: [
                {
                    name: "IGD",
                    data: globaldatakunjunganigdprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Rawat Jalan",
                    data: globaldatakunjunganrjprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Rawat Inap",
                    data: globaldatakunjunganriprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                }
            ]
        }
    );

});

$("#btndownloaddatadetailigd_table").on("click", function () {
    exportToExcel(
        null,
        null,
        "Kunjungan_IGD.xlsx",
        {
            multiSheet: [
                {
                    name: "Provider",
                    data: globaldatakunjunganigdprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                }
            ]
        }
    );

});

$("#btndownloaddatadetailrj_table").on("click", function () {
    exportToExcel(
        null,
        null,
        "Kunjungan_Rawat_Jalan.xlsx",
        {
            multiSheet: [
                {
                    name: "Poliklinik",
                    data: globaldatakunjunganrjpoliklinik,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Poliklinik": item.POLIKLINIK ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Dokter",
                    data: globaldatakunjunganrjdokter,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Nama Dokter": item.NAMADOKTER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Provider",
                    data: globaldatakunjunganrjprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Paket MCU",
                    data: globaldatakunjunganrjpaketmcu,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Nama Paket": item.NAMAPAKET ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                }
            ]
        }
    );

});

$("#btndownloaddatadetailri_table").on("click", function () {
    exportToExcel(
        null,
        null,
        "Kunjungan_Rawat_Inap.xlsx",
        {
            multiSheet: [
                {
                    name: "Dokter",
                    data: globaldatakunjunganridokter,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Nama Dokter": item.NAMADOKTER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                },
                {
                    name: "Provider",
                    data: globaldatakunjunganriprovider,
                    formatter: (item, index) => {

                        const jan = Number(item.JAN ?? 0);
                        const feb = Number(item.FEB ?? 0);
                        const mar = Number(item.MAR ?? 0);
                        const apr = Number(item.APR ?? 0);
                        const mei = Number(item.MEI ?? 0);
                        const jun = Number(item.JUN ?? 0);
                        const jul = Number(item.JUL ?? 0);
                        const agu = Number(item.AGU ?? 0);
                        const sep = Number(item.SEP ?? 0);
                        const okt = Number(item.OKT ?? 0);
                        const nov = Number(item.NOV ?? 0);
                        const des = Number(item.DES ?? 0);

                        return {
                            "No": index + 1,
                            "Provider": item.PROVIDER ?? "",
                            "Jan": jan,
                            "Feb": feb,
                            "Mar": mar,
                            "Apr": apr,
                            "Mei": mei,
                            "Jun": jun,
                            "Jul": jul,
                            "Agu": agu,
                            "Sep": sep,
                            "Okt": okt,
                            "Nov": nov,
                            "Des": des,
                            "Total": jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des
                        };

                    }
                }
            ]
        }
    );

});

function loaddata(){
    // datakunjungandokterigd();
    datakunjunganigdprovider();
    datakunjunganrjpoliklinik();
    datakunjunganrjdokter();
    datakunjunganrjprovider();
    datapaketmcu();
    datakunjunganriprovider();
    datakunjunganridokter();
};

// function datakunjungandokterigd() {
//     let selectperiode = $("select[name='selectperiode']").val();
//     $.ajax({
//         url       : url + "index.php/dashboard/dashboard/datakunjungandokterigd",
//         data      : {selectperiode: selectperiode},
//         type      : "POST",
//         dataType  : "JSON",

//         beforeSend: function () {

//             Swal.fire({
//                 title            : 'Processing',
//                 html             : 'Please wait while the system retrieves the requested data.',
//                 allowOutsideClick: false,
//                 allowEscapeKey   : false,
//                 showConfirmButton: false,
//                 didOpen          : () => Swal.showLoading()
//             });

//             $("#resultdatadokterigd").empty();
//             $("#footerdatadokterigd").empty();
//         },

//         success: function (response) {

//             if (response.responCode !== "00") {
//                 Swal.fire({
//                     icon             : 'warning',
//                     title            : 'No Records Found',
//                     text             : 'No records are available for the selected period.',
//                     showConfirmButton: false,
//                     timer            : 2000
//                 });
//                 return;
//             }

//             let totalJan = 0;
//             let totalFeb = 0;
//             let totalMar = 0;
//             let totalApr = 0;
//             let totalMei = 0;
//             let totalJun = 0;
//             let totalJul = 0;
//             let totalAug = 0;
//             let totalSep = 0;
//             let totalOkt = 0;
//             let totalNov = 0;
//             let totalDes = 0;
//             let grandTotal = 0;

            
//             const result = Array.isArray(response.responResult) ? response.responResult : [];
//             globaldatakunjungandokterigd = result;

//             let tableresult = "";
//             for (let i in result) {

//                 let jan = parseInt(result[i].JAN) || 0;
//                 let feb = parseInt(result[i].FEB) || 0;
//                 let mar = parseInt(result[i].MAR) || 0;
//                 let apr = parseInt(result[i].APR) || 0;
//                 let mei = parseInt(result[i].MEI) || 0;
//                 let jun = parseInt(result[i].JUN) || 0;
//                 let jul = parseInt(result[i].JUL) || 0;
//                 let aug = parseInt(result[i].AGU) || 0;
//                 let sep = parseInt(result[i].SEP) || 0;
//                 let okt = parseInt(result[i].OKT) || 0;
//                 let nov = parseInt(result[i].NOV) || 0;
//                 let des = parseInt(result[i].DES) || 0;

//                 let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

//                 totalJan += jan;
//                 totalFeb += feb;
//                 totalMar += mar;
//                 totalApr += apr;
//                 totalMei += mei;
//                 totalJun += jun;
//                 totalJul += jul;
//                 totalAug += aug;
//                 totalSep += sep;
//                 totalOkt += okt;
//                 totalNov += nov;
//                 totalDes += des;

//                 grandTotal += total;

//                 tableresult += `
//                     <tr>
//                         <td class="ps-4">${parseInt(i) + 1}</td>
//                         <td>${result[i].NAMADOKTER}</td>
//                         <td class="text-end">${todesimal(jan)}</td>
//                         <td class="text-end">${todesimal(feb)}</td>
//                         <td class="text-end">${todesimal(mar)}</td>
//                         <td class="text-end">${todesimal(apr)}</td>
//                         <td class="text-end">${todesimal(mei)}</td>
//                         <td class="text-end">${todesimal(jun)}</td>
//                         <td class="text-end">${todesimal(jul)}</td>
//                         <td class="text-end">${todesimal(aug)}</td>
//                         <td class="text-end">${todesimal(sep)}</td>
//                         <td class="text-end">${todesimal(okt)}</td>
//                         <td class="text-end">${todesimal(nov)}</td>
//                         <td class="text-end">${todesimal(des)}</td>
//                         <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
//                     </tr>
//                 `;
//             }

//             let footer = `
//                 <tr class="fw-bolder text-muted bg-light">
//                     <td colspan="2" class="text-center">
//                         TOTAL
//                     </td>
//                     <td class="text-end">${todesimal(totalJan)}</td>
//                     <td class="text-end">${todesimal(totalFeb)}</td>
//                     <td class="text-end">${todesimal(totalMar)}</td>
//                     <td class="text-end">${todesimal(totalApr)}</td>
//                     <td class="text-end">${todesimal(totalMei)}</td>
//                     <td class="text-end">${todesimal(totalJun)}</td>
//                     <td class="text-end">${todesimal(totalJul)}</td>
//                     <td class="text-end">${todesimal(totalAug)}</td>
//                     <td class="text-end">${todesimal(totalSep)}</td>
//                     <td class="text-end">${todesimal(totalOkt)}</td>
//                     <td class="text-end">${todesimal(totalNov)}</td>
//                     <td class="text-end">${todesimal(totalDes)}</td>
//                     <td class="text-end pe-4">
//                         ${todesimal(grandTotal)}
//                     </td>
//                 </tr>
//             `;

//             $("#resultdatadokterigd").html(tableresult);
//             $("#footerdatadokterigd").html(footer);

//             const table = initDataTable("#datadokterigd_table","#searchtable");
//         },
//         complete: function () {
//             Swal.close();
//         },
//         error: function () {
//             Swal.fire({
//                 icon: "error",
//                 title: "Request Failed",
//                 text: "We were unable to process your request due to a server error. Please try again later. If the problem persists, contact your system administrator.",
//                 confirmButtonText: "OK"
//             });
//         }
//     });
// };

function datakunjunganigdprovider() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganigdprovider",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataigdprovider").empty();
            $("#footerdataigdprovider").empty();
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

            let totalJan   = 0;
            let totalFeb   = 0;
            let totalMar   = 0;
            let totalApr   = 0;
            let totalMei   = 0;
            let totalJun   = 0;
            let totalJul   = 0;
            let totalAug   = 0;
            let totalSep   = 0;
            let totalOkt   = 0;
            let totalNov   = 0;
            let totalDes   = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganigdprovider = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].PROVIDER}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdataigdprovider").html(tableresult);
            $("#footerdataigdprovider").html(footer);

            const table = initDataTable("#dataigdprovider_table","#searchtable");

            const bulanField = ["JAN","FEB","MAR","APR","MEI","JUN","JUL","AGU","SEP","OKT","NOV","DES"];
            const namaBulan  = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartdata = bulanField.map((field, index) => {
                let all          = 0;

                result.forEach(item => {
                    const value = Number(item[field] || 0);
                    all += value;
                });

                return {
                    periode: namaBulan[index],
                    all,
                };

            });

            const dataProvider = [
                { label: "BPJS", value: 0 },
                { label: "Executive", value: 0 },
                { label: "Pasien Umum", value: 0 },
                { label: "Karyawan", value: 0 },
                { label: "Lain-Lain", value: 0 }
            ];

            result.forEach(item => {

                const total =
                    Number(item.JAN || 0) +
                    Number(item.FEB || 0) +
                    Number(item.MAR || 0) +
                    Number(item.APR || 0) +
                    Number(item.MEI || 0) +
                    Number(item.JUN || 0) +
                    Number(item.JUL || 0) +
                    Number(item.AGU || 0) +
                    Number(item.SEP || 0) +
                    Number(item.OKT || 0) +
                    Number(item.NOV || 0) +
                    Number(item.DES || 0);

                switch (item.REKANAN_ID) {

                    case "BPJS":
                        dataProvider[0].value += total;
                        break;

                    case "EXECU0000000001":
                        dataProvider[1].value += total;
                        break;

                    case "UMUM":
                        dataProvider[2].value += total;
                        break;

                    case "KARYA0000000002":
                    case "MCU K0000000001":
                        dataProvider[3].value += total;
                        break;

                    default:
                        dataProvider[4].value += total;
                        break;
                }

            });

            dataProvider.sort((a, b) => b.value - a.value);

            renderchartarea("grafikkunjunganigd",chartdata,"Periode Pelayanan","Jumlah Kunjungan","Jumlah Kunjungan","all",null,"","all");
            renderchartpie("grafikkunjunganigdprovider", dataProvider);
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

function datakunjunganrjpoliklinik() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganrjpoliklinik",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatapoliklinik").empty();
            $("#footerdatapoliklinik").empty();
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

            let totalJan = 0;
            let totalFeb = 0;
            let totalMar = 0;
            let totalApr = 0;
            let totalMei = 0;
            let totalJun = 0;
            let totalJul = 0;
            let totalAug = 0;
            let totalSep = 0;
            let totalOkt = 0;
            let totalNov = 0;
            let totalDes = 0;
            let grandTotal = 0;

            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganrjpoliklinik = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].POLIKLINIK}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdatapoliklinik").html(tableresult);
            $("#footerdatapoliklinik").html(footer);

            const table = initDataTable("#datapoliklinik_table","#searchtable");

            const bulanField = ["JAN","FEB","MAR","APR","MEI","JUN","JUL","AGU","SEP","OKT","NOV","DES"];
            const namaBulan  = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartdata = bulanField.map((field, index) => {
                let mcu = 0;

                result.forEach(item => {
                    const value = Number(item[field] || 0);

                    if(item.POLI_ID === "MEDIC0000000000"){
                        mcu += value;
                    }
                });

                return {
                    periode: namaBulan[index],
                    mcu
                };

            });

            renderchartarea("grafikkunjunganmcu",chartdata,"Periode Pelayanan","Jumlah Kunjungan","Jumlah Kunjungan","mcu",null,"","mcu");
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

function datakunjunganrjdokter() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganrjdokter",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatadokter").empty();
            $("#footerdatadokter").empty();
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

            let totalJan = 0;
            let totalFeb = 0;
            let totalMar = 0;
            let totalApr = 0;
            let totalMei = 0;
            let totalJun = 0;
            let totalJul = 0;
            let totalAug = 0;
            let totalSep = 0;
            let totalOkt = 0;
            let totalNov = 0;
            let totalDes = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganrjdokter = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].NAMADOKTER}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdatadokter").html(tableresult);
            $("#footerdatadokter").html(footer);

            const table = initDataTable("#datadokter_table","#searchtable");
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

function datakunjunganrjprovider() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganrjprovider",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataprovider").empty();
            $("#footerdataprovider").empty();
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

            let totalJan   = 0;
            let totalFeb   = 0;
            let totalMar   = 0;
            let totalApr   = 0;
            let totalMei   = 0;
            let totalJun   = 0;
            let totalJul   = 0;
            let totalAug   = 0;
            let totalSep   = 0;
            let totalOkt   = 0;
            let totalNov   = 0;
            let totalDes   = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganrjprovider = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].PROVIDER}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdataprovider").html(tableresult);
            $("#footerdataprovider").html(footer);

            const table = initDataTable("#dataprovider_table","#searchtable");

            const bulanField = ["JAN","FEB","MAR","APR","MEI","JUN","JUL","AGU","SEP","OKT","NOV","DES"];
            const namaBulan  = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartdata = bulanField.map((field, index) => {
                let all          = 0;
                let executive    = 0;
                let nonexecutive = 0;

                result.forEach(item => {
                    const value = Number(item[field] || 0);
                    all += value;

                    if(item.REKANAN_ID === "EXECU0000000001"){
                        executive += value;
                    }else{
                        nonexecutive += value;
                    }
                });

                return {
                    periode: namaBulan[index],
                    all,
                    executive,
                    nonexecutive
                };

            });

            const dataProvider = [
                { label: "BPJS", value: 0 },
                { label: "Executive", value: 0 },
                { label: "Pasien Umum", value: 0 },
                { label: "Karyawan", value: 0 },
                { label: "Lain-Lain", value: 0 }
            ];

            result.forEach(item => {

                const total =
                    Number(item.JAN || 0) +
                    Number(item.FEB || 0) +
                    Number(item.MAR || 0) +
                    Number(item.APR || 0) +
                    Number(item.MEI || 0) +
                    Number(item.JUN || 0) +
                    Number(item.JUL || 0) +
                    Number(item.AGU || 0) +
                    Number(item.SEP || 0) +
                    Number(item.OKT || 0) +
                    Number(item.NOV || 0) +
                    Number(item.DES || 0);

                switch (item.REKANAN_ID) {

                    case "BPJS":
                        dataProvider[0].value += total;
                        break;

                    case "EXECU0000000001":
                        dataProvider[1].value += total;
                        break;

                    case "UMUM":
                        dataProvider[2].value += total;
                        break;

                    case "KARYA0000000002":
                    case "MCU K0000000001":
                        dataProvider[3].value += total;
                        break;

                    default:
                        dataProvider[4].value += total;
                        break;
                }

            });

            dataProvider.sort((a, b) => b.value - a.value);

            renderchartarea("grafikkunjunganrjall",chartdata,"Periode Pelayanan","Jumlah Kunjungan","Jumlah Kunjungan","all",null,"","all");
            renderchartarea("grafikkunjunganrjnonexecutive",chartdata,"Periode Pelayanan","Jumlah Kunjungan","Jumlah Kunjungan","nonexecutive",null,"","nonexecutive");
            renderchartarea("grafikkunjunganexecutive",chartdata,"Periode Pelayanan","Jumlah Kunjungan","Jumlah Kunjungan","executive",null,"","executive");
            renderchartpie("grafikkunjunganrjprovider", dataProvider);
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

function datapaketmcu() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datapaketmcu",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatapaketmcu").empty();
            $("#footerdatapaketmcu").empty();
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

            let totalJan = 0;
            let totalFeb = 0;
            let totalMar = 0;
            let totalApr = 0;
            let totalMei = 0;
            let totalJun = 0;
            let totalJul = 0;
            let totalAug = 0;
            let totalSep = 0;
            let totalOkt = 0;
            let totalNov = 0;
            let totalDes = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganrjpaketmcu = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].NAMAPAKET}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdatapaketmcu").html(tableresult);
            $("#footerdatapaketmcu").html(footer);

            const table = initDataTable("#datapaketmcu_table","#searchtable");
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

function datakunjunganriprovider() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganriprovider",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdatriaprovider").empty();
            $("#footerdatariprovider").empty();
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

            let totalJan   = 0;
            let totalFeb   = 0;
            let totalMar   = 0;
            let totalApr   = 0;
            let totalMei   = 0;
            let totalJun   = 0;
            let totalJul   = 0;
            let totalAug   = 0;
            let totalSep   = 0;
            let totalOkt   = 0;
            let totalNov   = 0;
            let totalDes   = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganriprovider = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].PROVIDER}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdatariprovider").html(tableresult);
            $("#footerdatariprovider").html(footer);

            const table = initDataTable("#datariprovider_table","#searchtable");

            const bulanField = ["JAN","FEB","MAR","APR","MEI","JUN","JUL","AGU","SEP","OKT","NOV","DES"];
            const namaBulan  = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const chartdata = bulanField.map((field, index) => {
                let all          = 0;

                result.forEach(item => {
                    const value = Number(item[field] || 0);
                    all += value;
                });

                return {
                    periode: namaBulan[index],
                    all,
                };

            });

            const dataProvider = [
                { label: "BPJS", value: 0 },
                { label: "Executive", value: 0 },
                { label: "Pasien Umum", value: 0 },
                { label: "Karyawan", value: 0 },
                { label: "Lain-Lain", value: 0 }
            ];

            result.forEach(item => {

                const total =
                    Number(item.JAN || 0) +
                    Number(item.FEB || 0) +
                    Number(item.MAR || 0) +
                    Number(item.APR || 0) +
                    Number(item.MEI || 0) +
                    Number(item.JUN || 0) +
                    Number(item.JUL || 0) +
                    Number(item.AGU || 0) +
                    Number(item.SEP || 0) +
                    Number(item.OKT || 0) +
                    Number(item.NOV || 0) +
                    Number(item.DES || 0);

                switch (item.REKANAN_ID) {

                    case "BPJS":
                        dataProvider[0].value += total;
                        break;

                    case "EXECU0000000001":
                        dataProvider[1].value += total;
                        break;

                    case "UMUM":
                        dataProvider[2].value += total;
                        break;

                    case "KARYA0000000002":
                    case "MCU K0000000001":
                        dataProvider[3].value += total;
                        break;

                    default:
                        dataProvider[4].value += total;
                        break;
                }

            });

            dataProvider.sort((a, b) => b.value - a.value);

            renderchartarea("grafikkunjunganri",chartdata,"Periode Pelayanan","Jumlah Kunjungan","Jumlah Kunjungan","all",null,"","all");
            renderchartpie("grafikkunjunganriprovider", dataProvider);
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

function datakunjunganridokter() {
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/dashboard/dashboard/datakunjunganridokter",
        data      : {selectperiode: selectperiode},
        type      : "POST",
        dataType  : "JSON",

        beforeSend: function () {

            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataridokter").empty();
            $("#footerdataridokter").empty();
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

            let totalJan = 0;
            let totalFeb = 0;
            let totalMar = 0;
            let totalApr = 0;
            let totalMei = 0;
            let totalJun = 0;
            let totalJul = 0;
            let totalAug = 0;
            let totalSep = 0;
            let totalOkt = 0;
            let totalNov = 0;
            let totalDes = 0;
            let grandTotal = 0;

            
            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldatakunjunganridokter = result;

            let tableresult = "";
            for (let i in result) {

                let jan = parseInt(result[i].JAN) || 0;
                let feb = parseInt(result[i].FEB) || 0;
                let mar = parseInt(result[i].MAR) || 0;
                let apr = parseInt(result[i].APR) || 0;
                let mei = parseInt(result[i].MEI) || 0;
                let jun = parseInt(result[i].JUN) || 0;
                let jul = parseInt(result[i].JUL) || 0;
                let aug = parseInt(result[i].AGU) || 0;
                let sep = parseInt(result[i].SEP) || 0;
                let okt = parseInt(result[i].OKT) || 0;
                let nov = parseInt(result[i].NOV) || 0;
                let des = parseInt(result[i].DES) || 0;

                let total = jan + feb + mar + apr + mei + jun + jul + aug + sep + okt + nov + des;

                totalJan += jan;
                totalFeb += feb;
                totalMar += mar;
                totalApr += apr;
                totalMei += mei;
                totalJun += jun;
                totalJul += jul;
                totalAug += aug;
                totalSep += sep;
                totalOkt += okt;
                totalNov += nov;
                totalDes += des;

                grandTotal += total;

                tableresult += `
                    <tr>
                        <td class="ps-4">${parseInt(i) + 1}</td>
                        <td>${result[i].NAMADOKTER}</td>
                        <td class="text-end">${todesimal(jan)}</td>
                        <td class="text-end">${todesimal(feb)}</td>
                        <td class="text-end">${todesimal(mar)}</td>
                        <td class="text-end">${todesimal(apr)}</td>
                        <td class="text-end">${todesimal(mei)}</td>
                        <td class="text-end">${todesimal(jun)}</td>
                        <td class="text-end">${todesimal(jul)}</td>
                        <td class="text-end">${todesimal(aug)}</td>
                        <td class="text-end">${todesimal(sep)}</td>
                        <td class="text-end">${todesimal(okt)}</td>
                        <td class="text-end">${todesimal(nov)}</td>
                        <td class="text-end">${todesimal(des)}</td>
                        <td class="text-end pe-4 fw-bold">${todesimal(total)}</td>
                    </tr>
                `;
            }

            let footer = `
                <tr class="fw-bolder text-muted bg-light">
                    <td colspan="2" class="text-center">
                        TOTAL
                    </td>
                    <td class="text-end">${todesimal(totalJan)}</td>
                    <td class="text-end">${todesimal(totalFeb)}</td>
                    <td class="text-end">${todesimal(totalMar)}</td>
                    <td class="text-end">${todesimal(totalApr)}</td>
                    <td class="text-end">${todesimal(totalMei)}</td>
                    <td class="text-end">${todesimal(totalJun)}</td>
                    <td class="text-end">${todesimal(totalJul)}</td>
                    <td class="text-end">${todesimal(totalAug)}</td>
                    <td class="text-end">${todesimal(totalSep)}</td>
                    <td class="text-end">${todesimal(totalOkt)}</td>
                    <td class="text-end">${todesimal(totalNov)}</td>
                    <td class="text-end">${todesimal(totalDes)}</td>
                    <td class="text-end pe-4">
                        ${todesimal(grandTotal)}
                    </td>
                </tr>
            `;

            $("#resultdataridokter").html(tableresult);
            $("#footerdataridokter").html(footer);

            const table = initDataTable("#datadokterri_table","#searchtable");
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