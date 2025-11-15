<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Allocation Certificate - {{ $allocation->land->plot_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .certificate { border: 2px solid #000; padding: 30px; max-width: 800px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin-bottom: 5px; }
        .header h2 { color: #7f8c8d; margin-top: 0; }
        .content { margin: 20px 0; }
        .section { margin-bottom: 20px; }
        .signature { margin-top: 50px; }
        .signature-line { border-top: 1px solid #000; width: 200px; margin-top: 60px; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #7f8c8d; }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <h1>LAND ALLOCATION CERTIFICATE</h1>
            <h2>Certificate of Allocation</h2>
        </div>

        <div class="content">
            <div class="section">
                <p>This is to certify that:</p>
                <h3>{{ $allocation->client->name }}</h3>
                <p>Has been allocated the following land:</p>
            </div>

            <div class="section">
                <table style="width: 100%;">
                    <tr>
                        <td><strong>Land Plot Number:</strong></td>
                        <td>{{ $allocation->land->plot_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Location:</strong></td>
                        <td>{{ $allocation->land->location }}</td>
                    </tr>
                    <tr>
                        <td><strong>Area:</strong></td>
                        <td>{{ $allocation->land->area_acres }} acres</td>
                    </tr>
                    <tr>
                        <td><strong>Allocation Date:</strong></td>
                        <td>{{ $allocation->allocation_date->format('F j, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Purpose:</strong></td>
                        <td>{{ $allocation->purpose }}</td>
                    </tr>
                    <tr>
                        <td><strong>Chief:</strong></td>
                        <td>{{ $allocation->chief->name }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p><strong>Terms and Conditions:</strong></p>
                <ul>
                    <li>This allocation is subject to the land use regulations</li>
                    <li>The allocated land must be used for the specified purpose</li>
                    <li>Any transfer of rights must be approved by the authorities</li>
                    <li>The allocation may be revoked for violation of terms</li>
                </ul>
            </div>
        </div>

        <div class="signature">
            <div style="float: right;">
                <div class="signature-line"></div>
                <p><strong>{{ $allocation->chief->name }}</strong><br>
                Chief<br>
                Date: {{ now()->format('F j, Y') }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="footer">
            <p>This is a computer-generated certificate. For verification, contact the Land Allocation Office.</p>
            <p>Certificate ID: ALLOC-{{ $allocation->id }}-{{ $allocation->created_at->format('Ymd') }}</p>
        </div>
    </div>
</body>
</html>