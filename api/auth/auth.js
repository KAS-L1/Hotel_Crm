$('#formLogin').submit(function(e){
    e.preventDefault();
    $.post('api/auth/login.php', $('#formLogin').serialize(), function(res){
        $('#responseLogin').html(res);
    })
})