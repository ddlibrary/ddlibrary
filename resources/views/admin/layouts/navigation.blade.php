<style>
    .active-item {
        background-color: #eaecf4;
        margin: 2px 8px !important;
    }

    .active-menu {
        background: #3030a3 !important;
    }
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('admin') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-tools"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin Panel</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    @php

        function isActiveItem($segment, $expected)
        {
            return $segment == $expected ? 'active-item' : '';
        }
        
        $segment3 = request()->segment(3);
        $segment4 = request()->segment(4);
        $segment5 = request()->segment(5);

        $navPages = in_array($segment3, ['pages', 'news']);
        $navSurveys = in_array($segment3, ['surveys', 'survey_questions', 'survey_time']);
        $navSettings = in_array($segment3, ['subscribers', 'contacts', 'menu', 'taxonomy', 'vocabulary', 'settings']);
        $navReports = 'analytics';
    @endphp
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active {{ request()->segment(2) == 'admin' && !$segment3 ? 'active-menu' : '' }}">
        <a class="nav-link" href="{{ url('admin') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ $segment3 == 'resources' ? 'active-menu' : '' }}">
        <a class="nav-link {{ $segment3 == 'resources' ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Resources</span>
        </a>
        <div id="collapseTwo" class="collapse {{ $segment3 == 'resources' ? 'show' : '' }}" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Resources</h6>
                <a class="collapse-item {{ isActiveItem($segment4, 'index') }}" href="{{ url('admin/resources/index') }}">Resources</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'comments') }}" href="{{ url('admin/resources/comments') }}">Comments</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'flags') }}" href="{{ url('admin/resources/flags') }}">Flags</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'resource-images') }}" href="{{ url('admin/resources/resource-images') }}">Resource Images</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li
        class="nav-item {{ $navSurveys ? 'active-menu' : '' }}">
        <a class="nav-link {{ $navSurveys ? '' : 'collapsed' }}"
            href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true"
            aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Surveys</span>
        </a>
        <div id="collapseUtilities"
            class="collapse {{ $navSurveys ? 'show' : '' }}"
            aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Surveys</h6>
                <a class="collapse-item {{ isActiveItem($segment3, 'surveys') }}" href="{{ url('admin/surveys') }}">Surveys</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'survey_questions') }}" href="{{ url('admin/survey_questions') }}">Survey Results</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'survey_time') }}" href="{{ url('admin/survey_time') }}">Survey Settings</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ $navPages ? 'active-menu' : '' }}">
        <a class="nav-link {{ $navPages ? '' : 'collapsed' }}" href="#"
            data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Pages</span>
        </a>
        <div id="collapsePages" class="collapse {{ $navPages ? 'show' : '' }}"
            aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Pages & News</h6>
                <a class="collapse-item {{ isActiveItem($segment3, 'pages') }}" href="{{ url('admin/pages') }}">Pages</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'news') }}" href="{{ url('admin/news') }}">News</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ $segment3 == 'glossary_subjects' ? 'active-menu' : '' }}">
        <a class="nav-link {{ $segment3 == 'glossary_subjects' ? '' : 'collapsed' }}" href="#"
            data-toggle="collapse" data-target="#glossary" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Glossary</span>
        </a>
        <div id="glossary" class="collapse {{ $segment3 == 'glossary_subjects' ? 'show' : '' }}"
            aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Glossary</h6>
                <a class="collapse-item {{ isActiveItem($segment3, 'glossary_subjects') }}" href="{{ url('admin/glossary_subjects') }}">Subjects</a>
            </div>
        </div>
    </li>
  
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ $segment3 == $navReports ? 'active-menu' : '' }}">
        <a class="nav-link  {{ $segment3 == $navReports ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#reports" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Reports</span>
        </a>
        <div id="reports" class="collapse  {{ $segment3 == $navReports ? 'show' : '' }}" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reports</h6>
                <a class="collapse-item {{ isActiveItem($segment5, 'downloads') }}" href="{{ url('admin/analytics/reports/downloads') }}">Downloads</a>
                <a class="collapse-item {{ isActiveItem($segment5, 'glossary') }}" href="{{ url('admin/analytics/reports/glossary') }}">Glossary View</a>
                <a class="collapse-item {{ isActiveItem($segment5, 'sitewide') }}" href="{{ url('admin/analytics/reports/sitewide') }}">Resource View</a>
                <div class="collapse-divider"></div>
                <h6 class="collapse-header">Analytics</h6>
                <a class="collapse-item {{ isActiveItem($segment4, 'sitewide') }}" href="{{ url('admin/analytics/sitewide?is_bot=2') }}">Sitewide Analytics</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'resource') }}" href="{{ url('admin/analytics/resource') }}">Resource Analytics</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'glossary') }}" href="{{ url('admin/analytics/glossary?is_bot=2') }}">Glossary Analytics</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'user') }}" href="{{ url('admin/analytics/user') }}">User Analytics</a>
                <a class="collapse-item {{ isActiveItem($segment4, 'index') }}" href="{{ url('admin/analytics/index') }}">DDL Analytics</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    
    <li class="nav-item {{ $navSettings ? 'active-menu' : '' }}">
        <a class="nav-link  {{ $navSettings ? '' : 'collapsed' }}" href="#" data-toggle="collapse"
            data-target="#settings" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Settings</span>
        </a>
        <div id="settings" class="collapse  {{ $navSettings ? 'show' : '' }}" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Settings</h6>
                <a class="collapse-item {{ isActiveItem($segment3, 'subscribers') }}" href="{{ url('admin/subscribers') }}">Subscribers</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'contacts') }}" href="{{ url('admin/contacts') }}">Contacts</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'menu') }}" href="{{ url('admin/menu') }}">Menu</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'taxonomy') }}" href="{{ url('admin/taxonomy') }}">Taxonomys</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'vocabulary') }}" href="{{ url('admin/vocabulary') }}">Vocabulary</a>
                <a class="collapse-item {{ isActiveItem($segment3, 'settings') }}" href="{{ url('admin/settings') }}">Configurations</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Charts -->
    <li class="nav-item  {{ $segment3 == 'users' ? 'active-menu' : '' }}">
        <div class="item-activ">

            <a class="nav-link" href="{{ url('admin/users') }}">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Users</span></a>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Translation -->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('translations') }}">
            <i class="fas fa-language"></i>
            <span>Translation manager</span></a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
