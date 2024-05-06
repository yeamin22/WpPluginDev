jQuery(document).ready(function () {
    initMadp();
    function initMadp() {
        // Create a map object centered on Texas
        let container = document.getElementById('wcs_google_map');
        let markerData = container.getAttribute("data-json");
        let markers = JSON.parse(markerData);  
        if (container) {
            console.log(JSON.parse(markerData));
            var map = new google.maps.Map(container, {
                center: { lat: 42.809, lng: -112.259 },
                zoom: 5,
                mapId: "b3d4f6e59f36b7bd"
            });
            featureLayer = map.getFeatureLayer("ADMINISTRATIVE_AREA_LEVEL_1");
            // Define a style with purple fill and border.
            const featureStyleOptions = {
                strokeColor: "#810FCB",
                strokeOpacity: 1.0,
                strokeWeight: 3.0,
                fillColor: "#810FCB",
                fillOpacity: 0.5,
            };

            // Apply the style to a single boundary.
            featureLayer.style = (options) => {
                if (options.feature.placeId == "ChIJ6Znkhaj_WFMRWIf3FQUwa9A" || options.feature.placeId == "ChIJzfkTj8drTIcRP0bXbKVK370" || "ChIJVWqfm3xuk1QRdrgLettlTH0" == options.feature.placeId || "ChIJ-bDD5__lhVQRuvNfbGh4QpQ" == options.feature.placeId) {
                    return featureStyleOptions;
                }
            };
            /* Marker list */
            markers.forEach((item) => {
                const infowindow = new google.maps.InfoWindow({
                    content: item.wcs_glmap_marker_content,
                    ariaLabel: "Uluru",
                });                
                var icon = {
                    url: item.wcs_glmap_marker_icon.url, // url
                    scaledSize: new google.maps.Size(50, 50), // scaled size
                    origin: new google.maps.Point(0,0), // origin
                    anchor: new google.maps.Point(0, 0) // anchor
                };
                var marker = new google.maps.Marker({
                    position: {lat : Number(item.wcs_glmap_marker_lat), lng: Number(item.wcs_glmap_marker_lng)},
                    map,
                    title: item.wcs_glmap_marker_title,
                    icon: icon
                });
                marker.addListener('mouseover', function () {
                    infowindow.open({
                        anchor: marker,
                        map,
                });
                marker.addListener('mouseout', function() {
                    infowindow.close();
                });
                marker.addListener('click', function () {
                    window.location.href = item.wcs_glmap_marker_link.url;
                });

                });
            });

        }

    }


});






