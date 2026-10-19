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
            Swal.fire({icon: "warning",text: "Please enter your username."});
            return;
        }

        if (!password) {
            Swal.fire({icon: "warning",text: "Please enter your password."});
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
                timer            : code === "00" ? 2000 : undefined,
                timerProgressBar : code === "00",

                didOpen: function () {
                    Swal.showLoading();

                    const popup = Swal.getPopup();
                    popup.style.cursor = "pointer";

                    const redirect = function () {
                        if (
                            (code === "00" || code === "02") &&
                            response &&
                            response.url
                        ) {
                            window.location.href = response.url;
                        }
                    };

                    // Klik popup untuk redirect
                    popup.addEventListener("click", redirect);

                    // Tekan Enter untuk langsung redirect
                    const handleEnter = function (event) {
                        if (event.key === "Enter") {
                            event.preventDefault();
                            event.stopPropagation();
                            event.stopImmediatePropagation();

                            redirect();
                        }
                    };

                    // Capture agar event Enter tertangkap lebih awal
                    window.addEventListener("keydown", handleEnter, true);

                    // Simpan handler untuk dibersihkan
                    popup._handleEnter = handleEnter;
                },

                willClose: function () {
                    const popup = Swal.getPopup();

                    if (popup && popup._handleEnter) {
                        window.removeEventListener(
                            "keydown",
                            popup._handleEnter,
                            true
                        );

                        delete popup._handleEnter;
                    }
                },

                didClose: function () {
                    // Redirect otomatis setelah timer untuk kode 00
                    if (code === "00" && response && response.url) {
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