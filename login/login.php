<?php
// Initialize the session
session_start();
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: ../personal-page.php");
    exit;
}
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "أدخل البريد الإلكتروني.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = " من فضلك! قم بإدخل كلمة المرور";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT fname, id, email, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $fname, $id, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["fname"] = $fname;

                            // Redirect user to welcome page
                            header("location: ../personal-page.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "كلمة المرور غير صحيحة!";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = "لا يوجد حساب بهذا البريد.";
                }
            } else {
                echo "عفواً! هناك خطأ. حاول مرة اخرى.";
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

<br><br><br>
<div class="loginHtml">
    <div class="loginBody text-center">
        <main class="form-signin w-100 m-auto">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <img class="mb-4" src="../images/icons/hafiz.svg" alt="" width="150" height="150" />
                <h1 class="h3 mb-3 fw-normal">الرجاء تسجيل الدخول</h1>

                <div class="form-floating" <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>>
                    <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo $email; ?>"/>
                    <label for="floatingInput">البريد الإلكتروني</label>
                    <span class="foundsize"><?php echo $email_err; ?></span>
                </div>

                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" />
                    <label for="floatingPassword">كلمة المرور</label>
                    <span class="foundsize"><?php echo $password_err; ?></span>
                </div>

                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me" />
                        تذكرني
                    </label>
                </div>

                <button class="w-100 btn btn-lg text-light" style="background-color: #B45309" type="submit">
                    دخول
                </button>

                <br><br><br>
                تسجيل حساب جديد ؟ <a href="sign-up.php" class="mt-5 mb-3">تسجيل</a>
            </form>
        </main>
    </div>
</div>


<?php
    include('footer.php');
?>