const API_URL = process.env.MIX_API_URL;

function noop() {

}

if (!window.NProgress) {
    window.NProgress = {
        start: noop,
        done: noop
    }
}

export async function  $get(uri, params = {}, progress = true) {
    if (progress) {
        NProgress.start();
    }

    uri = isHttp(uri) ? uri :  ((API_URL ?? '') + uri);

    let url =  uri + '?' + buildQuery(params);

    return fetch(url).then(response => {
        if (progress) {
            NProgress.done();
        }

        return response.json();
    });
}

export async function $post(uri, params = {}, progress = true) {

    uri = isHttp(uri) ? uri :  ((API_URL ?? '') + uri);

    //   let formData = buildQuery(params);
    if (progress) {
        NProgress.start();
    }

    var token = $("meta[name='csrf-token']").attr("content");

    return fetch(uri, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
        },
        body: JSON.stringify(params),
    })
        .then((response) => {
            if (progress) {
                NProgress.done();
            }

            return response.json();
        })
}

export function buildQuery(data) {
    if (typeof data !== 'object') {
        return '';
    }

    var queries = [];
    for (var k in data) {
        if (data.hasOwnProperty(k)) {
            queries.push(k + '=' + encodeURIComponent(data[k]));
        }
    }
    return queries.join('&');
}

export function isHttp(url) {
    if (!url) {
        return false;
    }

    return url.startsWith('http://') || url.startsWith('https://');
}

export function $upload(uri, files, params = {}) {
    const formData = new FormData();
    /// const fileField = document.querySelector('input[type="file"]');

    forEach(params, (v,k) => {
        formData.append(k ,v);
    });

    for (let i = 0; i< files.length; i++) {
        formData.append('file_' + i, files[i]);
    }

    return fetch(uri, {
        method: 'POST',
        body: formData
    })
        .then((response) => response.json())
        .catch((error) => {
            console.error('Error:', error);
        });
}

export function forEach(obj, cb) {
    if (Array.isArray(obj)) {
        obj.forEach (cb);
        return;
    }

    for (var k in obj) {
        if (obj.hasOwnProperty(k)) {
            cb(obj[k], k);
        }
    }
}
