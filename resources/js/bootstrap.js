import axios from "axios";
import Swal from "sweetalert2"; // Declare the Swal variable
window.axios = axios;
window.Swal = Swal; // Assign the imported Swal to window.Swal

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Add CSRF token to all requests
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

// Request interceptor
window.axios.interceptors.request.use(
    (config) => {
        // Show loading indicator
        const loadingElements = document.querySelectorAll(".loading-indicator");
        loadingElements.forEach((el) => (el.style.display = "block"));

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor
window.axios.interceptors.response.use(
    (response) => {
        // Hide loading indicator
        const loadingElements = document.querySelectorAll(".loading-indicator");
        loadingElements.forEach((el) => (el.style.display = "none"));

        return response;
    },
    (error) => {
        // Hide loading indicator
        const loadingElements = document.querySelectorAll(".loading-indicator");
        loadingElements.forEach((el) => (el.style.display = "none"));

        // Handle common errors
        if (error.response) {
            switch (error.response.status) {
                case 401:
                    window.location.href = "/login";
                    break;
                case 403:
                    if (window.Swal) {
                        window.Swal.fire({
                            title: "Akses Ditolak",
                            text: "Anda tidak memiliki izin untuk mengakses resource ini.",
                            icon: "error",
                        });
                    }
                    break;
                case 422:
                    // Validation errors - handled by individual forms
                    break;
                case 500:
                    if (window.Swal) {
                        window.Swal.fire({
                            title: "Server Error",
                            text: "Terjadi kesalahan pada server. Silakan coba lagi nanti.",
                            icon: "error",
                        });
                    }
                    break;
            }
        }

        return Promise.reject(error);
    }
);
