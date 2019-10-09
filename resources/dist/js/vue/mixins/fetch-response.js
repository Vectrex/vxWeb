export default {

    data () {
        return {
            fetch: {
                response: null,
                status: null
            }
        };
    },

    watch: {
        "fetch.response": function(val) {
            if(val.locationHref) {
                window.location.href = val.locationHref;
            }
        }
    },

    methods: {

        fetchPostResponse(url, parameters) {

            fetch(url, {
                method: "POST",
                mode: "cors",
                cache: "no-cache",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": this.$el.querySelector("[name=_csrf_token]") ? this.$el.querySelector("[name=_csrf_token]").value || '' : ''
                },
                referrer: "no-referrer",
                body: JSON.stringify(parameters || {})
            })
                .then(response => response.json())
                .then(response => {
                    this.fetch.response = response;
                    this.fetch.status = "received";
                    this.$emit("response-received");
                });

            this.fetch.status = "sent";
            this.$emit("request-sent");
        },

        fetchGetResponse(url, parameters) {

            let query = [];

            Object.keys(parameters || {}).forEach(key => query += encodeURIComponent(key) + "=" + encodeURIComponent(parameters[key]));

            if(query.length) {
                url += (url.indexOf("?") === -1 ? '?' : '&') + query.join('&');
            }

            return fetch(url, {})
                .then(response => response.json())
                .then(response => {
                    this.fetch.response = response;
                    this.fetch.status = "received";
                    this.$emit("response-received");
                });

            this.fetch.status = "sent";
            this.$emit("request-sent");
        }

    }

}