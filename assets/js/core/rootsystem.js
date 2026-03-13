function todesimal(bilangan){
    if (bilangan === null || bilangan === undefined || bilangan === "") {
        return "0";
    }

    bilangan = bilangan.toString().replace(/[^\d]/g, "");

    if (bilangan === "" || bilangan == 0) {
        return "0";
    }

    var reverse = bilangan.split('').reverse().join('');
    var ribuan  = reverse.match(/\d{1,3}/g);

    if (!ribuan) {
        return "0";
    }

    return ribuan.join('.').split('').reverse().join('');
}

function openSejarah(pasienId) {
  const url     = "http://sejarah.rsudpm.local/index.php/sejarah?id=" + pasienId;
  const winName = "tabSejarah";
  const win     = window.open(url, winName);

  if (win) {
    win.focus();
  } else {
    alert("Pop-up diblokir! Izinkan pop-up untuk situs ini.");
  }
}

function exportTableToExcel(tableID, filename = ''){
    let table = $("#" + tableID);

    // simpan baris yang disembunyikan
    let hiddenRows = table.find("tr:hidden");

    // detach agar tidak ikut diexport
    let removedRows = hiddenRows.detach();

    $("#"+tableID).table2excel({
        exclude       : ".excludeThisClass",
        name          : "Worksheet Name",
        filename      : filename + ".xls",
        preserveColors: false
    });

    // kembalikan baris yang disembunyikan
    table.append(removedRows);
}

function setCountdownSLA(startTime, elementId, slaJam = 24) {

    const el = document.getElementById(elementId);
    if (!el) return;

    function updateTimer() {

        const startParts = startTime.split(" ");
        const startDate  = new Date(
            startParts[0].split(".").reverse().join("-") + "T" + startParts[1]
        );

        if (isNaN(startDate)) {
            el.innerHTML = "-";
            return;
        }

        const now = new Date();
        const diffMs = now - startDate;

        const diffJam     = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMenit   = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        const diffDetik   = Math.floor((diffMs % (1000 * 60)) / 1000);

        el.innerHTML = `${diffJam} Jam : ${diffMenit} Menit : ${diffDetik} Detik`;

        if (diffJam < slaJam) {
            el.className = "badge badge-light-success fw-bold";
        } else if (diffJam === slaJam) {
            el.className = "badge badge-light-warning fw-bold";
        } else {
            el.className = "badge badge-light-danger fw-bold";
        }
    }

    updateTimer();
    setInterval(updateTimer, 1000);
}

function setDurasiSLA(startTime, endTime, elementId, slaJam = 24) {

    const el = document.getElementById(elementId);
    if (!el || !startTime) {
        if (el) el.innerHTML = "-";
        return;
    }

    function parseDateTime(dateTimeStr) {
        if (!dateTimeStr) return null;

        const parts = dateTimeStr.split(" ");
        if (parts.length < 2) return null;

        return new Date(
            parts[0].split(".").reverse().join("-") + "T" + parts[1]
        );
    }

    const startDate = parseDateTime(startTime);
    const endDate   = endTime ? parseDateTime(endTime) : new Date();

    if (!startDate || isNaN(startDate)) {
        el.innerHTML = "-";
        return;
    }

    const finalEndDate = (!endDate || isNaN(endDate)) ? new Date() : endDate;

    const diffMs = finalEndDate - startDate;

    if (diffMs < 0) {
        el.innerHTML = "-";
        return;
    }

    const diffJam   = Math.floor(diffMs / (1000 * 60 * 60));
    const diffMenit = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
    const diffDetik = Math.floor((diffMs % (1000 * 60)) / 1000);

    el.innerHTML = `${diffJam} Jam : ${diffMenit} Menit : ${diffDetik} Detik`;

    if (diffJam < slaJam) {
        el.className = "badge badge-light-success fw-bold";
    } else if (diffJam === slaJam) {
        el.className = "badge badge-light-warning fw-bold";
    } else {
        el.className = "badge badge-light-danger fw-bold";
    }
}

function filterTableByKeywords(inputSelector, tableSelector){

    let spinner = $("#searchSpinner");
    spinner.removeClass("d-none");

    setTimeout(function(){

        let keywords = $(inputSelector).val()
            .toLowerCase()
            .split(",")
            .map(k => k.trim())
            .filter(k => k !== "");

        $(tableSelector + " tr").each(function () {

            let row = $(this);
            let rowText = row.text().toLowerCase();

            let match = keywords.every(function(word){
                return rowText.indexOf(word) > -1;
            });

            row.toggle(match);

        });

        spinner.addClass("d-none");

    }, 50);
}

toastr.options = {
    closeButton    : true,
    progressBar    : true,
    timeOut        : 0,
    extendedTimeOut: 0,
    tapToDismiss   : false,
    positionClass  : "toast-bottom-right"
};