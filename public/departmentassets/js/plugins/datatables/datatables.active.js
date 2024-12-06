(function ($) {
    "use strict";

    /*Default*/
    if ($('.data-table-default').length) {
        $('.data-table-default').DataTable({
            responsive: true,
            language: {
                paginate: {
                    previous: '<i class="bx bx-chevron-left"></i>',
                    next: '<i class="bx bx-chevron-right"></i>'
                }
            }
        });
    }

    /*Export Buttons*/
    if ($('.data-table-export').length) {
        $('.data-table-export').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export to Excel',
                    className: 'button button-primary button-sm'
                }
            ],
            language: {
                paginate: {
                    previous: '<i class="bx bx-chevron-left"></i>',
                    next: '<i class="bx bx-chevron-right"></i>'
                }
            }
        });
    }

})(jQuery);