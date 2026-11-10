document.addEventListener("DOMContentLoaded", function () {
    const userButton = document.getElementById("userButton");
    const userDropdown = document.querySelector(".user-dropdown");

    if (!userButton || !userDropdown) return;

    userButton.addEventListener("click", function (e) {
        e.stopPropagation();
        userDropdown.classList.toggle("show");
    });

    const menu = userDropdown.querySelector(".user-menu");
    menu.addEventListener("click", (e) => e.stopPropagation());

    document.addEventListener("click", function () {
        userDropdown.classList.remove("show");
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") userDropdown.classList.remove("show");
    });
});
