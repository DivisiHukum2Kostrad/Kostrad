<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Baru Diunggah</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 30px 20px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .document-info {
            background: #f0fdf4;
            border-left: 4px solid #059669;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .document-info h2 {
            margin: 0 0 15px 0;
            color: #065f46;
            font-size: 18px;
        }

        .doc-icon {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
        }

        .doc-icon-image {
            font-size: 48px;
        }

        .doc-details {
            flex: 1;
        }

        .doc-name {
            font-size: 16px;
            font-weight: 600;
            color: #065f46;
            margin-bottom: 5px;
        }

        .doc-meta {
            font-size: 13px;
            color: #64748b;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #d1fae5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            min-width: 140px;
            color: #64748b;
        }

        .info-value {
            color: #1e293b;
        }

        .category-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background: #dcfce7;
            color: #166534;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #059669;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }

        .button:hover {
            background: #047857;
        }

        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📎 Dokumen Baru Diunggah</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Yth. Bapak/Ibu,
            </div>

            <p>Dokumen baru telah diunggah untuk perkara yang Anda tangani di Sistem Informasi Perkara Kostrad.</p>

            <div class="document-info">
                <h2>📄 Informasi Dokumen</h2>

                <div class="doc-icon">
                    <div class="doc-icon-image">
                        @php
                            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                            $icon = match ($extension) {
                                'pdf' => '📕',
                                'doc', 'docx' => '📘',
                                'xls', 'xlsx' => '📗',
                                'jpg', 'jpeg', 'png', 'gif' => '🖼️',
                                default => '📄',
                            };
                        @endphp
                        {{ $icon }}
                    </div>
                    <div class="doc-details">
                        <div class="doc-name">{{ $document->nama_file }}</div>
                        <div class="doc-meta">
                            {{ strtoupper($extension) }} •
                            {{ number_format(filesize(storage_path('app/public/' . $document->file_path)) / 1024, 2) }}
                            KB
                        </div>
                    </div>
                </div>

                @if ($document->category)
                    <div style="margin: 10px 0;">
                        <span class="category-badge">{{ $document->category }}</span>
                    </div>
                @endif

                @if ($document->description)
                    <div style="margin: 15px 0; padding-top: 15px; border-top: 1px solid #d1fae5;">
                        <strong style="color: #065f46;">Deskripsi:</strong>
                        <p style="color: #475569; margin: 8px 0 0 0;">{{ $document->description }}</p>
                    </div>
                @endif

                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #d1fae5;">
                    <div class="info-row">
                        <div class="info-label">Diunggah oleh:</div>
                        <div class="info-value">{{ $uploadedBy->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Waktu Upload:</div>
                        <div class="info-value">{{ $document->created_at->format('d/m/Y H:i') }} WIB</div>
                    </div>
                    @if ($document->version > 1)
                        <div class="info-row">
                            <div class="info-label">Versi Dokumen:</div>
                            <div class="info-value">Versi {{ $document->version }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div
                style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <strong style="color: #92400e;">📋 Perkara Terkait:</strong>
                <p style="margin: 8px 0 0 0; color: #78350f;">
                    <strong>{{ $perkara->nama }}</strong><br>
                    {{ $perkara->nomor_perkara }}
                </p>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/perkara/' . $perkara->id) }}" class="button">
                    Lihat Detail Perkara & Dokumen
                </a>
            </div>

            <p style="margin-top: 20px; color: #64748b; font-size: 14px;">
                Silakan login ke sistem untuk mengunduh dan melihat dokumen ini.
            </p>
        </div>

        <div class="footer">
            <p><strong>Sistem Informasi Perkara Kostrad</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 10px;">
                Anda menerima email ini karena ada dokumen baru pada perkara yang Anda tangani.<br>
                Untuk mengatur preferensi notifikasi, silakan kunjungi pengaturan akun Anda.
            </p>
        </div>
    </div>
</body>

</html>
