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

    $title = $img = $contant = "";
    $title_err = $img_err = $contant_err ="";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
    

        if(empty(trim($_POST["title"]))){
            $title_err = "من فضلك قم بإدخل العنوان";     
        } else{
            $title = trim($_POST["title"]);
        }
        if(empty(trim($_POST["contant"]))){
            $contant_err = "من فضلك قم بإدخل المحتوى";     
        }else{
            $contant = trim($_POST["contant"]);
        }
        $imgname = $_FILES['img']['name'];
        $imgTmp = $_FILES['img']['tmp_name'];
        $img = rand(0 , 1000)."_".$imgname ;
        move_uploaded_file($imgTmp,"../uploads\imgposts\\".$img);
        // Check input errors before inserting in database
        if(empty($title_err) && empty($img_err) && empty($contant_err)){
            
            // Prepare an insert statement
            $sql = "INSERT INTO posts (title, img, contant, writer) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss",$title, $img, $contant, $fname);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Redirect to login page
                    echo "<div class='alert alert-success' role='alert'>" . "تم النشر بنجاح" . "<br>" . "سيتم تحويلك الي الصفحة الرئيسية " ."</div>";
                    header('REFRESH:1;URL=../personal-page.php');
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
            <div  <?php echo (!empty($img_err)) ? 'has-error' : ''; ?>>
                <label for="img">صورة المقال : </label>
                <input type="file" name="img" id="img" value="<?php echo $img; ?>">
                <span class="foundsize"><?php echo $img_err; ?></span>
                <p class="foundsize2">يفضل أن تكون مقاسات الصورة بعرض px1920 وإرتفاع px1080</p><br>
            </div>
            <div  <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>>
                <label for="title">عنوان المقال :</label>
                <input type="text" placeholder="أدخل عنوان المقال" name="title" value="<?php echo $title; ?>">
                <span class="foundsize"><?php echo $title_err; ?></span>
            </div>
            <div  <?php echo (!empty($contant_err)) ? 'has-error' : ''; ?>>
                <label for="contant">المحتوى :</label>
                <textarea style="height: 200px;width: 100%;margin: 8px 0;border: none;" placeholder="أدخل المحتوى" name="contant" value="<?php echo $contant; ?>" ></textarea>
                <span class="foundsize"><?php echo $contant_err; ?></span>
            </div>
            <div >
                <input type="submit" class="but" value="نشر">
                <a href="../personal-page.php">خروج</a>
            </div>
        </div>
    </form>
</div>    
    
<?php
    include('../login/footer.php');
?>