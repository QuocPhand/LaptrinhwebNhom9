function hideFunctionDropdown() {
    var dropdown = document.querySelector(".function-dropdown");
    dropdown.style.display = "none";
}

function showFunctionDropdown() {
    var dropdown = document.querySelector(".function-dropdown");
    dropdown.style.display = "block";
}

window.addEventListener("resize", function() {
    if (window.innerWidth > 768) {
        var content = document.querySelector(".main-content");
        content.style.paddingTop = 100 + "px"
        hideFunctionDropdown();
    }
});

function toggleFunctionDropdown() {
    var dropdown = document.querySelector(".function-dropdown");
    var content = document.querySelector(".main-content");

    if (dropdown.style.display === "block") {
        content.style.paddingTop = 100 + "px"
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
        content.style.paddingTop = 0 + "px"
    }
}
