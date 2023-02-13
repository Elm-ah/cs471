<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$old_password = $new_password = $confirm_password = "";
$old_password_err = $new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    if(empty(trim($_POST["old_password"]))){
        $old_password_err = "من فضلك! قم بإدخل كلمة المرور القديمة";
    } else{
        $old_password = trim($_POST["old_password"]);
    }
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "من فضلك! قم بإدخل كلمة المرور الجديدة";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "كلمة المرور يجب أن تتكون من 6 حرف او أكثر";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "من فضلك قم بتأكيد كلمة المرور";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "كلمة المرور غير متطابقة";
        }
    }
    $idpass = $_SESSION["id"];
    $sqlpass = "SELECT password FROM users WHERE id ='$idpass'";
    $resultpass = mysqli_query($conn, $sqlpass);
    if (mysqli_num_rows($resultpass) > 0) {
    $rowpass = mysqli_fetch_assoc($resultpass);
    $hashed_password = &$rowpass['password'];
    }
    if(password_verify($old_password, $hashed_password)){
        // Check input errors before updating the database
        if(empty($new_password_err) && empty($confirm_password_err)){
            // Prepare an update statement
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
                
                // Set parameters
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Password updated successfully. Destroy the session, and redirect to login page
                    session_destroy();
                    echo "<div class='alert alert-success'; role='alert'>" . "تم تغيير كلمة المرور بنجاح" . "<br>" . "سيتم تحويلك الي صفحة الدخول " ."</div>";
                    header('REFRESH:1;URL=login.php');
                    exit();
                } else{
                    echo "<div style='background-color: #ce9494;padding: 10px;text-align: center;'>" . "عفواً! هناك خطأ. حاول مرة اخرى." . "</div>";
                }
                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }else {
        $old_password_err = "كلمة المرور غير صحيحة";
    }
    // Close connection
    mysqli_close($conn);
}
?>


<?php
include('header.php');
?>
    
    <a href="reset-password.php"> تغيير كلمة المرور </a>
    <a href="logout.php"> تسجيل الخروج </a>
</div><!-- other tag in header file -->
<br><br><br>
<div class="loginHtml">
    <div class="loginBody text-center">
        <main class="form-signin w-100 m-auto">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <img class="mb-4" src="../images/icons/hafiz.svg" alt="" width="150" height="150" />
                <div class="form-floating" <?php echo (!empty($old_password_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="password" name="old_password" placeholder="Password" value="<?php echo $old_password; ?>">
                    <label for="old_password">كلمة المرور القديمة</label>
                    <span class="foundsize"><?php echo $old_password_err; ?></span>
                </div>
                <div class="form-floating" <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="password" name="new_password" placeholder="Password">
                    <label for="new_password">كلمة المرور الجديدة</label>
                    <span class="foundsize"><?php echo $new_password_err; ?></span>
                </div>
                <div class="form-floating" <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>>
                    <input class="form-control" type="password" name="confirm_password" placeholder="Password">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <span class="foundsize"><?php echo $confirm_password_err; ?></span>
                </div>
                <button class="w-100 btn btn-lg text-light" style="background-color: #B45309" type="submit">
                    تغيير
                </button><br><br><br>
                <a href="../personal-page.php" class="mt-5 mb-3">إلغاء</a>
            </form>
        </main>
    </div>
</div>

<?php
    include('footer.php');
?>