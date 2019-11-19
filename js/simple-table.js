(function ($) {
  Drupal.behaviors.entity_reference_diagram__simple_table = {
    attach: function (context) {

      var $table = $('#erd-simple-table', context);
      if (!$table.length) {
        return;
      }
      var $filters = $('<div id="erd-filters" />');

      // Create dropdown list.
      var $headers = $table.find('thead th');
      var data = $.map($headers, function (h) {
        return ($(h).text());
      });
      var $select = $('<select id="erd-column" />');
      for (var i = 0; i < data.length; i++) {
        $('<option />', {value: i, text: data[i]}).appendTo($select);
      }
      $select.on('change', function () {
        applyFilters();
      });

      // Create textfield.
      var $text = $('<input id="erd-text" type="text" size="10" maxlength="128" placeholder="Filter">');
      $text.on('keyup', function () {
        applyFilters();
      });

      // Place the filters in the DOM.
      $filters.append($text);
      $filters.append($select);
      $table.before($filters);

      function applyFilters() {
        var $table = $('#erd-simple-table', context);
        var column = parseInt($('#erd-column').find(":selected").val());
        var filterValue = $('#erd-text').val().toLowerCase();

        var $rows = $table.find('tbody tr');
        if (filterValue === '') {
          $rows.show();
        }
        else {
          $rows.each(function () {
            var $row = $(this);
            var $cells = $row.find('td').eq(column);
            var has = false;
            $cells.each(function () {
              var cellValue = $(this).text().toLowerCase();
              if (cellValue.indexOf(filterValue) > -1) {
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
      }
    }
  };

})(jQuery);
