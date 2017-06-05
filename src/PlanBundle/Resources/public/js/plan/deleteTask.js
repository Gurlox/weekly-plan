var deleteTask = (function(){
    var $task = $('.delete-task');

    $task.click(function(){
        var el = $(this);
        $.ajax({
            url: deleteTaskPath,
            data: {
                id: el.parent().data('id')
            },
            dataType: "json",
            type: 'DELETE',
            success: function(result){
                if (result['status'] == 'success') {
                    el.parent().remove();
                } else if (result['status'] == 'error') {
                    alert(result['message']);
                }
            },
        });
    });
})();
