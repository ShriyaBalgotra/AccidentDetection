<?php
	session_start();
	require_once('Database/Database.php');
	//phpinfo();
?>
<!DOCTYPE html>
<html>
<head>
<title>Sign Up Page</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body style="background-color:#f5eef8">
    
    <div class="page-header" style="background: #49274a;margin-top: 0;color:white">
    <div class="container">
        
        <div class="row">
            <div class="col-md-9">
            <a href="userinfo.php" class="logo" style="float: left; text-decoration: none"><h2 style="color: white;font-size: 37px;">Safety First</h2></a>
            </div>
            
            
        </div>
            </div>
      </div>
    
	<div class="container clearfix" style="width:30%; background:white">
	<center><h2>Sign Up Form</h2></center>
		<form action="" method="post">
            <div class="form-group">
                <label for="user">Username</label>
                <input type="text" id="user" name="username" class="form-control" placeholder="Your username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="cpass">Confirm Password</label>
                <input type="password" id="cpass" name="cpassword" class="form-control" placeholder="Re-enter password">
            </div>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Your email">
            </div>
            <div class="form-group">
                <label for="phone">Contact number</label>
                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Contact number">
            </div>
            
            <div style="float:right">
            <button name="register" type="submit" class="btn btn-success">Sign Up</button>
            <a href="index.php" role="button" class="btn btn-info">Back to Login</a>
            </div>
            
<!--
			<div class="">
				<img src="imgs/avatar.png" alt="Avatar" class="avatar">
			</div>
			<div class="">
				<label><b>Username</b></label>
				<input type="text" placeholder="Enter Username" name="username" required>
				<label><b>Password</b></label>
				<input type="password" placeholder="Enter Password" name="password" required>
				<label><b>Confirm Password</b></label>
				<input type="password" placeholder="Enter Password" name="cpassword" required>
				<button name="register" class="sign_up_btn" type="submit">Sign Up</button>

				<a href="index.php"><button type="button" class="back_btn"> Back to Login</button></a>
			</div>
-->
		</form>

		<?php
			if(isset($_POST['register']))
			{
				@$username=$_POST['username'];
				@$password=$_POST['password'];
				@$cpassword=$_POST['cpassword'];
                $email=$_POST['email'];
                $phone=$_POST['phone'];
				if($password==$cpassword)
				{
					$query = "select * from user where username='$username'";
					//echo $query;
					$stmt = db2_prepare($connect, $query);
				$query_run = db2_execute($stmt);
				//echo mysql_num_rows($query_run);
				if($query_run)
					{
						if(db2_num_rows($stmt)>0)
						{
							echo '<script type="text/javascript">alert("This Username Already exists.. Please try another username!")</script>';
						}
						else
						{
							$query = "insert into user(username,password,email,phone) values('$username','$password','$email','$phone')";
							$stmt = db2_prepare($connect, $query);
							$query_run = db2_execute($stmt);
							if($query_run)
							{
								//echo '<script type="text/javascript">alert("User Registered Successfully, Please Login")</script>';
								/*$_SESSION['username'] = $username;
								$_SESSION['password'] = $password;
                                
                                $query="select userid from user where username='$username'";
                                $stmt = db2_prepare($connect, $query);
                                $result=db2_execute($stmt);
                                
                                while($row=db2_fetch_assoc($stmt))
                                {
                                    $uid=$row['userid'];
                                    echo $uid;
                                }
                                $_SESSION['uid']=$uid;*/
                                //echo("<script>window.location = 'index.php';</script>");
								//header( "Location: index.php");
								echo '<script type="text/javascript">alert("User Registered Successfully, Please Login");location="index.php";</script>';
							}
							else
							{
								echo db2_con_error().'<p class="bg-danger msg-block">Registration Unsuccessful due to server error. Please try later</p>';
							}
						}
					}
					else
					{
						echo '<script type="text/javascript">alert("DB error")</script>';
					}
				}
				else
				{
					echo '<script type="text/javascript">alert("Password and Confirm Password do not match")</script>';
				}

			}
			else
			{
			}
		?>
	</div>
    <script src="google_maps.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
    
</body>
    <footer >
    <div style="background: #49274a;height: 50px;position: relative;bottom: 0;margin-top:100px;width:100%;color:white;">
      <div class="text-center" style="position: relative;top: 40%">
        <small>&copy; 2016-2019 All rights reserved.</small>
        </div>
    </div>
    </footer>
</html>
