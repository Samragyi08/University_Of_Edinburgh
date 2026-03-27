<?php include 'db.php'; ?>

<?php
$level = isset($_GET['level']) ? (int)$_GET['level'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT Programmes.*, Levels.LevelName, Staff.Name AS LeaderName
        FROM Programmes
        LEFT JOIN Levels ON Programmes.LevelID = Levels.LevelID
        LEFT JOIN Staff ON Programmes.ProgrammeLeaderID = Staff.StaffID
        WHERE Programmes.Published = 1";

if ($level > 0) {
    $sql .= " AND Programmes.LevelID = $level";
}

if ($search !== '') {
    $safeSearch = $conn->real_escape_string($search);
    $sql .= " AND (Programmes.ProgrammeName LIKE '%$safeSearch%' OR Programmes.Description LIKE '%$safeSearch%')";
}

$sql .= " ORDER BY Programmes.ProgrammeName";
$result = $conn->query($sql);

$staffSql = "SELECT * FROM Staff ORDER BY StaffID LIMIT 3";
$staffResult = $conn->query($staffSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="#programmes">Programmes</a>
            <a href="#about">About</a>
            <a href="#faculty">Faculty</a>
            <a href="admin/login.php">Admin</a>
        </nav>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h1>Explore your future at university</h1>
        <p>Discover undergraduate and postgraduate programmes, explore modules by year, meet expert staff, and register your interest for updates and events.</p>
        <div class="hero-buttons">
            <a class="btn-primary" href="#programmes">Explore Programmes</a>
            <a class="btn-secondary" href="#about">Learn More</a>
        </div>
    </div>
</section>

<div class="container">
    <div class="search-box">
        <form method="GET" action="index.php">
            <div class="search-grid">
                <div>
                    <label for="level">Filter by level</label>
                    <select name="level" id="level">
                        <option value="">All levels</option>
                        <option value="1" <?php if ($level === 1) echo 'selected'; ?>>Undergraduate</option>
                        <option value="2" <?php if ($level === 2) echo 'selected'; ?>>Postgraduate</option>
                    </select>
                </div>

                <div>
                    <label for="search">Search Programmes</label>
                    <input type="text" name="search" id="search" placeholder="Search programmes" value="<?php echo htmlspecialchars($search); ?>">
                </div>

                <div class="search-button-wrap">
                    <button type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

<section class="section" id="programmes">
    <div class="container">
        <h2 class="section-title">Available Programmes</h2>
        <p class="section-text">Browse our available courses and find the one that matches your interests.</p>

        <div class="card-grid">
            <?php if ($result && $result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <a class="card-link" href="programme.php?id=<?php echo $row['ProgrammeID']; ?>">
                        <article class="card">
                            <img class="card-image" src="<?php echo htmlspecialchars($row['Image']); ?>" alt="<?php echo htmlspecialchars($row['ProgrammeName']); ?>">
                            <div class="card-body">
                                <div class="card-meta"><?php echo htmlspecialchars($row['LevelName']); ?></div>
                                <h3><?php echo htmlspecialchars($row['ProgrammeName']); ?></h3>
                                <p><?php echo htmlspecialchars($row['Description']); ?></p>
                                <p class="small"><strong>Programme leader:</strong> <?php echo htmlspecialchars($row['LeaderName']); ?></p>
                                <span class="button card-button">View Programme</span>
                            </div>
                        </article>
                    </a>
                <?php } ?>
            <?php } else { ?>
                <p>No programmes found.</p>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section light-section" id="about">
    <div class="container two-column">
        <div>
            <h2 class="section-title">About the University</h2>
            <p>The University of Edinburgh website is designed to help prospective students explore programmes, learn about modules, and register interest easily.</p>
            <p>The system supports course discovery, staff information, and student communication for future open days and updates.</p>
        </div>

        <div class="info-box">
            <h3>Why choose us?</h3>
            <ul class="feature-list">
                <li>Undergraduate and postgraduate study options</li>
                <li>Experienced academic staff</li>
                <li>Clear programme structures</li>
                <li>Simple and accessible course information</li>
            </ul>
        </div>
    </div>
</section>

<section class="section" id="faculty">
    <div class="container">
        <h2 class="section-title">Meet Our Faculty</h2>
        <p class="section-text">Learn from experienced academic staff and programme leaders.</p>

        <div class="faculty-grid">
            <?php if ($staffResult && $staffResult->num_rows > 0) { ?>
                <?php while ($staff = $staffResult->fetch_assoc()) { ?>
                    <div class="faculty-card">
                        <img class="faculty-image" src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80" alt="<?php echo htmlspecialchars($staff['Name']); ?>">
                        <div class="faculty-body">
                            <h3><?php echo htmlspecialchars($staff['Name']); ?></h3>
                            <p class="small"><strong><?php echo htmlspecialchars($staff['JobTitle']); ?></strong></p>
                            <p><?php echo htmlspecialchars($staff['Bio']); ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>

<section class="section light-section">
    <div class="container">
        <h2 class="section-title">Administration Area</h2>
        <p class="section-text">The admin area allows authorised staff to manage programmes, modules, and student registrations.</p>

        <div class="admin-feature-grid">
            <div class="info-box">
                <h3>Programme Management</h3>
                <p>Create, update, publish, and delete programmes.</p>
            </div>
            <div class="info-box">
                <h3>Student Records</h3>
                <p>View, add, edit, delete, and export student registrations.</p>
            </div>
            <div class="info-box">
                <h3>Module Management</h3>
                <p>Add, update, and delete modules and assign leaders.</p>
            </div>
        </div>

        <p style="margin-top: 20px;">
            <a class="button" href="admin/login.php">Go to Admin Area</a>
        </p>
    </div>
</section>

<footer class="footer">
    <div class="container footer-grid">
        <div>
            <h3>UNIVERSITY OF EDINBURGH</h3>
            <p>A university course discovery platform for prospective students.</p>
        </div>
        <div>
            <h4>Explore</h4>
            <p><a href="#programmes">Programmes</a></p>
            <p><a href="#about">About</a></p>
            <p><a href="#faculty">Faculty</a></p>
        </div>
        <div>
            <h4>Contact</h4>
            <p>Email: admissions@example.ac.uk</p>
            <p>Phone: +44 20 1234 5678</p>
        </div>
    </div>
</footer>

</body>
</html>
