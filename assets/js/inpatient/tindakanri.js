let globaldatatindakanri = [];

datatindakanri();

$('#selectperiode').on('change', function () {
    datatindakanri();
});

$("#btndownloaddatatindakanri_table").on("click", function () {

    exportToExcel(
        globaldatatindakanri,
        "Tindakan Rawat Inap",
        "Tindakan_Rawat_Inap.xlsx",
        {
            formatter: (item, index) => ({
                "No"              : index + 1,
                "Nama Pelayanan"  : item.NAMA_LAYAN1 || "",
                "Kategori"        : item.KETERANGAN || "",
                "JAN"             : item.JAN || 0,
                "FEB"             : item.FEB || 0,
                "MAR"             : item.MAR || 0,
                "APR"             : item.APR || 0,
                "MEI"             : item.MEI || 0,
                "JUN"             : item.JUN || 0,
                "JUL"             : item.JUL || 0,
                "AGU"             : item.AGU || 0,
                "SEP"             : item.SEP || 0,
                "OKT"             : item.OKT || 0,
                "NOV"             : item.NOV || 0,
                "DES"             : item.DES || 0,
                "TOTAL"           : (
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
                    Number(item.DES || 0)
                )
            })
        }
    );

});

function datatindakanri(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/inpatient/tindakanri/datatindakanri",
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

            $("#resultdatatindakanri").empty();
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
            globaldatatindakanri = result;

            var tableresult    = "";
            for (var i in result) {

                var jan = Number(result[i].JAN || 0);
                var feb = Number(result[i].FEB || 0);
                var mar = Number(result[i].MAR || 0);
                var apr = Number(result[i].APR || 0);
                var mei = Number(result[i].MEI || 0);
                var jun = Number(result[i].JUN || 0);
                var jul = Number(result[i].JUL || 0);
                var agu = Number(result[i].AGU || 0);
                var sep = Number(result[i].SEP || 0);
                var okt = Number(result[i].OKT || 0);
                var nov = Number(result[i].NOV || 0);
                var des = Number(result[i].DES || 0);

                var total = jan + feb + mar + apr + mei + jun + jul + agu + sep + okt + nov + des;

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td>"+(result[i].NAMA_LAYAN1||"")+"</td>";
                tableresult += "<td>"+(result[i].KODE_ICD||"")+"</td>";
                tableresult += "<td>"+(result[i].LONG_DESCRIPTION||"")+"</td>";
                tableresult += "<td>"+(result[i].KETERANGAN||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].JAN)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].FEB)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].MAR)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].APR)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].MEI)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].JUN)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].JUL)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].AGU)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].SEP)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].OKT)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].NOV)||"")+"</td>";
                tableresult += "<td class='text-end'>"+(todesimal(result[i].DES)||"")+"</td>";
                tableresult += "<td class='text-end pe-4 fw-bold'>" + todesimal(total) + "</td>";
                tableresult += "</tr>";
            }

            $("#resultdatatindakanri").html(tableresult);
            const table = initDataTable("#datatindakanri_table","#searchtable",50);
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