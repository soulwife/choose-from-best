
$(document).ready(function(){
    $(".edit-list-btn").click(function(){
        $(".read-list-btn").toggle();
        $(".entities-list-checkbox").toggle();
    });

    $(".entities-list-checkbox").change(function() {
        this.checked ? $(this).parent().addClass('disabled') : $(this).parent().removeClass('disabled');
    });

    $(".read-list-btn").on('click', function() {
        var checkedItems = $('.entities-list-checkbox:checkbox:checked').map(function() {
            return this.value;
        }).get();

        $.ajax({
            type: 'post',
            url: $(this).data('url'),
            data: JSON.stringify(checkedItems.join(',')),
            dataType: 'json',
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (json) {
                $('#loading').hide();
            }
        });
    });

});

