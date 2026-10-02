// function aggregate(data, operation = "count", field = null) {

//     if (!Array.isArray(data)) return [];

//     operation = operation.toLowerCase();

//     switch (operation) {

//         case "count":

//             if (!field) {
//                 return [{
//                     periode: "TOTAL",
//                     value: data.length
//                 }];
//             }

//             const map = {};

//             data.forEach(item => {

//                 const key = item[field];

//                 if (key == null || key === "") return;

//                 map[key] = (map[key] || 0) + 1;

//             });

//             return Object.keys(map).map(key => ({
//                 periode: key,
//                 value: map[key]
//             }));

//         case "sum":

//             return [{
//                 periode: "TOTAL",
//                 value: data.reduce((t, item) => t + (parseFloat(item[field]) || 0), 0)
//             }];

//         case "avg":

//             const sum = data.reduce((t, item) => t + (parseFloat(item[field]) || 0), 0);

//             return [{
//                 periode: "TOTAL",
//                 value: data.length ? (sum / data.length) : 0
//             }];

//         default:
//             return [];
//     }
// }

function aggregate(data, operation = "count", groupField, seriesField, valueField = null) {

    if (!Array.isArray(data)) return [];

    const result    = {};
    const seriesSet = new Set();

    const seriesFields = Array.isArray(seriesField) ? seriesField : [seriesField];
    const valueFields = valueField === null ? seriesFields : (Array.isArray(valueField) ? valueField : [valueField]);

    data.forEach(item => {
        const group = item[groupField];

        if (!group) return;

        seriesFields.forEach((seriesFieldName, index) => {
            const series = (item[seriesFieldName] === null || item[seriesFieldName] === undefined || String(item[seriesFieldName]).trim() === "") ? "Undefined" : String(seriesFieldName);
            seriesSet.add(series);


            if(!result[group]){
                result[group] = {periode: group};
            }

            let value = 1;

            if(operation === "sum"){
                const valueFieldName = valueFields[index] || seriesFieldName;
                value = parseFloat(item[valueFieldName]) || 0;
            }

            result[group][series] = (result[group][series] || 0) + value;
        });
    });

    const allSeries = [...seriesSet].sort();

    return Object.values(result).map(row => {
        allSeries.forEach(series => {
            if(!Object.prototype.hasOwnProperty.call(row, series)){
                row[series] = 0;
            }
        });
        return row;
    });
}