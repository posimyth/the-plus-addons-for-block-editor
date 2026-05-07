// Fetch API (Vanilla JS)
async function nxtdash_fetch_api(args) {
    if (args) {
        try {
            const response = await fetch(nxt_code_snippet_data.ajax_url, {
                method: 'POST',
                body: args
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            return error.message;
        }
    } else {
        return false;
    }
}

// Append key-value pairs to FormData
function appendFormData(form, data) {
    for (const key in data) {
        if (Object.prototype.hasOwnProperty.call(data, key)) {
            form.append(key, data[key]);
        }
    }
}

// Main install call
async function nxtInstallCall(e, reload = true, slug = '') {
    let ajxData = new FormData();
    let formData = {
        action: 'nexter_ext_plugin_install',
        slug: slug,
        nexter_nonce: nxt_code_snippet_data.ajax_nonce
    };

    appendFormData(ajxData, formData);

    if (slug === 'nexter-extension' && nxt_code_snippet_data.extensionactivate != '1') {
        e.target.textContent = 'Installing Nexter Extension';
    }else if (slug === 'nexter-extension' && nxt_code_snippet_data.extensionactivate == '1') {
        e.target.textContent = 'Activating Nexter Extension';
    }

    try {
        const data = await nxtdash_fetch_api(ajxData);

        if (data) {
            setTimeout(() => {
                if (slug === 'nexter-extension') {
                    e.target.textContent = 'Activated Extension';
                }
            }, 2000);

            if (slug === 'nexter-extension') {
                nxt_code_snippet_data.extensioninstall = data.Success;
            }

            if (reload) {
                window.location.reload();
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Attach click event to button with class "nxt-inst-ext-btn"
document.addEventListener('DOMContentLoaded', function () {
    const installBtn = document.querySelector('.nxt-inst-ext-btn');
    if (installBtn) {
        installBtn.addEventListener('click', function (e) {
            nxtInstallCall(e, true, 'nexter-extension');
        });
    }
});