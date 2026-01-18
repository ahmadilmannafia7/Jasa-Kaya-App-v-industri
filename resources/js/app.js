import "./bootstrap";
import Alpine from "alpinejs";
import Chart from "chart.js/auto";
import Swal from "sweetalert2";

// Make Alpine available globally
window.Alpine = Alpine;
window.Chart = Chart;
window.Swal = Swal;

// Alpine.js components
Alpine.data("sidebar", () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
}));

Alpine.data("dropdown", () => ({
    open: false,
    toggle() {
        this.open = !this.open;
    },
    close() {
        this.open = false;
    },
}));

Alpine.data("modal", () => ({
    open: false,
    show() {
        this.open = true;
        document.body.style.overflow = "hidden";
    },
    hide() {
        this.open = false;
        document.body.style.overflow = "auto";
    },
}));

Alpine.data("notification", () => ({
    show: false,
    message: "",
    type: "success",
    notify(message, type = "success") {
        this.message = message;
        this.type = type;
        this.show = true;
        setTimeout(() => {
            this.show = false;
        }, 5000);
    },
}));

Alpine.data("confirmDelete", () => ({
    async confirm(
        url,
        title = "Apakah Anda yakin?",
        text = "Data yang dihapus tidak dapat dikembalikan!"
    ) {
        const result = await Swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
        });

        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = csrfToken;

            const methodInput = document.createElement("input");
            methodInput.type = "hidden";
            methodInput.name = "_method";
            methodInput.value = "DELETE";

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    },
}));

// Chart utilities
window.createChart = (ctx, config) => new Chart(ctx, config);

// Common chart configurations
window.chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: "bottom",
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: "rgba(0, 0, 0, 0.1)",
            },
        },
        x: {
            grid: {
                color: "rgba(0, 0, 0, 0.1)",
            },
        },
    },
};

// Utility functions
window.formatNumber = (num) => new Intl.NumberFormat("id-ID").format(num);

window.formatCurrency = (num) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(num);

window.formatDate = (date) =>
    new Intl.DateTimeFormat("id-ID", {
        year: "numeric",
        month: "long",
        day: "numeric",
    }).format(new Date(date));

// Auto-refresh functionality
function autoRefresh(interval = 30000) {
    setInterval(() => {
        if (document.querySelector("[data-auto-refresh]")) {
            window.location.reload();
        }
    }, interval);
}

// Form validation
window.validateForm = (formId) => {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll(
        "input[required], select[required], textarea[required]"
    );
    let isValid = true;

    inputs.forEach((input) => {
        if (!input.value.trim()) {
            input.classList.add("border-red-500");
            isValid = false;
        } else {
            input.classList.remove("border-red-500");
        }
    });

    return isValid;
};

// Initialize Alpine
Alpine.start();

// Initialize auto-refresh if enabled
if (document.querySelector("[data-auto-refresh]")) {
    autoRefresh();
}

console.log("Dashboard Jasa Kaya initialized successfully!");
