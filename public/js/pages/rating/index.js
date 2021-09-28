'use strict';

var KTDatatablesDataSourceAjaxClient = function() {
    var initTable1 = function() {
        var table = $('#kt_datatable');
        table.DataTable();

        var table2 = $('#kt_datatable2');
        table2.DataTable();
    }

    return {
        //main function to initiate the module
        init: function() {
            initTable1();
        },

    };
}();

jQuery(document).ready(function() {
    KTDatatablesDataSourceAjaxClient.init();
});
