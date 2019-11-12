

<script>
  var
      ckMini = {
          language: 'ru',
          toolbar: [
              { name: 'paragraph', items : [ 'NumberedList','BulletedList', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
              { name: 'links', items : [ 'Link','Image','Anchor' ] },
              { name: 'colors', items : [ 'TextColor','BGColor' ] },
          ]
      },
      ckSmall = {
        language: 'ru',
        allowedContent: true,
        toolbar: [
          { name: 'basicstyles', items : ['Source', 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
          { name: 'paragraph', items : [ 'NumberedList','BulletedList', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
          { name: 'links', items : [ 'Link','Image','Anchor' ] },
          { name: 'styles', items : [ 'Format','FontSize' ] },
          { name: 'colors', items : [ 'TextColor','BGColor' ] },
        ],
          filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
          filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
          filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
          filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
      },
      ckFull = {
        language: 'ru',
        allowedContent: true,

        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='

        // autoParagraph: false,
        // extraAllowedContent: 'p(*)',
        // uiColor: '#AADC6E',
        // enterMode: CKEDITOR.ENTER_BR,
        // shiftEnterMode: CKEDITOR.ENTER_P
        // extraAllowedContent: {
        //     'p' : {styles:'*',attributes:'*',classes:'*'}
        // },
      },
      xEditable = {},

      translates = {
        localeDateRangePicker: {
          "format": "MM/DD/YYYY",
          "separator": " - ",
          "applyLabel": "Apply",
          "cancelLabel": "Cancel",
          "fromLabel": "From",
          "toLabel": "To",
          "customRangeLabel": "Custom",
          "weekLabel": "W",
          "daysOfWeek": [
              "Su",
              "Mo",
              "Tu",
              "We",
              "Th",
              "Fr",
              "Sa"
          ],
          "monthNames": [
              "January",
              "February",
              "March",
              "April",
              "May",
              "June",
              "July",
              "August",
              "September",
              "October",
              "November",
              "December"
          ],
          "firstDay": 1
        }
      }
</script>

<script src="{{ asset('its-lte/js/its-plugins.js') }}"></script>
<script src="{{ asset('its-lte/vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('its-lte/vendor/ckeditor/adapters/jquery.js') }}"></script>
<script src="{{ asset('its-lte/js/its-admin.js') }}"></script>

<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<form action="" class="hidden" method="POST" id="js-action-form">
    @csrf
    @method('POST')
    <input type="hidden" name="destination">
</form>
<script>
    $('.js-action-destroy').on('click', function (e) {
        e.preventDefault()
        if (confirm('Подтверждаете удаление?')) {
            var $form = $('#js-action-form')
            $form.find('input[name="_method"]').val('DELETE')
            $form.find('input[name="destination"]').val($(this).data('destination'))
            $form.attr('action', $(this).data('url')).submit()
        }
    })

    $('.js-action-change').on('change', function (e) {
        e.preventDefault()
        if (confirm('Подтверждаете действие?')) {
            var $form = $('#js-action-form')
            $form.find('input[name="_method"]').val('POST')
            $form.find('input[name="destination"]').val($(this).data('destination'))
            $form.attr('action', $(this).data('url')).submit()
        }
        return false
    })



    /**
     * Upd: 08.02.2019
     * AJAX submit data (logout, cart, favorites, etc...).
     */
    $(document).on('click', '.js-action-click', function (e) {
        e.preventDefault()
        var $this = $(this),
            thisId = $this.data('id'),
            url = $this.data('url'),
            method = $this.data('method') || 'POST',
            htmlContainer = $this.data('html-container'),
            formData = $this.data('data') || {},
            confirmMsg = $this.data('confirm')

        if (confirmMsg !== undefined && !confirm(confirmMsg)) {
            return
        }

        $.ajax({
            url: url,
            method: method,
            dataType: 'json',
            data: formData,
            async:false,
            cache: false,
            success: function (result) {

                // обновление нужного контейнера
                if (result && result.html && htmlContainer) {
                    $(htmlContainer).html(result.html)
                }

                // команда действие от сервера
                if (result && result.action) {
                    switch (result.action) {
                        case 'redirect': // запись данных в флеш куки и редирект
                            //putFlashMessages(result.message, result.status, thisId)
                            window.location = result.destination
                            return
                    }
                }

                // показать сообщение
                if (result && result.message) {
                    var statusMsg = result.status || 'success'
                    toastr[statusMsg](result.message) // https://github.com/CodeSeven/toastr
                    // toastr.success(result.message) // https://github.com/CodeSeven/toastr
                }

                // показать модалку для формы в зависимости от статуса с сервера
                if (result && result.status && thisId) {
                    var $modalId = $(modalsAfterActions[thisId][result.status]);
                    if ($modalId.length) {
                        $('.modal').modal('hide')   // скрить все другии модалки
                        $modalId.modal()            // https://getbootstrap.com/docs/4.0/components/modal/
                    }
                }
            },
            error: function(result) {
                console.log('Error Ajax!')
                var response = result.responseJSON;

                if (response && response.errors !== undefined) {
                    $.each(response.errors, function (key, value) {
                        // Show error messages.
                        value.forEach(function (item, /*i, value*/) {
                            console.log(key, item)
                            toastr.error(item)
                        })
                    })
                }
            }
        })

    })
</script>

@stack('scripts')

</body>
</html>