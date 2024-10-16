<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

<section class="bs-form-wrapper">
    <div class="bs-form-main-container">
        <div class="bs-form-header">
            <h5>ADD BUSINESS</h5>
            <button id="close-bs-form"><i class='fas fa-times'></i></button>
        </div>
        <div class="bs-form-container">
            <form action="" id="bs-form">
                <div class="left-form">
                    <div class="logo-container">
                        <label>Logo <span class="red-text">*</span></label>
                        <img src="../images/placeholder.svg" alt="preview logo img">
                        <div class="input-file-div">
                            <label for="input-file-btn" class="custom-file-upload">
                                Upload Logo
                            </label>
                            <input type="file" name="logo-img" id="input-file-btn" value="" required>
                        </div>
                    </div>

                    <div>
                        <input type="hidden" name="business_id" value="" />
                        <label>Name <span class="red-text">*</span></label>
                        <input type="text" name="business_name" required>
                    </div>

                    <div>
                        <p>Field <span class="red-text">*</span></p>
                        <div class="field-select field-select-form">
                            <select name="field" required>
                                <?php
                                $businessModel = new BusinessModel($connection);
                                $businessFields = $businessModel->getBusinessFields();

                                if ($businessFields) {
                                    foreach ($businessFields as $field) {
                                        $id = htmlspecialchars($field['id']);
                                        $title = htmlspecialchars($field['title']);

                                        echo "<option value='$id'>$title</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label>Contact Number <span class="red-text">*</span></label>
                        <input type="tel" name="bus_contact_num" pattern="09\d{2} \d{3} \d{4}" placeholder="09XX XXX XXXX" maxlength="13" required>
                    </div>

                    <div class="social-links-container">
                        <label>Social Media Links</label>
                        <div>
                            <div><i class="fab fa-facebook-f"></i></div>
                            <input type="url" name="facebook" id="" placeholder="Enter the facebook link">
                        </div>
                        <div>
                            <div><i class="fab fa-instagram-square"></i></div>
                            <input type="url" name="instagram" id="" placeholder="Enter the instagram link">
                        </div>
                        <div>
                            <div><i class="fab fa-tiktok"></i></div>
                            <input type="url" name="tiktok" id="" placeholder="Enter the tiktok link">
                        </div>
                    </div>
                </div>

                <div class="right-form">
                    <div class="desc-container">
                        <label>Description <span class="red-text">*</span></label>
                        <textarea name="description" id="" required></textarea>
                    </div>

                    <div class="location-container">
                        <div class="location-output-container">
                            <label>Location</label>
                            <input type="text" name="location" id="">
                        </div>

                        <div class="choose-location-container">
                            <label>Choose Location</label>
                            <div class="search-location-container">
                                <input type="search" name="search-location-input" id="search-location-input" placeholder="Enter location or coordinates">
                                <button onclick="searchLocation()" type="button"><i class="fas fa-search"></i></button>
                            </div>

                            <div id="map">

                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submit-bs-form">Submit</button>
            </form>
        </div>
    </div>
</section>


<style>
    .bs-form-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        background-color: rgba(0, 0, 0, 0.8);
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;

        transform: scale(0);
        opacity: 0;
        visibility: hidden;
    }

    .bs-form-main-container {
        width: min(70em, 100%);
        height: min(50em, 100%);
        background-color: var(--bg-white);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        border-radius: 20px;

        transform: scale(0);
        opacity: 0;
        visibility: hidden;
        transition: 0.3s all ease-out;
    }

    input[type="file"] {
        display: none;
        /* Hide the default file input */
    }

    .custom-file-upload {
        display: inline-block;
        padding: 10px 20px;
        cursor: pointer;
        background-color: var(--primary);
        color: white;
        border-radius: 5px;
        text-align: center;
    }

    .bs-form-header {
        width: 100%;
        text-align: center;
        position: relative;
        padding: 1.5em;
        background-color: var(--primary);
        border-radius: 20px 20px 0 0;
    }

    .bs-form-header h5 {
        color: var(--font-white) !important;
    }

    #close-bs-form {
        background: var(--bg-white);
        width: 2em;
        height: 2em;
        border-radius: 50%;
        border: none;
        color: var(--primary);
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
    }

    .logo-container img {
        width: 5em;
        height: 5em;
        border-radius: 50%;
    }

    .bs-form-container {
        height: 100%;
        display: flex;
        align-items: center;
    }

    #bs-form {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1.75fr;
        gap: 3em;
        padding: 0em 2em;
    }

    #bs-form .left-form {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    #bs-form .left-form input[type="text"],
    #bs-form .left-form input[type="tel"],
    #bs-form .left-form input[type="url"],
    .location-output-container input {
        width: 100%;
        padding: 1em 1em;
        border-radius: 5px;
    }

    #bs-form label {
        font-size: var(--small);
    }

    .logo-container {
        display: grid;
        grid-template-columns: 5em 1fr;
        align-items: center;
        gap: 0.5em;

    }

    .logo-container label {
        grid-column: 1/3;
    }


    .social-links-container {
        display: flex;
        flex-direction: column;
        gap: 1em;
    }

    .social-links-container>div {
        display: flex;
        background-color: var(--bg-white);
    }

    .social-links-container>div input {
        outline: none;
        border: none;
    }

    .social-links-container>div>div {
        background-color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        aspect-ratio: 1/1;
        color: var(--font-white);
    }

    .right-form {
        display: flex;
        flex-direction: column;
        gap: 1em;
        width: 100%;
    }

    .desc-container {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
    }

    .desc-container textarea {
        width: 100%;
        height: 10em;
        resize: none;
        border-radius: 5px;
        padding: 0.5em 1em;
    }

    .location-container {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
    }

    .location-output-container {
        display: flex;
        flex-direction: column;
    }

    .choose-location-container {
        display: flex;
        flex-direction: column;
        gap: 0.5em;
    }

    .search-location-container {
        display: flex;
        background-color: var(--bg-white);
    }

    .search-location-container>button {
        background-color: var(--primary);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        aspect-ratio: 1/1;
        outline: none;
        border: none;
        padding: 0.5em;
        color: var(--font-white);
        cursor: pointer;
    }

    #search-location-input {
        padding: 0.5em;
        border-radius: 3px;
        outline: none;
        border: none;
        width: 100%;
        max-width: 20em;
    }








    #submit-bs-form {
        padding: 0.5em 1em;
        border-radius: 5px;
        background-color: var(--primary);
        color: var(--font-white);
        cursor: pointer;
        margin-top: -1.5em;
    }

    #map {
        width: 100%;
        max-width: 500px;
        height: 300px;
    }

    @media (max-width: 768px) {
        #bs-form {
            grid-template-columns: 1fr;
            gap: 1em;
            padding: 1em;
        }

        #submit-bs-form {
            margin-top: 0;
        }

        #map {
            width: 100%;
            max-width: 100%;
        }

        .bs-form-container {
            overflow: auto;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 2em;
            width: 100%;
        }

        .bs-form-main-container,
        .bs-form-header {
            border-radius: 0;
        }
    }

    @media (max-width: 768px) {
        .bs-form-container {
            padding: 0em;
        }
    }
</style>


<script>
    const bs_wrapper = document.querySelector('.bs-form-wrapper');
    const bs_container = document.querySelector('.bs-form-main-container');
    const bs_close_btn = document.querySelector('#close-bs-form');

    const logo_btn = document.querySelector('#input-file-btn');

    bs_close_btn.addEventListener('click', closeBSModal);
    logo_btn.addEventListener('change', (event) => setLogo(event));

    function closeBSModal() {
        // fieldModalWrapper.querySelector('form').reset();

        bs_container.style.opacity = '0';
        bs_container.style.visibility = 'hidden';
        bs_container.style.transform = 'scale(0)';

        bs_wrapper.style.opacity = '0';
        bs_wrapper.style.visibility = 'hidden';
        bs_wrapper.style.transform = 'scale(0)';
    }

    function showBSModal() {
        // fieldModalWrapper.querySelector('form').reset();

        bs_container.style.opacity = '1';
        bs_container.style.visibility = 'visible';
        bs_container.style.transform = 'scale(1)';

        bs_wrapper.style.opacity = '1';
        bs_wrapper.style.visibility = 'visible';
        bs_wrapper.style.transform = 'scale(1)';
    }

    function setLogo(e) {
        const img_preview = document.querySelector('.logo-container img');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img_preview.src = e.target.result;
            };
            reader.readAsDataURL(file); // Convert file to base64 string
        }
    }



















    // Initialize the map, centered on Gulang-Gulang, Lucena City
    var map = L.map('map', {
        center: [13.9312, 121.6194], // Center of Gulang-Gulang
        zoom: 15
    });

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Variable to store the current marker
    var currentMarker = null;

    // Enable Geocoder search
    var geocoder = L.Control.Geocoder.nominatim();

    function searchLocation() {
        var searchValue = document.getElementById('search-location-input').value.trim();

        // Check if the input is in latitude,longitude format
        var latLngPattern = /^([-+]?\d+(\.\d+)?),\s*([-+]?\d+(\.\d+)?)$/; // Regex for lat,lng

        if (latLngPattern.test(searchValue)) {
            var latLng = searchValue.split(',').map(Number);
            addMarker(latLng[0], latLng[1], "Coordinates: " + latLng[0] + ", " + latLng[1]);
        } else {
            // If it's not a lat/lng, use the geocoder to search by place name
            geocoder.geocode(searchValue, function(results) {
                if (results.length > 0) {
                    var latLng = results[0].center;
                    addMarker(latLng.lat, latLng.lng, results[0].name);
                } else {
                    alert('Location not found!');
                }
            });
        }
    }

    // Function to add a marker and manage the current marker
    function addMarker(lat, lng, popupContent) {
        // Remove existing marker if there is one
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Set the map view to the new coordinates and add the marker
        map.setView([lat, lng], 18);
        currentMarker = L.marker([lat, lng]).addTo(map)
            .bindPopup(popupContent)
            .openPopup();
    }

    const defaultMapCenter = [13.96066770927695, 121.60971066368803]; // Center of Gulang-Gulang
    const defaultMapZoom = 15;

    // Function to reset the map
    function resetMap() {
        map.setView(defaultMapCenter, defaultMapZoom);
        if (currentMarker) {
            map.removeLayer(currentMarker); // Remove existing marker if any
            currentMarker = null; // Reset currentMarker to null
        }
    }

    // Click event to get coordinates and add a single marker
    map.on('click', function(e) {
        var latLng = e.latlng;
        var coordinates = latLng.lat + ", " + latLng.lng;
        addMarker(latLng.lat, latLng.lng, "Coordinates: " + coordinates);
        document.getElementsByName('location')[0].value = coordinates;
    });

    // Initial fit to Gulang-Gulang
    map.setView(defaultMapCenter, defaultMapZoom);
</script>