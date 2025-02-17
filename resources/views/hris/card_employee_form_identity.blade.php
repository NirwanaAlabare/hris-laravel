<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .card {
            width: 24rem;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .card-header {
            background: #D30000;
            color: white;
            padding: 16px;
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
        }
        .card-body {
            padding: 24px;
        }
        .card-body div {
            margin-bottom:1px;
        }
        .label {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .value {
            font-weight: bold;
            word-wrap: break-word;
            overflow-wrap: break-word;
            white-space: normal;
        }
        .card-footer {
            background: #dbeafe;
            color: #D30000;
            text-align: center;
            padding: 16px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Kartu Identitas</div>
        <div class="card-body">
            <div>
                <p class="label">Nomor Dokumen</p>
                <p class="value">{{$no_form}}</p>
            </div>
            <div>
                <p class="label">Nama Lengkap</p>
                <p class="value">{{$data[0]->employee_name}}</p>
            </div>
            <div>
                <p class="label">No KTP</p>
                <p class="value">{{$data[0]->nomor_ktp}}</p>
            </div>
            <div>
                <p class="label">Tempat, Tanggal Lahir</p>
                <p class="value">{{$data[0]->tempat_lahir . ', '. $data[0]->tanggal_lahir}}</p>
            </div>
                <div style="display: flex; justify-content: flex-start; gap: 20px; align-items: center; width: 100%;">
                    <div>
                        <p class="label">Tanggal Masuk</p>
                        <p class="value">{{$data[0]->tanggal_mulai_kontrak}}</p>
                    </div>
                    <div>
                        <p class="label">Tanggal Berakhir</p>
                        <p class="value">{{$data[0]->tanggal_akhir_kontrak}}</p>
                    </div>
                </div>
        </div>
        <div class="card-footer">
            Kartu ini adalah dokumen resmi. Harap dijaga dengan baik.
        </div>
    </div>
</body>
</html>
