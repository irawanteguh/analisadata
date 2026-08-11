datapasientransit();

$('#selectperiode').on('change', function () {
    datapasientransit();
});

function datapasientransit(){
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url       : url +"index.php/inpatient/pasientransit/datapasientransit",
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

            $("#resultdatapasientransit").empty();
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

            var tableresult    = "";
            for (var i in result) {

                tableresult += "<tr>";
                tableresult += "<td class='ps-4'>" + (parseInt(i)+1) + "</td>";
                tableresult += "<td><div>"+(result[i].MRPAS||"")+"</div><div>"+(result[i].NAMAPASIEN||"")+"</div></td>";
                tableresult += "<td>"+(result[i].TGLMASUKTRANSIT||"")+"</td>";
                tableresult += "<td>"+(result[i].TGLKELUARTRANSIT||"")+"</td>";
                tableresult += "<td class='text-end pe-4'>"+calculateDuration(result[i].TGLMASUKTRANSIT,result[i].TGLKELUARTRANSIT)+"</td>";
                tableresult += "</tr>";
            }

            $("#resultdatapasientransit").html(tableresult);
            const table = initDataTable("#datapasientransit_table","#searchtable");

            const total        = aggregate(result, "count", "PERIODE");
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMap = {};

            total.forEach(item => {
                dataMap[item.periode] = item.value;
            });

            const chartData = bulanLengkap.map((bulan, index) => ({
                periode: namaBulan[index],
                value  : dataMap[bulan] || 0
            }));

            renderchartarea(
                "trenpasientransit",
                chartData,
                "Periode Masuk Ruang Transit",          // title X
                "Jumlah Pasien",    // title Y
                "Pasien Transit",   // nama series
                "value"             // field data
            );

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