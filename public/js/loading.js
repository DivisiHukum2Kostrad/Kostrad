/**
 * Loading States and Skeleton Screens
 * Provides visual feedback during async operations
 */

class LoadingManager {
    constructor() {
        this.activeLoaders = new Set();
        this.init();
    }

    init() {
        // Intercept form submissions to show loading
        document.addEventListener("submit", (e) => {
            if (
                e.target.tagName === "FORM" &&
                !e.target.hasAttribute("data-no-loading")
            ) {
                this.showFormLoading(e.target);
            }
        });

        // Intercept link clicks for page transitions
        document.addEventListener("click", (e) => {
            const link = e.target.closest("a[href]");
            if (
                link &&
                !link.hasAttribute("data-no-loading") &&
                !link.getAttribute("href").startsWith("#") &&
                !link.hasAttribute("download") &&
                !link.hasAttribute("target")
            ) {
                this.showPageLoading();
            }
        });
    }

    showFormLoading(form) {
        const submitButton = form.querySelector(
            'button[type="submit"], input[type="submit"]'
        );
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.dataset.originalText = originalText;

            submitButton.innerHTML = `
                <svg class="animate-spin inline-block h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        }
    }

    showPageLoading() {
        if (document.getElementById("page-loader")) return;

        const loader = document.createElement("div");
        loader.id = "page-loader";
        loader.className =
            "fixed inset-0 bg-white dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 z-50 flex items-center justify-center";
        loader.innerHTML = `
            <div class="text-center">
                <svg class="animate-spin h-12 w-12 text-green-600 dark:text-green-400 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400 font-medium">Loading...</p>
            </div>
        `;
        document.body.appendChild(loader);
    }

    hidePageLoading() {
        const loader = document.getElementById("page-loader");
        if (loader) {
            loader.remove();
        }
    }

    showButtonLoading(button, text = "Loading...") {
        if (!button) return;

        button.disabled = true;
        button.dataset.originalText = button.innerHTML;
        button.dataset.loadingId = Date.now();

        button.innerHTML = `
            <svg class="animate-spin inline-block h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${text}
        `;

        this.activeLoaders.add(button.dataset.loadingId);
    }

    hideButtonLoading(button) {
        if (!button || !button.dataset.originalText) return;

        button.disabled = false;
        button.innerHTML = button.dataset.originalText;

        if (button.dataset.loadingId) {
            this.activeLoaders.delete(button.dataset.loadingId);
            delete button.dataset.loadingId;
        }
        delete button.dataset.originalText;
    }

    createSkeletonCard() {
        return `
            <div class="animate-pulse bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-4"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-full mb-2"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-5/6 mb-2"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-4/6"></div>
            </div>
        `;
    }

    createSkeletonTable(rows = 5) {
        const skeletonRows = Array(rows)
            .fill(0)
            .map(
                () => `
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="px-6 py-4">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full animate-pulse"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 animate-pulse"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2 animate-pulse"></div>
                </td>
                <td class="px-6 py-4">
                    <div class="h-6 bg-gray-200 dark:bg-gray-700 rounded w-20 animate-pulse"></div>
                </td>
            </tr>
        `
            )
            .join("");

        return `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <tbody>
                        ${skeletonRows}
                    </tbody>
                </table>
            </div>
        `;
    }

    showSkeleton(container, type = "card", count = 3) {
        if (!container) return;

        let skeleton = "";
        if (type === "card") {
            skeleton = Array(count)
                .fill(0)
                .map(() => this.createSkeletonCard())
                .join("");
        } else if (type === "table") {
            skeleton = this.createSkeletonTable(count);
        }

        container.innerHTML = skeleton;
    }
}

// Initialize loading manager
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.loadingManager = new LoadingManager();
    });
} else {
    window.loadingManager = new LoadingManager();
}

// Global helper functions
window.showLoading = (button, text) =>
    window.loadingManager?.showButtonLoading(button, text);
window.hideLoading = (button) =>
    window.loadingManager?.hideButtonLoading(button);
window.showSkeleton = (container, type, count) =>
    window.loadingManager?.showSkeleton(container, type, count);
