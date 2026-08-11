function calculateDuration(startDate, endDate) {
    if (!startDate || !endDate) return "-";

    function parseDate(str) {
        const [date, time] = str.split(" ");
        const [d, m, y] = date.split(".");
        const [h, i, s] = time.split(":");

        return new Date(y, m - 1, d, h, i, s);
    }

    const start = parseDate(startDate);
    const end   = parseDate(endDate);

    let diff = Math.floor((end - start) / 1000);

    if (isNaN(diff) || diff < 0) return "-";

    const days  = Math.floor(diff / 86400);
          diff %= 86400;

    const hours  = Math.floor(diff / 3600);
          diff  %= 3600;

    const minutes = Math.floor(diff / 60);

    let result = [];

    if (days > 0) result.push(days + " Day");
    if (hours > 0) result.push(hours + " Hour");
    result.push(minutes + " Minute");

    return result.join(" ");
}