$(document).ready(function () {
    $('form').on('submit', function () {
        if ($("input[name='titulo']").val() == "") {
            alert('Informe um título para a newsletter');
            return false;
        }else{
            return true;
        }
    });
});
