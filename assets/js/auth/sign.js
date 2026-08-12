"use strict";

$(function () {

    const form   = $("#kt_sign_in_form");
    const button = $("#kt_sign_in_submit");

    form.trigger("reset");

    form.on("submit", function (e) {
        e.preventDefault();

        const username = $.trim($("[name='username']").val());
        const password = $.trim($("[name='password']").val());

        if (!username) {
            Swal.fire({
                icon: "warning",
                text: "Please enter your username."
            });
            return;
        }

        if (!password) {
            Swal.fire({
                icon: "warning",
                text: "Please enter your password."
            });
            return;
        }

        button.prop("disabled", true).attr("data-kt-indicator", "on");

        $.ajax({
            url     : form.attr("action"),
            type    : "POST",
            data    : form.serialize(),
            dataType: "JSON"
        })
        .done(function (response) {

            const code    = response.responCode || "01";
            const message = (response.responDesc || "An unexpected error occurred.").replace(/<br\s*\/?>/gi, "\n");

            let icon = "error";

            if (code === "00") {
                icon = "success";
            } else if (code === "02") {
                icon = "warning";
            }

            Swal.fire({
                icon             : icon,
                text             : message,
                showConfirmButton: false,
                allowOutsideClick: false,
                allowEscapeKey   : false,
                timer            : code === "00" ? 3000: undefined,
                timerProgressBar : code === "00",

                didOpen: function () {
                    Swal.showLoading();

                    Swal.getPopup().style.cursor = "pointer";

                    Swal.getPopup().addEventListener("click", function () {
                        if ((code === "00" || code === "02") && response.url) {
                            window.location.href = response.url;
                        }
                    });
                },

                didClose: function () {
                    // Redirect otomatis setelah timer selesai
                    if (code === "00" && response.url) {
                        window.location.href = response.url;
                    }
                }
            });
        })
        .fail(function () {
            Swal.fire({
                icon: "error",
                text: "Unable to process your login request. Please try again."
            });
        })
        .always(function () {
            form.trigger("reset");
            button.prop("disabled", false).removeAttr("data-kt-indicator");
        });
    });
});