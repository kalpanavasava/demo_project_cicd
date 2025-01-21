<!-- views/user_registration.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration and Data Display</title>
    <!-- External CSS for styles -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- User Registration Form -->
    <div class="form-container">
        <?php 
            $name_error = $formErrors['name_error'];
            $email_error = $formErrors['email_error'];
            $password_error = $formErrors['password_error'];
        ?>
        <h2>User Registration Form</h2>
        <form method="post">
            <table class="tbl">
                <tr>
                    <td><label for="name">Name</label></td>
                    <td><input type="text" id="name" name="name">
                        <div class="error" style="color: red;"><?php echo $name_error; ?></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="email">Email</label></td>
                    <td><input type="email" id="email" name="email">
                        <div class="error" style="color: red;"><?php echo $email_error; ?></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="password">Password</label></td>
                    <td><input type="password" id="password" name="password">
                        <div class="error" style="color: red;"><?php echo $password_error; ?></div>
                    </td>
                </tr>
                <tr>
                    <td><label for="contact">Contact</label></td>
                    <td><input type="text" id="contact" name="contact"></td>
                </tr>
                <tr>
                    <td><label for="city">City</label></td>
                    <td><input type="text" id="city" name="city"></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input type="radio" name="gender" value="male" id="male"> <label for="male">Male</label>
                        <input type="radio" name="gender" value="female" id="female"> <label for="female">Female</label>
                        <input type="radio" name="gender" value="other" id="other"> <label for="other">Other</label>
                    </td>
                </tr>
                <tr>
                    <td>Hobbies</td>
                    <td>
                        <input type="checkbox" name="hobby[]" value="Reading" id="reading"> <label for="reading">Reading</label>
                        <input type="checkbox" name="hobby[]" value="Singing" id="singing"> <label for="singing">Singing</label>
                        <input type="checkbox" name="hobby[]" value="Writing" id="writing"> <label for="writing">Writing</label>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button type="submit" name="submit">Submit</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- Displaying Inserted User Data -->
    <div class="data-container">
        <h2>User Data</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>City</th>
                    <th>Gender</th>
                    <th>Hobbies</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $n = 1;
                while ($row = mysqli_fetch_array($users)) {
                    echo "<tr>";
                    echo "<td>" . $n++ . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['contact'] . "</td>";
                    echo "<td>" . $row['city'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>" . implode(", ", explode(",", $row['hobby'])) . "</td>"; // Display hobbies
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
