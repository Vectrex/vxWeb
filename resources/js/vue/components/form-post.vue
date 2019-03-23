<script>
export default {

    methods: {
        submit() {
            let form = this;

            this.buttonClass = "loading";

            fetch(this.url, {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                headers: { "Content-Type": "application/json" },
                referrer: "no-referrer",
                body: JSON.stringify(this.form)
            })
            .then(response => response.json())
            .then(function (response) {

                form.buttonClass = "";

                if (!response.success) {
                    if (response.errors) {
                        form.errors = response.errors;
                    } else {
                        form.errors = { generic: true };
                    }
                } else {
                    form.errors = {};

                    if (response.id) {
                        form.form.id = response.id;
                    }
                }

                form.message = response.message;
                form.$emit("form-response-received", response);
            });
        }
    }
}
</script>