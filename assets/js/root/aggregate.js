function aggregate(data, operation, field = null) {

    if (!Array.isArray(data)) return [];

    operation = operation.toLowerCase();

    switch (operation) {

        case "count":

            if (!field) {
                return [{
                    periode: "TOTAL",
                    value: data.length
                }];
            }

            const map = {};

            data.forEach(item => {

                const key = item[field];

                if (key == null || key === "") return;

                map[key] = (map[key] || 0) + 1;

            });

            return Object.keys(map).map(key => ({
                periode: key,
                value: map[key]
            }));

        case "sum":

            return [{
                periode: "TOTAL",
                value: data.reduce((t, item) => t + (parseFloat(item[field]) || 0), 0)
            }];

        case "avg":

            const sum = data.reduce((t, item) => t + (parseFloat(item[field]) || 0), 0);

            return [{
                periode: "TOTAL",
                value: data.length ? (sum / data.length) : 0
            }];

        default:
            return [];
    }
}

function aggregateSeries(data, groupField, seriesField, operation = "count", valueField = null) {

    if (!Array.isArray(data)) return [];

    const result = {};
    const seriesSet = new Set();

    data.forEach(item => {

        const group = item[groupField];

        if (!group) return;

        const series = (
            item[seriesField] === null ||
            item[seriesField] === undefined ||
            String(item[seriesField]).trim() === ""
        )
            ? "Undefined"
            : String(item[seriesField]);

        seriesSet.add(series);

        if (!result[group]) {
            result[group] = {
                periode: group
            };
        }

        let value = 1;

        if (operation === "sum") {
            value = parseFloat(item[valueField]) || 0;
        }

        result[group][series] =
            (result[group][series] || 0) + value;

    });

    const allSeries = [...seriesSet].sort();

    return Object.values(result).map(row => {

        allSeries.forEach(series => {

            if (!Object.prototype.hasOwnProperty.call(row, series)) {
                row[series] = 0;
            }

        });

        return row;

    });
}