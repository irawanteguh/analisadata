// const today = new Date();
// startDate = today.toLocaleDateString('en-CA');
// endDate   = today.toLocaleDateString('en-CA');

// flatpickr('[name="dateperiode"]', {
//     mode: "range",
//     enableTime: false,
//     dateFormat: "d.m.Y",
//     maxDate: "today",
//     onChange: function (selectedDates, dateStr, instance) {
//         startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
//         endDate   = selectedDates[1]  ? selectedDates[1].toLocaleDateString('en-CA') : null;
//     }
// });

// $(document).on("click", ".btn-apply", function (e) {
//     e.preventDefault();

//     if (!startDate || !endDate) {
//         toastr["warning"]("Please select a valid date range", "Warning");
//         return;
//     }

//     pasienmeninggal(startDate,endDate);
// });


// pasientransit();
// pasienmeninggal(startDate,endDate);



// function pasienmeninggal(startDate,endDate){
//     $.ajax({
//         url       : url +"index.php/dashboard/dashboard/pasienmeninggal",
//         data      : {startdate:startDate,endate:endDate},
//         type      : "POST",
//         dataType  : "JSON",
//         beforeSend: function () {
//             Swal.fire({
//                 title            : 'Processing',
//                 html             : 'Please wait while the system displays the requested data.',
//                 allowOutsideClick: false,
//                 allowEscapeKey   : false,
//                 showConfirmButton: false,
//                 didOpen          : () => Swal.showLoading()
//             });

//             $("#totalpasienmeninggal").html("0 Px");
//             $("#descpasienmeninggal").html("Loading");
//             $("#resultdatapasienmeninggal").html("");
//         },
//         success:function(data){
//             let   tableresult      = "";
//             const result           = data.responResult || [];

//             if(data.responCode==="00"){
//                 for(var i in result){
//                     const timerId = "timer_" + i;
//                     const sexLabel    = result[i].SEXID === 'L' ? 'Laki-laki' : result[i].SEXID === 'P' ? 'Perempuan' : '';

//                     tableresult +="<tr>";
//                     tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
//                     tableresult +="<td class='text-end pe-4'><div>"+(result[i].MRPAS || "")+"</div><div>"+(result[i].NAMAPASIEN || "")+"</div></td>";
//                     tableresult +="</tr>"; 
//                 }
//             }

//             $("#totalpasienmeninggal").html(todesimal(result.length)+" Px");
//             $("#descpasienmeninggal").html(startDate+" - "+endDate);
//             $("#resultdatapasienmeninggal").html(tableresult);
            
//         },
//         complete: function () {
//             Swal.close();
//         },
//         error: function () {
//             Swal.fire({
//                 icon : 'error',
//                 title: 'Error',
//                 text : 'Unable to retrieve visit data.'
//             });
//         }
//     });
// };



demografiumur();
pasientransit();
datakunjunganrj();
datakunjunganri();
datakunjunganigd();
datakunjunganigdprovider();
datakunjunganrjprovider();
datakunjunganriprovider();

$('#selectperiode').on('change', function () {
    datakunjunganrj();
    datakunjunganri();
    datakunjunganigd();
    datakunjunganigdprovider();
    datakunjunganrjprovider();
    datakunjunganriprovider();
});

function pasientransit(){
    $.ajax({
        url       : url +"index.php/dashboard/dashboard/pasientransit",
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

            $("#totalpasientransit").html("0 Px");
            $("#resultdatapasientransit").html("");
        },
        success:function(data){
            let   tableresult      = "";
            const result           = data.responResult || [];

            if(data.responCode==="00"){
                for(var i in result){
                    const timerId = "timer_" + i;
                    const sexLabel    = result[i].SEXID === 'L' ? 'Laki-laki' : result[i].SEXID === 'P' ? 'Perempuan' : '';

                    tableresult +="<tr>";
                    tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult +="<td><div>"+(result[i].MRPAS || "")+"</div><div>"+(result[i].NAMAPASIEN || "")+"</div></td>";
                    tableresult +="<td>"+sexLabel+"</td>";
                    tableresult +="<td class='text-end'><div>"+(result[i].TGLMASUKTRANSIT || "")+"</div><div><span class='badge fw-bold' id='" + timerId + "'>Loading...</span></div></div></td>";
                    tableresult +="</tr>"; 
                }
            }

            $("#resultdatapasientransit").html(tableresult);
            $("#totalpasientransit").html(todesimal(result.length)+" Px");

            for(var i in result){
                const timerId = "timer_" + i;
                setCountdownSLA(result[i].TGLMASUKTRANSIT, timerId, 6);
            }
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve visit data.'
            });
        }
    });
};

function demografiumur(){
    $.ajax({
        url     : url + "index.php/dashboard/dashboard/demografiumur",
        type    : "POST",
        dataType: "JSON",
        cache   : false,
        beforeSend: function () {            
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system displays the requested data...',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });
        },

        success: function (data) {
            const result = data.responResult || [];
            renderPyramidChart(
                "grafikumur",
                result,
                [
                    {
                        name: "Laki-laki",
                        field: "LAKI_LAKI",
                        negative: true,
                        color: "#3b82f6"
                    },
                    {
                        name: "Perempuan",
                        field: "PEREMPUAN",
                        negative: false,
                        color: "#ec4899"
                    }
                ]
            );           
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve visit data.'
            });
        }
    });
};

function datakunjunganigd(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganigd",
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
                    icon : 'warning',
                    title: 'No Data Available',
                    text : 'No outpatient visit data found.'
                });
                return;
            }

            const result       = response.responResult || [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMapKunjungan = {};

            result.forEach(item => {
                dataMapKunjungan[item.BULAN] = parseInt(item.TOTAL_KUNJUNGAN);
            });

            const chartDataKunjungan = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                totalValue: dataMapKunjungan[b] ?? 0
            }));

            const bulanAktif = chartDataKunjungan.filter(item => item.totalValue > 0);
            const totalAll   = bulanAktif.reduce((sum, item) => sum + item.totalValue, 0);
            const avgValue   = bulanAktif.length > 0 ? totalAll / bulanAktif.length : 0;

            renderchartarea(
                "grafikkunjunganigd",
                chartDataKunjungan,
                "Periode Pelayanan",
                "Jumlah Kunjungan",
                "Kunjungan IGD",
                "totalValue",
                avgValue,
                "Rata-rata: " + Math.round(avgValue).toLocaleString()
            );
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve emergency visit data.'
            });
        }
    });
};

function datakunjunganrj(){
    let selectperiode = $("select[name='selectperiode']").val();

    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganrj",
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
                    icon : 'warning',
                    title: 'No Data Available',
                    text : 'No outpatient visit data found.'
                });
                return;
            }

            const result       = response.responResult || [];

            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const mapExec = {};
            const mapNonExec = {};

            result.forEach(item => {

                mapExec[item.BULAN]     = parseInt(item.KUNJUNGAN_EXECUTIVE);
                mapNonExec[item.BULAN]  = parseInt(item.KUNJUNGAN_NON_EXECUTIVE);

            });

            const chartData = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                executive: mapExec[b] ?? 0,
                nonexecutive: mapNonExec[b] ?? 0
            }));


            // rata-rata total kunjungan
            const totalAll = chartData.reduce((sum, item) =>
                sum + item.executive + item.nonexecutive, 0);

            const avgValue = totalAll / 12;


            renderchartarea(
                "grafikkunjunganrj",
                chartData,
                "Periode Pelayanan",
                "Non Executive",
                ["Non Executive","Executive"],
                ["nonexecutive","executive"],
                avgValue,
                "Rata-rata " + Math.round(avgValue).toLocaleString(),
                1,
                "Executive"
            );
        },

        complete: function () {
            Swal.close();
        },

        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve outpatient visit data.'
            });
        }
    });
}

function datakunjunganri(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganri",
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
                    icon : 'warning',
                    title: 'No Data Available',
                    text : 'No outpatient visit data found.'
                });
                return;
            }

            const result       = response.responResult || [];
            const bulanLengkap = ["01","02","03","04","05","06","07","08","09","10","11","12"];
            const namaBulan    = ["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Agu","Sep","Okt","Nov","Des"];

            const dataMapKunjungan = {};

            result.forEach(item => {
                dataMapKunjungan[item.BULAN] = parseInt(item.TOTAL_KUNJUNGAN);
            });

            const chartDataKunjungan = bulanLengkap.map((b, index) => ({
                periode: namaBulan[index],
                totalValue: dataMapKunjungan[b] ?? 0
            }));

            const bulanAktif = chartDataKunjungan.filter(item => item.totalValue > 0);
            const totalAll   = bulanAktif.reduce((sum, item) => sum + item.totalValue, 0);
            const avgValue   = bulanAktif.length > 0 ? totalAll / bulanAktif.length : 0;

            renderchartarea(
                "grafikkunjunganri",
                chartDataKunjungan,
                "Periode Pelayanan",
                "Jumlah Kunjungan",
                "Kunjungan Rawat Inap",
                "totalValue",
                avgValue,
                "Rata-rata: " + Math.round(avgValue).toLocaleString()
            );
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve inpatient visit data.'
            });
        }
    });
};

function datakunjunganigdprovider(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganigdprovider",
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
                    icon : 'warning',
                    title: 'No Data Available',
                    text : 'No outpatient visit data found.'
                });
                return;
            }

            renderchartpie("grafikkunjunganigdprovider", response.responResult);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve emergency visit data.'
            });
        }
    });
};

function datakunjunganrjprovider(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganrjprovider",
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
                    icon : 'warning',
                    title: 'No Data Available',
                    text : 'No outpatient visit data found.'
                });
                return;
            }

            renderchartpie("grafikkunjunganrjprovider", response.responResult);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve emergency visit data.'
            });
        }
    });
};

function datakunjunganriprovider(){
    let selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url      : url + "index.php/dashboard/dashboard/datakunjunganriprovider",
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
                    icon : 'warning',
                    title: 'No Data Available',
                    text : 'No outpatient visit data found.'
                });
                return;
            }

            renderchartpie("grafikkunjunganriprovider", response.responResult);
        },
        complete: function () {
            Swal.close();
        },
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'System Error',
                text : 'Failed to retrieve emergency visit data.'
            });
        }
    });
};

function analisaaikunjunganigd() {
    let selectperiode = $("select[name='selectperiode']").val();

    Swal.fire({
        title: 'Sedang menganalisis...',
        html: 'AI sedang memproses data kunjungan IGD. Mohon tunggu beberapa saat.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();

            $.ajax({
                url: url + "index.php/dashboard/dashboard/analisaaikunjunganigd",
                data: { selectperiode: selectperiode },
                type: "POST",
                dataType: "JSON",
                success: function(response) {
                    if(response.status){
                        Swal.fire({
                            title: 'Analisa Selesai',
                            html: '<pre style="text-align:left;">'+response.result_ai+'</pre>',
                            icon: 'success',
                            width: 600
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: response.message,
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr){
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menghubungi server.',
                        icon: 'error'
                    });
                }
            });
        }
    });
}

function typeWriterEffect(element, text, speed = 5, callback = null) {
    element.val('');
    let i = 0;

    function typing() {
        if (i < text.length) {
            element.val(element.val() + text.charAt(i));
            element.scrollTop(element[0].scrollHeight);
            i++;
            setTimeout(typing, speed);
        } else {
            if (callback) callback(); // 🔥 jalankan setelah selesai
        }
    }

    typing();
}