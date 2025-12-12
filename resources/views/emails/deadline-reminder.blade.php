<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Deadline Perkara</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
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

        .warning-box {
            background: #fef2f2;
            border: 2px solid #dc2626;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .warning-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .days-remaining {
            font-size: 36px;
            font-weight: 700;
            color: #dc2626;
            margin: 10px 0;
        }

        .days-label {
            font-size: 18px;
            color: #991b1b;
            font-weight: 600;
        }

        .case-info {
            background: #f8fafc;
            border-left: 4px solid #dc2626;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .case-info h2 {
            margin: 0 0 15px 0;
            color: #991b1b;
            font-size: 18px;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #fee2e2;
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

        .urgency-high {
            background: #fecaca;
            color: #7f1d1d;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .urgency-medium {
            background: #fed7aa;
            color: #7c2d12;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #dc2626;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }

        .button:hover {
            background: #b91c1c;
        }

        .action-list {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .action-list h3 {
            margin: 0 0 10px 0;
            color: #92400e;
        }

        .action-list ul {
            margin: 0;
            padding-left: 20px;
            color: #78350f;
        }

        .action-list li {
            margin: 5px 0;
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
            <h1>⚠️ Pengingat Deadline Perkara</h1>
        </div>

        <div class="content">
            <div style="text-align: center; margin-bottom: 20px;">
                <strong style="font-size: 18px; color: #dc2626;">PERHATIAN!</strong>
                <p style="margin: 8px 0;">Deadline perkara yang Anda tangani sudah dekat!</p>
            </div>

            <div class="warning-box">
                <div class="warning-icon">⏰</div>
                <div class="days-remaining">{{ $daysRemaining }}</div>
                <div class="days-label">HARI LAGI</div>
                <p style="margin: 10px 0 0 0; color: #7f1d1d;">
                    @if ($daysRemaining <= 1)
                        <span class="urgency-high">SANGAT MENDESAK!</span>
                    @elseif($daysRemaining <= 3)
                        <span class="urgency-high">MENDESAK</span>
                    @else
                        <span class="urgency-medium">PERHATIAN</span>
                    @endif
                </p>
            </div>

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
                    <div class="info-label">Status Saat Ini:</div>
                    <div class="info-value">
                        <strong style="color: #dc2626;">{{ ucfirst($perkara->status) }}</strong>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Perkara:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($perkara->tanggal_perkara)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <div class="action-list">
                <h3>✅ Tindakan yang Perlu Dilakukan:</h3>
                <ul>
                    @if ($perkara->status === 'baru')
                        <li>Segera proses perkara ini</li>
                        <li>Perbarui status menjadi "Dalam Proses"</li>
                    @elseif($perkara->status === 'proses')
                        <li>Periksa kelengkapan dokumen</li>
                        <li>Selesaikan proses yang sedang berjalan</li>
                        <li>Persiapkan penyelesaian perkara</li>
                    @endif
                    <li>Hubungi pihak terkait jika diperlukan</li>
                    <li>Update progress di sistem</li>
                    <li>Dokumentasikan semua tindakan yang diambil</li>
                </ul>
            </div>

            @if ($perkara->deskripsi)
                <div style="margin: 20px 0; padding: 15px; background: #f1f5f9; border-radius: 4px;">
                    <strong>Deskripsi Perkara:</strong>
                    <p style="color: #475569; margin-top: 8px;">{{ $perkara->deskripsi }}</p>
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ url('/admin/perkara/' . $perkara->id) }}" class="button">
                    Buka Perkara Sekarang
                </a>
            </div>

            <div
                style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 6px; padding: 15px; margin: 20px 0; text-align: center;">
                <strong style="color: #991b1b;">📢 Penting!</strong>
                <p style="margin: 8px 0 0 0; color: #7f1d1d;">
                    Jangan biarkan deadline terlewat. Segera ambil tindakan yang diperlukan untuk menyelesaikan perkara
                    ini.
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>Sistem Informasi Perkara Kostrad</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 10px;">
                Anda menerima email ini sebagai pengingat deadline perkara.<br>
                Untuk mengatur preferensi notifikasi, silakan kunjungi pengaturan akun Anda.
            </p>
        </div>
    </div>
</body>

</html>
