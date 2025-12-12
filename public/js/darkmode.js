/**
 * Dark Mode Toggle System
 * Persists theme preference in localStorage
 */

class DarkModeManager {
    constructor() {
        this.darkModeKey = "siperkara_dark_mode";
        this.init();
    }

    init() {
        // Load saved preference or default to light mode
        const savedMode = localStorage.getItem(this.darkModeKey);
        const prefersDark = window.matchMedia(
            "(prefers-color-scheme: dark)"
        ).matches;

        if (savedMode === "dark" || (!savedMode && prefersDark)) {
            this.enableDarkMode();
        } else {
            this.disableDarkMode();
        }

        // Listen for system theme changes
        window
            .matchMedia("(prefers-color-scheme: dark)")
            .addEventListener("change", (e) => {
                if (!localStorage.getItem(this.darkModeKey)) {
                    e.matches ? this.enableDarkMode() : this.disableDarkMode();
                }
            });

        // Setup toggle buttons
        this.setupToggleButtons();
    }

    enableDarkMode() {
        document.documentElement.classList.add("dark");
        localStorage.setItem(this.darkModeKey, "dark");
        this.updateToggleIcons();
    }

    disableDarkMode() {
        document.documentElement.classList.remove("dark");
        localStorage.setItem(this.darkModeKey, "light");
        this.updateToggleIcons();
    }

    toggle() {
        if (document.documentElement.classList.contains("dark")) {
            this.disableDarkMode();
        } else {
            this.enableDarkMode();
        }
    }

    isDarkMode() {
        return document.documentElement.classList.contains("dark");
    }

    setupToggleButtons() {
        const toggleButtons = document.querySelectorAll(
            "[data-dark-mode-toggle]"
        );
        toggleButtons.forEach((button) => {
            button.addEventListener("click", () => this.toggle());
        });
    }

    updateToggleIcons() {
        const isDark = this.isDarkMode();
        const sunIcons = document.querySelectorAll('[data-theme-icon="sun"]');
        const moonIcons = document.querySelectorAll('[data-theme-icon="moon"]');

        sunIcons.forEach((icon) => {
            icon.style.display = isDark ? "block" : "none";
        });

        moonIcons.forEach((icon) => {
            icon.style.display = isDark ? "none" : "block";
        });
    }
}

// Initialize dark mode when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        window.darkModeManager = new DarkModeManager();
    });
} else {
    window.darkModeManager = new DarkModeManager();
}
