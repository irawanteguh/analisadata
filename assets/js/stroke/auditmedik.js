let globaldataauditmedik = [];

dataauditmedik();

$('#selectperiode').on('change', function () {
    dataauditmedik();
});

$("#btndownloaddataauditmedik_table").on("click", function () {
    exportToExcel(
        globaldataauditmedik,
        "Audit Medik",
        "Audit_Medik_Stroke.xlsx",
        {
            formatter: (item, index) => ({
                "No"                         : index + 1,
                "Mr Pasien"                  : item.MRPAS || "",
                "Nama Pasien"                : item.NAMAPASIEN || "",
                "Jenis Kelamin"              : item.SEX_ID || "",
                "Tempat Lahir"               : item.TEMPAT_LAHIR_TXT || "",
                "Tgl Lahir"                  : item.TGLLAHIR || "",
                "Umur"                       : item.UMUR || "",
                "Tgl Masuk"                  : item.TGLMASUK || "",
                "Tgl Regis IGD"              : item.REGISTRASIIGD || "",
                "Tgl Code Stroke"            : item.CODECREATEDATE || "",
                "Jml Order CT Scan"          : item.JMLORDERCT || "",
                "First Order CT Scan"        : item.ORDERFIRST || "",
                "Last Order CT Scan"         : item.ORDERLAST || "",
                "First Radiografer CT Scan"  : item.RADIOGRAFERSTARTFIRST || "",
                "Last Radiografer CT Scan"   : item.RADIOGRAFERSTARTLAST || "",
                "First Radiolog CT Scan"     : item.RADIOLOGTFIRST || "",
                "Last Radiolog CT Scan"      : item.RADIOLOGLAST || ""
            })
        }
    );
});

function dataauditmedik() {
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url + "index.php/stroke/auditmedik/dataauditmedik",
        data      : { selectperiode: selectperiode },
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

            $("#resultdataauditmedik").empty();
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

            const result = Array.isArray(response.responResult) ? response.responResult : [];
            globaldataauditmedik = result;

            var tableresult = "";
            for (var i in result) {

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i) + 1) + "</td>";
                tableresult += "<td>" + (result[i].MRPAS || "") + "</td>";
                tableresult += "<td>" + (result[i].NAMAPASIEN || "") + "</td>";
                tableresult += "<td>" + (result[i].SEX_ID || "") + "</td>";
                tableresult += "<td>" + (result[i].TEMPAT_LAHIR_TXT || "") + "</td>";
                tableresult += "<td>" + (result[i].TGLLAHIR || "") + "</td>";
                tableresult += "<td>" + (result[i].UMUR || "") + "</td>";
                tableresult += "<td>" + (result[i].TGLMASUK || "") + "</td>";
                tableresult += "<td>" + (result[i].REGISTRASIIGD || "") + "</td>";
                tableresult += "<td>" + (result[i].CODECREATEDATE || "") + "</td>";
                tableresult += "<td>" + (result[i].JMLORDERCT || "") + "</td>";
                tableresult += "<td>" + (result[i].ORDERFIRST || "") + "</td>";
                tableresult += "<td>" + (result[i].ORDERLAST || "") + "</td>";
                tableresult += "<td>" + (result[i].RADIOGRAFERSTARTFIRST || "") + "</td>";
                tableresult += "<td>" + (result[i].RADIOGRAFERSTARTLAST || "") + "</td>";
                tableresult += "<td>" + (result[i].RADIOLOGTFIRST || "") + "</td>";
                tableresult += "<td class='pe-4 text-end'>" + (result[i].RADIOLOGLAST || "") + "</td>";
                tableresult += "</tr>";
            }

            $("#resultdataauditmedik").html(tableresult);
            const table = initDataTable("#dataauditmedik_table", "#searchtable");

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