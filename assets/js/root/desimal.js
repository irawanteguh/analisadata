function todesimal(nilai) {
    nilai = Number(nilai);
    if (isNaN(nilai)) {
        return "0";
    }
    return Math.round(nilai).toLocaleString('id-ID');
}