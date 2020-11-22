<!-- membuat navigation & dropdown -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <a class="navbar-brand" href="../user/index.php">SmartPoultry</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <div class="container">
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item">
                    <a class="nav-link" href="../user/index.php"><i class="fas fa-tachometer-alt"></i> Dashboard<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/view-data-schedule.php"><i class="fas fa-clock"></i> Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../user/about.php"><i class="fas fa-portrait"></i> About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php" onclick="return confirm('Yakin untuk logout?');"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
	        </ul>
        </div>
    </div>
</nav>