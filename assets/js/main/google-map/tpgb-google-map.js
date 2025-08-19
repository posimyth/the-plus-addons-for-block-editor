document.addEventListener("DOMContentLoaded", function () {
    tpgbInitMap();
});

function tpgbInitMap() {
    if (typeof google === "undefined" || !google.maps) {
        console.warn("Google Maps is not loaded yet.");
        return;
    }

    var elements = document.querySelectorAll(".tpgb-adv-map");

    elements.forEach(function (el) {
        var data_id = el.getAttribute("data-id"),
            data = JSON.parse(el.getAttribute("data-map-settings")),
            map = null,
            bounds = null,
            infoWindow = null,
            position = null,
            styles1 = "";

        if (!el.classList.contains("map-loaded")) {
            var map_toBuild = [];
            var build = function () {
                if (styles1 !== "") {
                    data.options.styles = JSON.parse(styles1);
                }
                console.log("data = ", data);

                bounds = new google.maps.LatLngBounds();
                map = new google.maps.Map(
                    document.getElementById(data_id),
                    data.options
                );
                infoWindow = new google.maps.InfoWindow();

                map.setTilt(45);

                google.maps.event.addListener(
                    infoWindow,
                    "domready",
                    function () {
                        var iwOuter = document.querySelector(".gm-style-iw");
                        var iwBackground = iwOuter.previousElementSibling;
                        var parentdiv = iwOuter.parentElement;
                        parentdiv.classList.add("marker-icon");
                        var iwCloseBtn = iwOuter.nextElementSibling;
                        iwCloseBtn.style.display = "none";
                        iwOuter.classList.add("marker-title");
                    }
                );

                for (let i = 0; i < data.places.length; i++) {
                    const place = data.places[i];

                    console.log("data.places[i].latOrAddr =", place.latOrAddr);

                    if (place.latOrAddr === "latitude") {
                        position = new google.maps.LatLng(
                            place.latitude,
                            place.longitude
                        );
                        bounds.extend(position);

                        const markerOptions = {
                            position: position,
                            map: map,
                            title: place.address || "",
                        };

                        if (place.pin_icon) {
                            markerOptions.icon = place.pin_icon;
                        }

                        let marker = new google.maps.Marker(markerOptions);

                        google.maps.event.addListener(
                            marker,
                            "click",
                            (function (marker, i) {
                                return function () {
                                    if (place.address?.length > 1) {
                                        infoWindow.setContent(
                                            `<div class="gmap_info_content"><p>${place.address}</p></div>`
                                        );
                                        infoWindow.open(map, marker);
                                    }
                                };
                            })(marker, i)
                        );

                        map.fitBounds(bounds);
                    }

                    // For address-based markers
                    if (place.latOrAddr === "address" && place.addr) {
                        console.log("place = ", place);
                        const geocoder = new google.maps.Geocoder();
                        const addr = place.addr;
                        const address = place.address || place.addr;
                        const icon = place.pin_icon || "";

                        console.log(
                            `[Geocode] Starting geocoding for: ${addr}`
                        );

                        geocoder.geocode(
                            { address: addr },
                            function (results, status) {
                                console.log(
                                    `[Geocode] Status for "${addr}":`,
                                    status
                                );

                                if (
                                    status === google.maps.GeocoderStatus.OK &&
                                    results[0]
                                ) {
                                    const position =
                                        results[0].geometry.location;
                                    console.log(
                                        `[Geocode] Geocoded position:`,
                                        position.toString()
                                    );

                                    bounds.extend(position);

                                    const markerOptions = {
                                        position: position,
                                        map: map,
                                        title: address || "",
                                    };

                                    if (icon) {
                                        markerOptions.icon = icon;
                                    }

                                    const marker = new google.maps.Marker(
                                        markerOptions
                                    );
                                    console.log(
                                        `[Geocode] Marker created for: "${address}"`
                                    );

                                    google.maps.event.addListener(
                                        marker,
                                        "click",
                                        (function (marker, i) {
                                            return function () {
                                                console.log(
                                                    `[Marker Click] for: "${address}"`
                                                );
                                                const infoContent = `<div class="gmap_info_content"><p>${address}</p></div>`;
                                                infoWindow.setContent(
                                                    infoContent
                                                );
                                                console.log(
                                                    `[InfoWindow] Content: ${infoContent}`
                                                );
                                                infoWindow.open(map, marker);
                                            };
                                        })(marker, i)
                                    );

                                    map.fitBounds(bounds);
                                    console.log(
                                        `[Map] Bounds fitted for: "${address}"`
                                    );
                                } else {
                                    console.warn(
                                        `[Geocode] Failed for "${address}" | Status: ${status}`
                                    );
                                }
                            }
                        );
                    }
                }

                // Reset zoom after bounds fit
                var bounds_Listener = google.maps.event.addListener(
                    map,
                    "idle",
                    function () {
                        this.setZoom(data.options.zoom);
                        google.maps.event.removeListener(bounds_Listener);
                    }
                );

                var update = function () {
                    google.maps.event.trigger(map, "resize");
                    map.setCenter(position);
                };
                update();
            };

            var init_Map = function () {
                for (var i = 0, l = map_toBuild.length; i < l; i++) {
                    map_toBuild[i]();
                }
            };

            var initialize = function () {
                init_Map();
            };

            map_toBuild.push(build);
            initialize();
            el.classList.add("map-loaded");
        }
    });
}
