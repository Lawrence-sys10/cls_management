@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <nav class="mb-4">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-general-tab" data-bs-toggle="tab" data-bs-target="#nav-general" type="button" role="tab" aria-controls="nav-general" aria-selected="true">
                        General Settings
                    </button>
                    <button class="nav-link" id="nav-system-tab" data-bs-toggle="tab" data-bs-target="#nav-system" type="button" role="tab" aria-controls="nav-system" aria-selected="false">
                        System Settings
                    </button>
                    <button class="nav-link" id="nav-backup-tab" data-bs-toggle="tab" data-bs-target="#nav-backup" type="button" role="tab" aria-controls="nav-backup" aria-selected="false">
                        Backup & Restore
                    </button>
                </div>
            </nav>
            
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-general" role="tabpanel" aria-labelledby="nav-general-tab">
                    @include('admin.settings.partials.general')
                </div>
                <div class="tab-pane fade" id="nav-system" role="tabpanel" aria-labelledby="nav-system-tab">
                    @include('admin.settings.partials.system')
                </div>
                <div class="tab-pane fade" id="nav-backup" role="tabpanel" aria-labelledby="nav-backup-tab">
                    @include('admin.settings.partials.backup')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection