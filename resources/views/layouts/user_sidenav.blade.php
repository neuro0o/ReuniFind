<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


<nav class="sidenav">
    <div class="sidenav-header">
        <img src="{{ asset('images/ReuniFind_Logo.svg') }}" alt="ReuniFind Logo" class="brand-logo">
        <h1 class="brand">ReuniFind</h1>
    </div>

    <ul class="nav-list">
        <li>
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="fas fa-home"></i> Home
            </a>
        </li>

        <!-- Collapsible: LOST & FOUND REPORT -->
        <li class="collapsible">
            <button class="collapsible-btn">
                <i class="fas fa-file-alt"></i> Lost & Found Report
                <i class="fas fa-chevron-right arrow"></i>
            </button>
            <ul class="collapsible-content">
                <li><a href="{{ route('report.lost') }}">Report Lost Item</a></li>
                <li><a href="{{ route('report.found') }}">Report Found Item</a></li>
                <li><a href="{{ route('report.view') }}">View All Reports</a></li>
                <li><a href="{{ route('report.matchmaking') }}">Report Matchmaking</a></li>
                <li><a href="{{ route('report.my') }}">My Reports</a></li>
            </ul>
        </li>

        <!-- Collapsible: CHAT & REQUEST -->
        <li class="collapsible">
            <button class="collapsible-btn">
                <i class="fas fa-comments"></i> Chat & Request
                <i class="fas fa-chevron-right arrow"></i>
            </button>
            <ul class="collapsible-content">
                <li><a href="{{ route('chat.claim') }}">Claim Request</a></li>
                <li><a href="{{ route('chat.return') }}">Return Request</a></li>
                <li><a href="{{ route('chat.private') }}">Private Chat</a></li>
            </ul>
        </li>

        <!-- Collapsible: DIGITAL ITEM TAG -->
        <li class="collapsible">
            <button class="collapsible-btn">
                <i class="fas fa-qrcode"></i> Digital Item Tag
                <i class="fas fa-chevron-right arrow"></i>
            </button>
            <ul class="collapsible-content">
                <li><a href="{{ route('tag.scan') }}">Scan Item Tag</a></li>
                <li><a href="{{ route('tag.register') }}">Register New Item</a></li>
                <li><a href="{{ route('tag.my') }}">My Item</a></li>
            </ul>
        </li>

        <!-- Collapsible: HELP CENTER -->
        <li class="collapsible">
            <button class="collapsible-btn">
                <i class="fas fa-question-circle"></i> Help Center
                <i class="fas fa-chevron-right arrow"></i>
            </button>
            <ul class="collapsible-content">
                <li><a href="{{ route('help.faq') }}">FAQ</a></li>
                <li><a href="{{ route('help.feedback') }}">Submit Feedback</a></li>
            </ul>
        </li>

        <li>
            <a href="{{ route('forum.index') }}" class="nav-link">
                <i class="fas fa-users"></i> Community Forum
            </a>
        </li>
    </ul>

    <div class="user-profile">
        <img src="{{ asset('images/default_user.png') }}" alt="User" class="user-pic">
        <span class="username">{{ Auth::user()->name ?? 'Guest' }}</span>
        <span class="email">{{ Auth::user()->email ?? 'neuro@gmail.com' }}</span>
    </div>

    <ul class="nav-section">
        <li><a href="{{ route('account.settings') }}"><i class="fas fa-user-cog"></i> Account Settings</a></li>
        <li><a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>

<!-- Collapsible JS -->
<script>
document.querySelectorAll('.collapsible-btn').forEach(button => {
  button.addEventListener('click', () => {
    const content = button.nextElementSibling;
    button.classList.toggle('active');
    content.classList.toggle('open');
  });
});
</script>
