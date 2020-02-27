export default {
    /**
     * @return {string}
     */
    create (url, query = {}) {
        const params = [];
        Object.keys(query).forEach(key => params.push(encodeURIComponent(key) + '=' + encodeURIComponent(query[key])));
        return url + (url.indexOf('?') !== -1 ? '&' : '?') + params.join('&');
    }
}
