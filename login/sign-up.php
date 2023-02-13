
<?php
    // Include config file
    require_once "config.php";
    // Initialize the session
    session_start();
    // Check if the user is already logged in, if yes then redirect him to welcome page
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
        header("location: ../personal-page.php");
        exit;
    }
    // Define variables and initialize with empty values
    $fname = $lname = $bdate = $email = $password = $confirm_password = "";
    $fname_err = $lname_err = $bdate_err = $email_err = $password_err = $confirm_password_err = "";
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Validate email
        if(empty(trim($_POST["email"]))){
            $email_err = "أدخل البريد الإلكتروني.";
        } else{
            // Prepare a select statement
            $sql = "SELECT id FROM users WHERE email = ?";
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_email);
                // Set parameters
                $param_email = trim($_POST["email"]);
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    /* store result */
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $email_err = "هذا الحساب موجود بالفعل!";
                    } else{
                        $email = trim($_POST["email"]);
                    }
                } else{
                    echo "عفواً! هناك خطأ. حاول مرة اخرى.";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        if(empty(trim($_POST["fname"]))){
            $fname_err = "من فضلك قم بإدخل الاسم";     
        } else{
            $fname = trim($_POST["fname"]);
        }
        if(empty(trim($_POST["lname"]))){
            $lname_err = "من فضلك قم بإدخل إسم العائلة"; 
        } else{
            $lname = trim($_POST["lname"]);
        }
        if(empty(trim($_POST["bdate"]))){
            $bdate_err = "من فضلك قم بإدخل تاريخ الميلاد";     
        } else{
            $bdate = trim($_POST["bdate"]);
        }
        // Validate password
        if(empty(trim($_POST["password"]))){
            $password_err = "أدخل كلمة المرور.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "كلمة المرور يجب أن تتكون من 6 حرف او أكثر";
        } else{
            $password = trim($_POST["password"]);
        }
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "من فضلك قم بتأكيد كلمة المرور";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "كلمة المرور غير متطابقة";
            }
        }
        // Check input errors before inserting in database
        if(empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($fname_err) && empty($lname_err) && empty($bdate_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO users (fname, lname, bdate, email, password) VALUES (?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss",$fname, $lname, $bdate, $param_email, $param_password);
                
                // Set parameters
                $param_email = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    echo "<div class='alert alert-success' role='alert'>" . "تم تسجيل بياناتك بنجاح" . "<br>" . "سيتم تحويلك الي صفحة الدخول " ."</div>";
                    header('REFRESH:3;URL=login.php');
                } else{
                    echo "<div class='alert alert-danger' role='alert'>" . "عفواً! هناك خطأ. حاول مرة اخرى." . "</div>";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
        // Close connection
        mysqli_close($conn);
    }
?>

<?php
    include('header.php');
?>
    
</div><!-- other tag in header file -->
    
<br>
<div class="loginHtml">
    <div class="loginBody text-center">
        <main class="form-signin w-100 m-auto">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <img class="mb-4" src="../images/icons/hafiz.svg" alt="" width="150" height="150" />
                
                <div class="form-floating" <?php echo (!empty($fname_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="text" placeholder="أدخل الإسم وإسم الأب" name="fname" value="<?php echo $fname; ?>">
                    <label for="fname">إسم المستخدم :</label>
                    <span class="foundsize"><?php echo $fname_err; ?></span>
                </div>

                <div class="form-floating" <?php echo (!empty($lname_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="text" placeholder="العائلة" name="lname" value="<?php echo $lname; ?>">
                    <label for="lname">العائلة : </label>
                    <span class="foundsize"><?php echo $lname_err; ?></span>
                </div>

                <div class="form-floating" <?php echo (!empty($bdate_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="date" placeholder="أدخل تاريخ الميلاد" name="bdate" value="<?php echo $bdate; ?>">
                    <label for="bdate">تاريخ الميلاد</label>
                    <span class="foundsize"><?php echo $bdate_err; ?></span>
                </div>

                <div class="form-floating" <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" value="<?php echo $email; ?>"/>
                    <label for="email">البريد الإلكتروني</label>
                    <span class="foundsize"><?php echo $email_err; ?></span>
                </div>

                <div class="form-floating" <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                    <label for="password">كلمة المرور </label>
                    <span class="foundsize"><?php echo $new_password_err; ?></span>
                </div>

                <div class="form-floating" <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="password" name="confirm_password" placeholder="Password">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <span class="foundsize"><?php echo $confirm_password_err; ?></span>
                </div>

                <button class="w-100 btn btn-lg text-light" style="background-color: #B45309" type="submit">
                    تسجيل
                </button>

                <br><br><br>

                لديك حساب بالفعل ؟ <a href="login.php" class="mt-5 mb-3">تسجيل دخول</a>
            </form>
        </main>
    </div>
</div>

<?php
    include('footer.php');
?>