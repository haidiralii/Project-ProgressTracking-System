{{-- resources/views/activities/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="main-content">
    <div class="content-header">
        <div class="header-left">
            <button type="button" class="btn-back" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="header-info">
                <h1 class="page-title">Create Activity</h1>
                <p class="page-subtitle">Add a new activity to the job/project</p>
            </div>
        </div>
        <div class="header-right">
            <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
        </div>
    </div>
    <div class="form-container">
        <form action="{{ route('activities.store') }}" method="POST" class="project-form">
            @csrf
            <!-- Activity Information Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div class="section-info">
                        <h3>Activity Information</h3>
                        <p>Main details of the activity</p>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="title" class="form-label">Activity Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" class="form-input" value="{{ old('title') }}" placeholder="Enter activity title..." required>
                    </div>
                    <div class="form-group full-width">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-textarea" rows="4" placeholder="Describe the activity...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Activity Timeline Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="section-info">
                        <h3>Activity Timeline</h3>
                        <p>Date and time of activity</p>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="activity_date" class="form-label">Activity Date <span class="required">*</span></label>
                        <input type="date" id="activity_date" name="activity_date" class="form-input" value="{{ old('activity_date') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="activity_time" class="form-label">Activity Time</label>
                        <input type="time" id="activity_time" name="activity_time" class="form-input" value="{{ old('activity_time') }}">
                    </div>
                </div>
            </div>
            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Create Activity
                </button>
                <a href="{{ route('activities.index') }}" class="btn-secondary ml-2">Back to Activities</a>
            </div>
        </form>
    </div>
</div>

<style>
:root {
    --primary-red: #dc3545;
    --dark-red: #b02a37;
    --light-red: #f8d7da;
    --text-dark: #2c3e50;
    --text-light: #6c757d;
    --border-color: #e9ecef;
    --bg-light: #f8f9fa;
    --white: #ffffff;
}
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-light); color: var(--text-dark); }
.main-content { max-width: 1200px; margin: 0 auto; padding: 2rem; }
.content-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.header-left { display: flex; align-items: center; gap: 1rem; }
.btn-back { background: none; border: none; font-size: 1.2rem; color: var(--text-light); cursor: pointer; padding: 0.5rem; border-radius: 4px; transition: all 0.3s ease; }
.btn-back:hover { background-color: var(--border-color); color: var(--text-dark); }
.page-title { font-size: 1.5rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.25rem; }
.page-subtitle { color: var(--text-light); font-size: 0.9rem; }
.btn-cancel { background: none; border: 1px solid var(--border-color); color: var(--text-light); padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem; }
.btn-cancel:hover { background-color: var(--border-color); color: var(--text-dark); }
.form-container { background: var(--white); border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); }
.form-section { padding: 2rem; border-bottom: 1px solid var(--border-color); }
.form-section:last-child { border-bottom: none; }
.section-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
.section-icon { width: 40px; height: 40px; background-color: var(--primary-red); color: var(--white); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
.section-info h3 { font-size: 1.1rem; font-weight: 600; color: var(--text-dark); margin-bottom: 0.25rem; }
.section-info p { color: var(--text-light); font-size: 0.9rem; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
.form-group { display: flex; flex-direction: column; }
.form-group.full-width { grid-column: span 2; }
.form-label { font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem; font-size: 0.9rem; }
.required { color: var(--primary-red); font-weight: 600; }
.optional { color: var(--text-light); font-weight: 400; }
.form-input, .form-select, .form-textarea { padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 4px; font-size: 0.9rem; transition: all 0.3s ease; font-family: inherit; }
.form-input:focus, .form-select:focus, .form-textarea:focus { outline: none; border-color: var(--primary-red); box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.1); }
.form-textarea { resize: vertical; min-height: 100px; }
.form-select { cursor: pointer; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 2.5rem; }
.error-message { color: var(--primary-red); font-size: 0.8rem; margin-top: 0.25rem; }
.form-actions { padding: 1.5rem 2rem; background-color: var(--bg-light); display: flex; justify-content: flex-end; gap: 1rem; }
.btn-secondary { background: none; border: 1px solid var(--border-color); color: var(--text-light); padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem; }
.btn-secondary:hover { background-color: var(--border-color); color: var(--text-dark); }
.btn-primary { background-color: var(--primary-red); color: var(--white); border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; transition: all 0.3s ease; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem; }
.btn-primary:hover { background-color: var(--dark-red); }
@media (max-width: 768px) { .main-content { padding: 1rem; } .content-header { flex-direction: column; align-items: flex-start; gap: 1rem; } .form-grid { grid-template-columns: 1fr; } .form-group.full-width { grid-column: span 1; } .form-section { padding: 1.5rem; } .form-actions { flex-direction: column; gap: 0.5rem; } .btn-primary, .btn-secondary { width: 100%; justify-content: center; } }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textarea = document.querySelector('.form-textarea');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    }
    // Form validation
    const form = document.querySelector('.project-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = 'var(--primary-red)';
                } else {
                    field.style.borderColor = 'var(--border-color)';
                }
            });
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields.');
            }
        });
    }
});
</script>
@endsection
