<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Budget Planner</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #333;
            font-size: 20px;
            margin-top: 0;
        }

        .tbudget h2 {
            color: #007bff;
            font-size: 22px;
            margin-top: 10px;
        }

        .tbudget {
            text-align: right;
            margin-bottom: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
            color: #555;
        }

        td {
            color: #333;
        }

        /* Button Styles */
        .add-btn, .edit-btn, .delete-btn {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .add-btn {
            background-color: #28a745;
            color: white;
            border: none;
            margin-bottom: 20px;
            text-align: center;
        }

        .add-btn:hover {
            background-color: #218838;
        }

        .edit-btn {
            background-color: #ffc107;
            color: white;
            border: none;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* Form Styles */
        .addrecord {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .addrecord form {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .addrecord input[type="text"],
        .addrecord input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .addrecord button[type="submit"],
        .addrecord button[type="reset"] {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .addrecord button[type="submit"] {
            background-color: #007bff;
            color: white;
        }

        .addrecord button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .addrecord button[type="reset"] {
            background-color: #6c757d;
            color: white;
        }

        .addrecord button[type="reset"]:hover {
            background-color: #5a6268;
        }
    </style>
    <script>
        function toggleForm() {
            var form = document.querySelector('.addrecord');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
            document.querySelector('.addrecord button[type="submit"]').innerText = 'Submit'; // Reset button text to "Submit"
            document.querySelector('.addrecord form').action = 'fbudget1.php'; // Reset form action to default (for adding)
            document.querySelector('.addrecord form').reset(); // Reset form inputs
        }

        function editBudget(id, category, amount) {
            document.querySelector('.addrecord input[name="category"]').value = category;
            document.querySelector('.addrecord input[name="amount"]').value = amount;
            document.querySelector('.addrecord button[type="submit"]').innerText = 'Save'; // Change button text to "Save"
            document.querySelector('.addrecord').style.display = 'block'; // Show the form
            document.querySelector('.addrecord form').action = 'fbudget1.php'; // Form action for update

            // Add hidden input for the record ID to identify the record being updated
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'id';
            hiddenInput.value = id;
            document.querySelector('.addrecord form').appendChild(hiddenInput);

            // Set the form to use the update functionality
            var updateInput = document.createElement('input');
            updateInput.type = 'hidden';
            updateInput.name = 'update'; // Indicate this is an update action
            updateInput.value = 'true';
            document.querySelector('.addrecord form').appendChild(updateInput);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Monthly Budget Planner</h1>
        <h2>Budget Overview</h2>
        <div class="tbudget">
            <h2>Total Budget: 
                <?php
                include('connect.php');
                $sql_total = "SELECT SUM(amount) AS total_budget FROM budgets";
                $result_total = mysqli_query($conn, $sql_total);
                if ($result_total) {
                    $row_total = mysqli_fetch_assoc($result_total);
                    echo "RS " . number_format($row_total['total_budget'], 2);
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
                mysqli_close($conn);
                ?>
            </h2>
        </div>
        <div class="record">
            <table>
                <tr>
                    <th>Category</th>
                    <th>Budget</th>
                    <th>Actions</th>
                </tr>
                <?php
                    include('connect.php');
                    $sql = "SELECT * FROM budgets";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['category']) . "</td>
                                    <td>RS " . number_format(htmlspecialchars($row['amount']), 2) . "</td>
                                    <td>
                                        <button class='edit-btn' onclick=\"editBudget(
                                        " . $row['id'] . ", 
                                        '" . addslashes(htmlspecialchars($row['category'])) . "', 
                                        " . $row['amount'] . "
                                        )\">Edit</button>
                                        <a href='fbudget1.php?action=delete&id=" . $row['id'] . "'><button class='delete-btn'>Delete</button></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No data found</td></tr>";
                    }
                    mysqli_close($conn);
                ?>
            </table>
        </div>
        <button class="add-btn" onclick="toggleForm()">Add Budget</button>
        <div class="addrecord" style="display:none;">
            <form action="fbudget1.php" method="POST">
                <input type="text" name="category" placeholder="Enter Category" required><br>
                <input type="text" name="amount" placeholder="Set Budget" required><br>
                <button type="submit" name="submit">Add</button>
                <button type="reset" onclick="toggleForm()">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>
