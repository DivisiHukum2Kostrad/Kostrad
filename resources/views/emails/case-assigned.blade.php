<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perkara Baru Ditugaskan</title>
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
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
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

        .case-info {
            background: #f8fafc;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .case-info h2 {
            margin: 0 0 15px 0;
            color: #1e3a8a;
            font-size: 18px;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
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

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-baru {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-proses {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-selesai {
            background-color: #d1fae5;
            color: #065f46;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }

        .button:hover {
            background: #2563eb;
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
            <h1>📋 Perkara Baru Ditugaskan</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Yth. Bapak/Ibu,
            </div>

            <p>Anda telah ditugaskan untuk menangani perkara baru di Sistem Informasi Perkara Kostrad.</p>

            <div class="case-info">
                <h2>📄 Detail Perkara</h2>
                <div class="info-row">
                    <div class="info-label">Nama Perkara:</div>
                    <div class="info-value"><strong>{{ $perkara->nama }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomor Perkara:</div>
                    <div class="info-value">{{ $perkara->nomor_perkara }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kategori:</div>
                    <div class="info-value">{{ $perkara->kategori->nama ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge status-{{ strtolower($perkara->status) }}">
                            {{ ucfirst($perkara->status) }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Perkara:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($perkara->tanggal_perkara)->format('d/m/Y') }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ditugaskan oleh:</div>
                    <div class="info-value">{{ $assignedBy->name }}</div>
                </div>
            </div>

            @if ($perkara->deskripsi)
                <div style="margin: 20px 0;">
                    <strong>Deskripsi:</strong>
                    <p style="color: #475569; margin-top: 8px;">{{ $perkara->deskripsi }}</p>
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ url('/admin/perkara/' . $perkara->id) }}" class="button">
                    Lihat Detail Perkara
                </a>
            </div>

            <p style="margin-top: 20px; color: #64748b; font-size: 14px;">
                Silakan login ke sistem untuk melihat detail lengkap dan mengelola perkara ini.
            </p>
        </div>

        <div class="footer">
            <p><strong>Sistem Informasi Perkara Kostrad</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 10px;">
                Anda menerima email ini karena Anda ditugaskan pada perkara baru.<br>
                Untuk mengatur preferensi notifikasi, silakan kunjungi pengaturan akun Anda.
            </p>
        </div>
    </div>
</body>

</html>
