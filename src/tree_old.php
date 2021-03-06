<?php
include('includes/header.php');
if (isset($_SESSION['Username']) && $_SESSION['is_prime_user'] == 1) {
    $userid = $_SESSION['Username'];
    $search = $userid;

    function tree_data($userid) {
        global $conn;
        $data = array();
        $query = mysqli_query($conn, "select * from tree where userid='$userid'");
        $result = mysqli_fetch_array($query);
        $data['left'] = $result['left'];
        $data['right'] = $result['right'];
        $data['leftcount'] = $result['leftcount'];
        $data['rightcount'] = $result['rightcount'];
        $data['leftcredits'] = $result['leftcredits'];
        $data['rightcredits'] = $result['rightcredits'];
        return $data;
    }

    if (isset($_GET['search-id'])) {
        $search_id = mysqli_real_escape_string($conn, $_GET['search-id']);
        if ($search_id != "") {
            $getmember = "SELECT * FROM users WHERE Username = '" . $_SESSION['Username'] . "'";
            $querymember = mysqli_query($conn, $getmember);
            $currentmember = mysqli_fetch_array($querymember);
            $membernumber = $currentmember['id'];

            $getsearch = "SELECT * FROM users WHERE Username = '$search_id'";
            $querysearch = mysqli_query($conn, $getsearch);
            $currentsearch = mysqli_fetch_array($querysearch);
            $membersearch = $currentsearch['id'];

            $query_check = mysqli_query($conn, "select * from users where Username='$search_id'");
            if (mysqli_num_rows($query_check) > 0 && $membernumber <= $membersearch) {
                $search = $search_id;
            } elseif (mysqli_num_rows($query_check) > 0 && $membernumber > $membersearch && $membernumber == $userid) {
                $search = $search_id;
            } else {
                $_SESSION['error'] = 1;
                $_SESSION['message'] = 'User Unknown.';
                $redirect = 'tree';
                echo "<script>location='" . BASE_URL . $redirect . "'</script>";
                exit;
            }
        } else {
            $_SESSION['error'] = 1;
            $_SESSION['message'] = 'Access Denied.';
            $redirect = 'tree';
            echo "<script>location='" . BASE_URL . $redirect . "'</script>";
            exit;
        }
    }
} else {
    $_SESSION['error'] = 1;
    $_SESSION['message'] = 'Please login to proceed further.';
    unset($_POST);
    unset($_SESSION);
    //header("Location:login");
    $redirect = 'login';
    echo "<script>location='" . BASE_URL . $redirect . "'</script>";
    exit;
}

include('includes/message.php');
?>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo BASE_URL; ?>" class="site_title"> <span><img src="../images/logo_transparent_small.png" alt="Bitc-Mine-Pool" ></span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile clearfix">
                        <div class="profile_pic">
                            <a href="<?php echo BASE_URL; ?>"><img src="../images/img.jpg" alt="..." class="img-circle profile_img"></a>              </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?php echo ' ' . strlen($_SESSION['Username']) > 15 ? ucfirst(substr($_SESSION['Username'], 0, 15)) . "..." : ucfirst($_SESSION['Username']); ?></h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <?php include('includes/menu.php'); ?>

                    <!-- /menu footer buttons -->

                    <!-- /menu footer buttons -->
                </div>
            </div>

            <?php include('includes/guestheader.php'); ?>


            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Binary Tree</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <!-- Search -->
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <form>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" name="search-id" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <input type="submit" name="search" class="btn btn-primary" value="Search">
                                                <a href="tree" class="btn btn-primary" role="button">Go to<span> Top</span></a>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="col-lg-2"></div>
                                </div>
                                <!-- /Search -->
                                <div class="row">
                                    <div class="col-lg-12 tree">
                                        <ul>
                                            <li>
                                                <?php
                                                $data = tree_data($search);
                                                $getgenderone = "SELECT Gender FROM users WHERE Username = '$search'";
                                                $querygenderone = mysqli_query($conn, $getgenderone);
                                                $genderone = mysqli_fetch_array($querygenderone);
                                                $mygenderone = $genderone['Gender'];

                                                $getrankone = "SELECT * FROM rank WHERE Username = '$search'";
                                                $queryrankone = mysqli_query($conn, $getrankone);
                                                $rankone = mysqli_fetch_array($queryrankone);
                                                $myrankone = $rankone['Rank'];
                                                $myrankid = $rankone['Rankid'];
                                                ?>
                                                <a href="#">
                                                    <img class="img-rounded" id="avatar" src="<?php echo ($mygenderone == 1) ? '../images/useravatar.png' : '../images/useravatarf.png'; ?>">
                                                </a><br>
                                                <span><?php echo $search; ?></span><br>
                                                <span>Rank: <?php echo $myrankone; ?></span>
                                                <ul>
                                                    <li>
                                                        <a href="#"><img class="img-rounded" id="avatar" src="useravatarf.png"></a>
                                                        <br><br>
                                                        <span> 
                                                            <select style="padding-bottom: 5px;">
                                                                <option>Choose a member</option>
                                                            </select>&nbsp;&nbsp;
                                                            <button type="submit" id="button[]" class="btn btn-sm btn-outline-danger">Add Member</button>
                                                        </span>
                                                        <ul>
                                                            <li>
                                                                <a href="#"><img class="img-rounded" id="avatar" src="useravatarn.png"></a>

                                                                <br><br>
                                                                <span> 
                                                                    <select style="padding-bottom: 5px;">
                                                                        <option>Choose a member</option>
                                                                    </select>&nbsp;&nbsp;
                                                                    <button type="submit" id="button[]" class="btn btn-sm btn-outline-danger">Add Member</button>
                                                                </span>


                                                            </li><li>
                                                                <a href="#"><img class="img-rounded" id="avatar" src="useravatarn.png"></a>

                                                                <br><br>
                                                                <span> 
                                                                    <select style="padding-bottom: 5px;">
                                                                        <option>Choose a member</option>
                                                                    </select>&nbsp;&nbsp;
                                                                    <button type="submit" id="button[]" class="btn btn-sm btn-outline-danger">Add Member</button>
                                                                </span>

                                                            </li>
                                                        </ul>
                                                    </li><li>
                                                        <a href="#"><img class="img-rounded" id="avatar" src="useravatarn.png"></a>

                                                        <br><br>
                                                        <span> 
                                                            <select style="padding-bottom: 5px;">
                                                                <option>Choose a member</option>
                                                            </select>&nbsp;&nbsp;
                                                            <button type="submit" id="button[]" class="btn btn-sm btn-outline-danger">Add Member</button>
                                                        </span>

                                                        <ul>
                                                            <li>
                                                                <a href="#"><img class="img-rounded" id="avatar" src="useravatarn.png"></a>

                                                                <br><br>
                                                                <span> 
                                                                    <select style="padding-bottom: 5px;">
                                                                        <option>Choose a member</option>
                                                                    </select>&nbsp;&nbsp;
                                                                    <button type="submit" id="button[]" class="btn btn-sm btn-outline-danger">Add Member</button>
                                                                </span>

                                                            </li><li>
                                                                <a href="#"><img class="img-rounded" id="avatar" src="useravatarn.png"></a>

                                                                <br><br>
                                                                <span> 
                                                                    <select style="padding-bottom: 5px;">
                                                                        <option>Choose a member</option>
                                                                    </select>&nbsp;&nbsp;
                                                                    <button type="submit" id="button[]" class="btn btn-sm btn-outline-danger">Add Member</button>
                                                                </span>

                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>    
                                <!-- Binary Tree -->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table" align="center" border="0" style="text-align:center">
                                                <tr height="150">
                                                    <?php
                                                    $data = tree_data($search);
                                                    ?>
                                                    <td><b class="fa fa-database" style="color:#1430B1"> Left Volume: $<?php echo $data['leftcount'] ?></b><p><b class="fa fa-money" style="color:#1430B1"> Left Credits: <?php echo $data['leftcredits'] ?></p></b></td>
                                                    <td colspan="2">

                                                        <!-- change image according to gender -->
                                                        <?php
                                                        $getgenderone = "SELECT Gender FROM users WHERE Username = '$search'";
                                                        $querygenderone = mysqli_query($conn, $getgenderone);
                                                        $genderone = mysqli_fetch_array($querygenderone);
                                                        $mygenderone = $genderone['Gender'];
                                                        if ($mygenderone == 1) {
                                                            ?><img src="../images/useravatar.png" alt=""><?php
                                                        } else {
                                                            ?>
                                                            <img src="../images/useravatarf.png" alt=""><?php
                                                    }
                                                        ?>
                                                        <!-- //change image according to gender -->

                                                        <p><?php echo $search; ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                            <!-- Get Rank -->
                                                            <?php
                                                            $getrankone = "SELECT * FROM rank WHERE Username = '$search'";
                                                            $queryrankone = mysqli_query($conn, $getrankone);
                                                            $rankone = mysqli_fetch_array($queryrankone);
                                                            $myrankone = $rankone['Rank'];
                                                            $myrankid = $rankone['Rankid'];
                                                            echo $myrankone;
                                                            ?>
                                                            <!-- //Get Rank -->
                                                        </p></td>
                                                    <td><b class="fa fa-database" style="color:#1430B1"> Right Volume: $<?php echo $data['rightcount'] ?></b><p><b class="fa fa-money" style="color:#1430B1"> Rights Credits: <?php echo $data['rightcredits'] ?></p></b></td>
                                                </tr>
                                                <tr height="150">
                                                    <?php
                                                    $first_left_user = $data['left'];
                                                    $first_right_user = $data['right'];
                                                    $data_left_credits = tree_data($first_left_user);
                                                    $first_left_credits = $data_left_credits['leftcredits'];
                                                    $first_right_credits = $data_left_credits['rightcredits'];
                                                    $data_right_credits = tree_data($first_right_user);
                                                    $first_leftuser_credits = $data_right_credits['leftcredits'];
                                                    $first_rightuser_credits = $data_right_credits['rightcredits'];
                                                    ?>
                                                    <?php
                                                    if ($first_left_user != "") {
                                                        ?>
                                                        <td colspan="2"><a href="tree.php?search-id=<?php echo $first_left_user ?>">

                                                                <!-- change image according to gender -->
                                                                <?php
                                                                $getgendertwo = "SELECT Gender FROM users WHERE Username = '$first_left_user'";
                                                                $querygendertwo = mysqli_query($conn, $getgendertwo);
                                                                $gendertwo = mysqli_fetch_array($querygendertwo);
                                                                $mygendertwo = $gendertwo['Gender'];
                                                                if ($mygendertwo == 1) {
                                                                    ?><img src="../images/useravatar.png" alt=""><?php
                                                                } else {
                                                                    ?>
                                                                    <img src="../images/useravatarf.png" alt=""><?php
                                                        }
                                                                ?>
                                                                <!-- //change image according to gender -->

                                                                </i><p><?php echo $first_left_user ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                                    <!-- Get Rank -->
                                                                    <?php
                                                                    $getranktwo = "SELECT * FROM rank WHERE Username = '$first_left_user'";
                                                                    $queryranktwo = mysqli_query($conn, $getranktwo);
                                                                    $ranktwo = mysqli_fetch_array($queryranktwo);
                                                                    $myranktwo = $ranktwo['Rank'];
                                                                    $myrankidtwo = $ranktwo['Rankid'];
                                                                    echo $myranktwo;
                                                                    ?>
                                                                    <!-- //Get Rank -->

                                                                </p><p><i class="fa fa-money" style="color:#361515"></i>  Left Credits: <?php echo $first_left_credits ?><p><i class="fa fa-money" style="color:#361515"></i> Right Credits: <?php echo $first_right_credits ?></p></p></a></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td colspan="2"><img src="../images/useravatarn.png"><p><form action="join.php" method="post">
                                                                <?php
                                                                $query1 = "SELECT * FROM users WHERE Sponsor = '" . $_SESSION['Username'] . "' AND Status='Close' AND treestatus='notree'";
                                                                $result1 = mysqli_query($conn, $query1);
                                                                ?>
                                                                <select  name="Usernameone" id="Usernameone" onchange="">
                                                                    <option>Choose Member</option>
                                                                    <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                        <option value="<?php echo $row1[6]; ?>"><?php echo $row1[6]; ?></option>
                                                                    <?php endwhile; ?>

                                                                </select>
                                                                <input type="hidden" name="under_userid" id="under_userid" value="<?php echo $search; ?>">
                                                                <input type="hidden" name="side" id="side" value="left">
                                                                <button id="join_user" type="submit" class="btn btn-primary">Add Member</button> </form></p></td>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($first_right_user != "") {
                                                        ?>
                                                        <td colspan="2"><a href="tree.php?search-id=<?php echo $first_right_user ?>">

                                                                <!-- change image according to gender -->
                                                                <?php
                                                                $getgenderthree = "SELECT Gender FROM users WHERE Username = '$first_right_user'";
                                                                $querygenderthree = mysqli_query($conn, $getgenderthree);
                                                                $genderthree = mysqli_fetch_array($querygenderthree);
                                                                $mygenderthree = $genderthree['Gender'];
                                                                if ($mygenderthree == 1) {
                                                                    ?><img src="../images/useravatar.png" alt=""><?php
                                                                } else {
                                                                    ?>
                                                                    <img src="../images/useravatarf.png" alt=""><?php
                                                        }
                                                                ?>
                                                                <!-- //change image according to gender -->

                                                                </i><p><?php echo $first_right_user ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                                    <!-- Get Rank -->
                                                                    <?php
                                                                    $getrankthree = "SELECT * FROM rank WHERE Username = '$first_right_user '";
                                                                    $queryrankthree = mysqli_query($conn, $getrankthree);
                                                                    $rankthree = mysqli_fetch_array($queryrankthree);
                                                                    $myrankthree = $rankthree['Rank'];
                                                                    $myrankidthree = $rankthree['Rankid'];
                                                                    echo $myrankthree;
                                                                    ?>
                                                                    <!-- //Get Rank -->
                                                                </p><p><i class="fa fa-money" style="color:#361515"></i>  Left Credits: <?php echo $first_leftuser_credits ?><p><i class="fa fa-money" style="color:#361515"></i> Right Credits: <?php echo $first_rightuser_credits ?></p></p></a></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td colspan="2"><img src="../images/useravatarn.png"><p><form action="jointwo.php" method="post"><?php
                                                    $query1 = "SELECT * FROM users WHERE Sponsor = '" . $_SESSION['Username'] . "' AND Status='Close' AND treestatus='notree'";
                                                    $result1 = mysqli_query($conn, $query1);
                                                        ?>
                                                                <select  name="Usernametwo" id="Usernametwo" onchange="">
                                                                    <option>Choose Member</option>
                                                                    <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                        <option value="<?php echo $row1[6]; ?>"><?php echo $row1[6]; ?></option>
                                                                    <?php endwhile; ?>

                                                                </select>
                                                                <input type="hidden" name="under_useridtwo" id="under_useridtwo" value="<?php echo $search; ?>">
                                                                <input type="hidden" name="sidetwo" id="sidetwo" value="right">
                                                                <button id="join_usertwo" type="submit" class="btn btn-primary">Add Member</button></form></p></td>
                                                        <?php
                                                    }
                                                    ?>
                                                </tr>
                                                <tr height="150">
                                                    <?php
                                                    $data_first_left_user = tree_data($first_left_user);
                                                    $second_left_user = $data_first_left_user['left'];
                                                    $second_right_user = $data_first_left_user['right'];
                                                    $data_first_right_user = tree_data($first_right_user);
                                                    $third_left_user = $data_first_right_user['left'];
                                                    $thidr_right_user = $data_first_right_user['right'];

                                                    $data_first_left_credits = tree_data($second_left_user);
                                                    $user_first_left_credits = $data_first_left_credits['leftcredits'];
                                                    $user_first_right_credits = $data_first_left_credits['rightcredits'];

                                                    $data_first_right_credits = tree_data($second_right_user);
                                                    $usertwo_first_left_credits = $data_first_right_credits['leftcredits'];
                                                    $usertwo_first_right_credits = $data_first_right_credits['rightcredits'];

                                                    $thirdcredits = tree_data($third_left_user);
                                                    $userthree_first_left_credits = $thirdcredits['leftcredits'];
                                                    $userthree_first_right_credits = $thirdcredits['rightcredits'];

                                                    $fourthcredits = tree_data($thidr_right_user);
                                                    $userfour_first_left_credits = $fourthcredits['leftcredits'];
                                                    $userfour_first_right_credits = $fourthcredits['rightcredits'];
                                                    ?>
                                                    <?php
                                                    if ($second_left_user != "") {
                                                        ?>
                                                        <td><a href="tree.php?search-id=<?php echo $second_left_user ?>">

                                                                <!-- change image according to gender -->
                                                                <?php
                                                                $getgenderfour = "SELECT Gender FROM users WHERE Username = '$second_left_user'";
                                                                $querygenderfour = mysqli_query($conn, $getgenderfour);
                                                                $genderfour = mysqli_fetch_array($querygenderfour);
                                                                $mygenderfour = $genderfour['Gender'];
                                                                if ($mygenderfour == 1) {
                                                                    ?><img src="../images/useravatar.png" alt=""><?php
                                                                } else {
                                                                    ?>
                                                                    <img src="../images/useravatarf.png" alt=""><?php
                                                        }
                                                                ?>
                                                                <!-- //change image according to gender -->

                                                                <p><?php echo $second_left_user ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                                    <!-- Get Rank -->
                                                                    <?php
                                                                    $getrankfour = "SELECT * FROM rank WHERE Username = '$second_left_user'";
                                                                    $queryrankfour = mysqli_query($conn, $getrankfour);
                                                                    $rankfour = mysqli_fetch_array($queryrankfour);
                                                                    $myrankfour = $rankfour['Rank'];
                                                                    $myrankidfour = $rankfour['Rankid'];
                                                                    echo $myrankfour;
                                                                    ?>
                                                                    <!-- //Get Rank -->

                                                                </p><p><i class="fa fa-money" style="color:#361515"></i>  Left Credits: <?php echo $user_first_left_credits ?> <p><i class="fa fa-money" style="color:#361515"></i> Right Credits: <?php echo $user_first_right_credits ?></p></a></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td><img src="../images/useravatarn.png"><p><form action="jointhree.php" method="post"><?php
                                                    $query1 = "SELECT * FROM users WHERE Sponsor = '" . $_SESSION['Username'] . "' AND Status='Close' AND treestatus='notree'";
                                                    $result1 = mysqli_query($conn, $query1);
                                                        ?>
                                                                <select  name="Usernamethree" id="Usernamethree" onchange="">
                                                                    <option>Choose Member</option>
                                                                    <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                        <option value="<?php echo $row1[6]; ?>"><?php echo $row1[6]; ?></option>
                                                                    <?php endwhile; ?>

                                                                </select>
                                                                <input type="hidden" name="under_useridthree" id="under_useridthree" value="<?php echo $first_left_user ?>">
                                                                <input type="hidden" name="sidethree" id="sidethree" value="left">
                                                                <button id="join_userthree" type="submit" class="btn btn-primary">Add Member</button></p></td></form>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($second_right_user != "") {
                                                        ?>
                                                        <td><a href="tree.php?search-id=<?php echo $second_right_user ?>">

                                                                <!-- change image according to gender -->
                                                                <?php
                                                                $getgenderfive = "SELECT Gender FROM users WHERE Username = '$second_right_user'";
                                                                $querygenderfive = mysqli_query($conn, $getgenderfive);
                                                                $genderfive = mysqli_fetch_array($querygenderfive);
                                                                $mygenderfive = $genderfive['Gender'];
                                                                if ($mygenderfive == 1) {
                                                                    ?><img src="../images/useravatar.png" alt=""><?php
                                                                } else {
                                                                    ?>
                                                                    <img src="../images/useravatarf.png" alt=""><?php
                                                        }
                                                                ?>
                                                                <!-- //change image according to gender -->

                                                                <p><?php echo $second_right_user ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                                    <!-- Get Rank -->
                                                                    <?php
                                                                    $getrankfive = "SELECT * FROM rank WHERE Username = '$second_right_user'";
                                                                    $queryrankfive = mysqli_query($conn, $getrankfive);
                                                                    $rankfive = mysqli_fetch_array($queryrankfive);
                                                                    $myrankfive = $rankfive['Rank'];
                                                                    $myrankidfive = $rankfive['Rankid'];
                                                                    echo $myrankfive;
                                                                    ?>
                                                                    <!-- //Get Rank -->

                                                                </p><p><i class="fa fa-money" style="color:#361515"></i>  Left Credits: <?php echo $usertwo_first_left_credits ?><p><i class="fa fa-money" style="color:#361515"></i> Right Credits: <?php echo $usertwo_first_right_credits ?></p></p></a></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td><img src="../images/useravatarn.png"><p><form action="joinfour.php" method="post"><?php
                                                    $query1 = "SELECT * FROM users WHERE Sponsor = '" . $_SESSION['Username'] . "' AND Status='Close' AND treestatus='notree'";
                                                    $result1 = mysqli_query($conn, $query1);
                                                        ?>
                                                                <select  name="Usernamefour" id="Usernamefour" onchange="">
                                                                    <option>Choose Member</option>
                                                                    <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                        <option value="<?php echo $row1[6]; ?>"><?php echo $row1[6]; ?></option>
                                                                    <?php endwhile; ?>

                                                                </select> 
                                                                <input type="hidden" name="under_useridfour" id="under_useridfour" value="<?php echo $first_left_user ?>">
                                                                <input type="hidden" name="sidefour" id="sidefour" value="right">
                                                                <button id="join_userfour" type="submit" class="btn btn-primary">Add Member</button></p></td></form>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($third_left_user != "") {
                                                        ?>
                                                        <td><a href="tree.php?search-id=<?php echo $third_left_user ?>">

                                                                <!-- change image according to gender -->
                                                                <?php
                                                                $getgendersix = "SELECT Gender FROM users WHERE Username = '$third_left_user'";
                                                                $querygendersix = mysqli_query($conn, $getgendersix);
                                                                $gendersix = mysqli_fetch_array($querygendersix);
                                                                $mygendersix = $gendersix['Gender'];
                                                                if ($mygendersix == 1) {
                                                                    ?><img src="../images/useravatar.png" alt=""><?php
                                                                } else {
                                                                    ?>
                                                                    <img src="../images/useravatarf.png" alt=""><?php
                                                        }
                                                                ?>
                                                                <!-- //change image according to gender -->

                                                                <p><?php echo $third_left_user ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                                    <!-- Get Rank -->
                                                                    <?php
                                                                    $getranksix = "SELECT * FROM rank WHERE Username = '$third_left_user'";
                                                                    $queryranksix = mysqli_query($conn, $getranksix);
                                                                    $ranksix = mysqli_fetch_array($queryranksix);
                                                                    $myranksix = $ranksix['Rank'];
                                                                    $myrankidsix = $ranksix['Rankid'];
                                                                    echo $myranksix;
                                                                    ?>
                                                                    <!-- //Get Rank -->
                                                                </p><p><i class="fa fa-money" style="color:#361515"></i>  Left Credits: <?php echo $userthree_first_left_credits ?><p><i class="fa fa-money" style="color:#361515"></i> Right Credits: <?php echo $userthree_first_right_credits ?></p></p></a></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td><img src="../images/useravatarn.png">

                                                            <p><form action="joinfive.php" method="post"><?php
                                                    $query1 = "SELECT * FROM users WHERE Sponsor = '" . $_SESSION['Username'] . "' AND Status='Close' AND treestatus='notree'";
                                                    $result1 = mysqli_query($conn, $query1);
                                                        ?>
                                                                <select  name="Usernamefive" id="Usernamefive" onchange="">
                                                                    <option>Choose Member</option>
                                                                    <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                        <option value="<?php echo $row1[6]; ?>"><?php echo $row1[6]; ?></option>
                                                                    <?php endwhile; ?>

                                                                </select>
                                                                <input type="hidden" name="under_useridfive" id="under_useridfive" value="<?php echo $first_right_user ?>">
                                                                <input type="hidden" name="sidefive" id="sidefive" value="left">
                                                                <button id="join_userfive" type="submit" class="btn btn-primary">Add Member</button></p></td></form>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php
                                                    if ($thidr_right_user != "") {
                                                        ?>
                                                        <td><a href="tree.php?search-id=<?php echo $thidr_right_user ?>">

                                                                <!-- change image according to gender -->
                                                                <?php
                                                                $getgenderseven = "SELECT Gender FROM users WHERE Username = '$thidr_right_user'";
                                                                $querygenderseven = mysqli_query($conn, $getgenderseven);
                                                                $genderseven = mysqli_fetch_array($querygenderseven);
                                                                $mygenderseven = $genderseven['Gender'];
                                                                if ($mygenderseven == 1) {
                                                                    ?><img src="../images/useravatar.png" alt=""><?php
                                                                } else {
                                                                    ?>
                                                                    <img src="../images/useravatarf.png" alt=""><?php
                                                        }
                                                                ?>
                                                                <!-- //change image according to gender -->

                                                                <p><?php echo $thidr_right_user ?></p><p><i class="fa fa-graduation-cap" style="color:#361515"></i>Rank: 
                                                                    <!-- Get Rank -->
                                                                    <?php
                                                                    $getrankseven = "SELECT * FROM rank WHERE Username = '$thidr_right_user'";
                                                                    $queryrankseven = mysqli_query($conn, $getrankseven);
                                                                    $rankseven = mysqli_fetch_array($queryrankseven);
                                                                    $myrankseven = $rankseven['Rank'];
                                                                    $myrankidseven = $rankseven['Rankid'];
                                                                    echo $myrankseven;
                                                                    ?>
                                                                    <!-- //Get Rank -->

                                                                </p><p><i class="fa fa-money" style="color:#361515"></i>  Left Credits: <?php echo $userfour_first_left_credits ?><p><i class="fa fa-money" style="color:#361515"></i> Right Credits: <?php echo $userfour_first_right_credits ?></p></p></a></td>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <td><img src="../images/useravatarn.png"><p><form action="joinsix.php" method="post"><?php
                                                    $query1 = "SELECT * FROM users WHERE Sponsor = '" . $_SESSION['Username'] . "' AND Status='Close' AND treestatus='notree'";
                                                    $result1 = mysqli_query($conn, $query1);
                                                        ?>
                                                                <select  name="Usernamesix" id="Usernamesix" onchange="">
                                                                    <option>Choose Member</option>
                                                                    <?php while ($row1 = mysqli_fetch_array($result1)):; ?>
                                                                        <option value="<?php echo $row1[6]; ?>"><?php echo $row1[6]; ?></option>
                                                                    <?php endwhile; ?>

                                                                </select> 
                                                                <input type="hidden" name="under_useridsix" id="under_useridsix" value="<?php echo $first_right_user ?>">
                                                                <input type="hidden" name="sidesix" id="sidesix" value="right">
                                                                <button id="join_usersix" type="submit" class="btn btn-primary">Add Member</button></p></i></td></form>
                                                                <?php
                                                            }
                                                            ?>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Binary Tree -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /page content -->

            <!-- footer content -->

            <!-- /footer content -->
        </div>
    </div>

    <?php
    include('includes/footer.php');
    ?>
</body>
</html>
