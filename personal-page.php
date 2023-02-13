<?php
    // Initialize the session
    session_start();
    $id = $_SESSION["id"];
    // Check if the user is logged in, if not then redirect him to login page
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login/login.php");
        exit;
    }
?>

<?php
    include('header.php');
?>


    <a href="login/reset-password.php"> تغيير كلمة المرور </a>
    <a href="login/logout.php"> تسجيل الخروج </a>
</div><!-- other tag in header file -->

<!-- programs -->
<div class="container-md" style="margin-bottom: 200px;">
    <h3 style="padding-top: 100px;">البيانات الشخصية </h3>
    <div class="mainbody">
        <?php
        // Include config file
        require_once "login/config.php";
        $sql = "SELECT * FROM users  WHERE id ='$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table>
                <tr>
                    <th>
                        المُعرّف الخاص بك : 
                        <?php echo $row['id']; ?>
                    </th>
                    <th>
                        الاسم : 
                        <?php echo $row['fname']; ?>
                        <?php echo $row['lname']; ?>
                    </th>
                </tr>
                <tr>
                    <th>
                        تاريخ الميلاد : 
                        <?php echo $row['bdate']; ?>
                    </th>
                    <th>
                        البريد الإلكتروني : 
                        <?php echo $row['email']; ?>
                    </th>
                </tr>
            </table>
        <?php
        } else {
            echo "لا توجد سجلات <br>";
        }
        ?>
    </div>
    <a class="btn btn-outline-success" role="button" href="login/sign-up-edit.php" style="margin: 30px 1px;"> تعديل البيانات الشخصية </a>
    <!-- start  admin part-->
    <?php
        require_once "login/config.php";
        $sql3 = "SELECT rank FROM users WHERE id ='$id'";
        $result3 = mysqli_query($conn, $sql3);
        if (mysqli_num_rows($result3) > 0) {
            $row3 = mysqli_fetch_assoc($result3);
            $rank = $row3["rank"];
        }
        if ($rank != NULL) {
    ?>
    <a class="btn btn-outline-success" role="button" href="pages/insert-post.php"> أضافة مقالات</a>
    <a class="btn btn-outline-success" role="button" href="pages/insert-result.php"> إدراج نتائج الإختبارات</a>
    <?php
        }
        else{
    ?>
    <!-- end of admin part -->
    <h3>القرآن الكريم</h3>
    <div class="mainbody">
        <?php
        // Include config file
        require_once "login/config.php";
        $id = $_SESSION["id"];
        $idp = "quran";
        $sql = "SELECT * FROM programs   WHERE id ='$id' AND idp = '$idp'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table>
                <tr>
                    <th>إسم الإختبار</th>
                    <th>النتيجة</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <?php echo $row['ntest']; ?>
                        </td>
                        <td>
                            <?php echo $row['rtest']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php
        } else {
            echo "لا توجد سجلات <br>";
        }
        ?>
    </div>
    <h3>السنة النبوية</h3>
    <div class="mainbody">
        <?php
        // Include config file
        require_once "login/config.php";
        $id = $_SESSION["id"];
        $idp = "sunnah";
        $sql = "SELECT * FROM programs   WHERE id ='$id' AND idp = '$idp'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table>
                <tr>
                    <th>إسم الإختبار</th>
                    <th>النتيجة</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <?php echo $row['ntest']; ?>
                        </td>
                        <td>
                            <?php echo $row['rtest']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php
        } else {
            echo "لا توجد سجلات <br>";
        }
        ?>
    </div>
    <h3>المتون العلمية</h3>
    <div class="mainbody">
        <?php
        // Include config file
        require_once "login/config.php";
        $id = $_SESSION["id"];
        $idp = "metun";
        $sql = "SELECT * FROM programs   WHERE id ='$id' AND idp = '$idp'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
        ?>
            <table>
                <tr>
                    <th>إسم الإختبار</th>
                    <th>النتيجة</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <?php echo $row['ntest']; ?>
                        </td>
                        <td>
                            <?php echo $row['rtest']; ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php
        } else {
            echo "لا توجد سجلات <br>";
        }
        ?>
    </div>
    <?php } ?>
</div>
<script src="js/bootstrap.min.js"></script>
<script src="js/fontawesomefree.min.js"></script>
<script src="js/script.js"></script>
<?php
    include('login/footer.php');
?>