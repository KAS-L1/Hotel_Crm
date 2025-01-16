$('#formLogin').submit(function(e) {
    e.preventDefault();
    $.post('api/auth/login.php', $('#formLogin').serialize(), function(res) {
        $('#responseLogin').html(res);
    }).fail(function() {
        $('#responseLogin').html('An error occurred. Please try again.');
    });
});
