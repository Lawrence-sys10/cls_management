<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Allocation Letter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { margin: 20px 0; }
        .footer { margin-top: 50px; text-align: right; }
        .signature { margin-top: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAND ALLOCATION LETTER</h1>
        <p>Official Allocation Document</p>
    </div>
    
    <div class="content">
        <p><strong>Allocation ID:</strong> {{ $allocation->id }}</p>
        <p><strong>Plot Number:</strong> {{ $allocation->land->plot_number }}</p>
        <p><strong>Client:</strong> {{ $allocation->client->user->name }}</p>
        <p><strong>Allocation Date:</strong> {{ $allocation->allocation_date->format('Y-m-d') }}</p>
        <p><strong>Purpose:</strong> {{ $allocation->purpose }}</p>
        <p><strong>Status:</strong> {{ ucfirst($allocation->approval_status) }}</p>
    </div>
    
    <div class="footer">
        <p>Authorized Signature</p>
        <div class="signature">
            <p>_________________________</p>
            <p>Land Management Office</p>
        </div>
    </div>
</body>
</html>