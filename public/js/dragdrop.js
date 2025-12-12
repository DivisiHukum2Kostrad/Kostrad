/**
 * Drag and Drop File Upload
 * Enhances file inputs with drag and drop functionality
 */

class DragDropUpload {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            maxSize: options.maxSize || 10 * 1024 * 1024, // 10MB default
            allowedTypes: options.allowedTypes || [
                "image/*",
                "application/pdf",
                ".doc",
                ".docx",
            ],
            multiple: options.multiple || false,
            onFilesSelected: options.onFilesSelected || null,
            ...options,
        };
        this.files = [];
        this.init();
    }

    init() {
        this.createDropZone();
        this.attachEvents();
    }

    createDropZone() {
        const fileInput = this.element.querySelector('input[type="file"]');
        if (!fileInput) {
            console.error("File input not found in element");
            return;
        }

        this.fileInput = fileInput;
        this.fileInput.style.display = "none";

        const dropZone = document.createElement("div");
        dropZone.className = `
            border-2 border-dashed border-gray-300 dark:border-gray-600 
            rounded-lg p-8 text-center cursor-pointer
            transition-all duration-200
            hover:border-green-500 dark:hover:border-green-400
            hover:bg-green-50 dark:hover:bg-gray-800
        `;
        dropZone.innerHTML = `
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                <span class="font-semibold text-green-600 dark:text-green-400">Click to upload</span>
                or drag and drop
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-500">
                ${this.getAcceptedTypesText()}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                Maximum file size: ${this.formatBytes(this.options.maxSize)}
            </p>
        `;

        this.dropZone = dropZone;
        this.element.appendChild(dropZone);

        // File list container
        this.fileList = document.createElement("div");
        this.fileList.className = "mt-4 space-y-2";
        this.element.appendChild(this.fileList);
    }

    attachEvents() {
        // Click to open file dialog
        this.dropZone.addEventListener("click", () => this.fileInput.click());

        // File input change
        this.fileInput.addEventListener("change", (e) => {
            this.handleFiles(Array.from(e.target.files));
        });

        // Drag and drop events
        ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
            this.dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ["dragenter", "dragover"].forEach((eventName) => {
            this.dropZone.addEventListener(eventName, () => {
                this.dropZone.classList.add(
                    "border-green-500",
                    "bg-green-50",
                    "dark:bg-gray-800"
                );
            });
        });

        ["dragleave", "drop"].forEach((eventName) => {
            this.dropZone.addEventListener(eventName, () => {
                this.dropZone.classList.remove(
                    "border-green-500",
                    "bg-green-50",
                    "dark:bg-gray-800"
                );
            });
        });

        this.dropZone.addEventListener("drop", (e) => {
            const files = Array.from(e.dataTransfer.files);
            this.handleFiles(files);
        });
    }

    handleFiles(files) {
        if (!this.options.multiple) {
            files = files.slice(0, 1);
            this.files = [];
        }

        files.forEach((file) => {
            if (this.validateFile(file)) {
                this.files.push(file);
                this.renderFileItem(file);
            }
        });

        // Update file input
        const dataTransfer = new DataTransfer();
        this.files.forEach((file) => dataTransfer.items.add(file));
        this.fileInput.files = dataTransfer.files;

        // Callback
        if (this.options.onFilesSelected) {
            this.options.onFilesSelected(this.files);
        }
    }

    validateFile(file) {
        // Check file size
        if (file.size > this.options.maxSize) {
            window.showError?.(
                `File "${
                    file.name
                }" is too large. Maximum size is ${this.formatBytes(
                    this.options.maxSize
                )}`
            );
            return false;
        }

        // Check file type
        const isAllowed = this.options.allowedTypes.some((type) => {
            if (type.startsWith(".")) {
                return file.name.toLowerCase().endsWith(type.toLowerCase());
            } else if (type.includes("/*")) {
                const category = type.split("/")[0];
                return file.type.startsWith(category);
            } else {
                return file.type === type;
            }
        });

        if (!isAllowed) {
            window.showError?.(`File type "${file.type}" is not allowed`);
            return false;
        }

        return true;
    }

    renderFileItem(file) {
        const fileItem = document.createElement("div");
        fileItem.className =
            "flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg";
        fileItem.dataset.fileName = file.name;

        const fileIcon = this.getFileIcon(file.type);

        fileItem.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    ${fileIcon}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                        ${this.escapeHtml(file.name)}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        ${this.formatBytes(file.size)}
                    </p>
                </div>
            </div>
            <button type="button" class="ml-3 text-red-500 hover:text-red-700 dark:hover:text-red-400" 
                    onclick="window.dragDropManager?.removeFile('${this.escapeHtml(
                        file.name
                    )}')">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        this.fileList.appendChild(fileItem);
    }

    removeFile(fileName) {
        this.files = this.files.filter((f) => f.name !== fileName);

        // Update file input
        const dataTransfer = new DataTransfer();
        this.files.forEach((file) => dataTransfer.items.add(file));
        this.fileInput.files = dataTransfer.files;

        // Remove from UI
        const fileItem = this.fileList.querySelector(
            `[data-file-name="${fileName}"]`
        );
        if (fileItem) {
            fileItem.remove();
        }

        // Callback
        if (this.options.onFilesSelected) {
            this.options.onFilesSelected(this.files);
        }
    }

    getFileIcon(type) {
        if (type.startsWith("image/")) {
            return `<svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>`;
        } else if (type === "application/pdf") {
            return `<svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>`;
        } else {
            return `<svg class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>`;
        }
    }

    getAcceptedTypesText() {
        const types = this.options.allowedTypes.map((type) => {
            if (type === "image/*") return "Images";
            if (type === "application/pdf") return "PDF";
            if (type === ".doc" || type === ".docx") return "Word";
            return type.replace(".", "").toUpperCase();
        });
        return types.join(", ");
    }

    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return (
            parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i]
        );
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

// Auto-initialize drag-drop on elements with data-drag-drop attribute
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initDragDrop);
} else {
    initDragDrop();
}

function initDragDrop() {
    const elements = document.querySelectorAll("[data-drag-drop]");
    window.dragDropManager = window.dragDropManager || {};

    elements.forEach((element) => {
        const options = {
            maxSize: parseInt(element.dataset.maxSize) || 10 * 1024 * 1024,
            multiple: element.dataset.multiple === "true",
            allowedTypes: element.dataset.allowedTypes
                ? element.dataset.allowedTypes.split(",")
                : undefined,
        };

        const instance = new DragDropUpload(element, options);
        window.dragDropManager[element.id] = instance;
    });
}

// Helper to remove file globally
window.dragDropManager = window.dragDropManager || {};
window.dragDropManager.removeFile = function (fileName) {
    Object.values(this).forEach((instance) => {
        if (instance instanceof DragDropUpload) {
            instance.removeFile(fileName);
        }
    });
};
