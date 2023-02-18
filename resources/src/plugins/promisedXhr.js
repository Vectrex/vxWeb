export default {
    install: (app, options) => {
        app.config.globalProperties.$promisedXhr = async(
            url, method = 'GET', headers = {}, payload = null, reqOptions = {}
        ) => {

        }
    }
}