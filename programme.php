<?php include 'db.php'; ?>

<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT Programmes.*, Levels.LevelName, Staff.Name AS ProgrammeLeaderName
        FROM Programmes
        LEFT JOIN Levels ON Programmes.LevelID = Levels.LevelID
        LEFT JOIN Staff ON Programmes.ProgrammeLeaderID = Staff.StaffID
        WHERE Programmes.ProgrammeID = $id AND Programmes.Published = 1";

$result = $conn->query($sql);
$programme = $result->fetch_assoc();

if (!$programme) {
    die("Programme not found.");
}

$modulesSql = "SELECT Modules.ModuleName, Modules.Description, Modules.Image, ProgrammeModules.Year, Staff.Name AS ModuleLeaderName
               FROM ProgrammeModules
               JOIN Modules ON ProgrammeModules.ModuleID = Modules.ModuleID
               LEFT JOIN Staff ON Modules.ModuleLeaderID = Staff.StaffID
               WHERE ProgrammeModules.ProgrammeID = $id
               ORDER BY ProgrammeModules.Year, Modules.ModuleName";
$modulesResult = $conn->query($modulesSql);

$staffSql = "SELECT DISTINCT Staff.Name, Staff.JobTitle, Staff.Bio
             FROM ProgrammeModules
             JOIN Modules ON ProgrammeModules.ModuleID = Modules.ModuleID
             JOIN Staff ON Modules.ModuleLeaderID = Staff.StaffID
             WHERE ProgrammeModules.ProgrammeID = $id";
$staffResult = $conn->query($staffSql);

$modulesByYear = [];
if ($modulesResult) {
    while ($module = $modulesResult->fetch_assoc()) {
        $modulesByYear[$module['Year']][] = $module;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($programme['ProgrammeName']); ?> - UNIVERSITY OF EDINBURGH</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="topbar">
    <div class="container nav">
        <div class="brand">UNIVERSITY OF EDINBURGH</div>
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="index.php#programmes">Programmes</a>
            <a href="admin/login.php">Admin</a>
        </nav>
    </div>
</header>

<section class="detail-hero" style="background-image: linear-gradient(rgba(13,45,82,0.82), rgba(13,45,82,0.82)), url('<?php echo htmlspecialchars($programme['Image']); ?>');">
    <div class="container">
        <p><?php echo htmlspecialchars($programme['LevelName']); ?></p>
        <h1><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h1>
        <p><?php echo htmlspecialchars($programme['Description']); ?></p>
        <p><strong>Programme leader:</strong> <?php echo htmlspecialchars($programme['ProgrammeLeaderName']); ?></p>
    </div>
</section>

<div class="container">
    <div class="detail-layout">
        <main>
            <section class="panel">
                <h2>About this programme</h2>
                <p><?php echo htmlspecialchars($programme['Description']); ?></p>

                <div class="highlight-grid">
                    <div class="info-box">
                        <h3>Study Level</h3>
                        <p><?php echo htmlspecialchars($programme['LevelName']); ?></p>
                    </div>
                    <div class="info-box">
                        <h3>Programme Leader</h3>
                        <p><?php echo htmlspecialchars($programme['ProgrammeLeaderName']); ?></p>
                    </div>
                    <div class="info-box">
                        <h3>Learning Style</h3>
                        <p>Lectures, workshops, coursework and independent study.</p>
                    </div>
                </div>
            </section>

<h2>Course Highlights</h2>
<ul>
<li>Learn through lectures and practical work</li>
<li>Develop technical and problem-solving skills</li>
<li>Prepare for industry careers</li>
</ul>

            <section class="panel">
                <h2>Modules by year</h2>
                <?php if (!empty($modulesByYear)) { ?>
                    <?php foreach ($modulesByYear as $year => $items) { ?>
                        <div class="year-group">
                            <h3>Year <?php echo $year; ?></h3>
                            <?php foreach ($items as $item) { ?>
                                <div class="module-item">
                                    <h4><?php echo htmlspecialchars($item['ModuleName']); ?></h4>
                                    <p><?php echo htmlspecialchars($item['Description']); ?></p>
                                    <p class="small"><strong>Module leader:</strong> <?php echo htmlspecialchars($item['ModuleLeaderName']); ?></p>
                                    <p class="small"><strong>Shared across programmes:</strong>
                                        <?php
                                        $moduleName = $conn->real_escape_string($item['ModuleName']);
                                        $sharedSql = "SELECT COUNT(*) AS TotalCount FROM ProgrammeModules
                                                      JOIN Modules ON ProgrammeModules.ModuleID = Modules.ModuleID
                                                      WHERE Modules.ModuleName = '$moduleName'";
                                        $sharedResult = $conn->query($sharedSql);
                                        $shared = $sharedResult->fetch_assoc();
                                        echo ($shared['TotalCount'] > 1) ? 'Yes' : 'No';
                                        ?>
                                    </p>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No modules available for this programme.</p>
                <?php } ?>
            </section>

            <section class="panel">
                <h2>Teaching Staff</h2>
                <?php if ($staffResult && $staffResult->num_rows > 0) { ?>
                    <?php while ($staff = $staffResult->fetch_assoc()) { ?>
                        <div class="staff-item">
                            <h4><?php echo htmlspecialchars($staff['Name']); ?></h4>
                            <p><strong><?php echo htmlspecialchars($staff['JobTitle']); ?></strong></p>
                            <p><?php echo htmlspecialchars($staff['Bio']); ?></p>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No staff information available.</p>
                <?php } ?>
            </section>
        </main>

        <aside>
            <section class="panel">
                <h2>Register your interest</h2>
                <form action="register.php" method="POST">
                    <input type="hidden" name="programme_id" value="<?php echo $programme['ProgrammeID']; ?>">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" required>
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" required>
                    <button type="submit">Register interest</button>
                </form>
            </section>

            <section class="panel">
                <h2>Withdraw interest</h2>
                <form action="withdraw.php" method="POST">
                    <input type="hidden" name="programme_id" value="<?php echo $programme['ProgrammeID']; ?>">
                    <label for="withdraw_email">Email address</label>
                    <input type="email" id="withdraw_email" name="email" required>
                    <button type="submit">Withdraw</button>
                </form>
            </section>
        </aside>
    </div>
</div>

</body>
</html>
