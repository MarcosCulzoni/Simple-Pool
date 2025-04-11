

jQuery(function($) {
    const $mensaje = $('#spoll-mensaje');
    const ajax = (action, extra = {}) => {
        return $.post(spoll_ajax.ajax_url, {
            action: action,
            nonce: spoll_ajax.nonce,
            contenido: $('#spoll-contenido').val(),
            ...extra
        });
    };

    $('#spoll-btn-guardar').on('click', function () {
        ajax('spoll_guardar').done(res => $mensaje.text(res.data));
    });

    $('#spoll-btn-enviar').on('click', function () {
        if (confirm("¿Enviar esta encuesta?")) {
            ajax('spoll_enviar').done(res => {
                $mensaje.text(res.data);
                $('#spoll-contenido').val('');
            });
        }
    });

    $('#spoll-btn-descartar').on('click', function () {
        if (confirm("¿Descartar encuesta actual y comenzar una nueva?")) {
            ajax('spoll_descartar').done(res => {
                $('#spoll-contenido').val(res.data);
                $mensaje.text('Encuesta reiniciada.');
            });
        }
    });
});
