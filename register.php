<?php
require_once "assets\db_details\db_details.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $confirm_password_err = $email_err="";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
          }
  }
  if(empty(trim($_POST["email"]))){
      $email_err = "Please enter a email.";
  } elseif(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', trim($_POST["email"]))){
      $email_err = "Invalid E-mail";
  }else{
    $email = trim($_POST["email"]);
  }

  // Validate password
  if(empty(trim($_POST["password"]))){
      $password_err = "Please enter a password.";
  } elseif(strlen(trim($_POST["password"])) < 6){
      $password_err = "Password must have atleast 6 characters.";
  } else{
      $password = trim($_POST["password"]);
  }

  // Validate confirm password
  if(empty(trim($_POST["confirm_password"]))){
      $confirm_password_err = "Please confirm password.";
  } else{
      $confirm_password = trim($_POST["confirm_password"]);
      if(empty($password_err) && ($password != $confirm_password)){
          $confirm_password_err = "Password did not match.";
      }
  }

  // Check input errors before inserting in database
  if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

      // Prepare an insert statement
      $sql = "INSERT INTO users (username, password, type, email) VALUES (:username, :password, 2, :email)";

      if($stmt = $pdo->prepare($sql)){
          // Bind variables to the prepared statement as parameters
          $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
          $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
          $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

          // Set parameters
          $param_email = $email;
          $param_username = $username;
          $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

          // Attempt to execute the prepared statement
          if($stmt->execute()){
              // Redirect to login page
              header("location: login.php");
          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }

          // Close statement
          unset($stmt);
      }
  }

  // Close connection
  unset($pdo);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<head>
  <style>
  .gradient-custom-3 {
/* fallback for old browsers */
background: #84fab0;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, rgba(132, 250, 176, 0.5), rgba(143, 211, 244, 0.5))
}
.gradient-custom-4 {
/* fallback for old browsers */
background: #84fab0;

/* Chrome 10-25, Safari 5.1-6 */
background: -webkit-linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1));

/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
background: linear-gradient(to right, rgba(132, 250, 176, 1), rgba(143, 211, 244, 1))
}
  </style>
<body>

  <section class="bg-image"
    style="background-image: url('images/bg/loginbg.jpg');  background-position: center; background-repeat: no-repeat;">
    <div class="mask d-flex align-items-center h-100 gradient-custom-3">
      <div class="container h-100 my-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="card" style="border-radius: 15px;">
              <div class="card-body p-5">
                <h2 class="text-uppercase text-center mb-5">Create an account</h2>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                  <div class="form-outline mb-4">
                    <input type="text" name="username" id="form3Example1cg" class="form-control form-control-lg <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" />
                    <label class="form-label" for="form3Example1cg">Your Name</label>
                    <span class="invalid-feedback"><?php echo $username_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="email" name="email" id="form3Example3cg" class="form-control form-control-lg <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" />
                    <label class="form-label" for="form3Example3cg">Your Email</label>
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="form3Example4cg" class="form-control form-control-lg <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" />
                    <label class="form-label" for="form3Example4cg">Password</label>
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                  </div>

                  <div class="form-outline mb-4">
                    <input type="password" name="confirm_password" id="form3Example4cdg" class="form-control form-control-lg <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" />
                    <label class="form-label" for="form3Example4cdg">Repeat your password</label>
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                  </div>

                  <div class="d-flex justify-content-center">
                    <input type="submit"
                      class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" value="submit">
                  </div>

                  <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="loginv2.php"
                      class="fw-bold text-body"><u>Login here</u></a></p>

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<body>
</html>
