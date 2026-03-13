let startDate, endDate;

flatpickr('[name="dateperiode"]', {
    mode: "range",
    enableTime: false,
    dateFormat: "d.m.Y",
    maxDate: "today",
    onChange: function (selectedDates, dateStr, instance) {
        startDate = selectedDates[0] ? selectedDates[0].toLocaleDateString('en-CA') : null;
        endDate   = selectedDates[1] ? selectedDates[1].toLocaleDateString('en-CA') : null;

    }
});

$(document).on("click", ".btn-apply", function (e) {
    e.preventDefault();

    if (!startDate || !endDate) {
        toastr["warning"]("Please select a valid date range", "Warning");
        return;
    }

    laporankematian(startDate, endDate);
});

$('#modal_analisakematian').on('shown.bs.modal', function () {
    chartews();
});

laporankematian(startDate, endDate);

function laporankematian(startDate,endDate){
    $.ajax({
        url     : url +"index.php/inpatientraw/kematian/laporankematian",
        data    : {startdate:startDate,endate:endDate},
        type    : "POST",
        dataType: "JSON",
        beforeSend : function () {
            toastr.clear();
            toastr["info"]("Sending request...", "Please wait");
            $("#resultdatalaporankematian").html("");
        },
        success:function(data){
            toastr.clear();
            var result       = "";
            var tableresult  = "";

            if(data.responCode==="00"){
                result = data.responResult;
                
                for(var i in result){
                    
                    getvariabel = " data-pasienid='"+result[i].PASIEN_ID+"'"+
                                  " data-episodeid='"+result[i].EPISODE_ID+"'";

                    btnaction  = "<a class='dropdown-item btn btn-sm' href='#' onclick=\"openSejarah('" + result[i].PASIEN_ID + "')\"><i class='bi bi-clock-history text-primary pe-4'></i>Sejarah</a>";
                    btnaction += "<a class='dropdown-item btn btn-sm' data-bs-toggle='modal' data-bs-target='#modal_analisakematian' "+getvariabel+"><i class='bi bi-heart-pulse text-danger'></i>Tindakan</a>";

                    tableresult +="<tr>";
                    tableresult +="<td class='ps-4'><a href='#' onclick=\"openSejarah('"+result[i].PASIEN_ID+"')\">"+result[i].REKAM_MEDIS+"</a></td>";
                    tableresult +="<td>"+result[i].NAMA_PASIEN+"</td>";
                    tableresult +="<td><div>"+result[i].TGLLAHIR+"</div><div>"+result[i].UMUR+"</div></td>";
                    tableresult +="<td>"+result[i].JENISKELAMIN+"</td>";
                    // tableresult +="<td>"+result[i].ALAMAT+"</td>";
                    tableresult +="<td>"+result[i].TGLMASUK+"</td>";
                    tableresult +="<td>"+result[i].TGLMASUKINAP+"</td>";
                    tableresult +="<td>"+result[i].TGLKELUAR+"</td>";
                    // tableresult +="<td>"+result[i].PROVIDER+"</td>";
                    // tableresult +="<td>"+(result[i].NO_SEP || "")+"</td>";
                    // tableresult +="<td>"+result[i].KELAS+"</td>";
                    // tableresult +="<td>"+(result[i].KELAS_TANGGUNGAN || "")+"</td>";
                    tableresult +="<td>"+result[i].RUANG_RWT+"</td>";
                    tableresult +="<td>"+result[i].NAMA_DOKTER+"</td>";
                    // tableresult +="<td>"+(result[i].DIAGNOSA_AWAL || result[i].SOAP_AWAL)+"</td>";
                    // tableresult +="<td>"+(result[i].DIAGNOSA_AKHIR || result[i].SOAP_AKHIR)+"</td>";
                    tableresult +="<td>"+result[i].KETERANGAN_PULANG+"</td>";
                    tableresult += "<td class='text-end'>";
                        tableresult += "<div class='btn-group' role='group'>";
                            tableresult += "<button id='btnGroupDrop1' type='button' class='btn btn-light-primary dropdown-toggle btn-sm' data-bs-toggle='dropdown' aria-expanded='false'>Action</button>";
                            tableresult += "<div class='dropdown-menu' aria-labelledby='btnGroupDrop1'>";
                                tableresult += btnaction;
                            tableresult += "</div>";
                        tableresult += "</div>";
                    tableresult += "</td>";
                    tableresult +="</tr>";                    
                }
            }
            $("#resultdatalaporankematian").html(tableresult);
            toastr[data.responHead](data.responDesc, "INFORMATION");
        },
        error: function(){
            // console.log("Error get data jumlah pasien pulang");
        }
    });
};


function chartews(){
    $.ajax({
        url      : url + "index.php/inpatientraw/kematian/chartews",
        type     : "POST",
        dataType : "JSON",
        success  : function(data){

            if(data.responCode !== "00") return;

            let raw = data.responResult;

            let categories = [];
            let skorArr = [];

            raw.forEach(row => {
                categories.push(row.CREATED_DATE);
                skorArr.push(parseInt(row.SKOR));
            });

            let len = categories.length;

            renderChartCustom(
                "chartews",
                categories,
                [
                    {
                        name: 'Normal',
                        type: 'bar',
                        color: '#1AE396',
                        data: repeatValue(4, len)
                    },
                    {
                        name: 'Warning',
                        type: 'bar',
                        color: '#FEB018',
                        data: repeatValue(6, len)
                    },
                    {
                        name: 'Danger',
                        type: 'bar',
                        color: '#FE4560',
                        data: repeatValue(11, len)
                    },
                    {
                        name: 'Skor EWS',
                        type: 'line',
                        color: '#775DD0',
                        data: skorArr
                    }
                ],
                "Skor Early Warning System",
                11
            );
        }
    });
}





