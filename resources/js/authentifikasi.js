document.addEventListener("DOMContentLoaded", () => {
    const loginBtn = document.getElementById("loginBtn");
    const loginForm = document.getElementById("loginForm");

    if (loginBtn && loginForm) {
        loginForm.addEventListener("submit", (e) => {
            showLoader("Sedang memuat halaman...");
        });
    } else {
    }
});

function showLoader(message = "Sedang memuat halaman...") {
    const loader = document.getElementById("page-loader");
    if (loader) {
        loader.classList.remove("hidden");
        loader.querySelector("p").textContent = message;
    }
}

function hideLoader() {
    const loader = document.getElementById("page-loader");
    if (loader) {
        loader.classList.add("hidden");
    }
}
