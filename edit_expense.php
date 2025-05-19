<?php
include("session.php");
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $new_amount = $_POST['expense_amount'];
    $query = "UPDATE expenses SET expense='$new_amount' WHERE id='$id' AND user_id='$userid'";
    mysqli_query($con, $query);
    header("Location: manage_expense.php");
}

$id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM expenses WHERE id='$id' AND user_id='$userid'");
$row = mysqli_fetch_array($result);
?>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <input type="number" name="expense_amount" value="<?php echo $row['expense']; ?>" required>
    <button type="submit" name="update">Update</button>
</form>