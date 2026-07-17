//Sidebar controls

document.addEventListener("DOMContentLoaded", function () {
    var sidebar = document.getElementById("sidebar");
    var mainWrapper = document.getElementById("mainWrapper");
    var sidebarButton = document.getElementById("sidebarButton");
    var mobileMenuButton = document.getElementById("mobileMenuButton");

    //Desktop button
    if (sidebarButton) {
        sidebarButton.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
            mainWrapper.classList.toggle("expanded");
        });
    }

    //Mobile button
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener("click", function () {
            sidebar.classList.toggle("mobile-open");
        });
    }
});
