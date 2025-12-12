@extends('admin.layout')

@section('title', 'Batch File Operations')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Batch File Operations</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage multiple files at once with batch operations</p>
        </div>

        <!-- Operation Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Generate Thumbnails -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <i class="fas fa-image text-2xl text-blue-600 dark:text-blue-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white ml-4">Generate Thumbnails</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Create image thumbnails for all documents in batch</p>
                <button onclick="showBatchOperation('thumbnails')"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition">
                    Start Operation
                </button>
            </div>

            <!-- Digital Signatures -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <i class="fas fa-file-signature text-2xl text-green-600 dark:text-green-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white ml-4">Digital Signatures</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Sign multiple documents with digital signature</p>
                <button onclick="showBatchOperation('sign')"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition">
                    Start Operation
                </button>
            </div>

            <!-- QR Codes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <i class="fas fa-qrcode text-2xl text-purple-600 dark:text-purple-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white ml-4">QR Codes</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Generate QR codes for document tracking</p>
                <button onclick="showBatchOperation('qrcodes')"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded transition">
                    Start Operation
                </button>
            </div>

            <!-- Batch Download -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full">
                        <i class="fas fa-download text-2xl text-indigo-600 dark:text-indigo-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white ml-4">Batch Download</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Download multiple files as ZIP archive</p>
                <button onclick="showBatchOperation('download')"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded transition">
                    Start Operation
                </button>
            </div>

            <!-- Move Documents -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <i class="fas fa-exchange-alt text-2xl text-yellow-600 dark:text-yellow-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white ml-4">Move Documents</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Move multiple documents to another case</p>
                <button onclick="showBatchOperation('move')"
                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded transition">
                    Start Operation
                </button>
            </div>

            <!-- Batch Delete -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                        <i class="fas fa-trash-alt text-2xl text-red-600 dark:text-red-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white ml-4">Batch Delete</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Delete multiple documents at once</p>
                <button onclick="showBatchOperation('delete')"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition">
                    Start Operation
                </button>
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Recent Documents</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll" class="rounded">
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Document</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Case</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Size</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
                        id="documentsTable">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                Loading documents...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Batch Operation Modal -->
    <div id="batchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-2xl w-full mx-4 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white" id="modalTitle">Batch Operation</h3>
                <button onclick="closeBatchModal()"
                    class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div id="modalContent"></div>

            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeBatchModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-500 transition">
                    Cancel
                </button>
                <button onclick="executeBatchOperation()" id="executeBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Execute
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentOperation = null;
        let selectedDocuments = [];

        // Load documents on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDocuments();

            // Select all checkbox
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.document-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateSelectedDocuments();
            });
        });

        function loadDocuments() {
            // In production, this would fetch from API
            fetch('/admin/documents/recent')
                .then(response => response.json())
                .then(data => {
                    renderDocuments(data.documents || []);
                })
                .catch(error => {
                    console.error('Error loading documents:', error);
                    document.getElementById('documentsTable').innerHTML = `
                <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                    No documents found
                </td></tr>
            `;
                });
        }

        function renderDocuments(documents) {
            const tbody = document.getElementById('documentsTable');

            if (documents.length === 0) {
                tbody.innerHTML = `
            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                No documents found
            </td></tr>
        `;
                return;
            }

            tbody.innerHTML = documents.map(doc => `
        <tr>
            <td class="px-6 py-4">
                <input type="checkbox" class="document-checkbox rounded" value="${doc.id}" 
                       onchange="updateSelectedDocuments()">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <i class="fas ${doc.icon} mr-2"></i>
                    <span class="text-sm text-gray-900 dark:text-white">${doc.name}</span>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${doc.case_number}</td>
            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">${doc.size}</td>
            <td class="px-6 py-4">
                ${doc.has_thumbnail ? '<span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded text-xs">Thumbnail</span>' : ''}
                ${doc.is_signed ? '<span class="px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded text-xs ml-1">Signed</span>' : ''}
            </td>
            <td class="px-6 py-4 text-sm">
                <button onclick="viewDocument(${doc.id})" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-2">
                    <i class="fas fa-eye"></i>
                </button>
            </td>
        </tr>
    `).join('');
        }

        function updateSelectedDocuments() {
            const checkboxes = document.querySelectorAll('.document-checkbox:checked');
            selectedDocuments = Array.from(checkboxes).map(cb => parseInt(cb.value));
        }

        function showBatchOperation(operation) {
            currentOperation = operation;
            const modal = document.getElementById('batchModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');

            updateSelectedDocuments();

            if (selectedDocuments.length === 0) {
                showToast('Please select at least one document', 'warning');
                return;
            }

            const operations = {
                'thumbnails': {
                    title: 'Generate Thumbnails',
                    content: `<p class="text-gray-600 dark:text-gray-400 mb-4">Generate thumbnails for ${selectedDocuments.length} selected document(s)?</p>
                     <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded">
                         <p class="text-sm text-blue-800 dark:text-blue-200">
                             <i class="fas fa-info-circle mr-2"></i>
                             Thumbnails will be generated for image files only (JPG, PNG, GIF, etc.)
                         </p>
                     </div>`
                },
                'sign': {
                    title: 'Sign Documents',
                    content: `<p class="text-gray-600 dark:text-gray-400 mb-4">Sign ${selectedDocuments.length} selected document(s) with digital signature?</p>
                     <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Signature Name</label>
                         <input type="text" id="signatureName" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" 
                                placeholder="Enter your name" required>
                     </div>`
                },
                'qrcodes': {
                    title: 'Generate QR Codes',
                    content: `<p class="text-gray-600 dark:text-gray-400 mb-4">Generate QR codes for ${selectedDocuments.length} selected document(s)?</p>
                     <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded">
                         <p class="text-sm text-purple-800 dark:text-purple-200">
                             <i class="fas fa-info-circle mr-2"></i>
                             QR codes can be scanned to track and verify documents
                         </p>
                     </div>`
                },
                'download': {
                    title: 'Batch Download',
                    content: `<p class="text-gray-600 dark:text-gray-400 mb-4">Download ${selectedDocuments.length} selected document(s) as ZIP archive?</p>`
                },
                'move': {
                    title: 'Move Documents',
                    content: `<p class="text-gray-600 dark:text-gray-400 mb-4">Move ${selectedDocuments.length} selected document(s) to another case?</p>
                     <div class="mb-4">
                         <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Target Case ID</label>
                         <input type="number" id="targetCaseId" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" 
                                placeholder="Enter case ID" required>
                     </div>`
                },
                'delete': {
                    title: 'Batch Delete',
                    content: `<div class="bg-red-50 dark:bg-red-900 p-4 rounded mb-4">
                         <p class="text-red-800 dark:text-red-200">
                             <i class="fas fa-exclamation-triangle mr-2"></i>
                             <strong>Warning:</strong> This will permanently delete ${selectedDocuments.length} document(s) and cannot be undone!
                         </p>
                     </div>
                     <p class="text-gray-600 dark:text-gray-400">Type DELETE to confirm:</p>
                     <input type="text" id="deleteConfirm" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white mt-2" 
                            placeholder="Type DELETE">`
                }
            };

            modalTitle.textContent = operations[operation].title;
            modalContent.innerHTML = operations[operation].content;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeBatchModal() {
            const modal = document.getElementById('batchModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentOperation = null;
        }

        function executeBatchOperation() {
            if (!currentOperation || selectedDocuments.length === 0) return;

            const executeBtn = document.getElementById('executeBtn');
            executeBtn.disabled = true;
            executeBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            let url = `/admin/batch-operations/${currentOperation}`;
            let data = {
                document_ids: selectedDocuments
            };

            // Additional data for specific operations
            if (currentOperation === 'sign') {
                const signatureName = document.getElementById('signatureName')?.value;
                if (!signatureName) {
                    showToast('Please enter signature name', 'error');
                    executeBtn.disabled = false;
                    executeBtn.innerHTML = 'Execute';
                    return;
                }
                data.signature_name = signatureName;
            } else if (currentOperation === 'move') {
                const targetCaseId = document.getElementById('targetCaseId')?.value;
                if (!targetCaseId) {
                    showToast('Please enter target case ID', 'error');
                    executeBtn.disabled = false;
                    executeBtn.innerHTML = 'Execute';
                    return;
                }
                data.target_case_id = targetCaseId;
            } else if (currentOperation === 'delete') {
                const deleteConfirm = document.getElementById('deleteConfirm')?.value;
                if (deleteConfirm !== 'DELETE') {
                    showToast('Please type DELETE to confirm', 'error');
                    executeBtn.disabled = false;
                    executeBtn.innerHTML = 'Execute';
                    return;
                }
            }

            if (currentOperation === 'download') {
                // Handle download differently
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                selectedDocuments.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'document_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);

                closeBatchModal();
                executeBtn.disabled = false;
                executeBtn.innerHTML = 'Execute';
                return;
            }

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        loadDocuments();
                        closeBatchModal();
                    } else {
                        showToast(data.message || 'Operation failed', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred', 'error');
                })
                .finally(() => {
                    executeBtn.disabled = false;
                    executeBtn.innerHTML = 'Execute';
                });
        }

        function viewDocument(id) {
            window.location.href = `/admin/documents/${id}`;
        }
    </script>
@endpush
