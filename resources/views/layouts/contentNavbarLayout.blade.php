@isset($pageConfigs)
    {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

@extends('layouts/commonMaster')

@php
    // =========================
    // Display Toggles (with defaults)
    // =========================
    $contentNavbar = $contentNavbar ?? true;
    $containerNav = $containerNav ?? 'container-xxl';
    $isNavbar = $isNavbar ?? true;
    $isMenu = $isMenu ?? true;
    $isFlex = $isFlex ?? false;
    $isFooter = $isFooter ?? true;
    $customizerHidden = $customizerHidden ?? '';

    // =========================
    // Config / Layout Classes
    // =========================
    $menuFixed = $configData['menuFixed'] ?? '';
    $menuCollapsed = $configData['menuCollapsed'] ?? '';
    $navbarType = $navbarType ?? ($configData['navbarType'] ?? '');
    $footerFixed = $configData['footerFixed'] ?? '';
    $navbarDetached = 'navbar-detached';

    // =========================
    // Content Container
    // =========================
    $container = $container ?? 'container-xxl';
@endphp

@section('layoutContent')
    <script type="module">
        console.log("🔥 FCM Init: Starting Firebase initialization...");

        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyByoL8iPjlkVpctypejghFLnhzOjwP4g9Y",
            authDomain: "kandura-store.firebaseapp.com",
            projectId: "kandura-store",
            storageBucket: "kandura-store.firebasestorage.app",
            messagingSenderId: "990745065212",
            appId: "1:990745065212:web:d6027cafedb6aa9d86c979",
            measurementId: "G-276FYB2V2M"
        };

        try {
            console.log("🔥 Initializing Firebase app...");
            const app = initializeApp(firebaseConfig);
            console.log("✅ Firebase app initialized");

            const messaging = getMessaging(app);
            console.log("✅ Firebase messaging initialized");

            // Check if browser supports notifications
            if (!('Notification' in window)) {
                console.warn("⚠️ Browser does not support notifications");
            } else {
                console.log("✅ Browser supports notifications, permission:", Notification.permission);
            }

            // Check if service workers are supported
            if (!('serviceWorker' in navigator)) {
                console.error("❌ Service Workers not supported");
            } else {
                console.log("✅ Service Workers supported");

                // Register service worker and WAIT for it to be ready
                navigator.serviceWorker.register('/firebase-messaging-sw.js')
                    .then((registration) => {
                        console.log("✅ Service Worker registration accepted");

                        // Wait for the service worker to be ACTIVE
                        return new Promise((resolve) => {
                            if (registration.active) {
                                console.log("✅ Service Worker already active");
                                resolve(registration);
                            } else {
                                console.log("⏳ Waiting for Service Worker to activate...");
                                registration.addEventListener('updatefound', () => {
                                    const newWorker = registration.installing;
                                    newWorker.addEventListener('statechange', () => {
                                        if (newWorker.state === 'activated') {
                                            console.log("✅ Service Worker activated");
                                            resolve(registration);
                                        }
                                    });
                                });
                            }
                        });
                    })
                    .then((registration) => {
                        console.log("📍 Service Worker is ready, scope:", registration.scope);

                        // NOW request notification permission
                        return Notification.requestPermission().then(permission => {
                            console.log("📍 Notification permission result:", permission);
                            return {
                                registration,
                                permission
                            };
                        });
                    })
                    .then(({
                        registration,
                        permission
                    }) => {
                        if (permission === 'granted') {
                            console.log("🔐 Getting FCM token...");
                            return getToken(messaging, {
                                    vapidKey: "BI4Lcqv7-_bw59mGn7RVeQ6J3fP2jovxGliyHlL4b3jNMNIlaWmlFigbRUEDAWcUFzmb2_5Ewm6Qu-WOJ940BYo",
                                    serviceWorkerRegistration: registration
                                })
                                .then(currentToken => {
                                    if (currentToken) {
                                        console.log("✅ FCM Token acquired:", currentToken.substring(0, 50) + "...");

                                        // Save token to server
                                        return fetch("{{ route('admin.fcm-token.store') }}", {
                                                method: "POST",
                                                headers: {
                                                    "Content-Type": "application/json",
                                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                                },
                                                body: JSON.stringify({
                                                    fcm_token: currentToken
                                                })
                                            })
                                            .then(response => {
                                                console.log("💾 Server response status:", response.status);
                                                if (response.ok) {
                                                    console.log("✅ FCM token saved to server successfully!");
                                                    return response.json();
                                                } else {
                                                    console.error("❌ Failed to save FCM token. Status:",
                                                        response.status);
                                                    return response.text().then(t => {
                                                        throw new Error(t);
                                                    });
                                                }
                                            });
                                    } else {
                                        console.warn("⚠️ No FCM token returned");
                                    }
                                })
                                .catch(err => {
                                    console.error("❌ Error retrieving FCM token:", err);
                                });
                        } else {
                            console.warn("⚠️ Notification permission denied");
                        }
                    })
                    .catch(err => {
                        console.error("❌ Service Worker setup failed:", err);
                    });
            }

            // Handle foreground messages
            onMessage(messaging, (payload) => {
                console.log("📨 Foreground message received:", payload);

                const title = payload.notification?.title || payload.data?.title || "New Notification";
                const body = payload.notification?.body || payload.data?.body || "";

                const options = {
                    body: body,
                    icon: payload.notification?.icon || "/firebase-logo.png",
                    data: payload.data || {}
                };  

                if (Notification.permission === "granted") {
                    console.log("🔔 Showing browser notification:", title);
                    new Notification(title, options);
                } else {
                    console.warn("⚠️ Notification permission not granted for foreground notification");
                }
            });

        } catch (error) {
            console.error("❌ Fatal error in FCM initialization:", error);
        }
    </script>



    <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
        <div class="layout-container">

            {{-- Sidebar / Menu --}}
            @if ($isMenu)
                @include('layouts/sections/menu/verticalMenu')
            @endif

            <!-- Layout page -->
            <div class="layout-page">

                {{-- Jetstream banner (لو استخدمته لاحقاً) --}}
                {{-- <x-banner /> --}}

                {{-- Navbar --}}
                @if ($isNavbar)
                    @include('layouts/sections/navbar/navbar')
                @endif

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    @if ($isFlex)
                        <div class="{{ $container }} d-flex align-items-stretch flex-grow-1 p-0">
                        @else
                            <div class="{{ $container }} flex-grow-1 container-p-y">
                    @endif

                    @yield('content')

                </div>
                <!-- / Content -->

                {{-- Footer --}}
                @if ($isFooter)
                    @include('layouts/sections/footer/footer')
                @endif

                <div class="content-backdrop fade"></div>
            </div>
            <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>

    @if ($isMenu)
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    @endif

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>
    </div>
@endsection

</script>
