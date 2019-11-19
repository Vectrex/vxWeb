export default async function simpleFetch(url, method = 'GET', headers = {}, payload = null) {

    if(!headers['X-CSRF-Token'] && document.querySelector('meta[name="csrf-token"]')) {
        headers['X-CSRF-Token'] = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    }

    const response = await fetch(
        url,
        {
            method: method.toUpperCase(),
            body: payload,
            headers: headers
        }
    );

    return await response.json();
}
