<!-- { extend: admin/layout_without_menu.php @ content_block } -->

<div class="modal active" id="login">
    <div class="modal-container">
        <div class="modal-header bg-primary text-light">
            <div class="modal-title h3" style="line-height: 0; padding-top: 2rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="320" height="76" viewBox="0 0 84.667 20.108"><path fill="#ffffff" d="M4.954 19.85h5.01l4.983-13.076h-3.542L7.459 17.286 3.485 6.774H0zm10.686 0h3.225v-2.19c0-1.843 1.498-3.37 3.255-3.37a3.374 3.374 0 0 1 3.37 3.37v2.19h3.225v-2.19c0-1.9-.893-3.657-2.189-4.924 1.296-1.21 2.189-2.88 2.189-4.868V6.774h-3.226v1.094c0 1.844-1.497 3.399-3.37 3.399-1.756 0-3.254-1.555-3.254-3.399V6.774H15.64v1.094c0 1.988.892 3.658 2.188 4.868-1.296 1.267-2.188 3.024-2.188 4.924zm15.507-6.28c0 3.687 2.88 6.538 6.537 6.538 1.93 0 3.687-.864 4.81-2.188 1.18 1.324 2.909 2.188 4.81 2.188 3.6 0 6.508-2.85 6.508-6.537V.323h-3.225V13.57c0 1.843-1.412 3.513-3.255 3.513-1.7 0-3.197-1.67-3.197-3.513V.323H40.91V13.57c0 1.843-1.44 3.513-3.226 3.513-1.843 0-3.312-1.67-3.312-3.513V.323h-3.225zm24.961-.374c0 3.658 2.938 6.653 6.624 6.653h6.192v-3.024h-4.838l5.126-5.097c-.691-2.938-3.312-5.184-6.48-5.184-3.686 0-6.624 2.995-6.624 6.652zm4.406 2.621a3.55 3.55 0 0 1-1.18-2.62c0-1.93 1.555-3.6 3.398-3.6 1.037 0 2.189.604 2.65 1.324zm14.102-2.39v-3.6h3.311c2.045 0 3.485 1.67 3.485 3.6 0 1.9-1.44 3.657-3.485 3.657-1.785 0-3.311-1.756-3.311-3.657zm-3.226 0c0 3.715 2.909 6.681 6.537 6.681 3.773 0 6.74-2.966 6.74-6.681 0-3.687-2.967-6.653-6.74-6.653h-3.311V.323H71.39z"/></svg>
                Administration
            </div>
        </div>
        <div class="modal-body">
            <form action="/admin/login" method="post" @submit.prevent="submit">
                <div class="form-group">
                    <input name="username" maxlength="128" class="form-input input-lg" type="text" v-model="form.username" placeholder="Username">
                </div>
                <password-input v-model="form.pwd" placeholder="Passwort" maxlength="128"></password-input>
                <div class="d-flex" style="align-items: center">
                    <div class="col-4">
                        <button name="submit_login" value="" type='submit' class='btn btn-success btn-lg col-12' :disabled="status === 'loading'" :class="{ 'loading': status === 'loading' }">Login</button>
                    </div>
                    <div class="col-8 text-right">
                        <a href="/" class="with-webfont-icon-left" data-icon="&#xe000;"><?= $_SERVER['HTTP_HOST'] ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <message-toast
        :message="toastProps.message"
        :classname="toastProps.messageClass"
        :active="status === 'received'"
        ref="toast"
    ></message-toast>
</div>

<script src="/js/vue/vxweb.umd.min.js"></script>
<script>
    const { MessageToast, PasswordInput } = window.vxweb.Components;
    const SimpleFetch =  window.vxweb.Util.SimpleFetch;

    const app = new Vue({

        components: {
            "message-toast": MessageToast,
            "password-input": PasswordInput
        },

        el: "#login",

        routes: {
            loginUrl: "/admin/login"
        },

        data: {
            form: {},
            response: {},
            status: null
        },

        computed: {
            toastProps () {
                return {
                    message: this.response.message ? this.response.message : "",
                    messageClass: this.response.error ? "toast-error" : "toast-success"
                }
            }
        },

        methods: {
            async submit () {
                if (this.form.username && this.form.pwd) {
                    this.status = "loading";
                    let response = await SimpleFetch(this.$options.routes.loginUrl, 'POST', {}, JSON.stringify(this.form));
                    if (response.locationHref) {
                        window.location.href = response.locationHref;
                    } else {
                        this.response = response;
                        this.status = "received";
                    }
                }
            }
        }
    });
</script>