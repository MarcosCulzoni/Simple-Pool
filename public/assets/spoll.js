window.SimplePoll = window.SimplePoll || {}; // Namespace global

SimplePoll.SPOLL_Encuesta = class {
    constructor() {
        this.$mensaje = $('#spoll-mensaje');
        this.$contenido = $('#spoll-contenido');
        this.initListeners();
    }

    ajax(action, extra = {}) {
        return $.post(spoll_ajax.ajax_url, {
            action: action,
            nonce: spoll_ajax.nonce,
            contenido: this.$contenido.val(),
            ...extra
        });
    }

    initListeners() {
        $('#spoll-btn-guardar').on('click', () => this.guardar());
        $('#spoll-btn-enviar').on('click', () => this.enviar());
        $('#spoll-btn-descartar').on('click', () => this.descartar());
    }

    guardar() {
        this.ajax('spoll_guardar').done(res => this.$mensaje.text(res.data));
    }

    enviar() {
        if (confirm("¿Enviar esta encuesta?")) {
            this.ajax('spoll_enviar').done(res => {
                this.$mensaje.text(res.data);
                this.$contenido.val('');
            });
        }
    }

    descartar() {
        if (confirm("¿Descartar encuesta actual y comenzar una nueva?")) {
            this.ajax('spoll_descartar').done(res => {
                this.$contenido.val(res.data);
                this.$mensaje.text('Encuesta reiniciada.');
            });
        }
    }
};


(function($){
    console.log('SPOLL JS iniciado');

    if (typeof SimplePoll !== 'undefined') {
        new SimplePoll.SPOLL_Encuesta();
    } else {
        console.error('SimplePoll no está definido');
    }

})(jQuery);

