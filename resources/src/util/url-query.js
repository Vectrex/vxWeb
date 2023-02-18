function urlQueryCreate (url, query = {}) {
    return url + (url.indexOf('?') !== -1 ? '&' : '?') + new URLSearchParams(query).toString();
}

/* @see https://stackoverflow.com/questions/8648892/how-to-convert-url-parameters-to-a-javascript-object */

function urlSearchToObject (query) {
    return Object.fromEntries(new URLSearchParams(query));
}

/* @see https://stackoverflow.com/questions/1714786/query-string-encoding-of-a-javascript-object */

export { urlQueryCreate, urlSearchToObject }