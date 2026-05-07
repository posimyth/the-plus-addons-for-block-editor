document.addEventListener('DOMContentLoaded', function () {
    var noticeDis = document.querySelector('.nxt-plugin-notice-dismiss')
    if(noticeDis){
        noticeDis.addEventListener('click', function (e) {
           let pluginNotice = document.querySelector('.nxt-plugin-rebranding-update');
            
            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'nxt_dismiss_plugin_rebranding',
                    nonce: tpgb_admin.tpgb_nonce,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (pluginNotice.style.opacity !== '1') {
                    if (pluginNotice.style.opacity === '0') {
                        pluginNotice.remove();
                    } else {
                        setTimeout(function () {
                            pluginNotice.remove();
                        }, 100);
                    }
                } else {
                    pluginNotice.style.transition = 'opacity 100ms';
                    pluginNotice.style.opacity = '0';
                    setTimeout(function () {
                        pluginNotice.style.display = 'none';
                        pluginNotice.remove();
                    }, 200);
                }
            })
            .catch(error => {
                alert(error.message || 'An unexpected error occurred.');
            });
        });
    }

    var hallnoticeDis = document.querySelector('.nxt-halloween-notice-dismiss')
    if(hallnoticeDis){
        hallnoticeDis.addEventListener('click', function (e) {
           let pluginNotice = document.querySelector('.nxt-plugin-halloween');
            
            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'nxt_dismiss_plugin_halloween',
                    nonce: tpgb_admin.tpgb_nonce,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (pluginNotice.style.opacity !== '1') {
                    if (pluginNotice.style.opacity === '0') {
                        pluginNotice.remove();
                    } else {
                        setTimeout(function () {
                            pluginNotice.remove();
                        }, 100);
                    }
                } else {
                    pluginNotice.style.transition = 'opacity 100ms';
                    pluginNotice.style.opacity = '0';
                    setTimeout(function () {
                        pluginNotice.style.display = 'none';
                        pluginNotice.remove();
                    }, 200);
                }
            })
            // .catch(error => {
            //     alert(error.message || 'An unexpected error occurred.');
            // });
        });
    }
    setTimeout(function () {
    const notices = document.querySelectorAll('.nxt-notice-wrap');
        notices.forEach(function (notice) {
            const dismissBtn = notice.querySelector('.notice-dismiss');
            const noticeId = notice.getAttribute('data-notice-id');
            if (dismissBtn && noticeId) {
                dismissBtn.addEventListener('click', function () {
                    const xhr = new XMLHttpRequest();
                    const formData = new FormData();
                    formData.append('action', 'nexter_dismiss_notice');
                    formData.append('notice_id', noticeId);
                    formData.append('nonce', tpgb_admin.tpgb_nonce);
                    xhr.open('POST', ajaxurl, true);
                    xhr.send(formData);
                });
            }
        });
    }, 500);
});