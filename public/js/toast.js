/**
 * Toast Notification System
 * Provides user feedback for actions
 */

class ToastManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create toast container if it doesn't exist
        if (!document.getElementById("toast-container")) {
            this.container = document.createElement("div");
            this.container.id = "toast-container";
            this.container.className = "fixed top-4 right-4 z-50 space-y-2";
            this.container.setAttribute("aria-live", "polite");
            this.container.setAttribute("aria-atomic", "true");
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById("toast-container");
        }
    }

    show(message, type = "info", duration = 3000) {
        const toast = this.createToast(message, type);
        this.container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => {
            toast.classList.remove("translate-x-full", "opacity-0");
        });

        // Auto dismiss
        if (duration > 0) {
            setTimeout(() => this.dismiss(toast), duration);
        }

        return toast;
    }

    createToast(message, type) {
        const toast = document.createElement("div");
        toast.className = `
            transform translate-x-full opacity-0 transition-all duration-300 ease-out
            max-w-sm w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto 
            ring-1 ring-black ring-opacity-5 overflow-hidden
        `;
        toast.setAttribute("role", "alert");

        const colors = {
            success: "text-green-500 dark:text-green-400",
            error: "text-red-500 dark:text-red-400",
            warning: "text-yellow-500 dark:text-yellow-400",
            info: "text-blue-500 dark:text-blue-400",
        };

        const icons = {
            success: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>`,
            error: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>`,
            warning: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>`,
            info: `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>`,
        };

        toast.innerHTML = `
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 ${
                            colors[type]
                        }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            ${icons[type]}
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            ${this.escapeHtml(message)}
                        </p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button onclick="window.toastManager.dismiss(this.closest('[role=alert]'))" 
                                class="inline-flex text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;

        return toast;
    }

    dismiss(toast) {
        if (!toast) return;

        toast.classList.add("translate-x-full", "opacity-0");
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    }

    success(message, duration) {
        return this.show(message, "success", duration);
    }

    error(message, duration) {
        return this.show(message, "error", duration);
    }

    warning(message, duration) {
        return this.show(message, "warning", duration);
    }

    info(message, duration) {
        return this.show(message, "info", duration);
    }

    escapeHtml(text) {
        const map = {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;",
        };
        return text.replace(/[&<>"']/g, (m) => map[m]);
    }
}

// Initialize toast manager
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.toastManager = new ToastManager();
    });
} else {
    window.toastManager = new ToastManager();
}

// Helper functions for global access
window.showToast = (message, type, duration) =>
    window.toastManager.show(message, type, duration);
window.showSuccess = (message, duration) =>
    window.toastManager.success(message, duration);
window.showError = (message, duration) =>
    window.toastManager.error(message, duration);
window.showWarning = (message, duration) =>
    window.toastManager.warning(message, duration);
window.showInfo = (message, duration) =>
    window.toastManager.info(message, duration);
