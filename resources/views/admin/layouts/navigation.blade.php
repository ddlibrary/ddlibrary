<style>
    .active-item {
        background-color: #eaecf4;
        margin: 2px 8px !important;
    }

    .active-menu {
        background: #3030a3;
    }
</style>

<!-- Sidebar -->
<nav class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('admin') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-tools" aria-hidden="true"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    @php
        

        $segments = [
            request()->segment(3),
            request()->segment(4),
            request()->segment(5)
        ];
    @endphp

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->segment(2) == 'admin' && !$segments[0] ? 'active-menu' : '' }}">
        <a class="nav-link" href="{{ url('admin') }}">
            <i class="fas fa-fw fa-tachometer-alt" aria-hidden="true"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Resources Collapse Menu -->
    <li class="nav-item {{ $segments[0] == 'resources' ? 'active-menu' : '' }}">
        <a class="nav-link {{ $segments[0] == 'resources' ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseResources" aria-expanded="{{ $segments[0] == 'resources' ? 'true' : 'false' }}" aria-controls="collapseResources">
            <i class="fas fa-fw fa-cog" aria-hidden="true"></i>
            <span>Resources</span>
        </a>
        <div id="collapseResources" class="collapse {{ $segments[0] == 'resources' ? 'show' : '' }}" aria-labelledby="headingResources" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Resources</h6>
                <a class="collapse-item {{ isActive($segments[1], 'index') }}" href="{{ url('admin/resources/index') }}">Resources</a>
                <a class="collapse-item {{ isActive($segments[1], 'comments') }}" href="{{ url('admin/resources/comments') }}">Comments</a>
                <a class="collapse-item {{ isActive($segments[1], 'flags') }}" href="{{ url('admin/resources/flags') }}">Flags</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Surveys Collapse Menu -->
    <li class="nav-item {{ in_array($segments[0], ['surveys', 'survey_questions', 'survey_time']) ? 'active-menu' : '' }}">
        <a class="nav-link {{ in_array($segments[0], ['surveys', 'survey_questions', 'survey_time']) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseSurveys" aria-expanded="{{ in_array($segments[0], ['surveys', 'survey_questions', 'survey_time']) ? 'true' : 'false' }}" aria-controls="collapseSurveys">
            <i class="fas fa-fw fa-wrench" aria-hidden="true"></i>
            <span>Surveys</span>
        </a>
        <div id="collapseSurveys" class="collapse {{ in_array($segments[0], ['surveys', 'survey_questions', 'survey_time']) ? 'show' : '' }}" aria-labelledby="headingSurveys" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Surveys</h6>
                <a class="collapse-item {{ isActive($segments[0], 'surveys') }}" href="{{ url('admin/surveys') }}">Surveys</a>
                <a class="collapse-item {{ isActive($segments[0], 'survey_questions') }}" href="{{ url('admin/survey_questions') }}">Survey Results</a>
                <a class="collapse-item {{ isActive($segments[0], 'survey_time') }}" href="{{ url('admin/survey_time') }}">Survey Settings</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ in_array($segments[0], ['pages', 'news']) ? 'active-menu' : '' }}">
        <a class="nav-link {{ in_array($segments[0], ['pages', 'news']) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="{{ in_array($segments[0], ['pages', 'news']) ? 'true' : 'false' }}" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder" aria-hidden="true"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse {{ in_array($segments[0], ['pages', 'news']) ? 'show' : '' }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pages & News</h6>
                <a class="collapse-item {{ isActive($segments[0], 'pages') }}" href="{{ url('admin/pages') }}">Pages</a>
                <a class="collapse-item {{ isActive($segments[0], 'news') }}" href="{{ url('admin/news') }}">News</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Glossary Collapse Menu -->
    <li class="nav-item {{ $segments[0] == 'glossary_subjects' ? 'active-menu' : '' }}">
        <a class="nav-link {{ $segments[0] == 'glossary_subjects' ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseGlossary" aria-expanded="{{ $segments[0] == 'glossary_subjects' ? 'true' : 'false' }}" aria-controls="collapseGlossary">
            <i class="fas fa-fw fa-folder" aria-hidden="true"></i>
            <span>Glossary</span>
        </a>
        <div id="collapseGlossary" class="collapse {{ $segments[0] == 'glossary_subjects' ? 'show' : '' }}" aria-labelledby="headingGlossary" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Glossary</h6>
                <a class="collapse-item {{ isActive($segments[0], 'glossary_subjects') }}" href="{{ url('admin/glossary_subjects') }}">Subjects</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Reports Collapse Menu -->
    <li class="nav-item {{ $segments[0] == 'analytics' ? 'active-menu' : '' }}">
        <a class="nav-link {{ $segments[0] == 'analytics' ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseReports" aria-expanded="{{ $segments[0] == 'analytics' ? 'true' : 'false' }}" aria-controls="collapseReports">
            <i class="fas fa-fw fa-folder" aria-hidden="true"></i>
            <span>Reports</span>
        </a>
        <div id="collapseReports" class="collapse {{ $segments[0] == 'analytics' ? 'show' : '' }}" aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reports</h6>
                <a class="collapse-item {{ isActive($segments[1], 'downloads') }}" href="{{ url('admin/analytics/reports/downloads') }}">Downloads</a>
                <a class="collapse-item {{ isActive($segments[1], 'glossary') }}" href="{{ url('admin/analytics/reports/glossary') }}">Glossary View</a>
                <a class="collapse-item {{ isActive($segments[1], 'sitewide') }}" href="{{ url('admin/analytics/reports/sitewide') }}">Resource View</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Analytics</h6>
                <a class="collapse-item {{ isActive($segments[1], 'sitewide') }}" href="{{ url('admin/analytics/sitewide?is_bot=2') }}">Sitewide Analytics</a>
                <a class="collapse-item {{ isActive($segments[1], 'resource') }}" href="{{ url('admin/analytics/resource') }}">Resource Analytics</a>
                <a class="collapse-item {{ isActive($segments[1], 'glossary') }}" href="{{ url('admin/analytics/glossary?is_bot=2') }}">Glossary Analytics</a>
                <a class="collapse-item {{ isActive($segments[1], 'user') }}" href="{{ url('admin/analytics/user') }}">User Analytics</a>
                <a class="collapse-item {{ isActive($segments[1], 'index') }}" href="{{ url('admin/analytics/index') }}">DDL Analytics</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Settings Collapse Menu -->
    <li class="nav-item {{ in_array($segments[0], ['subscribers', 'contacts', 'menu', 'taxonomy', 'vocabulary', 'settings']) ? 'active-menu' : '' }}">
        <a class="nav-link {{ in_array($segments[0], ['subscribers', 'contacts', 'menu', 'taxonomy', 'vocabulary', 'settings']) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseSettings" aria-expanded="{{ in_array($segments[0], ['subscribers', 'contacts', 'menu', 'taxonomy', 'vocabulary', 'settings']) ? 'true' : 'false' }}" aria-controls="collapseSettings">
            <i class="fas fa-fw fa-folder" aria-hidden="true"></i>
            <span>Settings</span>
        </a>
        <div id="collapseSettings" class="collapse {{ in_array($segments[0], ['subscribers', 'contacts', 'menu', 'taxonomy', 'vocabulary', 'settings']) ? 'show' : '' }}" aria-labelledby="headingSettings" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Settings</h6>
                <a class="collapse-item {{ isActive($segments[0], 'subscribers') }}" href="{{ url('admin/subscribers') }}">Subscribers</a>
                <a class="collapse-item {{ isActive($segments[0], 'contacts') }}" href="{{ url('admin/contacts') }}">Contacts</a>
                <a class="collapse-item {{ isActive($segments[0], 'menu') }}" href="{{ url('admin/menu') }}">Menu</a>
                <a class="collapse-item {{ isActive($segments[0], 'taxonomy') }}" href="{{ url('admin/taxonomy') }}">Taxonomies</a>
                <a class="collapse-item {{ isActive($segments[0], 'vocabulary') }}" href="{{ url('admin/vocabulary') }}">Vocabulary</a>
                <a class="collapse-item {{ isActive($segments[0], 'settings') }}" href="{{ url('admin/settings') }}">Configurations</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Users -->
    <li class="nav-item {{ $segments[0] == 'users' ? 'active-menu' : '' }}">
        <a class="nav-link" href="{{ url('admin/users') }}">
            <i class="fas fa-fw fa-chart-area" aria-hidden="true"></i>
            <span>Users</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Translation Manager -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('translations') }}">
            <i class="fas fa-language" aria-hidden="true"></i>
            <span>Translation Manager</span>
        </a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</nav>
<!-- End of Sidebar -->
