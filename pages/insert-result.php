<?php
    // Include config file
    require_once "../login/config.php";
    session_start();
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: ../personal-page.php");
        exit;
    }

    $id = $_SESSION["id"];
    $sql3 = "SELECT rank FROM users WHERE id ='$id'";
    $result3 = mysqli_query($conn, $sql3);
    if (mysqli_num_rows($result3) > 0) {
        $row3 = mysqli_fetch_assoc($result3);
        $rank = $row3["rank"];
    }
    if ($rank == NULL) {
        header("location: ../personal-page.php");
        exit;
    }

    $sql2 = "SELECT fname FROM users WHERE rank ='$rank'";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
    $row = mysqli_fetch_assoc($result2);
    $fname = $row["fname"];
    }



    $id = $idp = $ntest = $rtest = "";
    $id_err = $idp_err = $ntest_err = $rtest_err ="";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
            if(empty(trim($_POST["id"]))){
                $id_err = "من فضلك قم بإدخل رقم الطالب";     
            } else{
                $id = trim($_POST["id"]);
            }
            if(empty(trim($_POST["idp"]))){
                $idp_err = "من فضلك قم بإدخل إسم البرنامج";     
            } else{
                $idp = trim($_POST["idp"]);
            }
            if(empty(trim($_POST["ntest"]))){
                $ntest_err = "من فضلك قم بإدخل إسم الإختبار";     
            } else{
                $ntest = trim($_POST["ntest"]);
            }
            if(empty(trim($_POST["rtest"]))){
                $rtest_err = "من فضلك قم بإدخل النتيجة";     
            } else{
                $rtest = trim($_POST["rtest"]);
            }
        // Check input errors before inserting in database
        if(empty($id_err) && empty($idp_err) && empty($ntest_err) && empty($rtest_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO programs (id, idp, ntest, rtest, fname) VALUES (?, ?, ?, ?,?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss",$id, $idp, $ntest, $rtest, $fname);
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    echo "<div class='alert alert-success' role='alert'>" . "تم الإدراج بنجاح" . "</div>";
                    header('REFRESH:1;URL=insert-result.php');
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
        <a href="../login/reset-password.php"> تغيير كلمة المرور </a>
        <a href="../login/logout.php"> تسجيل الخروج </a>
    </div>
    
<div class="myModal">
    <form class="myModal-content" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <div class="container">
            <div  <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>>
                <label for="id">رقم الطالب :</label>
                <input type="number" placeholder="من فضلك قم بإدخل رقم الطالب" name="id" value="<?php echo $id; ?>">
                <span class="foundsize"><?php echo $id_err; ?></span>
            </div>
            <div  <?php echo (!empty($idp_err)) ? 'has-error' : ''; ?>>
                <label for="idp">إسم البرنامج</label>
                <input  type="text" placeholder="من فضلك قم بإختيار إسم البرنامج" list="idplist" name="idp" value="<?php echo $idp; ?>">
                <datalist id="idplist">
                    <option>quran</option>
                    <option>sunnah</option>
                    <option>metun</option>
                </datalist>
                <span class="foundsize"><?php echo $idp_err; ?></span>
            </div>
            <div  <?php echo (!empty($ntest_err)) ? 'has-error' : ''; ?>>
                <label for="ntest">إسم الإختبار</label>
                <input  type="text" placeholder="من فضلك قم بإدخل إسم الإختبار" name="ntest" value="<?php echo $ntest; ?>">
                <span class="foundsize"><?php echo $ntest_err; ?></span>
            </div>
            <div  <?php echo (!empty($rtest_err)) ? 'has-error' : ''; ?>>
                <label for="rtest">النتيجة</label>
                <input  type="number" placeholder="من فضلك قم بإدخل النتيجة" name="rtest" value="<?php echo $rtest; ?>">
                <span class="foundsize"><?php echo $rtest_err; ?></span>
            </div>
            <div >
                <input type="submit" class="but" value="إدخال">
                <a href="../personal-page.php">خروج</a>
            </div>
        </div>
    </form>
</div>

    
<?php
    include('../login/footer.php');
?>