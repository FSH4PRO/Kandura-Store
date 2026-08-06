<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>FCM Test</title>
</head>

<body style="font-family: Arial; padding:20px;">
    <h2>FCM Test Page</h2>

    <button id="btn" style="padding:10px 15px;">Save Token</button>
    <pre id="out" style="background:#111;color:#0f0;padding:10px;margin-top:10px;"></pre>

    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js"></script>

    <script>
        const out = document.getElementById('out');

        const firebaseConfig = {
            apiKey: "AIzaSyByoL8iPjlkVpctypejghFLnhzOjwP4g9Y",
            authDomain: "kandura-store.firebaseapp.com",
            projectId: "kandura-store",
            storageBucket: "kandura-store.appspot.com",
            messagingSenderId: "990745065212",
            appId: "1:990745065212:web:d6027cafedb6aa9d86c979",
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function log(x) {
            out.textContent += x + "\n";
        }

        document.getElementById('btn').addEventListener('click', async () => {
            try {
                log("permission: " + Notification.permission);

                const reg = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                log("service worker registered ✅");

                const token = await messaging.getToken({
                    vapidKey: "BI4Lcqv7-_bw59mGn7RVeQ6J3fP2jovxGliyHlL4b3jNMNIlaWmlFigbRUEDAWcUFzmb2_5Ewm6Qu-WOJ940BYo",
                    serviceWorkerRegistration: reg,
                });

                log("token: " + token);

                const res = await fetch("{{ route('admin.fcm-token.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        fcm_token: token
                    })
                });

                log("save status: " + res.status);
                log("save response: " + await res.text());

            } catch (e) {
                log("ERROR: " + (e?.message || e));
                console.error(e);
            }
        });
    </script>
</body>

</html>
