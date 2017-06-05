var register = (function(){
    var $form = $('#register-form'),
        $formError = $('#form-error'),
        template = $('#register-errors-template').html();

    $form.on('submit', function(e){
        $.ajax({
            url: registrationPath,
            data: $(this).serializeArray(),
            dataType: "json",
            type: 'POST',
            success: function(result){
                if(result['status']=='error') {
                    var info = Mustache.to_html(template, result['errors']);
                    $formError.html(info);
                } else {
                    window.location.replace(redirect);
                }
            },
        });

        e.preventDefault();
    });
})();
