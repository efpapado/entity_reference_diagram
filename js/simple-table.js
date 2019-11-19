(function ($) {
  Drupal.behaviors.entity_reference_diagram__simple_table = {
    attach: function (context) {

      $('input#erd-search', context).once('erd-search').on('keyup', function (e) {
        var filterValue = e.target.value.toUpperCase();
        var $table = $('#erd-simple-table', context);
        var $rows = $table.find('tbody tr');
        if (filterValue === '') {
          $rows.show();
        }
        else {
          $rows.each(function() {
            var $row = $(this);
            var $cells = $row.find('td');
            var has = false;
            $cells.each(function() {
              var cellValue = $(this).text();
              if (cellValue.toUpperCase().indexOf(filterValue) > -1) {
                has = true;
              }
            });
            if (has) {
              $row.show();
            }
            else {
              $row.hide();
            }
          });
        }
      });
    },
  };

})(jQuery);
