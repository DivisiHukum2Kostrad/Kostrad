# SiPerkara REST API Documentation

## Base Information

**Base URL:** `http://your-domain.com/api`  
**Version:** 1.0  
**Authentication:** Bearer Token (Laravel Sanctum)

---

## Authentication

### 1. Login

Authenticate and receive an API token.

**Endpoint:** `POST /api/login`

**Request Body:**

```json
{
    "email": "admin@siperkara.mil.id",
    "password": "password123"
}
```

**Response (200 OK):**

```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@siperkara.mil.id",
        "role": "admin",
        "permissions": [
            "view_cases",
            "manage_cases",
            "manage_documents",
            "manage_users"
        ],
        "created_at": "2025-01-01 10:00:00",
        "updated_at": "2025-01-01 10:00:00"
    },
    "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz123456789",
    "token_type": "Bearer"
}
```

**Error Response (422 Unprocessable Entity):**

```json
{
    "message": "The provided credentials are incorrect.",
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

---

### 2. Get Authenticated User

Get current user information.

**Endpoint:** `GET /api/me`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@siperkara.mil.id",
        "role": "admin",
        "permissions": [
            "view_cases",
            "manage_cases",
            "manage_documents",
            "manage_users"
        ],
        "created_at": "2025-01-01 10:00:00",
        "updated_at": "2025-01-01 10:00:00"
    }
}
```

---

### 3. Logout

Revoke current access token.

**Endpoint:** `POST /api/logout`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
    "message": "Logged out successfully"
}
```

---

### 4. Logout All Devices

Revoke all access tokens for the user.

**Endpoint:** `POST /api/logout-all`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
    "message": "All tokens revoked successfully"
}
```

---

## Cases (Perkara)

### 1. List All Cases

Get paginated list of cases with filtering and sorting.

**Endpoint:** `GET /api/perkaras`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Query Parameters:**

| Parameter         | Type    | Description                                           | Example  |
| ----------------- | ------- | ----------------------------------------------------- | -------- |
| `search`          | string  | Search in case number, type, name, or description     | PKR-001  |
| `status`          | string  | Filter by status (Proses\|Selesai)                    | Proses   |
| `priority`        | string  | Filter by priority (Low\|Medium\|High\|Urgent)        | High     |
| `kategori`        | integer | Filter by category ID                                 | 1        |
| `deadline_status` | string  | Filter by deadline (overdue\|upcoming\|no_deadline)   | overdue  |
| `assigned_to`     | string  | Filter by assigned personnel                          | John Doe |
| `per_page`        | integer | Items per page (default: 15)                          | 20       |
| `sort_by`         | string  | Sort field (created_at\|deadline\|priority\|progress) | deadline |
| `sort_dir`        | string  | Sort direction (asc\|desc)                            | asc      |

**Example Request:**

```
GET /api/perkaras?status=Proses&priority=High&per_page=10&sort_by=deadline&sort_dir=asc
```

**Response (200 OK):**

```json
{
    "data": [
        {
            "id": 1,
            "nomor_perkara": "PKR-001/2025",
            "nama": "Kasus A",
            "jenis_perkara": "Pelanggaran Disiplin",
            "deskripsi": "Deskripsi singkat kasus",
            "kategori": {
                "id": 1,
                "nama": "Pidana"
            },
            "priority": "High",
            "priority_badge": "<span class='...'> High </span>",
            "status": "Proses",
            "status_badge": "<span class='...'> Proses </span>",
            "progress": 50,
            "deadline": "2025-12-31",
            "deadline_status": "warning",
            "days_until_deadline": 20,
            "assigned_to": "John Doe",
            "tanggal_perkara": "2025-01-15",
            "tanggal_masuk": "2025-01-10",
            "tanggal_selesai": null,
            "estimated_days": 30,
            "keterangan": "Keterangan tambahan",
            "internal_notes": null,
            "tags": ["Mendesak", "Prioritas Tinggi"],
            "is_public": true,
            "file_dokumentasi": null,
            "created_at": "2025-01-10 09:00:00",
            "updated_at": "2025-01-15 14:30:00",
            "is_overdue": false,
            "is_deadline_approaching": true,
            "dokumens_count": 5
        }
    ],
    "links": {
        "first": "http://domain.com/api/perkaras?page=1",
        "last": "http://domain.com/api/perkaras?page=5",
        "prev": null,
        "next": "http://domain.com/api/perkaras?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 15,
        "to": 15,
        "total": 75
    }
}
```

---

### 2. Get Single Case

Get detailed information about a specific case.

**Endpoint:** `GET /api/perkaras/{id}`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
    "data": {
        "id": 1,
        "nomor_perkara": "PKR-001/2025",
        "nama": "Kasus A",
        "jenis_perkara": "Pelanggaran Disiplin",
        "deskripsi": "Deskripsi lengkap kasus...",
        "kategori": {
            "id": 1,
            "nama": "Pidana"
        },
        "priority": "High",
        "status": "Proses",
        "progress": 50,
        "deadline": "2025-12-31",
        "deadline_status": "warning",
        "days_until_deadline": 20,
        "assigned_to": "John Doe",
        "tags": ["Mendesak", "Prioritas Tinggi"],
        "dokumens": [
            {
                "id": 1,
                "nama_dokumen": "Surat Keputusan",
                "jenis_dokumen": "SK",
                "kategori_dokumen": "Surat Keputusan",
                "file_size": 524288,
                "formatted_file_size": "512 KB",
                "mime_type": "application/pdf",
                "download_count": 5
            }
        ]
    }
}
```

**Error Response (404 Not Found):**

```json
{
    "message": "Case not found"
}
```

---

### 3. Create Case

Create a new case.

**Endpoint:** `POST /api/perkaras`

**Headers:**

```
Authorization: Bearer {your-token}
Content-Type: application/json
```

**Required Permission:** `manage_cases`

**Request Body:**

```json
{
    "nomor_perkara": "PKR-002/2025",
    "jenis_perkara": "Pelanggaran Disiplin",
    "nama": "Kasus B",
    "deskripsi": "Deskripsi singkat",
    "kategori_id": 1,
    "tanggal_masuk": "2025-01-15",
    "tanggal_perkara": "2025-01-14",
    "status": "Proses",
    "priority": "High",
    "deadline": "2025-12-31",
    "progress": 0,
    "estimated_days": 30,
    "assigned_to": "Jane Smith",
    "keterangan": "Keterangan tambahan",
    "internal_notes": "Catatan internal",
    "tags": "Mendesak, Prioritas Tinggi",
    "is_public": true
}
```

**Required Fields:**

-   `nomor_perkara` (string, unique)
-   `jenis_perkara` (string)
-   `kategori_id` (integer, exists in kategoris table)
-   `tanggal_masuk` (date, format: Y-m-d)
-   `status` (enum: Proses|Selesai)
-   `priority` (enum: Low|Medium|High|Urgent)

**Response (201 Created):**

```json
{
  "message": "Case created successfully",
  "data": {
    "id": 2,
    "nomor_perkara": "PKR-002/2025",
    "nama": "Kasus B",
    ...
  }
}
```

**Error Response (422 Unprocessable Entity):**

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nomor_perkara": ["The nomor perkara has already been taken."],
        "kategori_id": ["The selected kategori id is invalid."]
    }
}
```

**Error Response (403 Forbidden):**

```json
{
    "message": "Unauthorized. You do not have permission to create cases."
}
```

---

### 4. Update Case

Update an existing case.

**Endpoint:** `PUT /api/perkaras/{id}`

**Headers:**

```
Authorization: Bearer {your-token}
Content-Type: application/json
```

**Required Permission:** `manage_cases`

**Request Body:** (Same as Create, all fields optional except required ones)

**Response (200 OK):**

```json
{
  "message": "Case updated successfully",
  "data": {
    "id": 2,
    "nomor_perkara": "PKR-002/2025",
    ...
  }
}
```

---

### 5. Delete Case

Delete a case and its associated file.

**Endpoint:** `DELETE /api/perkaras/{id}`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Required Permission:** `manage_cases`

**Response (200 OK):**

```json
{
    "message": "Case deleted successfully"
}
```

---

### 6. Get Case Statistics

Get statistical overview of all cases.

**Endpoint:** `GET /api/perkaras/statistics`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
    "total": 150,
    "by_status": {
        "proses": 85,
        "selesai": 65
    },
    "by_priority": {
        "urgent": 10,
        "high": 25,
        "medium": 70,
        "low": 45
    },
    "overdue": 8,
    "upcoming_deadline": 15
}
```

---

## Documents (Dokumen Perkara)

### 1. List Documents for a Case

Get all documents associated with a case.

**Endpoint:** `GET /api/perkaras/{perkara_id}/documents`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
    "data": [
        {
            "id": 1,
            "nama_dokumen": "Surat Keputusan",
            "jenis_dokumen": "SK",
            "kategori_dokumen": "Surat Keputusan",
            "category_badge": "<span class='...'> Surat Keputusan </span>",
            "file_path": "documents/1/1234567890_sk.pdf",
            "file_name": "1234567890_sk.pdf",
            "file_size": 524288,
            "formatted_file_size": "512 KB",
            "mime_type": "application/pdf",
            "file_icon": "fa-file-pdf",
            "download_count": 5,
            "is_previewable": true,
            "metadata": null,
            "keterangan": "Surat keputusan final",
            "perkara_id": 1,
            "uploaded_by": {
                "id": 1,
                "name": "Admin User",
                "email": "admin@siperkara.mil.id"
            },
            "created_at": "2025-01-10 10:00:00",
            "updated_at": "2025-01-10 10:00:00"
        }
    ]
}
```

---

### 2. Upload Document

Upload a new document to a case.

**Endpoint:** `POST /api/perkaras/{perkara_id}/documents`

**Headers:**

```
Authorization: Bearer {your-token}
Content-Type: multipart/form-data
```

**Required Permission:** `manage_documents`

**Request Body (Form Data):**

```
nama_dokumen: "Surat Keputusan"
jenis_dokumen: "SK"
kategori_dokumen: "Surat Keputusan"
file: [binary file data]
keterangan: "Surat keputusan final"
```

**Field Validations:**

-   `nama_dokumen`: required, string, max 255 chars
-   `jenis_dokumen`: required, string, max 100 chars
-   `kategori_dokumen`: required, enum (Surat Keputusan|Berita Acara|Laporan|Bukti|Lainnya)
-   `file`: required, file, max 10MB
-   `keterangan`: optional, string

**Response (201 Created):**

```json
{
  "message": "Document uploaded successfully",
  "data": {
    "id": 2,
    "nama_dokumen": "Surat Keputusan",
    "jenis_dokumen": "SK",
    ...
  }
}
```

---

### 3. Get Document Details

Get detailed information about a document.

**Endpoint:** `GET /api/documents/{id}`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response (200 OK):**

```json
{
  "data": {
    "id": 1,
    "nama_dokumen": "Surat Keputusan",
    ...
  }
}
```

---

### 4. Update Document Metadata

Update document information (not the file itself).

**Endpoint:** `PUT /api/documents/{id}`

**Headers:**

```
Authorization: Bearer {your-token}
Content-Type: application/json
```

**Required Permission:** `manage_documents`

**Request Body:**

```json
{
    "nama_dokumen": "Surat Keputusan Updated",
    "jenis_dokumen": "SK",
    "kategori_dokumen": "Surat Keputusan",
    "keterangan": "Updated description"
}
```

**Response (200 OK):**

```json
{
  "message": "Document updated successfully",
  "data": {
    "id": 1,
    ...
  }
}
```

---

### 5. Delete Document

Delete a document and its file.

**Endpoint:** `DELETE /api/documents/{id}`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Required Permission:** `manage_documents`

**Response (200 OK):**

```json
{
    "message": "Document deleted successfully"
}
```

---

### 6. Download Document

Download a document file.

**Endpoint:** `GET /api/documents/{id}/download`

**Headers:**

```
Authorization: Bearer {your-token}
```

**Response:** Binary file download with appropriate headers

**Note:** This endpoint increments the `download_count` for the document.

---

## Error Responses

### 401 Unauthorized

```json
{
    "message": "Unauthenticated."
}
```

### 403 Forbidden

```json
{
    "message": "Unauthorized. You do not have permission to perform this action."
}
```

### 404 Not Found

```json
{
    "message": "Resource not found"
}
```

### 422 Unprocessable Entity

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### 500 Internal Server Error

```json
{
    "message": "Server Error"
}
```

---

## Rate Limiting

The API implements rate limiting to prevent abuse:

-   **Authenticated requests:** 60 requests per minute
-   **Login endpoint:** 5 attempts per minute

When rate limit is exceeded:

```json
{
    "message": "Too Many Attempts."
}
```

**Response Headers:**

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 0
Retry-After: 45
```

---

## Best Practices

### 1. Token Management

-   Store tokens securely (never in localStorage for web apps)
-   Refresh tokens before expiration
-   Implement token rotation for long-lived sessions
-   Revoke tokens on logout

### 2. Pagination

-   Use `per_page` parameter to control response size
-   Default: 15 items per page
-   Maximum: 100 items per page
-   Always check `meta.last_page` for total pages

### 3. Filtering & Sorting

-   Combine multiple filters for precise queries
-   Use `sort_by` and `sort_dir` for custom ordering
-   Search is case-insensitive

### 4. File Uploads

-   Maximum file size: 10MB
-   Supported formats: PDF, Word, Excel, Images
-   Use `multipart/form-data` content type

### 5. Error Handling

-   Always check HTTP status codes
-   Parse error messages from response body
-   Handle validation errors field by field

---

## Example Usage

### JavaScript (Fetch API)

```javascript
// Login
const login = async (email, password) => {
    const response = await fetch("http://domain.com/api/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    localStorage.setItem("api_token", data.token);
    return data;
};

// Get Cases
const getCases = async (filters = {}) => {
    const token = localStorage.getItem("api_token");
    const queryString = new URLSearchParams(filters).toString();

    const response = await fetch(
        `http://domain.com/api/perkaras?${queryString}`,
        {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        }
    );

    return await response.json();
};

// Create Case
const createCase = async (caseData) => {
    const token = localStorage.getItem("api_token");

    const response = await fetch("http://domain.com/api/perkaras", {
        method: "POST",
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
        body: JSON.stringify(caseData),
    });

    return await response.json();
};

// Upload Document
const uploadDocument = async (perkaraId, formData) => {
    const token = localStorage.getItem("api_token");

    const response = await fetch(
        `http://domain.com/api/perkaras/${perkaraId}/documents`,
        {
            method: "POST",
            headers: {
                Authorization: `Bearer ${token}`,
            },
            body: formData, // FormData object with file
        }
    );

    return await response.json();
};
```

### PHP (Guzzle)

```php
use GuzzleHttp\Client;

// Login
$client = new Client(['base_uri' => 'http://domain.com/api/']);

$response = $client->post('login', [
    'json' => [
        'email' => 'admin@siperkara.mil.id',
        'password' => 'password123'
    ]
]);

$data = json_decode($response->getBody(), true);
$token = $data['token'];

// Get Cases
$response = $client->get('perkaras', [
    'headers' => [
        'Authorization' => "Bearer {$token}",
        'Accept' => 'application/json',
    ],
    'query' => [
        'status' => 'Proses',
        'priority' => 'High',
        'per_page' => 20
    ]
]);

$cases = json_decode($response->getBody(), true);
```

### Python (requests)

```python
import requests

# Login
response = requests.post('http://domain.com/api/login', json={
    'email': 'admin@siperkara.mil.id',
    'password': 'password123'
})

data = response.json()
token = data['token']

# Get Cases
headers = {
    'Authorization': f'Bearer {token}',
    'Accept': 'application/json'
}

params = {
    'status': 'Proses',
    'priority': 'High',
    'per_page': 20
}

response = requests.get('http://domain.com/api/perkaras',
                       headers=headers,
                       params=params)

cases = response.json()
```

---

## Postman Collection

A Postman collection is available for testing the API. Import the collection and set up the following environment variables:

-   `base_url`: http://your-domain.com/api
-   `token`: (will be auto-set after login)

**Collection Structure:**

```
📁 SiPerkara API
├── 📁 Authentication
│   ├── POST Login
│   ├── GET Me
│   ├── POST Logout
│   └── POST Logout All
├── 📁 Cases
│   ├── GET List Cases
│   ├── POST Create Case
│   ├── GET Get Case
│   ├── PUT Update Case
│   ├── DELETE Delete Case
│   └── GET Statistics
└── 📁 Documents
    ├── GET List Documents
    ├── POST Upload Document
    ├── GET Get Document
    ├── PUT Update Document
    ├── DELETE Delete Document
    └── GET Download Document
```

---

## Support

For API support, please contact:

-   **Email:** support@siperkara.mil.id
-   **Documentation:** http://your-domain.com/api/docs

**API Version:** 1.0  
**Last Updated:** December 11, 2025
