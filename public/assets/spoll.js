window.SimplePoll = window.SimplePoll || {}; // Namespace global

SimplePoll.SPOLL_Encuesta = class {
    constructor() {
        this.$mensaje = jQuery('#spoll-mensaje');
        this.$contenido = jQuery('#spoll-contenido');
        this.initListeners();
    }

    ajax(action, extra = {}) {
        return jQuery.post(spoll_ajax.ajax_url, {
            action: action,
            nonce: spoll_ajax.nonce,
            contenido: this.$contenido.val(),
            ...extra
        });
    }

    initListeners() {
        jQuery('#spoll-btn-guardar').on('click', () => this.guardar());
        jQuery('#spoll-btn-enviar').on('click', () => this.enviar());
        jQuery('#spoll-btn-descartar').on('click', () => this.descartar());
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



jQuery(function ($) {
    console.log('SPOLL JS iniciado');
    new SimplePoll.SPOLL_Encuesta();
});