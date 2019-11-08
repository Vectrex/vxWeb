export default async function simpleFetch(url, method = 'GET', headers = {}, payload = null) {

    const response = await fetch(
        url,
        {
            method: method.toUpperCase(),
            body: payload
        }
    );

    return await response.json();

}
