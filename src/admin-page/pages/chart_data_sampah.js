$('#form-perorangan').slideUp();

$('#checkbox-data-perorangan').change(function () {
    if ($(this).is(':checked')) {
        $('#form-perorangan').slideDown();
        $('#form-seluruh-data').slideUp();
    } else {
        $('#form-perorangan').slideUp();
        $('#form-seluruh-data').slideDown();
    }
});
