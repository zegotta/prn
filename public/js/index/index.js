$(document).ready(function () {
    $('form').on('submit', function () {
        if ($("input[type='checkbox']:checked").length < 1) {
            alert('Selecione pelo menos uma notícia');
            return false;
        }else{
            return true;
        }
    });
});

