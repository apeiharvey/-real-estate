'use strict';

var KTDatatablesDataSourceAjaxClient = function() {
    var initTable1 = function() {
        var table = $('#kt_datatable');
        table.DataTable();

        var arrows;
        if (KTUtil.isRTL()) {
            arrows = {
                leftArrow: '<i class="la la-angle-right"></i>',
                rightArrow: '<i class="la la-angle-left"></i>'
            }
        } else {
            arrows = {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }

        $("[name='kt_datatable_length']").parent("label").addClass("mr-10");
        $("#kt_datatable_length").append(`<label>Date
                                          <form>
                                            <div class="input-daterange input-group" id="kt_datepicker_5" style="max-width: 300px;">
                                                <input type="text" value="${start}" class="form-control" autocomplete="off" name="start" style="height: calc(1.1em + 1.3rem + 2px) !important;" placeholder="From Date" required/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-ellipsis-h"></i>
                                                    </span>
                                                </div>
                                                <input type="text" value="${end}" class="form-control" autocomplete="off" name="end" style="height: calc(1.1em + 1.3rem + 2px) !important;" placeholder="To Date" required/>
                                                <button class="btn btn-primary" style="padding: 0px 10px;border-radius: 0px 3px 3px 0px;">Set</button>
                                            </div>
                                          </form>
                                          <span style="color:#fff;">.</span></label>`);
        $('#kt_datepicker_5').datepicker({
            rtl: KTUtil.isRTL(),
            todayHighlight: true,
            templates: arrows
        });
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
