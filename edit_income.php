<?php
include("session.php");

$notification = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_income'])) {
    $income_id = $_POST['income_id'];
    $new_amount = $_POST['income_amount'];

    // Ensure input is valid (no negative or zero values)
    if ($new_amount <= 0) {
        $notification = "error|Amount must be greater than zero!";
    } else {
        // Update income in the database
        $query = "UPDATE income SET amount = '$new_amount' WHERE income_id = '$income_id' AND user_id = '$userid'";
        if (mysqli_query($con, $query)) {
            $notification = "success|Income updated successfully!";
        } else {
            $notification = "error|Error updating income.";
        }
    }
}

// Check if delete is requested
if (isset($_GET['delete'])) {
    $income_id = $_GET['delete'];

    // Delete the selected income
    $query = "DELETE FROM income WHERE income_id = '$income_id' AND user_id = '$userid'";
    if (mysqli_query($con, $query)) {
        $notification = "success|Income deleted successfully!";
    } else {
        $notification = "error|Error deleting income.";
    }
}

// Fetch all income records for the user
$income_fetched = mysqli_query($con, "SELECT * FROM income WHERE user_id = '$userid'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Income</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
    /* Add hover effects */
    .btn-primary:hover {
        background-color: #004085;
        transform: scale(1.05);
    }

    .btn-danger:hover {
        background-color: #c82333;
        transform: scale(1.05);
    }

    /* Table animations */
    .table tbody tr {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center">Edit Income</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Amount (₹)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($income_fetched)) { ?>
                    <tr>
                        <form method="post">
                            <td>
                                <input type="number" class="form-control" name="income_amount" 
                                    value="<?php echo $row['amount']; ?>" required min="1">
                                <input type="hidden" name="income_id" value="<?php echo $row['income_id']; ?>">
                            </td>
                            <td>
                                <button type="submit" name="update_income" class="btn btn-primary btn-sm">Update</button>
                                <a href="javascript:void(0);"
                                    class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $row['income_id']; ?>)">Delete</a>
                            </td>
                        </form>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="manage_income.php" class="btn btn-secondary">Back to Manage Income</a>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (!empty($notification)) {
            list($type, $message) = explode("|", $notification); ?>
            Swal.fire({
                title: "Manage Income",
                text: "<?php echo $message; ?>",
                icon: "<?php echo $type; ?>"
            }).then(() => {
                window.location.href = "manage_income.php";
            });
        <?php } ?>
    });
    </script>
    <script>
    function confirmDelete(incomeId) {
        Swal.fire({
            title: "Manage Income",
            text: "Are you sure you want to delete this income?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "edit_income.php?delete=" + incomeId;
            }
        });
    }
    </script>

</body>
</html>
