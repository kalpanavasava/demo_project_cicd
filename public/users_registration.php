<?php
include 'db_connection.php';

$name_error = $email_error = $password_error = '';

if( isset($_POST['submit']) ){
    
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $contact = isset($_POST['contact']) ? $_POST['contact'] : '';
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $hobbies = isset($_POST['hobby']) ? implode(",", $_POST['hobby']) : '';

    // Validate fields
    $valid = true;

    if( empty($name) ){
        $name_error = "Name is required.";
        $valid = false;
    }

    if( empty($email) ){
        $email_error = "Email is required.";
        $valid = false;
    }elseif( !filter_var($email, FILTER_VALIDATE_EMAIL) ){
        $email_error = "Invalid email format.";
        $valid = false;
    }
    
    if( empty($password) ){
        $password_error = "Password is required.";
        $valid = false;
    }

    if( $valid ){
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "insert into users (name, email, password, contact, city, gender, hobby) 
              values ('" . $name . "', '" . $email . "', '" . $hashed_password . "', 
                      '" . $contact . "', '" . $city . "', '" . $gender . "', 
                      '" . $hobbies . "')";

        if( mysqli_query($conn, $query) ){
            echo "<h2>Data Inserted Successfully!</h2>";
        }else{
            echo "<h2>Error: " . mysqli_error($conn) . "</h2>";
        }
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert and Display User Data</title>
</head>
<body>
    <!-- User Registration Form -->
    <form method="post">
        <table class="tbl" align="left" border="2" cellpadding="6" cellspacing="6">
            <tr>
                <th colspan="2"><h2>User Registration Form</h2></th>
            </tr>
            <tr>
                <td>Name</td>
                <td>
                    <input type="text" name="name">
                    <div style="color: red;"><?php echo $name_error; ?></div>
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    <input type="email" name="email">
                    <div style="color: red;"><?php echo $email_error; ?></div>
                </td>
            </tr>
            <tr>
                <td>Password</td>
                <td>
                    <input type="password" name="password">
                    <div style="color: red;"><?php echo $password_error; ?></div>
                </td>
            </tr>
            <tr>
                <td>Contact</td>
                <td><input type="text" name="contact"></td>
            </tr>
            <tr>
                <td>City</td>
                <td><input type="text" name="city"></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td>
                    <input type="radio" name="gender" value="male">Male
                    <input type="radio" name="gender" value="female">Female
                    <input type="radio" name="gender" value="other">Other
                </td>
            </tr>
            <tr>
                <td>Hobbies</td>
                <td>
                    <input type="checkbox" name="hobby[]" value="Reading">Reading
                    <input type="checkbox" name="hobby[]" value="Singing">Singing
                    <input type="checkbox" name="hobby[]" value="Writing">Writing
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <button type="submit" name="submit">Submit</button>
                </td>
            </tr>
        </table>
    </form>

    <!-- Displaying Inserted Data -->
    <h2>Inserted User Data</h2>
    <table border="2" cellpadding="6" cellspacing="6">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>City</th>
                <th>Gender</th>
                <th>Hobbies</th>
                <!-- <th colspan="2">Action</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $show = mysqli_query($conn, "SELECT * FROM users");
            $n = 1;
            if(mysqli_num_rows($show) > 0){
                while($row = mysqli_fetch_array($show)){ ?>        
                    <tr>
                        <td><?php  echo $n;?></td>
                        <td><?php  echo $row['name'];?></td>
                        <td><?php  echo $row['email'];?></td>
                        <td><?php  echo $row['contact'];?></td>
                        <td><?php  echo $row['city'];?></td>
                        <td><?php  echo $row['gender'];?></td>
                        <td><?php  echo $row['hobby'];?></td>
                        <!-- <td>
                            <a href="users_registration.php?edit_id=<?php echo ($row['id']); ?>" class="edit_btn">Edit</a>
                        </td>
                        <td>
                            <a href="users_registration.php?id=<?php echo ($row['id']); ?>" class="del_btn"
                                onclick="return confirm('Are You Sure You Want To Delete?');">Delete</a>
                        </td> -->
                    </tr>
                    <?php
                    $n++;
                }
            } else { ?>
                <tr><td colspan='7' align='center'>No user records found!</td></tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
