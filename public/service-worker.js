const CACHE_NAME = "laravel-pwa-v1";

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            // Podemos opcionalmente adicionar apenas a página inicial e offline
            return cache.addAll(["/", "/offline"]);
        })
    );
});

self.addEventListener("fetch", (event) => {
    const request = event.request;

    // Para CSS, JS e imagens: cache-first
    if (["style", "script", "image"].includes(request.destination)) {
        event.respondWith(
            caches.match(request).then((cached) => {
                return (
                    cached ||
                    fetch(request).then((response) => {
                        caches
                            .open(CACHE_NAME)
                            .then((cache) =>
                                cache.put(request, response.clone())
                            );
                        return response;
                    })
                );
            })
        );
        return;
    }

    // Para HTML: network-first
    event.respondWith(
        fetch(request).catch(() =>
            caches.match(request).then((r) => r || caches.match("/offline"))
        )
    );
});
