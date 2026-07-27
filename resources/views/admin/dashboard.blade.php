@extends('layouts.aethryna')

@section('title', 'Admin Dashboard | SkillsCo-op')

@section('content')
    @php
        use App\Models\User;
        use App\Models\Assessment;
        use App\Models\Pathway;

        // Admin statistics
        $totalUsers = User::count();
        $totalAssessments = Assessment::count();
        $completedAssessments = Assessment::where('status', 'completed')->count();
        $popularPathways = Pathway::withCount(['assessmentResults'])->orderBy('assessment_results_count', 'desc')->take(5)->get();

        // Assessment completion rate
        $completionRate = $totalAssessments > 0 ? round(($completedAssessments / $totalAssessments) * 100, 1) : 0;

        // Cluster distribution
        $clusterData = [
            'T' => Assessment::where('status', 'completed')->where('scores->T', '>', 2)->count(),
            'C' => Assessment::where('status', 'completed')->where('scores->C', '>', 2)->count(),
            'B' => Assessment::where('status', 'completed')->where('scores->B', '>', 2)->count(),
            'S' => Assessment::where('status', 'completed')->where('scores->S', '>', 2)->count(),
            'F' => Assessment::where('status', 'completed')->where('scores->F', '>', 2)->count(),
        ];
    @endphp

    <!-- Admin Header -->
    <section class="bg-gradient-to-r from-teal-700 to-teal-900 text-white py-32 px-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-400 opacity-10 rounded-full -mr-48 -mt-48"></div>
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row justify-between items-center relative z-10 gap-8">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl font-bold mb-2">Admin Dashboard</h1>
                <p class="text-lg opacity-90">Assessment analytics and platform management</p>
            </div>
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center text-black text-2xl font-bold">
                    <i class="fas fa-cog"></i>
                </div>
                <div>
                    <h3 class="text-xl font-semibold">{{ Auth::user()->name }}</h3>
                    <p class="text-sm opacity-80">Administrator</p>
                    <span
                        class="inline-block mt-1 bg-yellow-400 text-black text-xs font-bold px-3 py-1 rounded-full">Admin</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="max-w-6xl mx-auto px-8 mt-8">
        <div class="bg-white rounded-2xl p-6 shadow-md">
            <h3 class="text-xl font-bold text-teal-700 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('assessment.index') }}" target="_blank"
                    class="flex flex-col items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                    <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-play"></i>
                    </div>
                    <span class="text-sm font-semibold text-teal-700 text-center">Test Assessment</span>
                </a>

                <a href="{{ route('admin.reports') }}"
                    class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <span class="text-sm font-semibold text-blue-700 text-center">View Reports</span>
                </a>

                <a href="{{ route('admin.content') }}"
                    class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-cog"></i>
                    </div>
                    <span class="text-sm font-semibold text-green-700 text-center">Manage Content</span>
                </a>

                <a href="{{ route('admin.users') }}"
                    class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-sm font-semibold text-purple-700 text-center">User Management</span>
                </a>

                @php
                    $openConcerns = \App\Models\SafeguardingConcern::whereIn('status', ['new', 'acknowledged'])->count();
                @endphp
                <a href="{{ route('admin.safeguarding.index') }}"
                    class="relative flex flex-col items-center p-4 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    @if ($openConcerns > 0)
                        <span class="absolute top-2 right-2 min-w-6 h-6 px-2 bg-red-600 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $openConcerns }}</span>
                    @endif
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <span class="text-sm font-semibold text-red-700 text-center">Safeguarding</span>
                </a>

                @php
                    $overdueRisks = \App\Models\Risk::where('status', '!=', 'closed')
                        ->whereNotNull('review_due')
                        ->whereDate('review_due', '<', now())
                        ->count();
                @endphp
                <a href="{{ route('admin.risks.index') }}"
                    class="relative flex flex-col items-center p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors">
                    @if ($overdueRisks > 0)
                        <span class="absolute top-2 right-2 min-w-6 h-6 px-2 bg-amber-600 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $overdueRisks }}</span>
                    @endif
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <span class="text-sm font-semibold text-amber-700 text-center">Risk Register</span>
                </a>

                @php
                    // Applications waiting on a decision. Mentors come through
                    // this same pipeline, so this covers both.
                    $openApplications = \App\Models\VolunteerEngagement::where('status', 'applied')->count();
                @endphp
                <a href="{{ route('admin.volunteers.index') }}"
                    class="relative flex flex-col items-center p-4 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                    @if ($openApplications > 0)
                        <span class="absolute top-2 right-2 min-w-6 h-6 px-2 bg-teal-600 text-white text-xs font-bold rounded-full flex items-center justify-center">{{ $openApplications }}</span>
                    @endif
                    <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <span class="text-sm font-semibold text-teal-700 text-center">Volunteers</span>
                </a>

                <a href="{{ route('admin.volunteer-roles.index') }}"
                    class="relative flex flex-col items-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                    <div class="w-12 h-12 bg-slate-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 text-center">Positions</span>
                </a>

                <a href="{{ route('admin.volunteer-documents.index') }}"
                    class="relative flex flex-col items-center p-4 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                    <div class="w-12 h-12 bg-slate-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 text-center">Onboarding Pack</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Assessment Analytics Overview -->
    <section class="bg-white py-16 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Total Users</h3>
                    <p class="text-2xl font-bold text-teal-600">{{ number_format($totalUsers) }}</p>
                    <p class="text-sm text-gray-600">Registered users</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Assessments Taken</h3>
                    <p class="text-2xl font-bold text-teal-600">{{ number_format($totalAssessments) }}</p>
                    <p class="text-sm text-gray-600">Total assessments</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Completion Rate</h3>
                    <p class="text-2xl font-bold text-teal-600">{{ $completionRate }}%</p>
                    <p class="text-sm text-gray-600">{{ $completedAssessments }} completed</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white mb-3">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-teal-700 mb-2">Pathways</h3>
                    <p class="text-2xl font-bold text-teal-600">{{ Pathway::count() }}</p>
                    <p class="text-sm text-gray-600">Available pathways</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Assessment Analytics Dashboard -->
    <section class="bg-gray-100 py-16 px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <!-- Cluster Distribution -->
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Pathway Distribution</h3>
                            <p class="text-gray-600 text-sm">Assessment results by cluster</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @php
                            $totalCompleted = array_sum($clusterData);
                        @endphp
                        @foreach($clusterData as $cluster => $count)
                            @php
                                $percentage = $totalCompleted > 0 ? round(($count / $totalCompleted) * 100, 1) : 0;
                                $clusterNames = [
                                    'T' => 'Technical',
                                    'C' => 'Creative',
                                    'B' => 'Business',
                                    'S' => 'Security',
                                    'F' => 'Foundation'
                                ];
                                $clusterColors = [
                                    'T' => 'blue',
                                    'C' => 'purple',
                                    'B' => 'green',
                                    'S' => 'red',
                                    'F' => 'yellow'
                                ];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-8 h-8 bg-{{ $clusterColors[$cluster] }}-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                        {{ $cluster }}
                                    </span>
                                    <div>
                                        <div class="font-semibold text-teal-700">{{ $clusterNames[$cluster] }}</div>
                                        <div class="text-sm text-gray-600">{{ $count }} users</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-teal-600">{{ $percentage }}%</div>
                                    <div class="w-20 bg-gray-200 rounded-full h-2">
                                        <div class="bg-{{ $clusterColors[$cluster] }}-500 h-2 rounded-full"
                                            style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Assessment Management -->
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Assessment Management</h3>
                            <p class="text-gray-600 text-sm">Admin controls and tools</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-teal-700">Database Status</div>
                                    <div class="text-sm text-gray-600">Questions & pathways loaded</div>
                                </div>
                            </div>
                            <span class="bg-green-100 text-green-800 text-sm font-bold px-2 py-1 rounded">Active</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-teal-700">User Assessments</div>
                                    <div class="text-sm text-gray-600">{{ $completedAssessments }} completed</div>
                                </div>
                            </div>
                            <span
                                class="bg-blue-100 text-blue-800 text-sm font-bold px-2 py-1 rounded">{{ $totalAssessments }}</span>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('assessment.index') }}"
                                    class="bg-teal-500 text-white px-4 py-3 rounded-lg font-semibold hover:bg-teal-600 transition-colors text-center">
                                    View Assessment
                                </a>
                                <button
                                    class="border border-gray-300 text-gray-600 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                                    Export Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Pathways & Recent Assessments -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Popular Pathways -->
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gold rounded-full flex items-center justify-center text-black">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Popular Pathways</h3>
                            <p class="text-gray-600 text-sm">Most recommended by assessments</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($popularPathways as $index => $pathway)
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <div
                                    class="flex-shrink-0 w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-teal-700">{{ $pathway->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $pathway->category }} • {{ $pathway->difficulty_level }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-teal-600">{{ $pathway->assessment_results_count }}</div>
                                    <div class="text-xs text-gray-500">recommendations</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Assessment Activity -->
                <div class="bg-white rounded-2xl p-6 shadow-md">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-teal-700">Recent Assessments</h3>
                            <p class="text-gray-600 text-sm">Latest assessment completions</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @php
                            $recentAssessments = Assessment::with(['user', 'results.pathway'])
                                ->where('status', 'completed')
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        @forelse($recentAssessments as $assessment)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                <div
                                    class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                    @if($assessment->user)
                                        {{ strtoupper(substr($assessment->user->name, 0, 1)) }}
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-teal-700">
                                            {{ $assessment->user ? $assessment->user->name : 'Anonymous User' }}
                                        </span>
                                        @if($assessment->results->count() > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">
                                                {{ $assessment->results->first()->cluster }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        Completed assessment •
                                        {{ $assessment->completed_at ? $assessment->completed_at->diffForHumans() : 'Recently' }}
                                    </p>
                                    @if($assessment->results->count() > 0)
                                        <p class="text-xs text-gray-500 mt-1">
                                            Recommended: {{ $assessment->results->first()->pathway->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div
                                    class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-4">
                                    <i class="fas fa-inbox text-2xl"></i>
                                </div>
                                <p class="text-gray-600">No recent assessments</p>
                                <p class="text-sm text-gray-500">Assessment activity will appear here</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        
        </div>
    </section>

    @push('styles')
        <style>
            /* Custom styles that can't be easily done with Tailwind alone */
            .chart-bar {
                background: #038b89;
                border-radius: 4px 4px 0 0;
                width: 100%;
                transition: height 1s ease;
            }

            /* Table specific styles */
            .table-header,
            .table-row {
                display: grid;
                grid-template-columns: 2fr 2fr 1fr 1fr 1fr 1.5fr;
                gap: 1rem;
                padding: 1rem;
                align-items: center;
            }

            .table-header {
                background: #F5F5F5;
                border-radius: 20px;
                font-weight: 600;
                color: #038b89;
                margin-bottom: 0.5rem;
            }

            .table-row {
                border-bottom: 1px solid #e9ecef;
                transition: all 0.3s ease;
            }

            .table-row:hover {
                background: #F5F5F5;
            }

            .table-row:last-child {
                border-bottom: none;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                background: #038b89;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
            }

            .role-badge,
            .status-badge {
                padding: 0.3rem 0.8rem;
                border-radius: 15px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .role-badge.user {
                background: #ee9d1d;
                color: #0C0C0C;
            }

            .role-badge.admin {
                background: #038b89;
                color: white;
            }

            .status-badge.active {
                background: #28a745;
                color: white;
            }

            .status-badge.inactive {
                background: #6c757d;
                color: white;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
                border: none;
                border-radius: 50%;
                background: transparent;
                color: #038b89;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-icon:hover {
                background: #038b89;
                color: white;
            }

            .btn-icon.danger:hover {
                background: #dc3545;
                color: white;
            }

            .page-btn {
                width: 32px;
                height: 32px;
                border: 2px solid #e9ecef;
                background: white;
                border-radius: 6px;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.9rem;
            }

            .page-btn:hover,
            .page-btn.active {
                background: #038b89;
                color: white;
                border-color: #038b89;
            }

            .page-btn.disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            /* Content status badges */
            .content-status.published {
                background: #28a745;
                color: white;
            }

            .content-status.draft {
                background: #ffc107;
                color: #0C0C0C;
            }

            /* Status indicators */
            .status-indicator.online {
                background: #28a745;
            }

            .status-indicator.warning {
                background: #ffc107;
            }

            /* Buttons */
            .btn-primary {
                background: #038b89;
                color: white;
            }

            .btn-primary:hover {
                background: #245a68;
                transform: translateY(-2px);
            }

            .btn-outline {
                background: transparent;
                color: #038b89;
                border: 2px solid #038b89;
            }

            .btn-outline:hover {
                background: #038b89;
                color: white;
            }

            .btn.danger {
                background: #dc3545;
                color: white;
            }

            .btn.danger:hover {
                background: #c82333;
            }

            /* Responsive adjustments */
            @media (max-width: 992px) {

                .table-header,
                .table-row {
                    grid-template-columns: 1fr;
                    gap: 0.5rem;
                }

                .table-row {
                    padding: 1rem 0;
                }

                .col:not(:first-child) {
                    display: flex;
                    justify-content: space-between;
                    width: 100%;
                }

                .col:not(:first-child)::before {
                    content: attr(data-label);
                    font-weight: 600;
                    color: #055860;
                }
            }

            @media (max-width: 768px) {
                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .section-actions {
                    flex-direction: column;
                    align-items: stretch;
                }

                .search-input {
                    width: 100%;
                }

                .content-grid {
                    grid-template-columns: 1fr;
                }

                .system-health {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 576px) {
                .admin-actions {
                    flex-direction: column;
                    width: 100%;
                }

                .admin-actions .btn {
                    justify-content: center;
                }

                .table-pagination {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }

                .pagination {
                    justify-content: center;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Table search functionality
            const searchInput = document.querySelector('.search-input');
            const tableRows = document.querySelectorAll('.table-row');

            searchInput.addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            // Filter functionality
            const filterSelect = document.querySelector('.filter-select');

            filterSelect.addEventListener('change', function () {
                const filterValue = this.value;

                tableRows.forEach(row => {
                    const roleBadge = row.querySelector('.role-badge');
                    const statusBadge = row.querySelector('.status-badge');

                    let show = false;

                    if (filterValue === 'all') {
                        show = true;
                    } else if (filterValue === 'active' && statusBadge.classList.contains('active')) {
                        show = true;
                    } else if (filterValue === 'inactive' && statusBadge.classList.contains('inactive')) {
                        show = true;
                    } else if (filterValue === 'admin' && roleBadge.classList.contains('admin')) {
                        show = true;
                    }

                    row.style.display = show ? '' : 'none';
                });
            });

            // Animate chart bars on load
            document.addEventListener('DOMContentLoaded', function () {
                const chartBars = document.querySelectorAll('.chart-bar');
                chartBars.forEach((bar, index) => {
                    setTimeout(() => {
                        bar.style.height = bar.style.height;
                    }, index * 100);
                });
            });

            // Action button tooltips
            const actionButtons = document.querySelectorAll('.btn-icon');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function () {
                    this.style.transform = 'scale(1.1)';
                });

                button.addEventListener('mouseleave', function () {
                    this.style.transform = 'scale(1)';
                });
            });
        </script>
    @endpush
@endsection