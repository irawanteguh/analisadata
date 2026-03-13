perhitunganshift();

$('#selectperiode').on('change', function () {
    perhitunganshift();
});

function perhitunganshift() {
    const selectperiode = $("select[name='selectperiode']").val();
    $.ajax({
        url     : url + "index.php/hrd/shift/perhitunganshift",
        type    : "POST",
        dataType: "JSON",
        data    : { selectperiode: selectperiode },

        beforeSend: function () {
            Swal.fire({
                title            : 'Processing',
                html             : 'Please wait while the system retrieves the requested data.',
                allowOutsideClick: false,
                allowEscapeKey   : false,
                showConfirmButton: false,
                didOpen          : () => Swal.showLoading()
            });

            $("#resultdataperhitunganshift").empty();
            $("#resultdataperhitunganshiftname").empty();
        },

        success: function (data) {

            const result = data.responResult || [];
            const total  = result.length;

            if (data.responCode !== "00") {
                Swal.fire({
                    icon : 'warning',
                    title: 'No Records Found',
                    text : 'No records are available for the selected period.',
                    showConfirmButton: false
                });
                return;
            }

            Swal.close();

            const tbodyDetail  = document.getElementById("resultdataperhitunganshift");
            const tbodySummary = document.getElementById("resultdataperhitunganshiftname");

            tbodyDetail.innerHTML  = "";
            tbodySummary.innerHTML = "";

            const summary = {};
            let index = 0;
            const batchSize = 200; // render 200 row per frame (super smooth)

            function renderBatch() {

                const fragment = document.createDocumentFragment();

                let count = 0;

                while (index < total && count < batchSize) {

                    const row = result[index];

                    const tr = document.createElement("tr");

                    tr.innerHTML = `
                        <td class='ps-4'>${index + 1}</td>
                        <td>${row.PERIODETGL || ""}</td>
                        <td>${row.NAMAHARI || ""}</td>
                        <td>${row.NIK || ""}</td>
                        <td>${row.NAMAKARYAWAN || ""}</td>
                        <td>${row.UNIT || ""}</td>
                        <td>${row.SUB_UNIT || ""}</td>
                        <td>${row.JENIS_PEGAWAI || ""}</td>
                        <td>${row.FLAG || ""}</td>
                        <td class='text-end'>${row.JAMMASUK || ""}</td>
                        <td class='text-end'>${row.JAMPULANG || ""}</td>
                        <td class='text-end'>${row.REALMASUK || ""}</td>
                        <td class='text-end'>${row.REALPULANG || ""}</td>
                        <td class='text-end'>${todesimal(row.NOMINALUANGSHIFT)}</td>
                        <td class='text-end pe-4'>${row.HARILIBUR || ""}</td>
                    `;

                    fragment.appendChild(tr);

                    // ===== summary =====
                    if (!summary[row.NIK]) {
                        summary[row.NIK] = {
                            nik      : row.NIK,
                            nama     : row.NAMAKARYAWAN,
                            unit     : row.UNIT,
                            subunit  : row.SUB_UNIT,
                            kategori : row.JENIS_PEGAWAI,
                            total    : 0
                        };
                    }

                    summary[row.NIK].total += parseFloat(row.NOMINALUANGSHIFT || 0);

                    index++;
                    count++;
                }

                tbodyDetail.appendChild(fragment);

                if (index < total) {
                    requestAnimationFrame(renderBatch);
                } else {
                    buildSummary();
                }
            }

            function buildSummary() {

                const fragment = document.createDocumentFragment();
                let no = 1;

                Object.values(summary).forEach(item => {

                    const tr = document.createElement("tr");

                    tr.innerHTML = `
                        <td class="ps-4">${no++}</td>
                        <td>${item.nik}</td>
                        <td>${item.nama}</td>
                        <td>${item.unit}</td>
                        <td>${item.subunit || ''}</td>
                        <td>${item.kategori}</td>
                        <td class="text-end">${todesimal(item.total)}</td>
                        <td class="pe-4 text-end">
                            <button 
                                class="btn btn-sm btn-light-primary btn-detail-shift"
                                data-nik="${item.nik}">
                                Detail
                            </button>
                        </td>
                    `;

                    fragment.appendChild(tr);
                });

                tbodySummary.appendChild(fragment);
            }

            renderBatch();
        },

        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to retrieve shift data.'
            });
        }
    });
}

$(document).on("click", ".btn-detail-shift", function () {

    const nik = $(this).data("nik");
    const data = window.globalShiftResult || [];

    console.log(data);

    let detailHtml = "";
    let totalNominal = 0;
    let no = 1;

    const filtered = data.filter(row => 
        String(row.NIK) === String(nik)
    );

    if (filtered.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "No Detail Found",
            text: "No detail records available for this employee."
        });
        return;
    }

    filtered.forEach(row => {

        totalNominal += parseFloat(row.NOMINALUANGSHIFT || 0);

        detailHtml += `
            <tr>
                <td class="ps-4">${no++}</td>
                <td>${row.PERIODETGL || ""}</td>
                <td>${row.NAMAHARI || ""}</td>
                <td>${row.FLAG || ""}</td>
                <td>${row.JAMMASUK || ""}</td>
                <td>${row.JAMPULANG || ""}</td>
                <td>${row.REALMASUK || ""}</td>
                <td>${row.REALPULANG || ""}</td>
                <td class="text-end">${todesimal(row.NOMINALUANGSHIFT)}</td>
                <td class="text-end pe-4">${row.HARILIBUR || ""}</td>
            </tr>
        `;
    });

    $("#resultdetailperhitunganshift").html(detailHtml);
    $("#totalnominaldetail").html(todesimal(totalNominal));

    const modal = new bootstrap.Modal(
        document.getElementById('modal_detailshift')
    );
    modal.show();
});

// function perhitunganshift() {
//     const selectperiode = $("select[name='selectperiode']").val();
//     $.ajax({
//         url     : url + "index.php/hrd/shift/perhitunganshift",
//         type    : "POST",
//         dataType: "JSON",
//         data    : { selectperiode:selectperiode },
//         beforeSend: function () {
//             Swal.fire({
//                 title: 'Processing',
//                 html: 'Please wait while the system displays the requested data.',
//                 allowOutsideClick: false,
//                 allowEscapeKey: false,
//                 showConfirmButton: false,
//                 didOpen: () => Swal.showLoading()
//             });

//             $("#resultdataperhitunganshift").empty();
//             $("#resultdataperhitunganshiftname").empty();
//         },
//         success: function (data) {
//             let   tableresult      = "";
//             const result           = data.responResult || [];

//             if(data.responCode==="00"){
//                 for(var i in result){
//                     tableresult +="<tr>";
//                     tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
//                     tableresult +="<td>"+(result[i].PERIODETGL || "")+"</td>";
//                     tableresult +="<td>"+(result[i].NAMAHARI || "")+"</td>";
//                     tableresult +="<td>"+(result[i].NIK || "")+"</td>";
//                     tableresult +="<td>"+(result[i].NAMAKARYAWAN || "")+"</td>";
//                     tableresult +="</tr>"; 
//                 }
//             }

//             $("#resultdataperhitunganshift").html(tableresult);
//         },
//         complete: function () {
//             Swal.close();
//         },
//         error: function () {
//             Swal.close();
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: 'Unable to retrieve shift data.'
//             });
//         }
//     });
// }

// function perhitunganshift() {
//     const selectperiode = $("select[name='selectperiode']").val();
//     $.ajax({
//         url     : url + "index.php/hrd/shift/perhitunganshift",
//         type    : "POST",
//         dataType: "JSON",
//         data    : { selectperiode: selectperiode },
//         beforeSend: function () {
//             Swal.fire({
//                 title: 'Processing',
//                 html: 'Please wait while the system displays the requested data.',
//                 allowOutsideClick: false,
//                 allowEscapeKey: false,
//                 showConfirmButton: false,
//                 didOpen: () => Swal.showLoading()
//             });

//             $("#resultdataperhitunganshift").empty();
//         },
//         success: function (data) {
//             let   tableresult = "";
//             const result      = data.responResult || [];
//             const total       = result.length;

//             if (data.responCode !== "00") {
//                 Swal.fire({
//                     icon: 'warning',
//                     title: 'No Records Found',
//                     text: 'No records are available for the selected period.'
//                 });
//                 return;
//             }

//             for(var i in result){
//                 tableresult +="<tr>";
//                 tableresult +="<td class='ps-4'>"+(parseInt(i)+1)+"</td>";
//                 tableresult +="<td>"+(result[i].PERIODETGL || "")+"</td>";
//                 tableresult +="<td>"+(result[i].NAMAHARI || "")+"</td>";
//                 tableresult +="<td>"+(result[i].NIK || "")+"</td>";
//                 tableresult +="<td>"+(result[i].NAMAKARYAWAN || "")+"</td>";
//                 tableresult +="</tr>"; 

//                 Swal.getHtmlContainer().innerHTML = 'Rendering data... <b>' + (i + 1) + '</b> / ' + total + ' rows';
//             }

//             $("#resultdataperhitunganshift").html(tableresult);

//             Swal.fire({
//                 icon: 'success',
//                 title: 'Completed',
//                 text: total + ' records have been successfully rendered.',
//                 timer: 2000,
//                 showConfirmButton: false
//             });
//         },
//         error: function () {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: 'Unable to retrieve shift data.'
//             });
//         }
//     });
// }

// function perhitunganshift() {
//     const selectperiode = $("select[name='selectperiode']").val();
//     $.ajax({
//         url: url + "index.php/hrd/shift/perhitunganshift",
//         type: "POST",
//         dataType: "JSON",
//         data: { selectperiode:selectperiode },

//         beforeSend: function () {
//             Swal.fire({
//                 title: 'Processing',
//                 html: 'Please wait while the system displays the requested data.',
//                 allowOutsideClick: false,
//                 allowEscapeKey: false,
//                 showConfirmButton: false,
//                 didOpen: () => Swal.showLoading()
//             });

//             $("#resultdataperhitunganshift").empty();
//             $("#resultdataperhitunganshiftname").empty();
//         },

//         success: function (data) {

//             if (data.responCode !== "00") {
//                 Swal.close();
//                 toastr.error("Data tidak ditemukan");
//                 return;
//             }

//             const result = data.responResult || [];
//             window.globalShiftData = result;

//             renderShiftData(result);
//         },

//         error: function () {
//             Swal.close();
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: 'Unable to retrieve shift data.'
//             });
//         }
//     });
// }

// function renderShiftData(result) {

//     const tableBody = $("#resultdataperhitunganshift");
//     const groupBody = $("#resultdataperhitunganshiftname");

//     let grouping = {};
//     let index = 0;
//     const batchSize = 500;
//     const total = result.length;

//     // Toast Progress
//     const toast = toastr.info(
//         `Rendering data... <b>0</b> / ${total}`,
//         "Processing",
//         {
//             timeOut: 0,
//             extendedTimeOut: 0,
//             closeButton: false,
//             tapToDismiss: false,
//             progressBar: true
//         }
//     );

//     function renderBatch() {

//         const limit = Math.min(index + batchSize, total);
//         const fragment = document.createDocumentFragment();

//         for (let i = index; i < limit; i++) {
//             const row = result[i];

//             fragment.appendChild(createDetailRow(i, row));
//             updateGrouping(grouping, row);
//         }

//         tableBody.append(fragment);
//         index = limit;
//         toast.find("b").text(index);

//         if (index < total) {
//             setTimeout(renderBatch, 0);
//         } else {
//             finishRender();
//         }
//     }

//     function finishRender() {

//         Swal.close();

//         const groupingArray = Object.values(grouping)
//             .sort((a, b) =>
//                 a.SUB_UNIT.localeCompare(b.SUB_UNIT) ||
//                 a.UNIT.localeCompare(b.UNIT) ||
//                 a.NAMA.localeCompare(b.NAMA)
//             );

//         const fragment = document.createDocumentFragment();

//         groupingArray.forEach((item, i) => {
//             fragment.appendChild(createGroupRow(i + 1, item));
//         });

//         groupBody.append(fragment);

//         // Update toast jadi success
//         toast.removeClass("toast-info").addClass("toast-success");
//         toast.find(".toast-title").text("Success");
//         toast.find(".toast-message").html("Rendering completed");

//         setTimeout(() => {
//             toast.fadeOut(400, function () { $(this).remove(); });
//         }, 2500);
//     }

//     renderBatch();
// }