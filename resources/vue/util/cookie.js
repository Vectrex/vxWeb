function _computeExpires(str) {
    const value = parseInt(str, 10);
    let expires = new Date();

    switch (str.charAt(str.length - 1)) {
        case 'Y': expires.setFullYear(expires.getFullYear() + value); break;
        case 'M': expires.setMonth(expires.getMonth() + value); break;
        case 'D': expires.setDate(expires.getDate() + value); break;
        case 'h': expires.setHours(expires.getHours() + value); break;
        case 'm': expires.setMinutes(expires.getMinutes() + value); break;
        case 's': expires.setSeconds(expires.getSeconds() + value); break;
        default: expires = new Date(str);
    }

    return expires;
}

function _conv (opts) {
    let res = '';

    Object.getOwnPropertyNames(opts).forEach(
        key => {
            if (/^expires$/i.test(key)) {
                let expires = opts[key];

                if (typeof expires !== 'object') {
                    expires += typeof expires === 'number' ? 'D' : '';
                    expires = _computeExpires(expires);
                }
                res += ';' + key + '=' + expires.toUTCString();
            } else if (/^secure$/.test(key)) {
                if (opts[key]) {
                    res += ';' + key;
                }
            } else {
                res += ';' + key + '=' + opts[key];
            }
        }
    );
    if(!opts.hasOwnProperty('path')) {
        res += ';path=/';
    }
    return res;
}

function enabled () {
    const key = '__vxweb-key__', val = 1, regex = new RegExp('(?:^|; )' + key + '=' + val + '(?:;|$)');
    document.cookie = key + '=' + val + ';path=/';
    if(regex.test(document.cookie)) {
        remove(key);
        return true;
    }
    return false;
}

function get (key) {
    const raw = getRaw(key);
    return  raw ? decodeURIComponent(raw) : null;
}

function set (key, val, options = {}) {
    document.cookie = key + '=' + encodeURIComponent(val) + _conv(options);
}

function getAll () {
    const reKey = /(?:^|; )([^=]+?)(?:=([^;]*))?(?:;|$)/g, cookies = {};
    let match;

    while ((match = reKey.exec(document.cookie))) {
        reKey.lastIndex = (match.index + match.length) - 1;
        cookies[match[1]] = decodeURIComponent(match[2]);
    }

    return cookies;
}

function remove (key, options = {}) {
    return set (key, 'x',{ ...options, ...{ expires: -1 }});
}

function setRaw (key, val, options) {
    document.cookie = key + '=' + val + _conv(options);
}

function getRaw (key) {
    if (!key || typeof key !== 'string') {
        return null;
    }
    const escKey = key.replace(/[.*+?^$|[\](){}\\-]/g, '\\$&');
    const match = (new RegExp('(?:^|; )' + escKey + '(?:=([^;]*))?(?:;|$)').exec(document.cookie));
    if(match === null) {
        return null;
    }
    return match[1];
}

export {
    enabled,
    get,
    getAll,
    set,
    getRaw,
    setRaw,
    remove
}