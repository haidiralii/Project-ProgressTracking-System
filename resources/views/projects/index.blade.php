@extends('layouts.admin')

@section('title', 'ManPro - Projects Overview')
@section('page-title', 'Projects')
@section('page-description', 'Manage and track all your projects in one place.')

@section('content')
<style>
    .card-shadow-hover {
        transition: all 0.3s ease-in-out;
    }
    .card-shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s cubic-bezier(0.4,0,0.2,1) forwards;
        opacity: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(24px);}
        to { opacity: 1; transform: translateY(0);}
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 4.5em; /* Ensures consistent height even with less text */
        max-height: 4.5em;
    }
    .project-card-min-height {
        min-height: 370px; /* Adjust as needed for consistent card height */
    }

    /* color variables */
    :root {
        --maroon-primary: #CA2626;
        --maroon-light: #E04B4B;
        --maroon-dark: #9B1A1A;
        --maroon-lighter: #F8E4E4;
    }
    .text-maroon { color: var(--maroon-primary); }
    .bg-maroon { background-color: var(--maroon-primary); }
    .bg-maroon-light { background-color: var(--maroon-light); }
    .bg-maroon-dark { background-color: var(--maroon-dark); }
    .bg-maroon-lighter { background-color: var(--maroon-lighter); }

    /* Status badge styles */
    .status-badge {
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px; /* Full pill shape */
        font-size: 0.75rem; /* text-xs */
        text-transform: capitalize;
        white-space: nowrap;
    }
    /* Status badge colors */
    .status-active { background-color: #dcfce7; color: #15803d; } /* green-100, green-700 */
    .status-in_progress { background-color: #fefce8; color: #a16207; } /* yellow-100, yellow-700 */
    .status-completed { background-color: #e0f2f7; color: #0e7490; } /* cyan-100, cyan-700 */
    .status-on_hold { background-color: #fee2e2; color: #b91c1c; } /* red-100, red-700 */
    .status-cancelled { background-color: #e5e7eb; color: #4b5563; } /* gray-200, gray-700 */
    
</style>


<div class="flex flex-col sm:flex-row items-center justify-between mb-8 space-y-4 sm:space-y-0">
    <!-- View Switch Buttons -->
    <div>
        <button id="gridViewBtn"
            class="px-6 py-2 rounded-xl font-semibold text-gray-600 bg-white shadow-md text-sm
                transition-all duration-300 hover:scale-105 hover:shadow-lg
                hover:text-maroon hover:bg-maroon-lighter
                {{ !request()->has('view') || request('view') === 'grid' ? 'text-maroon bg-maroon-lighter' : '' }}
                animate-fade-in-up"
            style="animation-delay:0.1s;">
            <i class="fas fa-th-large text-lg"></i>
        </button>

        <button id="listViewBtn"
            class="px-6 py-2 rounded-xl font-semibold text-gray-600 bg-white shadow-md text-sm
                transition-all duration-300 hover:scale-105 hover:shadow-lg
                hover:text-maroon hover:bg-maroon-lighter
                {{ request('view') === 'list' ? 'text-maroon bg-maroon-lighter' : '' }}
                animate-fade-in-up"
            style="animation-delay:0.2s;">
            <i class="fas fa-list text-lg"></i>
        </button>
    </div>

    <!-- Spacer -->
    <div class="text-center sm:text-left"></div>

    <!-- Filter + New Project -->
    <div class="w-full sm:w-auto flex flex-col sm:flex-row items-center sm:items-end gap-4">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('projects.index') }}"
              class="flex flex-wrap gap-4 items-center w-full sm:w-auto animate-fade-in-up"
              style="animation-delay:0.3s;">
            <select name="status" class="rounded-xl border px-4 py-2 text-sm pr-8 min-w-max shadow transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
            </select>
            <select name="starred" class="rounded-xl border px-4 py-2 text-sm pr-8 min-w-max shadow transition-all duration-300 hover:scale-105 hover:shadow-lg">
                <option value="" {{ request('starred') == '' ? 'selected' : '' }}>All Projects</option>
                <option value="1" {{ request('starred') == '1' ? 'selected' : '' }}>Starred Only</option>
            </select>
            <button
                type="submit"
                class="px-6 py-2 text-white rounded-xl font-semibold shadow-md transition-all duration-300 hover:scale-105 hover:shadow-lg animate-fade-in-up"
                style="animation-delay:0.4s; background-image: linear-gradient(to right, var(--maroon-primary), var(--maroon-light));">
                Apply Filter
            </button>
        </form>

        <!-- New Project Button -->
        @if(auth()->user()->role === 'admin')
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center justify-center bg-gradient-to-r from-maroon-primary to-maroon-light
                  hover:from-maroon-dark hover:to-maroon-primary text-white px-6 py-2 rounded-xl font-semibold
                  transition-all duration-300 shadow-md hover:scale-105 hover:shadow-lg w-full sm:w-auto text-center animate-fade-in-up"
            style="animation-delay:0.4s; background-image: linear-gradient(to right, var(--maroon-primary), var(--maroon-light));">
            <i class="fas fa-plus mr-2"></i>New Project
        </a>
        @endif
        
    </div>
</div>

<div id="projectsGrid" class="grid {{ request('view') === 'list' ? 'grid-cols-1 list-view' : 'md:grid-cols-2 lg:grid-cols-3' }} gap-6">
    @forelse ($projects as $project)
    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105 overflow-hidden group flex flex-col project-card-min-height animate-fade-in-up"
        style="animation-delay: {{ $loop->index * 0.1 + 0.5 }}s;">           
            <div class="bg-gradient-to-r from-maroon-primary to-maroon-light p-5 text-white rounded-t-xl shadow-sm">
                <div class="flex items-start justify-between">
                    <!-- Kartu Proyek dengan Ikon, Info, dan Badge Status -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow relative">
                        @php
                            $status = $project->status ?? 'N/A';
                            $statusClasses = match ($status) {
                                'active'      => 'bg-green-100 text-green-700',
                                'in_progress' => 'bg-yellow-100 text-yellow-800',
                                'completed'   => 'bg-blue-100 text-blue-700',
                                'on_hold'     => 'bg-gray-200 text-gray-800',
                                default       => 'bg-red-100 text-red-700',
                            };
                        @endphp

                        <!-- Ikon + Info -->
                        <div class="flex items-start gap-14">
                            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg text-white flex-shrink-0">
                                <i class="fas fa-folder text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="mb-2">
                                    <h3 class="font-semibold text-gray-900 text-lg leading-tight tracking-wide">
                                        {{ $project->name ?? 'Unnamed Project' }}
                                    </h3>
                                </div>
                                <p class="text-sm text-gray-500">
                                    {{ $project->project_info ?? 'Internal Project' }}
                                </p>
                            </div>
                        </div>

                        <!-- Badge status -->
                        <div class="mt-4 text-right">
                            <span class="inline-block min-w-[80px] text-center px-3 py-1 text-xs font-semibold rounded-full {{ $statusClasses }}">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 flex flex-col flex-1">
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                    {{ $project->description ?? 'No description available for this project.' }}
                </p>

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $project->name }}</h3>
                    <span class="text-sm text-gray-600">{{ $project->created_at->format('d M Y') }}</span>
                </div>

                <!-- Progress Bar -->
                <div class="w-full mb-6">
                    @php
                        $progress = $project->progress_percentage ?? 0;
                        $hasJobs = $project->jobs && $project->jobs->count() > 0;
                        // Batang bar minimal 8px jika progress > 0
                        $barWidth = $hasJobs ? max($progress, 2) : 0; // 2% minimal agar selalu tampak
                    @endphp

                    <!-- Label -->
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm font-medium text-gray-600">Project Progress</span>
                        <span class="text-xs font-semibold text-gray-500">
                            {{ $hasJobs ? $progress . '%' : '0%' }}
                        </span>
                    </div>

                    <!-- Bar Container -->
                    <div class="relative w-full h-5 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                        <div class="absolute left-0 top-0 h-full rounded-full
                                    transition-all duration-700 ease-in-out
                                    {{ $progress >= 100 ? 'bg-green-500' : 'bg-gradient-to-r from-red-600 to-red-700' }}"
                            style="width: {{ $barWidth }}%;">
                        </div>
                    </div>

                    <!-- Text When No Jobs -->
                    @unless($hasJobs)
                        <div class="text-xs text-gray-400 mt-2 italic">
                            No jobs added yet — progress will show when jobs are assigned.
                        </div>
                    @endunless
                </div>


                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-900">{{ $project->jobs->count() }}</div>
                        <div class="text-xs text-gray-500">Jobs</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-900">{{ $project->jobs->where('status', 'completed')->count() }}</div>
                        <div class="text-xs text-gray-500">Jobs Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-gray-900">
                            {{ $project->jobs->flatMap->operators->unique('id')->count() }}
                        </div>
                        <div class="text-xs text-gray-500">Operators</div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex -space-x-2">
                        @php
                            $assignedOperators = $project->jobs->flatMap->operators->unique('id');
                        @endphp
                        @if($assignedOperators->count() > 0)
                            @foreach($assignedOperators->take(3) as $member)
                            <div class="w-8 h-8 bg-gradient-to-br from-maroon-lighter to-maroon-light rounded-full flex items-center justify-center text-white text-xs font-medium border-2 border-white shadow-sm">
                                {{ substr($member->name, 0, 1) }}
                            </div>
                            @endforeach
                            @if($assignedOperators->count() > 3)
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 text-xs font-medium border-2 border-white shadow-sm">
                                +{{ $assignedOperators->count() - 3 }}
                            </div>
                            @endif
                        @else
                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-xs border-2 border-white shadow-sm">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-gray-500">Due Date</div>
                        <div class="text-sm font-medium text-gray-900">
                            {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'No due date' }}
                        </div>
                    </div>
                </div>
                <!-- Spacer to push action buttons to bottom -->
                <div class="flex-grow"></div> 
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-auto">
                    <div class="flex items-center space-x-3">
                            <a href="{{ route('projects.show', $project->id) }}"
                                class="flex items-center text-maroon hover:text-maroon-dark text-sm font-medium transition duration-200">
                                    <i class="fas fa-eye mr-1"></i> View
                            </a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('projects.edit', $project->id) }}"
                                class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium transition duration-200">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <a href="{{ route('jobs.create', ['project_id' => $project->id]) }}"
                                class="flex items-center text-green-600 hover:text-green-700 text-sm font-medium transition duration-200">
                                <i class="fas fa-tasks mr-1"></i> Add Jobs
                            </a>
                        @endif
                    </div>
                    @if(auth()->user()->role === 'admin')
                        <div class="flex items-center space-x-2">
                            <button class="star-toggle p-2 text-gray-400 hover:text-yellow-500 hover:bg-gray-100 rounded-lg transition duration-200"
                                    data-id="{{ $project->id }}">
                                @if($project->is_starred)
                                    <i class="fas fa-star text-yellow-500"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl shadow-lg p-12 text-center animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="w-24 h-24 bg-gradient-to-br from-red-100 to-red-200 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-project-diagram text-3xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Projects Found</h3>
            <p class="text-gray-500 mb-6">Get started by creating your project</p>
        </div>
    @endforelse
</div>

@if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator && $projects->hasPages())
<div class="mt-8 flex justify-center animate-fade-in-up" style="animation-delay: 1s;">
    {{ $projects->links() }}
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');
    const projectsGrid = document.getElementById('projectsGrid');

    // Ganti tampilan berdasarkan URL param
    const currentView = new URLSearchParams(window.location.search).get('view');
    if (currentView === 'list') {
        projectsGrid.classList.remove('md:grid-cols-2', 'lg:grid-cols-3');
        projectsGrid.classList.add('grid-cols-1');
        listBtn.classList.add('text-maroon', 'bg-gray-100');
        gridBtn.classList.remove('text-maroon', 'bg-gray-100');
    } else {
        projectsGrid.classList.remove('grid-cols-1');
        projectsGrid.classList.add('md:grid-cols-2', 'lg:grid-cols-3');
        gridBtn.classList.add('text-maroon', 'bg-gray-100');
        listBtn.classList.remove('text-maroon', 'bg-gray-100');
    }

    // Update URL saat tombol tampilan diklik
    function updateUrlParam(param, value) {
        const url = new URL(window.location.href);
        if (value) {
            url.searchParams.set(param, value);
        } else {
            url.searchParams.delete(param);
        }
        window.history.pushState({ path: url.href }, '', url.href);
    }

    gridBtn.addEventListener('click', function() {
        if (!projectsGrid.classList.contains('md:grid-cols-2')) {
            projectsGrid.classList.remove('grid-cols-1');
            projectsGrid.classList.add('md:grid-cols-2', 'lg:grid-cols-3');
            gridBtn.classList.add('text-maroon', 'bg-gray-100');
            listBtn.classList.remove('text-maroon', 'bg-gray-100');
            updateUrlParam('view', 'grid');
        }
    });

    listBtn.addEventListener('click', function() {
        if (!projectsGrid.classList.contains('grid-cols-1')) {
            projectsGrid.classList.remove('md:grid-cols-2', 'lg:grid-cols-3');
            projectsGrid.classList.add('grid-cols-1');
            listBtn.classList.add('text-maroon', 'bg-gray-100');
            gridBtn.classList.remove('text-maroon', 'bg-gray-100');
            updateUrlParam('view', 'list');
        }
    });

    // Toggle bintang
    document.querySelectorAll('.star-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            fetch(`/projects/${id}/toggle-star`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.innerHTML = data.is_starred
                    ? '<i class="fas fa-star text-yellow-500"></i>'
                    : '<i class="far fa-star"></i>';
            });
        });
    });
});
</script>
@endpush
