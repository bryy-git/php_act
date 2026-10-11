<?php
include('dbconn.php');

if (isset($_POST['add_user'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (!empty($name) && !empty($email)) {
        $query = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php?msg=added");
            exit();
        }
    }
}

if (isset($_POST['update_user'])) {
    $id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE users SET name = '$name', email = '$email' WHERE id = '$id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: index.php?msg=updated");
        exit();
    }
}

$is_edit = false;
$edit_name = "";
$edit_email = "";
$edit_id = "";

if (isset($_GET['edit_id']) && !empty($_GET['edit_id'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit_id']);
    $fetch_query = "SELECT * FROM users WHERE id = '$edit_id'";
    $fetch_result = mysqli_query($conn, $fetch_query);

    if (mysqli_num_rows($fetch_result) > 0) {
        $edit_data = mysqli_fetch_assoc($fetch_result);
        $is_edit = true;
        $edit_name = $edit_data['name'];
        $edit_email = $edit_data['email'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4 text-center fw-bold">User Management System</h2>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-transparent border-0 pt-3 pb-0 fw-medium fs-5">
                    <?= $is_edit ? 'Add New User'  : 'Add New User'; ?>
                </div>

                <div class="card-body">
                    <?php if (isset($_GET['msg'])): ?>
                        <?php if ($_GET['msg'] == 'added'): ?>
                            <div class="alert alert-success alert-dismissible fade show border-0 py-2 fs-6 mb-3" role="alert">
                                User added successfully!
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($_GET['msg'] == 'updated'): ?>
                            <div class="alert alert-success alert-dismissible fade show border-0 py-2 fs-6 mb-3" role="alert">
                                User updated successfully!
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        <?php elseif ($_GET['msg'] == 'deleted'): ?>
                            <div class="alert alert-danger alert-dismissible fade show border-0 py-2 fs-6 mb-3" role="alert">
                                User deleted successfully!
                                <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="index.php" method="POST" autocomplete="on" onsubmit="return confirmAction(<?= $is_edit ? '\'update\'' : '\'add\''; ?>)">
                        <?php if ($is_edit): ?>
                            <input type="hidden" name="user_id" value="<?= $edit_id; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label text-secondary small">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Enter Full Name" value="<?= htmlspecialchars($edit_name); ?>" autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary small">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="Enter Email Address" value="<?= htmlspecialchars($edit_email); ?>" autocomplete="email">
                        </div>

                        <?php if ($is_edit): ?>
                            <div class="d-flex gap-2">
                                <button type="submit" name="update_user" class="btn btn-primary w-100 fw-medium">Submit</button>
                            </div>
                        <?php else: ?>
                            <button type="submit" name="add_user" class="btn btn-primary w-100 fw-medium">Submit</button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-transparent border-0 pt-3 pb-0 fw-medium fs-5">
                    Registered Users
                </div>
                <div class="card-body p-3">
                    <table class="table table-borderless table-hover mb-0 align-middle">
                        <thead class="bg-transparent border-bottom">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM users ORDER BY id ASC";
                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0):
                                $i = 1; 
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                    <tr class="border-bottom">
                                        <td><?= $i++; ?></td>
                                        <td><?= htmlspecialchars($row['name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= date('F j, Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <a href="index.php?edit_id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                            <a href="delete.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                        </td>
                                    </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No data found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmAction(type) {
        if (type === 'add') {
            return confirm('Are you sure you want to add this user?');
        } else if (type === 'update') {
            return confirm('Are you sure you want to update this user?');
        }
        return true;
    }
</script>
</body>
</html>