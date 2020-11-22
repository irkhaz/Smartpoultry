<!-- membuat navigation & dropdown -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="../admin/index.php">SmartPoultry</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <div class="container">
            <ul class="navbar-nav justify-content-end navbar-right">
                <li class="nav-item">
                    <a class="nav-link" href="../admin/index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/view-data-user.php"><i class="fas fa-users"></i> User Manager</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/view-data-schedule.php"><i class="fas fa-clock"></i> Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/about.php"><i class="fas fa-portrait"></i> About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" onclick="return confirm('Yakin untuk logout?');" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
	        </ul>
        </div>
    </div>
</nav>