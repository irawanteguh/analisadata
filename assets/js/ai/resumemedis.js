function generateAI(id_kunjungan) {

    $.ajax({
        url: "http://localhost/rsudpasarminggu/prod/analisadata/index.php/generateresumeai/" + id_kunjungan,
        type: "POST",
        dataType: "JSON",

        beforeSend: function () {
            $("#aiOverlay").fadeIn(200);
            $(".ai-icon").addClass("animate-ai"); // 🔵 mulai animasi
            $("#btnGenerateAI").prop("disabled", true);
        },

        success: function (response) {
            $(".ai-icon").removeClass("animate-ai"); // 🟢 stop animasi
            $("#aiOverlay").fadeOut(300);
            $("#btnGenerateAI").prop("disabled", false);

            typeWriterEffect($("#hasil_resume"), response.resume, 3);
        },
        complete: function () {
            $("#aiOverlay").addClass("d-none").hide();
        },
        error: function () {
            $(".ai-icon").removeClass("animate-ai"); // 🔴 stop animasi
            $("#aiOverlay").fadeOut(300);
            $("#btnGenerateAI").prop("disabled", false);
            alert("AI Server Error");
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