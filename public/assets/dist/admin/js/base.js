/**
 * Base backend
 */
'use strict';
let notice = sessionStorage.getItem('notice');
if (notice !== null) {
  showSuccess(notice == 1 ? '送信しました。' : notice == 3 ? '削除しました。' : '保存しました。');
  sessionStorage.removeItem('notice');
}

let pathname = window.location.pathname.split('/');
window.module = pathname[2];
window.controller = pathname[3];
window.action = pathname[4] ? pathname[4] : 'index';

$('#nav')
  .find('a[href*="/' + window.module + '/' + window.controller + '"]')
  .parent()
  .addClass('menu-open');

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

function mysummernote(wrapper) {
  wrapper.find('.mysummernote').summernote({ lang: 'ja-JP' });
}
var nativeHtmlBuilderFunc = $.summernote.options.modules.videoDialog.prototype.createVideoNode;
$.summernote.options.modules.videoDialog.prototype.createVideoNode = function (url) {
  // get original generate html (that has the iframe...)
  let html = nativeHtmlBuilderFunc(url);

  return html ? $('<div class="video-wrap">').append(html)[0] : html;
};
mysummernote($('body'));

$.extend(true, $.fn.datetimepicker.defaults, {
  dayViewHeaderFormat: 'YYYY年 M月',
  locale: 'ja',
  stepping: 5,
  showTodayButton: true,
  allowInputToggle: true,
  showClear: true,
  useCurrent: false,
  icons: {
    time: 'fa fa-clock',
    clear: 'fa fa-trash',
  },
});

function mydatetimepicker(wrapper) {
  wrapper.find('.mydatetimepicker').each(function () {
    let current = $(this).find('input').attr('autocomplete', 'off').val();
    if (!isNaN(current)) {
      current = current * 1 ? moment.unix(current).format('YYYY-MM-DD HH:mm') : '';
    } else if (current) {
      current = moment(current).format('YYYY-MM-DD HH:mm');
    }

    $(this).datetimepicker({
      date: current,
      format: 'YYYY-MM-DD HH:mm',
    });
  });
}
mydatetimepicker($('body'));

function mydatepicker(wrapper) {
  wrapper.find('.mydatepicker').each(function () {
    let current = $(this).find('input').attr('autocomplete', 'off').val();
    if (!isNaN(current)) {
      current = current * 1 ? moment.unix(current).format('YYYY-MM-DD') : '';
    } else if (current) {
      current = moment(current).format('YYYY-MM-DD');
    }

    $(this).datetimepicker({
      date: current,
      format: 'YYYY-MM-DD',
    });
  });
}
mydatepicker($('body'));

function mydaterangepicker(wrapper) {
  wrapper.find('.mydaterangepicker').each(function () {
    let self = $(this);
    let start = self.find('.date-range-start').val();
    if (!isNaN(start)) {
      start = start * 1 ? moment.unix(start).format('YYYY-MM-DD') : undefined;
    } else if (start) {
      start = moment(start).format('YYYY-MM-DD');
    }

    let end = self.find('.date-range-end').val();
    if (!isNaN(end)) {
      end = end * 1 ? moment.unix(end).format('YYYY-MM-DD') : undefined;
    } else if (end) {
      end = moment(end).format('YYYY-MM-DD');
    }

    self.find('.date-range').daterangepicker(
      {
        locale: {
          format: 'YYYY-MM-DD',
          separator: ' ~ ',
          applyLabel: '適用',
          cancelLabel: 'キャンセル',
        },
        startDate: start,
        endDate: end,
        drops: 'up',
      },
      function (start, end, label) {
        self.find('.date-range-start').val(start.format('YYYY-MM-DD'));
        self.find('.date-range-end').val(end.format('YYYY-MM-DD'));
      }
    );
  });
}

mydaterangepicker($('body'));

// Initialize Select2 Elements
function myselect2(wrapper) {
  wrapper.find('.myselect2').each(function () {
    let myselect2 = $(this);

    myselect2.select2({
      theme: 'bootstrap4',
      placeholder: '選択してください',
      ajax: {
        url: myselect2.data('target'),
        data: function (params) {
          var query = {
            search: params.term,
            page: params.page || 1,
          };

          return query;
        },
      },
    });

    if (myselect2.data('val')) {
      $.ajax({
        url: myselect2.data('target'),
        data: { ids: (myselect2.data('val') + '').replace(/^,+|,+$/g, '').split(',') },
      }).then(function (data) {
        $.each(data.results, function (index, value) {
          let option = new Option(value.text, value.id, true, true);
          myselect2.append(option);
        });
      });
    }
  });
}
myselect2($('body'));

var fileTemplate = $(`<div class="d-flex align-items-center">
  <span><i class="fas fa-file"></i><img><a download></a></span>
  <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i><span> 削除</span></button>
</div>`);
function myupload(wrapper) {
  wrapper.find('.myupload').each(function () {
    let dragCounter = 0;
    let container = $(this);
    let isImg = container.hasClass('img');
    let oldFile = container.find('input[type=hidden]');
    let newFile = container.find('input[type=file]');

    if (oldFile.val()) {
      if (oldFile.data('path')) {
        var path = oldFile.data('path') + oldFile.val();
      } else {
        var path = storageUrl + window.module + '-' + window.controller + '/' + oldFile.val();
      }
      let clone = fileTemplate.clone();
      if (isImg) {
        clone.find('img').attr('src', path);
      }
      clone.find('a').attr('href', path).text(oldFile.val());
      container.append(clone);
    }

    container.on('click', 'button', function (e) {
      e.preventDefault();
      container.find('div').remove();
      newFile.val('');
      oldFile.val('');
    });

    // Function to read and store files.
    const showFile = (file) => {
      let clone = fileTemplate.clone();

      const reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onloadend = function () {
        if (isImg) {
          clone.find('img').attr('src', reader.result);
        }
        clone.find('a').attr({ href: reader.result, download: file.name }).text(file.name);
      };

      container.find('div').remove().end().append(clone);
    };

    newFile.on('change', function onChange() {
      showFile(this.files[0]);
    });

    container
      .on('dragover', function (e) {
        e.preventDefault();
      })
      .on('dragenter', function (e) {
        e.preventDefault();
        container.css('background-color', '#f4f6f9');
        dragCounter++;
      })
      .on('dragleave', function (e) {
        e.preventDefault();
        dragCounter--;
        if (dragCounter == 0) {
          container.css('background-color', '');
        }
      })
      .on('drop', function (e) {
        e.preventDefault();
        showFile(e.originalEvent.dataTransfer.files[0]);
        container.css('background-color', '');
        newFile[0].files = e.originalEvent.dataTransfer.files;
        dragCounter = 0;
      });
  });
}
myupload($('body'));

$.validator &&
  $.validator.setDefaults({
    ignore: [],
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      if (element.parent().hasClass('custom-control')) {
        element.parent().parent().append(error);
      } else {
        element.parent().append(error);
      }
    },
    highlight: function (element, errorClass, validClass) {
      if (element.type === 'checkbox' || element.type === 'radio') {
        this.findByName(element.name).addClass('is-invalid');
      } else {
        $(element).addClass('is-invalid');
      }
    },
    unhighlight: function (element, errorClass, validClass) {
      if (element.type === 'checkbox' || element.type === 'radio') {
        this.findByName(element.name).removeClass('is-invalid');
      } else {
        $(element).removeClass('is-invalid');
      }
    },
    submitHandler: function (form) {
      let validator = this;
      let save = $('#btn-save');
      save.attr('disabled', true);

      $.ajax({
        processData: false,
        contentType: false,
        url: form.action,
        data: new FormData(form),
        method: 'POST',
      })
        .done(function (data) {
          // If successful
          if (!$.isEmptyObject(data.errors)) {
            showError(data.errors);
          } else {
            sessionStorage.setItem('notice', save.data('notice') ? save.data('notice') : 0);
            window.location = $('#btn-list-back').attr('href');
          }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
          showError('無効なリクエストです。');
        })
        .always(function (jqXHR, textStatus, errorThrown) {
          save.attr('disabled', false);
        });
    },
  });
$('#detail-form').validate();

$('#btn-copy').click(function (e) {
  // This will create a new entry in the browser's history, without reloading
  window.history.replaceState(null, null, window.location.href.replace('/detail/', '/copy/'));
  window.action = 'copy';
  document.title = document.title.replace('詳細', '複製');
  $('body').removeClass('detail').addClass('copy').find('.content-header').find('h1').text(document.title);
  showSuccess('複製画面に移動しました');
});

$('#btn-del').click(function () {
  $('#del-modal').modal('hide');
  $.ajax({
    method: 'DELETE',
  })
    .done(function (data) {
      // If successful
      if (!$.isEmptyObject(data.errors)) {
        showError(data.errors);
      } else {
        sessionStorage.setItem('notice', 3);
        window.location = $('#btn-list-back').attr('href');
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      showError('無効なリクエストです。');
    });
});

// CSRF security
$.ajaxSetup({
  beforeSend: function (xhr, setting) {
    if (setting.type != 'GET') {
      xhr.setRequestHeader('X-XSRF-TOKEN', getCsrf());
    }
  },
});

function showSuccess(title, body) {
  $(document).Toasts('create', {
    class: 'bg-success',
    title: title,
    body: body,
    autohide: true,
    delay: 3000,
  });
}

function showError(errors) {
  $(document).Toasts('create', {
    class: 'bg-danger',
    title: 'エラー',
    autohide: true,
    delay: 5000,
    body: typeof errors === 'object' ? Object.values(errors).join('<br>') : errors,
  });
}

function getCsrf() {
  let cStart = document.cookie.indexOf('XSRF-TOKEN=');
  if (cStart != -1) {
    cStart = cStart + 11;
    let cEnd = document.cookie.indexOf(';', cStart);
    if (cEnd == -1) {
      cEnd = document.cookie.length;
    }

    return unescape(document.cookie.substring(cStart, cEnd));
  }

  return '';
}
