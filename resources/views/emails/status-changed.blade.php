<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Perkara Berubah</title>
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
            background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%);
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

        .status-change {
            background: #f8fafc;
            border-left: 4px solid #7c3aed;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .status-change h2 {
            margin: 0 0 15px 0;
            color: #5b21b6;
            font-size: 18px;
        }

        .status-comparison {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            gap: 15px;
        }

        .status-box {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
        }

        .status-old {
            background: #fecaca;
            color: #991b1b;
        }

        .status-new {
            background: #d1fae5;
            color: #065f46;
        }

        .arrow {
            font-size: 24px;
            color: #7c3aed;
        }

        .case-info {
            background: #faf5ff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            padding: 8px 0;
            border-bottom: 1px solid #e9d5ff;
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

        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #7c3aed;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }

        .button:hover {
            background: #6d28d9;
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
            <h1>🔄 Status Perkara Berubah</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Yth. Bapak/Ibu,
            </div>

            <p>Status perkara yang Anda tangani telah diperbarui di Sistem Informasi Perkara Kostrad.</p>

            <div class="status-change">
                <h2>📊 Perubahan Status</h2>

                <div class="status-comparison">
                    <div class="status-box status-old">
                        {{ ucfirst($oldStatus) }}
                    </div>
                    <div class="arrow">→</div>
                    <div class="status-box status-new">
                        {{ ucfirst($newStatus) }}
                    </div>
                </div>
            </div>

            <div class="case-info">
                <h3 style="margin-top: 0; color: #5b21b6;">📄 Detail Perkara</h3>
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
                    <div class="info-label">Diubah oleh:</div>
                    <div class="info-value">{{ $changedBy->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Waktu Perubahan:</div>
                    <div class="info-value">{{ now()->format('d/m/Y H:i') }} WIB</div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/perkara/' . $perkara->id) }}" class="button">
                    Lihat Detail Perkara
                </a>
            </div>

            <p style="margin-top: 20px; color: #64748b; font-size: 14px;">
                Silakan login ke sistem untuk melihat riwayat lengkap perubahan status perkara ini.
            </p>
        </div>

        <div class="footer">
            <p><strong>Sistem Informasi Perkara Kostrad</strong></p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p style="margin-top: 10px;">
                Anda menerima email ini karena ada perubahan status pada perkara yang Anda tangani.<br>
                Untuk mengatur preferensi notifikasi, silakan kunjungi pengaturan akun Anda.
            </p>
        </div>
    </div>
</body>

</html>
