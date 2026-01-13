jQuery(function($){

    // Open Add Player Modal
    $('#addPlayerBtn').on('click',function(){
        $('#fmPlayerModal').show();
        $('#playerModalTitle').text('Add Player');
        $('#player_id').val('');
        $('#player_name,#player_team,#player_position,#player_image').val('');
    });

    // Save Player
    $('#savePlayer').on('click',function(){
        var data={
            action:'fm_add_player',
            nonce:fm_ajax.nonce,
            box_id:$('#player_box').val(),
            player_name:$('#player_name').val(),
            team:$('#player_team').val(),
            position:$('#player_position').val(),
            image_url:$('#player_image').val()
        };
        var player_id = $('#player_id').val();
        if(player_id){
            data.action='fm_edit_player';
            data.player_id=player_id;
        }

        $.post(fm_ajax.ajax_url,data,function(r){
            if(r.success){
                alert('Player saved!');
                $('#fmPlayerModal').hide();
                loadPlayersTable(); // Function to refresh table dynamically
            }
        });
    });

    // Edit Player
    $(document).on('click','.editPlayer',function(){
        var row = $(this).closest('tr');
        var pid = $(this).data('id');
        $('#player_id').val(pid);
        $('#player_box').val(row.data('box'));
        $('#player_name').val(row.find('.playerName').text());
        $('#player_team').val(row.find('.playerTeam').text());
        $('#player_position').val(row.find('.playerPosition').text());
        $('#player_image').val(row.find('.playerImage img').attr('src'));
        $('#playerModalTitle').text('Edit Player');
        $('#fmPlayerModal').show();
    });

    // Delete Player
    $(document).on('click','.deletePlayer',function(){
        if(!confirm('Delete this player?')) return;
        var row = $(this).closest('tr');
        var pid = $(this).data('id');
        $.post(fm_ajax.ajax_url,{
            action:'fm_delete_player',
            nonce:fm_ajax.nonce,
            player_id:pid
        },function(r){
            if(r.success){
                row.remove();
            }
        });
    });

});