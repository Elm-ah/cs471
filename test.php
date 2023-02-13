<?php
    // Include config file
    require_once "../login/config.php";

    $idwriter = $id = $idp = $ntest = $rtest = "";
    $idwriter_err = $id_err = $idp_err = $ntest_err = $rtest_err ="";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
            // Validate email
            if(empty(trim($_POST["idwriter"]))){
                $idwriter_err = "عفواً ! قم بإدخال المعرف الشخصي";
            } else{
                // Prepare a select statement
                $sql = "SELECT fname FROM users WHERE idwriter = ?";
                
                if($stmt = mysqli_prepare($conn, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_idwriter);
                    
                    // Set parameters
                    $param_idwriter = trim($_POST["idwriter"]);
                    
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        /* store result */
                        mysqli_stmt_store_result($stmt);
                        
                        if(mysqli_stmt_num_rows($stmt) == 1){
                            $idwriter = trim($_POST["idwriter"]);
                        } else{
                            $idwriter_err = "عفواً ! لا يسمح لك بالكتابة تواصل مع إدارة الموقع في حال حدوث خطأ";
                        }
                    } else{
                        echo "عفواً! هناك خطأ. حاول مرة اخرى.";
                    }
        
                    // Close statement
                    mysqli_stmt_close($stmt);
                }
                $sql2 = "SELECT fname FROM users WHERE idwriter ='$idwriter'";
                $result2 = mysqli_query($conn, $sql2);
                if (mysqli_num_rows($result2) > 0) {
                $row = mysqli_fetch_assoc($result2);
                $fname = $row["fname"];
                }
            }
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
        if(empty($idwriter_err) && empty($id_err) && empty($idp_err) && empty($ntest_err) && empty($rtest_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO programs (id, idp, ntest, rtest, fname) VALUES (?, ?, ?, ?,?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss",$id, $idp, $ntest, $rtest, $fname);
                
                // Set parameters
                $param_idwriter = $idwriter;                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    echo "<div class='alert alert-success' role='alert'>" . "تم الإدراج بنجاح" . "</div>";
                    header('REFRESH:2;URL=insertprt.php');
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

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesomefree.min.css">
    <link rel="stylesheet" href="../css/stylelogin.css">
    <title>حافظ</title>
</head>

<body>
    <header>
        <a href="http://127.0.0.1/mysite/" class="logo"><img width="40px" src="../images/icons/hafiz.svg" /> حافظ </a>
        <nav class="navigation">
            <a href="../personal-page.php"><i class="fa-solid fa-user"></i></a>
            <a href="#" onclick="openNav()"><i class="fa-solid fa-bars"></i></a>
        </nav>
    </header>
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="http://127.0.0.1/mysite/">الرئيسية</a>
        <a href="../personal-page.php">الصفحة الشخصية</a>
        <a href="http://127.0.0.1/mysite/#aboutUs">من نحن</a>
        <a href="http://127.0.0.1/mysite/#contact">تواصل معنا</a>
    

    <a href="insertPost.php"> أضافة مقالات</a>
    <a href="../login/reset-password.php"> تغيير كلمة المرور </a>
    <a href="../login/logout.php"> تسجيل الخروج </a>
</div><!-- other tag in header file -->

        <div class="modal">
            <form class="modal-content" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="imgcontainer">
                    <img src="../theme/img/logo1.png" alt="Avatar" class="avatar">
                </div>
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
                        <option>itqan-alalom</option>
                        <option>voice-massage</option>
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
                <div  <?php echo (!empty($idwriter_err)) ? 'has-error' : ''; ?>>
                    <label>المعرف الشخصي</label>
                    <input type="number" name="idwriter" placeholder=" من فضلك قم بإدخال الرقم السري الخاص بك لإدخال النتائج " value="<?php echo $idwriter; ?>">
                    <span class="foundsize"><?php echo $idwriter_err; ?></span>
                </div>    
                <div >
                    <input type="submit" class="but" value="إدخال">
                    <a href="../ppage.php">خروج</a>
                </div>
            </div>
            </form>
        </div>

    
        <footer class="footer2">
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-facebook"></i></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fa-brands fa-telegram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
        <p class="footer-title">جميع الحقوق محفوظة لحافظ @ 2023</p>
    </footer>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/fontawesomefree.min.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>
