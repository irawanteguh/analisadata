loaddata();

$('#selectperiode').on('change', function () {
    loaddata();
});

function loaddata(){
    datajampulangpasienbln();
    datajampulangharian();
};

function datajampulangpasienbln(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/kpi/datajampulangpasienbln",
        type     : "POST",
        dataType : "JSON",
        data     : { selectperiode: selectperiode },

        beforeSend: function () {
            Swal.fire({
                title: 'Processing',
                html : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
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

            const result       = response.responResult || [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMap = {};

            result.forEach(item => {
                dataMap[item.BULAN] = {
                    val1: item.PERSENTASE,
                    val2: item.BIAYA_MAKAN
                };
            });


            const chartData = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                Value1 : dataMap[b]?.val1 ?? 0,
                Value2 : dataMap[b]?.val2 ?? 0
            }));

            renderchartarea("grafikkpipasienpulang",chartData,"Periode Pelayanan","% Pulang < Pukul 12:00",["Presentasi","Biaya Makan"],["Value1","Value2"],true,"Biaya Makan","Value1","Avg % Pulang < Pukul 12:00",null);
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

function datajampulangharian(){
    $.ajax({
        url      : url + "index.php/dashboard/kpi/datajampulangharian",
        type     : "POST",
        dataType : "JSON",
        beforeSend: function () {
            Swal.fire({
                title: 'Processing',
                html : 'Please wait while the system displays the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
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

            const result       = response.responResult || [];

            const chartData = result.map(item => ({
                periode: item.PERIODE,
                Value1 : item.PERSENTASE ?? 0,
                Value2 : item.BIAYA_MAKAN ?? 0
            }));


            renderchartarea("grafikkpipasienpulangharian",chartData,"Periode Pelayanan","% Pulang < Pukul 12:00",["Presentasi","Biaya"],["Value1","Value2"],true,"Biaya Gizi","Value1","Avg % Pulang < Pukul 12:00",null);
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