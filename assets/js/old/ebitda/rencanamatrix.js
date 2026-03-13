matrixms();

function getdata(btn){
    var data_matrixid = btn.attr("data_matrixid");
    var data_type     = btn.attr("data_type");

    $('#modal_addmatrix_matrixid').val(data_matrixid);
    $('#modal_addmatrix_type').val(data_type);
};

function matrixms() {
    $.ajax({
        url      : url + "index.php/ebitda/rencanamatrix/matrixms",
        type     : "POST",
        dataType : "JSON",
        beforeSend() {
            Swal.fire({
                title             : 'Processing',
                html              : 'Please wait while the system displays the requested data.',
                allowOutsideClick : false,
                allowEscapeKey    : false,
                showConfirmButton : false,
                didOpen           : () => Swal.showLoading()
            });
            $("#resultdatarencanamatrix").empty();
        },
        success(res) {

            if (res.responCode !== "00" || !Array.isArray(res.responResult)) {
                Swal.close();
                return;
            }

            const data = res.responResult;

            /* =====================================================
               1️⃣ PREPARE MAP (RELATION + TOTAL DT)
            ===================================================== */
            const childrenMap = Object.create(null);
            const dtTotal     = Object.create(null);
            const finalTotal  = Object.create(null);
            const rowMap      = Object.create(null);

            for (const r of data) {

                if (r.MATRIX_ID) {
                    rowMap[r.MATRIX_ID] = r;
                }

                if (r.MATRIX_ID && !childrenMap[r.MATRIX_ID]) {
                    childrenMap[r.MATRIX_ID] = [];
                }

                if (r.MATRIX_ID_HEADER) {
                    if (!childrenMap[r.MATRIX_ID_HEADER]) {
                        childrenMap[r.MATRIX_ID_HEADER] = [];
                    }
                    childrenMap[r.MATRIX_ID_HEADER].push(r);
                }

                // akumulasi DT ke header
                if (r.JENIS === "DT") {
                    const nilai = Number(r.HARGADETAIL) || 0;
                    dtTotal[r.MATRIX_ID_HEADER] =
                        (dtTotal[r.MATRIX_ID_HEADER] || 0) + nilai;
                }
            }

            /* =====================================================
               2️⃣ HITUNG TOTAL MS (RECURSIVE + CACHE)
            ===================================================== */
            function hitungTotal(matrixId) {

                if (finalTotal[matrixId] !== undefined) {
                    return finalTotal[matrixId];
                }

                let total = dtTotal[matrixId] || 0;
                const childs = childrenMap[matrixId] || [];

                for (const c of childs) {
                    if (c.JENIS === "MS" && c.MATRIX_ID) {
                        total += hitungTotal(c.MATRIX_ID);
                    }
                }

                finalTotal[matrixId] = total;
                return total;
            }

            for (const r of data) {
                if (r.JENIS === "MS" && r.MATRIX_ID) {
                    hitungTotal(r.MATRIX_ID);
                }
            }

            /* =====================================================
               3️⃣ HITUNG LEVEL (INDENT YANG BENAR)
            ===================================================== */
            const levelMap = Object.create(null);

            function hitungLevelById(matrixId) {

                if (!matrixId) return 0;

                if (levelMap[matrixId] !== undefined) {
                    return levelMap[matrixId];
                }

                const row = rowMap[matrixId];
                if (!row || !row.MATRIX_ID_HEADER) {
                    levelMap[matrixId] = 0;
                    return 0;
                }

                const level = hitungLevelById(row.MATRIX_ID_HEADER) + 1;
                levelMap[matrixId] = level;
                return level;
            }

            /* =====================================================
               4️⃣ RENDER TABLE (SINGLE INJECT)
            ===================================================== */
            let html = "";

            for (const r of data) {

                const isMS = r.JENIS === "MS";
                let level  = 0;

                if (isMS) {
                    level = hitungLevelById(r.MATRIX_ID);
                } else {
                    // DT selalu 1 level lebih dalam dari MS parent
                    level = hitungLevelById(r.MATRIX_ID_HEADER) + 1;
                }

                const padding = 16 + (level * 20);

                html += `
                <tr>
                    <td style="padding-left:${padding}px">
                        <div class="fw-${isMS ? 'bold' : 'normal'}">
                            ${isMS ? (r.KODE || "") : '<span class="text-muted me-2">↳</span>'}
                            ${r.COMPONENT || ""}
                        </div>
                        ${r.TYPE ? `<span class="badge badge-light-primary me-2">${r.TYPE}</span>` : ""}
                        ${r.CATEGORY ? `<span class="badge badge-light-info">${r.CATEGORY}</span>` : ""}
                    </td>

                    <td class="text-end">${!isMS ? todesimal(r.VOL) : ""}</td>
                    <td class="text-end">${r.SATUAN || ""}</td>
                    <td class="text-end">${!isMS ? todesimal(r.HARGA) : ""}</td>
                    <td>${r.NOTE || ""}</td>

                    <td class="text-end ${isMS ? 'fw-bolder text-primary' : ''}">
                        ${todesimal(isMS ? (finalTotal[r.MATRIX_ID] || 0) : r.HARGADETAIL)}
                    </td>

                    <td class="pe-4 text-end">
                        ${isMS ? `
                        <a class="btn btn-sm btn-primary"
                           data_matrixid="${r.MATRIX_ID}"
                           data_type="${r.TYPEID || ''}"
                           data-bs-toggle="modal"
                           data-bs-target="#modal_addmatrix"
                           onclick="getdata($(this));">
                           Add Sub Matrix
                        </a>` : ""}
                    </td>
                </tr>`;
            }

            $("#resultdatarencanamatrix").html(html);
        },
        complete() {
            Swal.close();
        },
        error() {
            Swal.fire({
                icon  : 'error',
                title : 'Error',
                text  : 'Unable to retrieve matrix data.'
            });
        }
    });
}





$(document).on("submit", "#formaddcomponent", function (e) {
	e.preventDefault();
    e.stopPropagation();
	var form = $(this);
    var url  = $(this).attr("action");
	$.ajax({
        url       : url,
        data      : form.serialize(),
        method    : "POST",
        dataType  : "JSON",
        cache     : false,
        beforeSend: function(){
			$("#modal_addmatrix_btn").addClass("disabled");
        },
		success: function (data) {

            if(data.responCode == "00"){
                matrixms();
                $("#modal_addmatrix").modal("hide");
			}

		},
        complete: function(){
            $("#modal_addmatrix_btn").removeClass("disabled");
		},
        error: function () {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Unable to retrieve visit data.'
            });
        }
	});
    return false;
});