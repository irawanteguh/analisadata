function initDataTable(tableSelector, searchSelector = null, pageLength = 10, options = {}) {

    if ($.fn.DataTable.isDataTable(tableSelector)) {
        $(tableSelector).DataTable().destroy();
    }

    const defaultOptions = {
        responsive: false,
        pageLength: pageLength,
        autoWidth: false,
        destroy: true,
        ordering: false,
        searching: true,
        info: true,
        language: {
            emptyTable: "No data available"
        }
    };

    const table = $(tableSelector).DataTable(
        $.extend(true, {}, defaultOptions, options)
    );

    // Search
    if (searchSelector) {
        $(searchSelector)
            .off("keyup.datatable")
            .on("keyup.datatable", function () {
                table.search(this.value).draw();
            });
    }

    return table;
}