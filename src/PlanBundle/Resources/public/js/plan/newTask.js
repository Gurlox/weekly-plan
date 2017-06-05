var createTask = (function(){
    var $form = $('#task-form'),
        $formError = $('#form-error'),
        template = $('#task-errors-template').html();

    $form.on('submit', function(e){
        var data = $(this).serializeArray();
        data.push({name: "plan[day]", value: routeDay});
        $.ajax({
            url: createTaskPath,
            data: data,
            dataType: "json",
            type: 'POST',
            success: function(result){
                if(result['status']=='error') {
                    var info = Mustache.to_html(template, result['errors']);
                    $formError.html(info);
                } else {
                    location.reload();
                }
            },
        });

        e.preventDefault();
    });
})();
