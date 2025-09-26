import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'helm_key',
    cluster: 'mt1',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    encrypted: false,
});

window.Echo.channel('helm-monitor')
    .listen('HelmStatusUpdated', (e) => {
        console.log('Data helm diterima:', e);
        // update tampilan dashboard
        document.getElementById('statusHelm').innerText = e.status_helm;
        document.getElementById('statusPekerja').innerText = e.status_pekerja;
    });
