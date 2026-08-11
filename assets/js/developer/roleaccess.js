datauser();

$(document).on("click", ".btnPilihUser", function(){
    let userid = $(this).data("userid");
    let nama   = $(this).data("nama");
    datamodules(userid);
});


function datauser(){
    $.ajax({
        url       : url +"index.php/developer/roleaccess/datauser",
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

            $("#resultdatauser").html("");
        },
        success:function(data){
            var   tableresult      = "";
            const result           = data.responResult || [];

            if(data.responCode==="00"){
                for(var i in result){
                    let nilai = todesimal(result[i].T_SELISIH) || "0";

                    tableresult +="<tr>";
                    tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
                    tableresult +="<td>"+(result[i].USER_ID || "")+"</td>";      
                    tableresult +="<td>"+(result[i].NAMA || "")+"</td>";
                    tableresult += `
                        <td class='text-end'>
                            <a href="javascript:void(0)"
                               class="btn btn-sm btn-light-primary btnPilihUser"
                               data-userid="${result[i].USER_ID}"
                               data-nama="${result[i].NAMA}">
                               Pilih
                            </a>
                        </td>
                    `;  
                    tableresult +="</tr>";                    
                }
            }

            $("#resultdatauser").html(tableresult);
            const table = initDataTable("#datauser_table","#searchtable");
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

function datamodules(userid) {
    $.ajax({
        url       : url + "index.php/developer/roleaccess/datamodules",
        data      : {userid:userid},
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
        beforeSend: function () {
            $("#listmodules").html("");
        },
        success: function (data) {
            var tableresult = "";

            if (data.responCode === "00") {
                var result = data.responResult;

                function generateChildElements(parentId, level) {
                    var childElements = "";
                    for (var j in result) {
                        if (result[j].MODULES_HEADER_ID === parentId) {
                            var indent = level * 20;

                            childElements += "<div class='d-flex align-items-center p-3 rounded-3 border-2 border-dashed border-gray-300 mb-1 d-flex justify-content-between' style='margin-left:" + indent + "px;' data-kt-search-element='customer'>";
                            childElements += "<div class='fw-bold'>";
                            childElements += "<span class='fs-6 text-gray-800 me-2'><i class='" + result[j].ICON + "'></i> " + result[j].MODULES_NAME + "</span><br>";
                            childElements += "<span class='fs-6 text-muted me-2'>" + result[j].PACKAGE + (result[j].DEF_CONTROLLER ? " - " + result[j].DEF_CONTROLLER : "") + " </span>";
                            childElements += "</div>";
                            childElements += "<div class='fw-bold d-flex justify-content-end'>";
                            childElements += "<div class='form-check form-switch form-check-custom form-check-solid'>";
                            childElements += "<input class='form-check-input h-20px w-30px' type='checkbox' id='" + result[j].MODULES_ID + "' data-parent-id='" + parentId + "' " + (result[j].TRANS_ID != null ? "checked='checked'" : "") + " />";
                            childElements += "</div>";
                            childElements += "</div>";
                            childElements += "</div>";

                            childElements += generateChildElements(result[j].MODULES_ID, level + 1);
                        }
                    }
                    return childElements;
                }

                for (var i in result) {
                    if (result[i].PARENT === "C") {
                        tableresult += "<div class='d-flex align-items-center p-3 rounded-3 border-2 border-dashed border-gray-300 mb-1 d-flex justify-content-between' data-kt-search-element='customer'>";
                        tableresult += "<div class='fw-bold'>";
                        tableresult += "<span class='fs-6 text-gray-800 me-2'><i class='" + result[i].ICON + "'></i> " + result[i].MODULES_NAME + "</span>";
                        tableresult += "</div>";
                        tableresult += "<div class='fw-bold d-flex justify-content-end'>";
                        tableresult += "<div class='form-check form-switch form-check-custom form-check-solid'>";
                        tableresult += "<input class='form-check-input h-20px w-30px' type='checkbox' id='" + result[i].MODULES_ID + "' data-parent-id='' " + (result[i].TRANS_ID != null ? "checked='checked'" : "") + " />";
                        tableresult += "</div>";
                        tableresult += "</div>";
                        tableresult += "</div>";

                        tableresult += generateChildElements(result[i].MODULES_ID, 1);
                    }
                }
            }

            $("#listmodules").html(tableresult);

            $(document).on("change", ".form-check-input", function (e) {
                e.preventDefault();
                var switchId    = $(this).attr('id');
                var switchValue = $(this).prop('checked');
                var parentId    = $(this).data('parent-id');
            
                if(switchValue){
                    if(parentId){
                        checkParentCheckboxes(parentId);
                    }
                } else {
                    uncheckChildCheckboxes(switchId);
                }

                $.ajax({
                    url       : url + "index.php/developer/roleaccess/addaccess",
                    data      : {switchId:switchId,switchValue:switchValue,userid:userid},
                    method    : "POST",
                    dataType  : "JSON",
                    cache     : false,
                    beforeSend: function () {
                        // toastr.clear();
                        // toastr["info"]("Sending request...", "Please wait");
                    },
                    success: function (data) {
                        // toastr.clear();
                        // toastr[data.responHead](data.responDesc, "INFORMATION");
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            title            : "<h1 class='font-weight-bold' style='color:#234974;'>I'm Sorry</h1>",
                            html             : "<b>" + error + "</b>",
                            icon             : "error",
                            confirmButtonText: "Please Try Again",
                            buttonsStyling   : false,
                            timerProgressBar : true,
                            timer            : 5000,
                            customClass      : { confirmButton: "btn btn-danger" },
                            showClass        : { popup: "animate__animated animate__fadeInUp animate__faster" },
                            hideClass        : { popup: "animate__animated animate__fadeOutDown animate__faster" }
                        });
                    }
                });

                function checkParentCheckboxes(parentId) {
                    if (parentId) {
                        var parentCheckbox = $("#" + parentId);
                        if (parentCheckbox.length) {
                            parentCheckbox.prop('checked', true);
                            var grandParentId = parentCheckbox.data('parent-id');
                            if (grandParentId) {
                                checkParentCheckboxes(grandParentId);
                            }
                        }
                    }
                };
            
                function uncheckChildCheckboxes(parentId) {
                    $(".form-check-input[data-parent-id='" + parentId + "']").each(function () {
                        $(this).prop('checked', false);
                        uncheckChildCheckboxes($(this).attr('id'));
                    });
                };
            });
    
        },
        complete: function () {
            toastr.clear();
        },
        error: function (xhr, status, error) {
            Swal.fire({
                title            : "<h1 class='font-weight-bold' style='color:#234974;'>I'm Sorry</h1>",
                html             : "<b>" + error + "</b>",
                icon             : "error",
                confirmButtonText: "Please Try Again",
                buttonsStyling   : false,
                timerProgressBar : true,
                timer            : 5000,
                customClass      : { confirmButton: "btn btn-danger" },
                showClass        : { popup: "animate__animated animate__fadeInUp animate__faster" },
                hideClass        : { popup: "animate__animated animate__fadeOutDown animate__faster" }
            });
        }
    });
    return false;
};