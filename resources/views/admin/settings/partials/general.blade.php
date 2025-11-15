<div class="card">
    <div class="card-header">
        <h5 class="card-title">General Settings</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.settings.update.general') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', 'Land Allocation System') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="site_email" class="form-label">Site Email</label>
                        <input type="email" class="form-control" id="site_email" name="site_email" value="{{ old('site_email', 'admin@landallocation.com') }}">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Save General Settings</button>
        </form>
    </div>
</div>