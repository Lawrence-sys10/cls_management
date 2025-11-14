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
        <p><strong>Allocation ID:</strong> <?php echo e($allocation->id); ?></p>
        <p><strong>Plot Number:</strong> <?php echo e($allocation->land->plot_number); ?></p>
        <p><strong>Client:</strong> <?php echo e($allocation->client->user->name); ?></p>
        <p><strong>Allocation Date:</strong> <?php echo e($allocation->allocation_date->format('Y-m-d')); ?></p>
        <p><strong>Purpose:</strong> <?php echo e($allocation->purpose); ?></p>
        <p><strong>Status:</strong> <?php echo e(ucfirst($allocation->approval_status)); ?></p>
    </div>
    
    <div class="footer">
        <p>Authorized Signature</p>
        <div class="signature">
            <p>_________________________</p>
            <p>Land Management Office</p>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\pprhl\cls_management\resources\views/allocations/allocation-letter.blade.php ENDPATH**/ ?>