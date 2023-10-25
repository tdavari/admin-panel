// $(document).ready(function () {
//   // Initialize DataTable with theme-specific JavaScript
//   var dt_filter_table = $('.dt-column-search').DataTable({
//     // Theme-specific DataTable initialization options
//   });

//   // Add individual search boxes for each column
//   $('.dt-column-search thead th').each(function () {
//     var title = $(this).text();
//     $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
//   });

//   // Apply search to each column input
//   dt_filter_table.columns().every(function () {
//     var that = this;
//     $('input', this.header()).on('keyup change', function () {
//       if (that.search() !== this.value) {
//         that.search(this.value).draw();
//       }
//     });
//   });
// });

/**
 * DataTables Advanced (jquery)
 */

'use strict';

$(function () {
  var dt_ajax_table = $('.datatables-ajax'),
    dt_filter_table = $('.dt-column-search'),
    dt_adv_filter_table = $('.dt-advanced-search'),
    dt_responsive_table = $('.dt-responsive'),
    startDateEle = $('.start_date'),
    endDateEle = $('.end_date');

  // Advanced Search Functions Starts
  // --------------------------------------------------------------------

  // Datepicker for advanced filter
  var rangePickr = $('.flatpickr-range'),
    dateFormat = 'MM/DD/YYYY';

  if (rangePickr.length) {
    rangePickr.flatpickr({
      mode: 'range',
      dateFormat: 'm/d/Y',
      orientation: isRtl ? 'auto right' : 'auto left',
      locale: {
        format: dateFormat
      },
      onClose: function (selectedDates, dateStr, instance) {
        var startDate = '',
          endDate = new Date();
        if (selectedDates[0] != undefined) {
          startDate = moment(selectedDates[0]).format('MM/DD/YYYY');
          startDateEle.val(startDate);
        }
        if (selectedDates[1] != undefined) {
          endDate = moment(selectedDates[1]).format('MM/DD/YYYY');
          endDateEle.val(endDate);
        }
        $(rangePickr).trigger('change').trigger('keyup');
      }
    });
  }

  // Filter column wise function
  function filterColumn(i, val) {
    if (i == 5) {
      var startDate = startDateEle.val(),
        endDate = endDateEle.val();
      if (startDate !== '' && endDate !== '') {
        $.fn.dataTableExt.afnFiltering.length = 0; // Reset datatable filter
        dt_adv_filter_table.dataTable().fnDraw(); // Draw table after filter
        filterByDate(i, startDate, endDate); // We call our filter function
      }
      dt_adv_filter_table.dataTable().fnDraw();
    } else {
      dt_adv_filter_table.DataTable().column(i).search(val, false, true).draw();
    }
  }

  // Advance filter function
  // We pass the column location, the start date, and the end date
  $.fn.dataTableExt.afnFiltering.length = 0;
  var filterByDate = function (column, startDate, endDate) {
    // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(function (oSettings, aData, iDataIndex) {
      var rowDate = normalizeDate(aData[column]),
        start = normalizeDate(startDate),
        end = normalizeDate(endDate);

      // If our date from the row is between the start and end
      if (start <= rowDate && rowDate <= end) {
        return true;
      } else if (rowDate >= start && end === '' && start !== '') {
        return true;
      } else if (rowDate <= end && start === '' && end !== '') {
        return true;
      } else {
        return false;
      }
    });
  };

  // converts date strings to a Date object, then normalized into a YYYYMMMDD format (ex: 20131220). Makes comparing dates easier. ex: 20131220 > 20121220
  var normalizeDate = function (dateString) {
    var date = new Date(dateString);
    var normalized =
      date.getFullYear() + '' + ('0' + (date.getMonth() + 1)).slice(-2) + '' + ('0' + date.getDate()).slice(-2);
    return normalized;
  };
  // Advanced Search Functions Ends

  // Ajax Sourced Server-side
  // --------------------------------------------------------------------

  // if (dt_ajax_table.length) {
  //   var dt_ajax = dt_ajax_table.dataTable({
  //     processing: true,
  //     ajax: assetsPath + 'json/ajax.php',
  //     dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
  //   });
  // }

  // Column Search
  // --------------------------------------------------------------------

  if (dt_filter_table.length) {
    // Setup - add a text input to each footer cell
    $('.dt-column-search thead tr').clone(true).appendTo('.dt-column-search thead');
    $('.dt-column-search thead tr:eq(1) th').each(function (i) {
      var title = $(this).text();
      $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');

      $('input', this).on('keyup change', function () {
        if (dt_filter.column(i).search() !== this.value) {
          dt_filter.column(i).search(this.value).draw();
        }
      });
    });

    var dt_filter = dt_filter_table.DataTable({
      ajax: '/api/ixp/table', //assetsPath + 'json/output.json', //'/users',
      columns: [{ data: 'switch' }, { data: 'vlan' }, { data: 'name' }, { data: 'vni' }, { data: 'intf' }, { data: 'desc' }, { data: 'learn_mac' }],
      orderCellsTop: true,
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>><"table-responsive"t><"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>B>',
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-label-primary dropdown-toggle me-2',
          text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
          buttons: [
            {
              extend: 'print',
              text: '<i class="ti ti-printer me-1" ></i>Print',
              className: 'dropdown-item',
              customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.bodyBg);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              text: '<i class="ti ti-file-text me-1" ></i>Csv',
              className: 'dropdown-item'
            },
            {
              extend: 'excel',
              text: '<i class="ti ti-file-spreadsheet me-1"></i>Excel',
              className: 'dropdown-item'
            },
            {
              extend: 'pdf',
              text: '<i class="ti ti-file-description me-1"></i>PDF',
              className: 'dropdown-item'
            },
            {
              extend: 'copy',
              text: '<i class="ti ti-copy me-1" ></i>Copy',
              className: 'dropdown-item'
            }
          ]
        }
        // ,{
        //   text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add New Record</span>',
        //   className: 'create-new btn btn-primary'
        // }
      ]
    });
  }

  // Advanced Search
  // --------------------------------------------------------------------

  // Advanced Filter table
  // if (dt_adv_filter_table.length) {
  //   var dt_adv_filter = dt_adv_filter_table.DataTable({
  //     dom: "<'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6 dataTables_pager'p>>",
  //     ajax: '/users',
  //     columns: [{ data: '' }, { data: 'name' }, { data: 'email' }, { data: 'id' }],

  //     columnDefs: [
  //       {
  //         className: 'control',
  //         orderable: false,
  //         targets: 0,
  //         render: function (data, type, full, meta) {
  //           return '';
  //         }
  //       }
  //     ],
  //     orderCellsTop: true,
  //     responsive: {
  //       details: {
  //         display: $.fn.dataTable.Responsive.display.modal({
  //           header: function (row) {
  //             var data = row.data();
  //             return 'Details of ' + data['full_name'];
  //           }
  //         }),
  //         type: 'column',
  //         renderer: function (api, rowIdx, columns) {
  //           var data = $.map(columns, function (col, i) {
  //             return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
  //               ? '<tr data-dt-row="' +
  //                   col.rowIndex +
  //                   '" data-dt-column="' +
  //                   col.columnIndex +
  //                   '">' +
  //                   '<td>' +
  //                   col.title +
  //                   ':' +
  //                   '</td> ' +
  //                   '<td>' +
  //                   col.data +
  //                   '</td>' +
  //                   '</tr>'
  //               : '';
  //           }).join('');

  //           return data ? $('<table class="table"/><tbody />').append(data) : false;
  //         }
  //       }
  //     }
  //   });
  // }

  // on key up from input field
  $('input.dt-input').on('keyup', function () {
    filterColumn($(this).attr('data-column'), $(this).val());
  });

  // Responsive Table
  // --------------------------------------------------------------------

  // if (dt_responsive_table.length) {
  //   var dt_responsive = dt_responsive_table.DataTable({
  //     ajax: assetsPath + 'json/table-datatable.json',
  //     columns: [
  //       { data: '' },
  //       { data: 'full_name' },
  //       { data: 'email' },
  //       { data: 'post' },
  //       { data: 'city' },
  //       { data: 'start_date' },
  //       { data: 'salary' },
  //       { data: 'age' },
  //       { data: 'experience' },
  //       { data: 'status' }
  //     ],
  //     columnDefs: [
  //       {
  //         className: 'control',
  //         orderable: false,
  //         targets: 0,
  //         searchable: false,
  //         render: function (data, type, full, meta) {
  //           return '';
  //         }
  //       },
  //       {
  //         // Label
  //         targets: -1,
  //         render: function (data, type, full, meta) {
  //           var $status_number = full['status'];
  //           var $status = {
  //             1: { title: 'Current', class: 'bg-label-primary' },
  //             2: { title: 'Professional', class: ' bg-label-success' },
  //             3: { title: 'Rejected', class: ' bg-label-danger' },
  //             4: { title: 'Resigned', class: ' bg-label-warning' },
  //             5: { title: 'Applied', class: ' bg-label-info' }
  //           };
  //           if (typeof $status[$status_number] === 'undefined') {
  //             return data;
  //           }
  //           return (
  //             '<span class="badge ' + $status[$status_number].class + '">' + $status[$status_number].title + '</span>'
  //           );
  //         }
  //       }
  //     ],
  //     // scrollX: true,
  //     destroy: true,
  //     dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
  //     responsive: {
  //       details: {
  //         display: $.fn.dataTable.Responsive.display.modal({
  //           header: function (row) {
  //             var data = row.data();
  //             return 'Details of ' + data['full_name'];
  //           }
  //         }),
  //         type: 'column',
  //         renderer: function (api, rowIdx, columns) {
  //           var data = $.map(columns, function (col, i) {
  //             return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
  //               ? '<tr data-dt-row="' +
  //                   col.rowIndex +
  //                   '" data-dt-column="' +
  //                   col.columnIndex +
  //                   '">' +
  //                   '<td>' +
  //                   col.title +
  //                   ':' +
  //                   '</td> ' +
  //                   '<td>' +
  //                   col.data +
  //                   '</td>' +
  //                   '</tr>'
  //               : '';
  //           }).join('');

  //           return data ? $('<table class="table"/><tbody />').append(data) : false;
  //         }
  //       }
  //     }
  //   });
  // }

  // Filter form control to default size
  // ? setTimeout used for multilingual table initialization
  setTimeout(() => {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 200);
});
