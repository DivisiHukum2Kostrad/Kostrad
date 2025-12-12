/**
 * Keyboard Shortcuts System
 * Provides power user shortcuts for common actions
 */

class KeyboardShortcuts {
    constructor() {
        this.shortcuts = new Map();
        this.helpModalOpen = false;
        this.init();
    }

    init() {
        // Register default shortcuts
        this.registerDefaults();

        // Listen for keyboard events
        document.addEventListener("keydown", (e) => this.handleKeyDown(e));

        // Create help modal
        this.createHelpModal();
    }

    registerDefaults() {
        // Global shortcuts
        this.register("?", "Show keyboard shortcuts", () =>
            this.toggleHelpModal()
        );
        this.register("Ctrl+/", "Toggle dark mode", () =>
            window.darkModeManager?.toggle()
        );
        this.register("Escape", "Close modals", () => this.closeModals());

        // Navigation shortcuts (only on dashboard/list pages)
        if (
            window.location.pathname.includes("/dashboard") ||
            window.location.pathname.includes("/perkaras")
        ) {
            this.register("n", "New case", () =>
                this.navigateTo("/admin/perkaras/create")
            );
            this.register("d", "Go to dashboard", () =>
                this.navigateTo("/admin/dashboard")
            );
            this.register("p", "Go to cases", () =>
                this.navigateTo("/admin/perkaras")
            );
        }

        // Search shortcut
        this.register("/", "Focus search", (e) => {
            e.preventDefault();
            const searchInput = document.querySelector(
                'input[type="search"], input[name="search"]'
            );
            if (searchInput) searchInput.focus();
        });
    }

    register(key, description, callback) {
        this.shortcuts.set(key, { description, callback });
    }

    handleKeyDown(e) {
        // Don't trigger shortcuts when typing in input fields
        if (
            e.target.tagName === "INPUT" ||
            e.target.tagName === "TEXTAREA" ||
            e.target.isContentEditable
        ) {
            // Exception for Escape key
            if (e.key === "Escape") {
                e.target.blur();
                this.closeModals();
            }
            return;
        }

        // Build key combination string
        let key = e.key;
        if (e.ctrlKey && e.key !== "Control") key = `Ctrl+${e.key}`;
        if (e.altKey && e.key !== "Alt") key = `Alt+${e.key}`;
        if (e.shiftKey && e.key !== "Shift" && e.key.length > 1)
            key = `Shift+${e.key}`;

        // Execute shortcut if registered
        const shortcut = this.shortcuts.get(key);
        if (shortcut) {
            e.preventDefault();
            shortcut.callback(e);
        }
    }

    navigateTo(path) {
        if (window.location.pathname !== path) {
            window.location.href = path;
        }
    }

    closeModals() {
        // Close help modal
        if (this.helpModalOpen) {
            this.toggleHelpModal();
        }

        // Close any other modals
        const modals = document.querySelectorAll('[role="dialog"], .modal');
        modals.forEach((modal) => {
            if (modal.style.display !== "none") {
                modal.style.display = "none";
            }
        });
    }

    toggleHelpModal() {
        const modal = document.getElementById("keyboard-shortcuts-modal");
        if (!modal) return;

        this.helpModalOpen = !this.helpModalOpen;
        modal.style.display = this.helpModalOpen ? "flex" : "none";
    }

    createHelpModal() {
        const modal = document.createElement("div");
        modal.id = "keyboard-shortcuts-modal";
        modal.className =
            "fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden";
        modal.style.display = "none";
        modal.setAttribute("role", "dialog");
        modal.setAttribute("aria-modal", "true");
        modal.setAttribute("aria-labelledby", "shortcuts-title");

        const shortcuts = Array.from(this.shortcuts.entries());
        const shortcutsList = shortcuts
            .map(
                ([key, { description }]) => `
            <tr class="border-b border-gray-200 dark:border-gray-700">
                <td class="py-2 px-4">
                    <kbd class="px-2 py-1 text-xs font-semibold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded">${this.escapeHtml(
                        key
                    )}</kbd>
                </td>
                <td class="py-2 px-4 text-gray-700 dark:text-gray-300">${this.escapeHtml(
                    description
                )}</td>
            </tr>
        `
            )
            .join("");

        modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[80vh] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h2 id="shortcuts-title" class="text-xl font-bold text-gray-900 dark:text-white">
                            Keyboard Shortcuts
                        </h2>
                        <button onclick="window.keyboardShortcuts.toggleHelpModal()" 
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 rounded">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6 overflow-y-auto max-h-[calc(80vh-120px)]">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                <th class="py-2 px-4 text-left font-semibold text-gray-700 dark:text-gray-300">Key</th>
                                <th class="py-2 px-4 text-left font-semibold text-gray-700 dark:text-gray-300">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${shortcutsList}
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Press <kbd class="px-2 py-1 text-xs font-semibold bg-gray-200 dark:bg-gray-700 rounded">?</kbd> anytime to see this help
                    </p>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Close on background click
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                this.toggleHelpModal();
            }
        });
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

// Initialize keyboard shortcuts
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.keyboardShortcuts = new KeyboardShortcuts();
    });
} else {
    window.keyboardShortcuts = new KeyboardShortcuts();
}
