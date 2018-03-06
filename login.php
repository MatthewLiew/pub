<?php 
    include 'header.php';
    $error = ''; // Variable To Store Error Message   
    if (isset($_POST['submit'])) {        
        if (empty($_POST['user_id']) || empty($_POST['user_pass'])) {
            $error = "Username or Password is invalid";
        }
        else{
            // Define $username and $password
            $user_id = $_POST['user_id'];
            $user_pass = $_POST['user_pass'];
            $login_result = check_user_login($user_id, $user_pass);
            if($login_result[0] == false)
                $error = $login_result[1];
            else {
                if($_SESSION['login_user_role']=='1')
                    header("location: index.php");
                else{
                    $checked = check_first_time($user_id);
                    //echo $checked; exit;
                    //order_history
                    if($checked==0)
                        header("location: user_profile.php");
                    else
                        header("location: order_history.php");
                }
                    
            }
        }
    }
?>
        <div class="container">
            <div class="card card-login mx-auto mt-5">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <?php 
                        if($error!=""){
                            echo "<label style='color:red'>" . $error . "</label>";
                        }
                    ?>
                    <form action="login.php" method="POST" id="submit">
                        <div class="form-group">
                            <label for="UserID">User ID / Phone Number</label>
                            <input class="form-control" id="user_id" name="user_id" type="text"  placeholder="User ID">
                        </div>
                        <div class="form-group">
                            <label for="UserPassword">Password</label>
                            <input class="form-control" id="user_pass" name="user_pass" type="password" placeholder="Password">
                        </div>
						<!--
                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"> Remember Password</label>
                            </div>
                        </div>
						-->
                        <!-- <a class="btn btn-primary btn-block" href="index.html">Login</a>-->
                        <button class="btn btn-primary btn-block" type="submit" name="submit">Login</button>
                    </form>
                    <div class="text-center">
                        <!-- <a class="d-block small mt-3" href="register.html">Register an Account</a> 
                        <br/>
                        <a class="d-block small" href="forgot-password.html">Forgot Password?</a>-->
                    </div>
                </div>
            </div>
        </div>
		<style>
			.card-login {
				max-width: 40rem!important;
			}
			.mt-5, .my-5 {
				margin-top: 5rem!important;
			}
			.card-header{
				padding: 1.75rem 1.25rem;
				font-size: large;
				font-weight: 600; 
			}
			label {
				padding-bottom: 10px;
			}
			.card-body{
				padding: 20px 20px 50px 20px;
			}
		</style>
<?php include 'footer.php'; ?>