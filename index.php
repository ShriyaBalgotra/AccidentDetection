<?php
	session_start();
	include('Database/Database.php');
	//phpinfo();
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--    <link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.min.css">-->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    
</head>
<body style="background:#f5eef8">
    <div class="page-header" style="background: #49274a;margin-top: 0;color:white">
    <div class="container">
        
        <div class="row">
            <div class="col-md-9">
            <a href="userinfo.php" class="logo" style="float: left; text-decoration: none"><h2 style="color: white;font-size: 37px;">Safety First</h2></a>
                </div>
            
            
        </div>
            </div>
      </div>
	<div class="container" >
	<center><h2>Login Form</h2></center>
		
        <center><img src="https://img.icons8.com/ios/50/000000/user-male-circle-filled.png"></center>
        
        <form action="index.php" method="post" style="width:300px; margin:0 auto">
            <div class="form-group has-feedback">
                <label for="username">Username</label>
                <input id="username" class="form-control" type="text" placeholder="Enter username" name="username" required>
                <i class="glyphicon glyphicon-user form-control-feedback"></i>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" name="password" class="form-control" type="password" placeholder="Your password" required style="margin-bottom:5px;">
            </div>
            <center>
            <button type="submit" class="btn btn-success" name="login" style="display:block;margin:5px ">Login</button>
            <a href="register.php" class="btn btn-info" role="button" style="position:relative;left:">Register</a>
            </center>
        </form>
        

		<?php
			if(isset($_POST['login']))
			{
				$username=$_POST['username'];
				$password=$_POST['password'];
				$query = "select * from user where username= '$username' and password='$password' ";
				//$query = "DESCRIBE user";
				//echo $query;
				$stmt = db2_prepare($connect,$query);
    			$query_run = db2_execute($stmt);//,array($username,$password));
				//echo mysql_num_rows($query_run);
	
				if($query_run)
				{
				    $row = db2_fetch_both($stmt);
				    //echo $row['userid'];
					if($row)
					{
					//$row = db2_fetch_assoc($stmt);

					$_SESSION['username'] = $username;
					$_SESSION['password'] = $password;
					$_SESSION['uid']=$row[0];
					
					header( "Location: userinfo.php");
					}
					else
					{
						echo '<script type="text/javascript">alert("No such User exists. Invalid Credentials")</script>';
					}
				}
				else
				{
					echo '<script type="text/javascript">alert("Database Error")</script>';
				}
			}
			else
			{
			}
		?>

	</div>
<!--    <script src="css/bootstrap/js/bootstrap.js"></script>-->
    <script src="google_maps.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
</body>
    
    <footer>
    <div style="background: #49274a;height: 50px;position:fixed;bottom: 0;width:100%;color:white;">
      <div class="text-center" style="position: relative;top: 40%">
        <small>&copy; 2016-2019 All rights reserved.</small>
        </div>
    </div>
    </footer>
</html>
