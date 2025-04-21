console.log("✅ Auth.js loaded ssss");

document.addEventListener("DOMContentLoaded", () => {
    const loginBtn = document.getElementById("loginBtn");
    const loginForm = document.getElementById("loginForm");

    if (loginBtn && loginForm) {
        console.log("✅ JavaScript loaded and login button found."); // 🔍 Untuk memastikan JS nyala
        loginForm.addEventListener("submit", (e) => {
            showLoader("Sedang memuat halaman...");
            console.log("🌀 Login button clicked, loader shown."); // 🔍 Untuk debug
        });
    } else {
        console.log("⚠️ Login button or form not found.");
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
