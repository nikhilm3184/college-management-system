<?php
session_start();
require('database.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['username'];


$sql2 = "SELECT * FROM User WHERE USERNAME=?";
$stmt = $conn->prepare($sql2);
$stmt->bind_param("s", $user);
$stmt->execute();
$result2 = $stmt->get_result();
$currentUser = $result2->fetch_assoc();
$stmt->close();

if ($currentUser['STATUS'] != 1) {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'toggle' && isset($_POST['user_id'])) {
        $userId = (int)$_POST['user_id'];
        $authStatus = (int)$_POST['auth_status'];
        $newAuthStatus = $authStatus ? 0 : 1;

        $update = $conn->prepare("UPDATE User SET is_authorized = ? WHERE id = ?");
        $update->bind_param("ii", $newAuthStatus, $userId);
        $update->execute();
        $update->close();
        header("Location: admin.php");
        exit;
    }

    if ($_POST['action'] === 'delete' && isset($_POST['user_id'])) {
        $userId = (int)$_POST['user_id'];
        $delete = $conn->prepare("DELETE FROM User WHERE id = ?");
        $delete->bind_param("i", $userId);
        $delete->execute();
        $delete->close();
        header("Location: admin.php");
        exit;
    }

    if (isset($_POST['action']) && ($_POST['action'] === 'approve' || $_POST['action'] === 'reject') && isset($_POST['pending_student_id'])) {
        $pendingStudentId = (int)$_POST['pending_student_id'];
        $action = $_POST['action'];

        
        $stmtPending = $conn->prepare("SELECT * FROM PendingStudents WHERE ID = ?");
        $stmtPending->bind_param("i", $pendingStudentId);
        $stmtPending->execute();
        $pendingStudent = $stmtPending->get_result()->fetch_assoc();
        $stmtPending->close();

        if ($pendingStudent) {
            if ($action === 'approve') {
                
                $stmtInsert = $conn->prepare("INSERT INTO Student (NAME, AGE, EMAIL, COURSE, GENDER) VALUES ( ?, ?, ?, ?, ?)");
                $stmtInsert->bind_param(
                    "sisss",
                    $pendingStudent['NAME'],
                    $pendingStudent['AGE'],
                    $pendingStudent['EMAIL'],
                    $pendingStudent['COURSE'],
                    $pendingStudent['GENDER']
                );
                $stmtInsert->execute();
            
                $stmtInsert->close();

                
                $stmtUpdate = $conn->prepare("UPDATE PendingStudents SET APPROVAL_STATUS = 'approved' WHERE ID = ?");
                $stmtUpdate->bind_param("i", $pendingStudentId);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            } elseif ($action === 'reject') {
                $stmtReject = $conn->prepare("UPDATE PendingStudents SET APPROVAL_STATUS = 'rejected' WHERE ID = ?");
                $stmtReject->bind_param("i", $pendingStudentId);
                $stmtReject->execute();
                $stmtReject->close();
            }
        }
        header("Location: admin.php");
        exit;
    }
}


$resultUsers = $conn->query("SELECT id, email, username, is_authorized, status FROM User");
$resultPendingStudents = $conn->query("SELECT * FROM PendingStudents WHERE APPROVAL_STATUS = 'pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
            margin-bottom: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #343a40;
            color: white;
        }
        .toggle-btn, .edit-btn, .delete-btn, .approve-btn, .reject-btn {
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            margin: 2px;
            font-size: 0.9em;
        }
        .toggle-btn { background-color: #17a2b8; }
        .edit-btn { background-color: #ffc107; }
        .delete-btn, .reject-btn { background-color: #dc3545; }
        .approve-btn { background-color: #28a745; }

        .toggle-btn:hover { background-color: #138496; }
        .edit-btn:hover { background-color: #e0a800; }
        .delete-btn:hover, .reject-btn:hover { background-color: #c82333; }
        .approve-btn:hover { background-color: #218838; }

        .back {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
            font-size: 1.1em;
        }
        .back:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const idInput = document.createElement('input');
                idInput.name = 'user_id';
                idInput.value = userId;
                form.appendChild(idInput);

                const actionInput = document.createElement('input');
                actionInput.name = 'action';
                actionInput.value = 'delete';
                form.appendChild(actionInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body>

<h2>Admin Management - User Accounts</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Username</th>
        <th>Is Authorized</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($row = $resultUsers->fetch_assoc()):
        if ($row['status'] == 1) continue; ?>
        <tr>
            <td><?= htmlspecialchars($row['id']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= $row['is_authorized'] ? '1' : '0' ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="auth_status" value="<?= $row['is_authorized'] ?>">
                    <input type="hidden" name="action" value="toggle">
                    <button type="submit" class="toggle-btn">
                        <?= $row['is_authorized'] ? 'Revoke Access' : 'Grant Access' ?>
                    </button>
                </form>

                <form action="edituser.php" method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="edit-btn">Edit</button>
                </form>

                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="delete-btn">Delete</button>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>Pending Student Approvals</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Email</th>
        <th>Course</th>
        <th>Gender</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php while ($student = $resultPendingStudents->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($student['ID']) ?></td>
            <td><?= htmlspecialchars($student['NAME']) ?></td>
            <td><?= htmlspecialchars($student['AGE']) ?></td>
            <td><?= htmlspecialchars($student['EMAIL']) ?></td>
            <td><?= htmlspecialchars($student['COURSE']) ?></td>
            <td><?= htmlspecialchars($student['GENDER']) ?></td>
            <td><?= htmlspecialchars($student['APPROVAL_STATUS']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="pending_student_id" value="<?= $student['ID'] ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="approve-btn">Approve</button>
                </form>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="pending_student_id" value="<?= $student['ID'] ?>">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="reject-btn">Reject</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<a class="back" href="homepage.php">← Back to Home Page</a>

</body>
</html>

<?php $conn->close(); ?>
