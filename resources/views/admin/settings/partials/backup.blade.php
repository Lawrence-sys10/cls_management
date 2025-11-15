<div class="card">
    <div class="card-header">
        <h5 class="card-title">Backup & Restore</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.backup.create') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Backup Type</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="backup_type" id="full_backup" value="full" checked>
                    <label class="form-check-label" for="full_backup">Full Backup</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Create Backup</button>
        </form>
    </div>
</div>