jQuery(function($) {
    $("#create").on('click', function(event){
        event.preventDefault();
        var $note = $(this);
        $.post('/add', null,
            function(data){
                if(data.response == true){
                    $note.after("<div class=\"note\"><textarea id=\"note-"+data.new_note_id+"\"></textarea><a href=\"#\" id=\"remove-"+data.new_note_id+"\"class=\"remove-note\">X</a></div>");
                } else {
                    console.log('could not add');
                }
            }, 'json');
    });

    $('#notes').on('click', 'a.remove-note',function(event){
        event.preventDefault();
        var $note = $(this);
        var remove_id = $(this).attr('id');
        remove_id = remove_id.replace("remove-","");

        $.post("/delete", {
                id: remove_id
            },
            function(data){
                if(data.response == true)
                    $note.parent().remove();
                else{
                    console.log('could not remove ');
                }
            }, 'json');
    });

    $('#notes').on('keyup', 'textarea', function(event){
        var $note = $(this);
        var update_id = $note.attr('id'),
            update_content = $note.val();
        update_id = update_id.replace("note-","");

        $.post("/edit", {
            id: update_id,
            content: update_content
        },function(data){
            if(data.response == false){
                console.log('could not update');
            }
        }, 'json');

    });
});