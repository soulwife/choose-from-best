webpackJsonp([2],{

/***/ "./assets/js/app.js":
/*!**************************!*\
  !*** ./assets/js/app.js ***!
  \**************************/
/*! dynamic exports provided */
/*! all exports used */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($) {window.$ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");
window.Popper = __webpack_require__(/*! popper.js */ "./node_modules/popper.js/dist/esm/popper.js");
__webpack_require__(/*! bootstrap */ "./node_modules/bootstrap/dist/js/bootstrap.js");

$(document).ready(function () {
    $(".edit-list-btn").click(function () {
        $(".read-list-btn").toggle();
        $(".entities-list-checkbox").toggle();
    });

    $(".entities-list-checkbox").change(function () {
        this.checked ? $(this).parent().addClass('disabled') : $(this).parent().removeClass('disabled');
    });

    $(".read-list-btn").on('click', function () {
        var checkedItems = $('.entities-list-checkbox:checkbox:checked').map(function () {
            return this.value;
        }).get();

        $.ajax({
            type: 'post',
            url: $(this).data('url'),
            data: JSON.stringify(checkedItems.join(',')),
            dataType: 'json',
            beforeSend: function beforeSend() {
                $('#loading').show();
            },
            success: function success(json) {
                $('#loading').hide();
            }
        });
    });
});
/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},["./assets/js/app.js"]);