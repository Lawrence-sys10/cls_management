@extends('layouts.app')

@section('title', 'Backup & Restore')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Backup & Restore</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Create Backup</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Create a full system backup including database and files.</p>
                                    <form action="{{ route('admin.settings.backup.create') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Backup Type</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="backup_type" id="full_backup" value="full" checked>
                                                <label class="form-check-label" for="full_backup">Full Backup (Database + Files)</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="backup_type" id="db_backup" value="database">
                                                <label class="form-check-label" for="db_backup">Database Only</label>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Include</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="include_media" name="include_media" checked>
                                                <label class="form-check-label" for="include_media">Media Files</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="include_logs" name="include_logs">
                                                <label class="form-check-label" for="include_logs">System Logs</label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-download me-2"></i>Create Backup
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Restore Backup</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Restore system from a previous backup.</p>
                                    <div class="alert alert-warning">
                                        <small>
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Warning: Restoring a backup will overwrite current data. Proceed with caution.
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="backup_file" class="form-label">Select Backup File</label>
                                        <input type="file" class="form-control" id="backup_file" accept=".zip,.sql">
                                        <small class="form-text text-muted">Supported formats: ZIP, SQL</small>
                                    </div>
                                    
                                    <button type="button" class="btn btn-warning" disabled>
                                        <i class="fas fa-upload me-2"></i>Restore Backup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title">Recent Backups</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                No backups found
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection