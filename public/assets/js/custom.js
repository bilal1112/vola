$(document).ready(function () {

    $('.autoFadeOut').delay(5000).fadeOut('slow');


    $(document).on('click', '.ordering', function () {
        let orderBy = $(this).attr('orderBy');
        let field = $(this).attr('field');
        if (orderBy === '' || orderBy === 'desc') {
            orderBy = 'asc';
        } else if (orderBy === 'asc') {
            orderBy = 'desc';
        }

        location.href = QUOTE_LIST+'?s='+SEARCH_KEY+'&status='+STATUS+'&' + field + "=" + orderBy
    });


    $(document).on('click', '.confirm_deletion', function () {
        let url=$(this).attr('href');
        let delMessage='Are you sure you want to delete?';

        $("#error_message").html(delMessage);
        $("#deletelinkbutton").attr("href",url);

        $('#delete_confirm_modal').modal('show');
        return false;
    });

});
