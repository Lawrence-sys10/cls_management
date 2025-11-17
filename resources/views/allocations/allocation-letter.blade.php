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
        .section { margin-bottom: 25px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAND ALLOCATION LETTER</h1>
        <p>Official Allocation Document</p>
    </div>
    
    <div class="content">
        <div class="section">
            <h3>Allocation Details</h3>
            <p><strong>Allocation ID:</strong> {{ $allocation->id }}</p>
            <p><strong>Plot Number:</strong> {{ $allocation->land?->plot_number ?? 'N/A' }}</p>
            <p><strong>Allocation Date:</strong> {{ $allocation->allocation_date?->format('Y-m-d') ?? 'N/A' }}</p>
            <p><strong>Purpose:</strong> {{ $allocation->purpose ?? 'Not specified' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($allocation->approval_status ?? 'pending') }}</p>
        </div>

        <div class="section">
            <h3>Client Information</h3>
            <p><strong>Client Name:</strong> {{ $allocation->client?->full_name ?? 'N/A' }}</p>
            <p><strong>ID Number:</strong> {{ $allocation->client?->id_number ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $allocation->client?->phone ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $allocation->client?->email ?? 'N/A' }}</p>
        </div>

        <div class="section">
            <h3>Land Information</h3>
            <p><strong>Land Name:</strong> {{ $allocation->land?->name ?? 'N/A' }}</p>
            <p><strong>Location:</strong> {{ $allocation->land?->location ?? 'N/A' }}</p>
            <p><strong>Size:</strong> {{ $allocation->land?->size ?? 'N/A' }}</p>
            <p><strong>Plot Number:</strong> {{ $allocation->land?->plot_number ?? 'N/A' }}</p>
        </div>

        <div class="section">
            <h3>Approval Information</h3>
            <p><strong>Chief:</strong> {{ $allocation->chief?->name ?? 'N/A' }}</p>
            <p><strong>Chief Approval Date:</strong> {{ $allocation->chief_approval_date?->format('Y-m-d') ?? 'Pending' }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst($allocation->payment_status ?? 'pending') }}</p>
            <p><strong>Payment Amount:</strong> {{ $allocation->payment_amount ? 'GHS ' . number_format($allocation->payment_amount, 2) : 'N/A' }}</p>
        </div>
    </div>
    
    <div class="footer">
        <p>Authorized Signature</p>
        <div class="signature">
            <p>_________________________</p>
            <p>Land Management Office</p>
            <p>Date: {{ date('Y-m-d') }}</p>
        </div>
    </div>
</body>
</html>