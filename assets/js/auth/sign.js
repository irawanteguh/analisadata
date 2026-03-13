var validatorsignin = function () {
    var form, submitButton, validator;

    function signin() {
        var formdata = new FormData(form);
        var url      = form.getAttribute('action');

        $.ajax({
            url        : url,
            type       : 'POST',
            data       : formdata,
            dataType   : 'JSON',
            cache      : false,
            contentType: false,
            processData: false,
            beforeSend : function () {
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;
            },
            success: function (response) {
                if (response.responCode === "00") {
                    Swal.fire({
                        title            : "<h1 class='font-weight-bold' style='color:#234974;'>Success</h1>",
                        html             : "<b>" + response.responDesc + "</b>",
                        icon             : response.responHead,
                        confirmButtonText: 'Yeah, got it!',
                        customClass      : { confirmButton: 'btn btn-success' },
                        timerProgressBar : true,
                        timer            : 2000,
                        showClass        : { popup: "animate__animated animate__fadeInUp animate__faster" },
                        hideClass        : { popup: "animate__animated animate__fadeOutDown animate__faster" }
                    }).then(function () {
                        form.querySelector('[name="username"]').value = "";
                        form.querySelector('[name="password"]').value = "";
                        window.location.href = response.url;
                    });
                } else if (response.responCode === "03") {
                    $('#modal_auth_change_mobilephone').modal('show');
                } else if (response.responCode === "02") {
                    window.location.href = response.url;
                } else {
                    Swal.fire({
                        title            : "<h1 class='font-weight-bold' style='color:#234974;'>For Your Information</h1>",
                        html             : "<b>" + response.responDesc + "</b>",
                        icon             : response.responHead,
                        confirmButtonText: "Please Try Again",
                        buttonsStyling   : false,
                        timerProgressBar : true,
                        timer            : 5000,
                        customClass      : { confirmButton: "btn btn-danger" },
                        showClass        : { popup: "animate__animated animate__fadeInUp animate__faster" },
                        hideClass        : { popup: "animate__animated animate__fadeOutDown animate__faster" }
                    }).then(function () {
                        form.querySelector('[name="username"]').value = "";
                        form.querySelector('[name="password"]').value = "";
                    });
                }
            },
            complete: function () {
                submitButton.removeAttribute("data-kt-indicator");
                submitButton.disabled = false;
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    title: "<h1 class='font-weight-bold' style='color:#234974;'>I'm Sorry</h1>",
                    html: "<b>" + error + "</b>",
                    icon: "error",
                    confirmButtonText: "Please Try Again",
                    buttonsStyling: false,
                    timerProgressBar: true,
                    timer: 5000,
                    customClass: { confirmButton: "btn btn-danger" },
                    showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
                    hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
                }).then(function () {
                    form.querySelector('[name="username"]').value = "";
                    form.querySelector('[name="password"]').value = "";
                });
            }
        });
    }

    return {
        init: function () {
            form         = document.querySelector("#kt_sign_in_form");
            submitButton = document.querySelector("#kt_sign_in_submit");

            validator = FormValidation.formValidation(form, {
                fields: {
                    username: {
                        validators: {
                            notEmpty: { message: "Username is required" }
                        }
                    },
                    password: {
                        validators: {
                            notEmpty: { message: "Password is required" }
                        }
                    }
                },
                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: ".fv-row"
                    })
                }
            });

            submitButton.addEventListener("click", function (event) {
                event.preventDefault();
                validator.validate().then(function (status) {
                    if (status === "Valid") {
                        signin();
                    } else {
                        Swal.fire({
                            title: "<h1 class='font-weight-bold' style='color:#234974;'>Validation Error</h1>",
                            html: "<b>Sorry, some fields are empty or incorrect.</b>",
                            icon: "error",
                            confirmButtonText: "Please Try Again",
                            buttonsStyling: false,
                            timerProgressBar: true,
                            timer: 5000,
                            customClass: { confirmButton: "btn btn-danger" },
                            showClass: { popup: "animate__animated animate__fadeInUp animate__faster" },
                            hideClass: { popup: "animate__animated animate__fadeOutDown animate__faster" }
                        });
                    }
                });
            });
        }
    };
}();

// Init on page load
document.addEventListener("DOMContentLoaded", function () {
    validatorsignin.init();
});
