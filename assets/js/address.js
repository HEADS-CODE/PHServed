//Address dropdowns

document.addEventListener("DOMContentLoaded", function () {
    var regionSelect = document.getElementById("region");
    var provinceSelect = document.getElementById("province");
    var citySelect = document.getElementById("city");
    var barangaySelect = document.getElementById("barangay");

    var addressData = {};
    var selectedRegion = "";
    var selectedProvince = "";
    var selectedCity = "";
    var desiredProvince = provinceSelect.dataset.selected || "";
    var desiredCity = citySelect.dataset.selected || "";
    var desiredBarangay = barangaySelect.dataset.selected || "";

    //Address form check
    if (!regionSelect) {
        return;
    }

    fetch("../assets/data/philippine-addresses.json")
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            addressData = data;

            Object.keys(addressData).forEach(function (regionCode) {
                var option = document.createElement("option");

                option.value = regionCode;
                option.textContent =
                    addressData[regionCode].region_name;

                regionSelect.appendChild(option);
            });

            regionSelect.disabled = false;

            if (desiredProvince !== "") {
                var matchingRegion = Object.keys(addressData).find(
                    function (regionCode) {
                        return Object.prototype.hasOwnProperty.call(
                            addressData[regionCode].province_list,
                            desiredProvince
                        );
                    }
                );

                if (matchingRegion) {
                    regionSelect.value = matchingRegion;
                    regionSelect.dispatchEvent(new Event("change"));

                    provinceSelect.value = desiredProvince;
                    provinceSelect.dispatchEvent(new Event("change"));

                    citySelect.value = desiredCity;
                    citySelect.dispatchEvent(new Event("change"));

                    barangaySelect.value = desiredBarangay;
                }
            }
        })
        .catch(function () {
            alert(
                "The Philippine address file could not be loaded. " +
                "Please check assets/data/philippine-addresses.json."
            );
        });

    regionSelect.addEventListener("change", function () {
        selectedRegion = regionSelect.value;

        resetSelect(provinceSelect, "Select Province");
        resetSelect(citySelect, "Select City / Municipality");
        resetSelect(barangaySelect, "Select Barangay");

        citySelect.disabled = true;
        barangaySelect.disabled = true;

        if (selectedRegion === "") {
            provinceSelect.disabled = true;
            return;
        }

        var provinces =
            addressData[selectedRegion].province_list;

        Object.keys(provinces).forEach(function (provinceName) {
            addOption(provinceSelect, provinceName, provinceName);
        });

        provinceSelect.disabled = false;
    });

    provinceSelect.addEventListener("change", function () {
        selectedProvince = provinceSelect.value;

        resetSelect(citySelect, "Select City / Municipality");
        resetSelect(barangaySelect, "Select Barangay");

        barangaySelect.disabled = true;

        if (selectedProvince === "") {
            citySelect.disabled = true;
            return;
        }

        var cities =
            addressData[selectedRegion]
                .province_list[selectedProvince]
                .municipality_list;

        Object.keys(cities).forEach(function (cityName) {
            addOption(citySelect, cityName, cityName);
        });

        citySelect.disabled = false;
    });

    citySelect.addEventListener("change", function () {
        selectedCity = citySelect.value;

        resetSelect(barangaySelect, "Select Barangay");

        if (selectedCity === "") {
            barangaySelect.disabled = true;
            return;
        }

        var barangays =
            addressData[selectedRegion]
                .province_list[selectedProvince]
                .municipality_list[selectedCity]
                .barangay_list;

        barangays.forEach(function (barangayName) {
            addOption(
                barangaySelect,
                barangayName,
                barangayName
            );
        });

        barangaySelect.disabled = false;
    });

    function addOption(selectElement, value, label) {
        var option = document.createElement("option");

        option.value = value;
        option.textContent = label;

        selectElement.appendChild(option);
    }

    function resetSelect(selectElement, firstLabel) {
        selectElement.innerHTML =
            '<option value="">' + firstLabel + "</option>";
    }
});
