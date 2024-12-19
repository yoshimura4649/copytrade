/**
 * Base backend
 */
'use strict';
if (typeof DataTable !== 'undefined') {
  DataTable.Buttons.defaults.dom.button.liner.tag = null;
  DataTable.Buttons.defaults.dom.button.className = 'btn btn-default btn-sm';
  DataTable.Buttons.defaults.dom.container.className = 'dt-buttons flex-wrap';

  let searchForm = $('#search-form');
  $.extend(true, DataTable.defaults, {
    layout: {
      topStart: 'buttons',
      topEnd: {
        className: 'd-flex flex-wrap align-items-center dt-layout-end',
        features: {
          pageLength: {},
          info: {},
          paging: {},
          div: {
            id: 'list-setting',
            className: 'btn ml-0',
            html: '<div class="fas fa-ellipsis-v"></div>',
          },
        },
      },
      bottomStart: null,
      bottomEnd: null,
    },
    buttons: [
      {
        extend: 'csvHtml5',
        text: '<i class="fas fa-download"></i> CSVエクスポート',
        action: function (e, dt, button, config, cb) {
          var self = this;
          var oldStart = dt.settings()[0]._iDisplayStart;

          dt.one('preXhr', function (e, s, data) {
            // Just this once, load all data from the server...
            data.start = 0;
            data.length = 5000;

            dt.one('preDraw', function (e, settings) {
              // Call the original action function
              DataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config, cb);

              settings._iDisplayStart = oldStart;
              data.start = oldStart;

              // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
              setTimeout(dt.ajax.reload(), 0);

              // Prevent rendering of the full data to the DOM
              return false;
            });
          });

          // Requery the server with the new one-time export settings
          dt.ajax.reload();
        },
        exportOptions: {
          columns: ':visible',
        },
      },
    ],
    pagingType: 'simple_numbers',
    processing: true,
    serverSide: true,
    stateSave: true,
    order: [],
    ajax: {
      data: function (data, settings) {
        console.log(settings);

        delete data.columns;
        if (data.search['regex']) {
          data.filter = searchForm
            .find('form')
            .serializeArray()
            .reduce((o, p) => {
              const inputType = searchForm.find(`form [name="${p.name}"]`).attr('type');

              if (inputType === 'checkbox') {
                if (!o[p.name]) {
                  o[p.name] = [];
                }
                o[p.name].push(p.value);
              } else {
                o[p.name] = p.value;
              }
              return o;
            }, {});
        }

        $.each(data.order, function (key, value) {
          value['column'] = settings.aoColumns[value['column']]['data'];
        });

        return data;
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // reload if error.
        // location.reload();
      },
    },
    stateSaveParams: function (settings, data) {
      data.filter = searchForm
        .find('form')
        .serializeArray()
        .reduce((o, p) => {
          const inputType = searchForm.find(`form [name="${p.name}"]`).attr('type');

          if (inputType === 'checkbox') {
            if (!o[p.name]) {
              o[p.name] = [];
            }
            o[p.name].push(p.value);
          } else {
            o[p.name] = p.value;
          }
          return o;
        }, {});
    },
    stateLoadParams: function (settings, data) {
      if (data.search['regex']) {
        searchForm.find('#search-toggle').addClass('active');
      } else if (data.search.search) {
        searchForm.find('#form-simple').val(data.search.search).end().find('#search-simple').addClass('active');
      }
      $.each(data.filter, function (key, value) {
        searchForm.find('[name=' + key + ']').val(value);
      });
    },
    initComplete: function (settings, json) {
      let self = this.api();

      let listSetting = $('#setting-modal');
      let items = '';
      $.each(settings.aoColumns, function (key, value) {
        if (value.sTitle) {
          items +=
            '<li class="col-6"><div class="custom-control custom-checkbox">' +
            '<input class="custom-control-input" type="checkbox" id="setting-item' +
            key +
            '" value="' +
            key +
            '"' +
            (value.bVisible ? 'checked' : '') +
            '>' +
            '<label for="setting-item' +
            key +
            '" class="custom-control-label">' +
            value.sTitle +
            '</label></div></li>';
        }
      });

      listSetting
        .find('#setting-item')
        .on('change', 'input', function () {
          let column = self.column($(this).val());
          column.visible(!column.visible());
        })
        .append(items);

      listSetting
        .find('[name="length"]')
        .change(function () {
          self.page.len($(this).val()).draw();
        })
        .filter('#length' + self.page.len())
        .prop('checked', true);

      searchForm
        .on('shown.bs.modal', function () {
          searchForm.find('#form-simple, #search-simple').prop('disabled', true);
        })
        .on('hidden.bs.modal', function () {
          searchForm.find('#form-simple, #search-simple').prop('disabled', false);
        })
        .find('#form-advanced')
        .on('click', function (e) {
          e.stopPropagation();
        })
        .find('button')
        .on('click', function (e) {
          e.preventDefault();
          searchForm.find('#form-advanced').modal('hide');
        });

      searchForm.find('#search-simple').on('click', function (e) {
        e.preventDefault();
        let search = searchForm.find('#form-simple').val();
        self.search(search, false).draw();
        if (search) {
          $(this).addClass('active');
        } else {
          $(this).removeClass('active');
        }
        searchForm.find('#search-toggle').removeClass('active');
        if (searchForm.find('form').length > 0) {
          searchForm.find('form')[0].reset();
        }
      });

      searchForm.find('#search-clear').on('click', function (e) {
        self.search('', false).draw();
        searchForm.find('form')[0].reset();
        searchForm.find('#search-toggle').removeClass('active');
      });

      searchForm.find('#search-advanced').on('click', function (e) {
        let isAdvanced = false;
        searchForm
          .find('#form-advanced')
          .find('input, select')
          .each(function () {
            if ($(this).val()) {
              isAdvanced = true;
              return false;
            }
          });

        self.search('', isAdvanced).draw();
        if (isAdvanced) {
          searchForm.find('#search-toggle').addClass('active');
        } else {
          searchForm.find('#search-toggle').removeClass('active');
        }
        searchForm.find('#form-simple').val('').end().find('#search-simple').removeClass('active');
      });
    },
    language: {
      sEmptyTable: 'テーブルにデータがありません',
      sInfo: '_START_-_END_ / _TOTAL_件',
      sInfoEmpty: '0件',
      sInfoFiltered: '',
      sInfoPostFix: '',
      sInfoThousands: ',',
      sLengthMenu: '表示件数　_MENU_',
      sLoadingRecords: '読み込み中...',
      sProcessing: '処理中...',
      sSearch: '検索:',
      sZeroRecords: '一致するレコードがありません',
      oPaginate: {
        sFirst: '先頭',
        sLast: '最終',
        sNext: '<i class="fas fa-chevron-right"></i>',
        sPrevious: '<i class="fas fa-chevron-left"></i>',
      },
      oAria: {
        sSortAscending: ': 列を昇順に並べ替えるにはアクティブにする',
        sSortDescending: ': 列を降順に並べ替えるにはアクティブにする',
      },
    },
    responsive: true,
    autoWidth: false,
  });
  $('#list-table').length > 0 && $('#list-table').DataTable(tableConfig);
}
