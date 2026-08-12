dataauditmedik();

$('#selectperiode').on('change', function () {
    dataauditmedik();
});

function dataauditmedik() {
    let selectperiode   = $("select[name='selectperiode']").val();
    $.ajax({
        url     : url + "index.php/stroke/auditmedik/dataauditmedik",
        type    : "POST",
        dataType: "JSON",
        data    : {selectperiode:selectperiode},

        beforeSend: function () {

            Swal.fire({
                title: "Processing",
                html: "Please wait while the system retrieves the requested data.",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });

            // kosongkan seluruh tbody
            $("[id^='resultdatabln']").empty();

        },

        success: function (data) {
            Swal.close();

            if (data.responCode !== "00") {

                Swal.fire({
                    icon: "warning",
                    title: "No Records Found",
                    text: "No records are available."
                });

                return;
            }

            const result = data.responResult || [];
            const html = {};

            for (let i = 1; i <= 12; i++) {
                html[String(i).padStart(2, "0")] = "";
            }

            // nomor urut tiap bulan
            const nomor = {};

            for (let i = 1; i <= 12; i++) {
                nomor[String(i).padStart(2, "0")] = 1;
            }

            result.forEach(function (item) {
                const bulan = item.TGLMASUK.substring(3, 5);

                html[bulan] += `
                    <tr>
                        <td class="ps-4">${nomor[bulan]++}</td>
                        <td>${item.MRPAS ?? "-"}</td>
                        <td>${item.NAMAPASIEN ?? "-"}</td>
                        <td>${item.SEX_ID ?? "-"}</td>
                        <td>${item.TEMPAT_LAHIR_TXT ?? "-"}</td>
                        <td>${item.TGLLAHIR ?? "-"}</td>
                        <td>${item.UMUR ?? "-"}</td>
                        <td>${item.TGLMASUK ?? "-"}</td>
                        <td>${item.REGISTRASIIGD ?? "-"}</td>
                        <td>${item.CODECREATEDATE ?? "-"}</td>
                        <td>${item.JMLORDERCT ?? "-"}</td>
                        <td>${item.ORDERFIRST ?? "-"}</td>
                        <td>${item.ORDERLAST ?? "-"}</td>
                        <td>${item.RADIOGRAFERSTARTFIRST ?? "-"}</td>
                        <td>${item.RADIOGRAFERSTARTLAST ?? "-"}</td>
                        <td>${item.RADIOLOGTFIRST ?? "-"}</td>
                        <td>${item.RADIOLOGLAST ?? "-"}</td>

                        <td class="text-end pe-4">

                            <button class="btn btn-sm btn-light-primary"
                                onclick="detail('${item.EPISODE_ID}')">

                                Detail

                            </button>

                        </td>

                    </tr>
                `;

            });

            // tampilkan ke masing-masing tabel
            Object.keys(html).forEach(function (bulan) {

                $("#resultdatabln" + bulan).html(html[bulan]);

            });

        },

        error: function () {

            Swal.close();

            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Unable to retrieve data."
            });

        }

    });

}
