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