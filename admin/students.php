<?php
require_once "../backend/admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */

$q = trim($_GET['q'] ?? '');
$dept = trim($_GET['dept'] ?? '');

$conditions = ["u.role = 'student'"];
$params = [];
$types = "";

if ($q !== '') {
    $conditions[] = "(u.full_name LIKE ? OR u.email LIKE ? OR u.roll_number LIKE ?)";
    $search_param = '%' . $q . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if ($dept !== '') {
    $conditions[] = "u.department = ?";
    $params[] = $dept;
    $types .= "s";
}

$where_clause = implode(" AND ", $conditions);

$sql = "SELECT u.id, u.full_name, u.email, u.roll_number,
               u.phone, u.department, u.batch, u.semester, u.created_at,
               GROUP_CONCAT(DISTINCT sup.full_name SEPARATOR ', ') AS supervisor_name
        FROM users u
        LEFT JOIN projects p ON p.student_id = u.id
        LEFT JOIN supervisor_assignments sa ON sa.project_id = p.id
        LEFT JOIN users sup ON sa.supervisor_id = sup.id
        WHERE $where_clause
        GROUP BY u.id
        ORDER BY u.created_at DESC";

if (!empty($params)) {
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Database error preparing query.");
    }
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Database error loading students.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Mentra</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="page-wrapper">
<main class="container">
    <h1>Students</h1>
    <p class="subtitle">Manage registered students and track supervisor assignments</p>

    <div class="table-toolbar">
        <form method="GET" action="students.php" class="search-form">
            <input type="search" name="q" placeholder="Search name, email, roll no..." value="<?php echo htmlspecialchars($q); ?>">
            
            <select name="dept" style="width: 170px;" onchange="this.form.submit()">
                <option value="">All Departments</option>
                <option value="BCA" <?php if ($dept === 'BCA') echo 'selected'; ?>>BCA</option>
                <option value="BIT" <?php if ($dept === 'BIT') echo 'selected'; ?>>BIT</option>
                <option value="BSc CSIT" <?php if ($dept === 'BSc CSIT') echo 'selected'; ?>>BSc CSIT</option>
            </select>

            <button type="submit" class="btn-secondary" style="margin-top: 0; width: auto; padding: 11px 18px;">Filter</button>

            <?php if ($q !== '' || $dept !== ''): ?>
                <a href="students.php" class="btn-secondary" style="margin-top: 0;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Roll Number</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Assigned Supervisor</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($student = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($student['full_name']); ?></strong>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($student['roll_number'] ?: '—'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($student['email']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($student['department'] ?? '—'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($student['phone'] ?: '—'); ?>
                        </td>
                        <td>
                            <?php if (!empty($student['supervisor_name'])): ?>
                                <?php echo htmlspecialchars($student['supervisor_name']); ?>
                            <?php else: ?>
                                <span class="not-assigned">Not Assigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="student_detail.php?id=<?php echo (int) $student['id']; ?>" class="action-link action-view">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #7a9abf; padding: 30px;">
                        No students found matching your criteria.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</div>

</body>
</html>