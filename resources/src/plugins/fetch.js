export default {
    install: (app, options) => {
        app.config.globalProperties.$fetch = async (
            url, method = 'GET', headers = {}, payload = null, reqOptions = {}
        ) => {
            const headerKeys = Object.keys(headers).map(key => key.toLowerCase());

            if (headerKeys.indexOf('x-csrf-token') === -1 && document.querySelector('meta[name="csrf-token"]')) {
                headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
            }
            if (headerKeys.indexOf('content-type') === -1) {
                headers['Content-Type'] = 'application/json';
            }

            let bearerToken = sessionStorage.getItem("bearerToken");

            if (bearerToken) {
                headers['Authorization'] = 'Bearer ' + bearerToken;
            }

            const response = await fetch(
                url,
                Object.assign( {}, reqOptions, { method: method.toUpperCase(), body: payload, headers: headers, credentials: 'same-origin' } )
            );

            if (response.status >= 400) {
                if (options.router && options.authFailureRoute) {
                    options.router.replace(options.authFailureRoute);
                }
                else {
                    throw {status: response.status};
                }
            }
            const decodedResponse = await response.json();

            if (decodedResponse.bearerToken) {
                sessionStorage.setItem("bearerToken", decodedResponse.bearerToken);
            }

            return decodedResponse;
        }
    }
}