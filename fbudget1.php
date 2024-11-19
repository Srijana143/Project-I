<?php
// Include your database connection
include('connect.php');

// Handle Add and Update Actions
if (isset($_POST['submit']) || isset($_POST['update'])) {
    $category = $_POST['category'];
    $amount = $_POST['amount'];

    // Sanitize inputs
    $category = mysqli_real_escape_string($conn, $category);
    $amount = mysqli_real_escape_string($conn, $amount);

    // Handle Add New Record (Insert)
    if (isset($_POST['submit'])) {
        // Check if category already exists
        $sql_check = "SELECT * FROM budgets WHERE category = '$category'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            echo "Error: The category already exists.";
        } else {
            $sql_insert = "INSERT INTO budgets (category, amount) VALUES ('$category', '$amount')";
            if (mysqli_query($conn, $sql_insert)) {
                header("Location: fbudget.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }

    // Handle Update Record
    if (isset($_POST['update']) && isset($_POST['id'])) {
        $id = (int)$_POST['id']; // Ensure ID is an integer
        // Check if category already exists (excluding the current record)
        $sql_check_update = "SELECT * FROM budgets WHERE category = '$category' AND id != $id";
        $result_check_update = mysqli_query($conn, $sql_check_update);

        if (mysqli_num_rows($result_check_update) > 0) {
            echo "Error: The category already exists.";
        } else {
            $sql_update = "UPDATE budgets SET category = '$category', amount = '$amount' WHERE id = $id";
            if (mysqli_query($conn, $sql_update)) {
                header("Location: fbudget.php");
                exit();
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Handle Delete Action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Ensure ID is an integer
    $sql_delete = "DELETE FROM budgets WHERE id = $id";
    if (mysqli_query($conn, $sql_delete)) {
        header("Location: fbudget.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
