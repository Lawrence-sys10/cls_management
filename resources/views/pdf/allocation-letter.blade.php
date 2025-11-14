<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Allocation Letter - {{ $allocation->land->plot_number }}</title>
    <style>
        body {
            font-family: ''DejaVu Sans'', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d3748;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
        }
        .subtitle {
            font-size: 14px;
            color: #718096;
        }
        .content {
            margin: 30px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .signature-section {
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 300px;
            margin-top: 40px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #718096;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            background: #e2e8f0;
            border-radius: 4px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">TECHIMAN CUSTOMARY LANDS SECRETARIAT</div>
        <div class="subtitle">Customary Lands Management System</div>
        <div class="subtitle">Techiman Traditional Council</div>
    </div>

    <div class="content">
        <h2 style="text-align: center; color: #2d3748;">LAND ALLOCATION CERTIFICATE</h2>
        
        <div class="section">
            <p><strong>Certificate Number:</strong> CLS/{{ $allocation->id }}/{{ date(''Y'') }}</p>
            <p><strong>Date of Issue:</strong> {{ $allocation->allocation_date->format(''F d, Y'') }}</p>
        </div>

        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">ALLOCATION DETAILS</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; width: 30%;"><strong>Plot Number:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->land->plot_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Location:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->land->location }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Area:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        {{ number_format($allocation->land->area_acres, 2) }} acres 
                        ({{ number_format($allocation->land->area_hectares, 2) }} hectares)
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Land Use:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ ucfirst($allocation->land->land_use) }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">CLIENT INFORMATION</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; width: 30%;"><strong>Full Name:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->client->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>ID Number:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        {{ ucfirst(str_replace(''_'', '' '', $allocation->client->id_type)) }}: {{ $allocation->client->id_number }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Phone:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->client->phone }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Occupation:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->client->occupation }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">APPROVAL INFORMATION</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0; width: 30%;"><strong>Approving Chief:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->chief->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Jurisdiction:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->chief->jurisdiction }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Processed By:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">{{ $allocation->processedBy->user->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;"><strong>Status:</strong></td>
                    <td style="padding: 8px; border: 1px solid #e2e8f0;">
                        <span class="badge">{{ ucfirst($allocation->approval_status) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        @if($allocation->notes)
        <div class="section">
            <h3 style="color: #2d3748; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">ADDITIONAL NOTES</h3>
            <p>{{ $allocation->notes }}</p>
        </div>
        @endif
    </div>

    <div class="signature-section">
        <div style="float: left; width: 45%;">
            <div class="signature-line"></div>
            <p><strong>{{ $allocation->chief->name }}</strong><br>
            Approving Chief<br>
            {{ $allocation->chief->jurisdiction }}</p>
        </div>
        
        <div style="float: right; width: 45%;">
            <div class="signature-line"></div>
            <p><strong>Registrar</strong><br>
            Techiman Customary Lands Secretariat<br>
            Techiman Traditional Council</p>
        </div>
        
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        <p>This is a computer-generated document. No physical signature is required.</p>
        <p>Generated on: {{ date(''F d, Y \\a\\t H:i'') }}</p>
        <p>CLS Management System - Techiman Customary Lands Secretariat</p>
    </div>
</body>
</html>
