<div
    x-data="{
        lat: @entangle($attributes->wire('lat')),
        lng: @entangle($attributes->wire('lng')),
        map: null,
        marker: null,

        init() {
            this.map = L.map($refs.map).setView(
                [this.lat ?? 41.3111, this.lng ?? 69.2797],
                13
            );

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(this.map);

            this.marker = L.marker(
                [this.lat ?? 41.3111, this.lng ?? 69.2797],
                { draggable: true }
            ).addTo(this.map);

            this.marker.on('dragend', (e) => {
                let pos = e.target.getLatLng();
                this.lat = pos.lat;
                this.lng = pos.lng;
            });

            this.map.on('click', (e) => {
                this.marker.setLatLng(e.latlng);
                this.lat = e.latlng.lat;
                this.lng = e.latlng.lng;
            });
        }
    }"
    class="w-full"
>
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />

    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    <div
        x-ref="map"
        style="height: 400px;"
        class="rounded-lg border"
    ></div>
</div>