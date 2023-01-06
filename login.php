<?php

include 'lib/Init.php';

$user = new UserServices($databaseCon);

if ($user->isUserOnline()) {
    header('Location: index.php');
    exit;
}
function validate($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
/* Initiating  Registration Error Handling and Messages  */
$registerErrors = [];
$validRegister = TRUE;
$displayRegisterMessage = "";
/* Activating tabs based on user input */
$tabs = ['register'=>'', 'login'=>'show active'] ;
if (isset($_POST['loginFormSubmit'])) {
    $tabs['login'] = "show active";
    if (isset($_POST['loginName']) && isset($_POST['loginPassword'])) {
        $user = new UserServices($databaseCon);
        if ($user->userLogin($_POST['loginName'], $_POST['loginPassword'])) {
            header('Location: home.php');
            exit;
        } else {
            header('Location: login.php?loginError=true');
        }

    }
} elseif (isset($_POST['registerFormSubmit'])) {
    $tabs['register'] = "show active";
    $tabs['login'] = "";
    /* First checking if data is not empty 
    Validating Full Name */
    if (isset($_POST['registerName']) && !empty($_POST['registerName'])) {
        $registerName = validate($_POST['registerName']);
        if (strlen($registerName) < 4 || strlen($registerName) > 64) {
            array_push($registerErrors, 'Full name cannot be less than 4 characters or higher than 64!');
            $validRegister = FALSE;
        }

    } else {
        array_push($registerErrors, 'Full Name cannot be empty');
        $validRegister = FALSE;
    }
    /* Validating Username */
    if (isset($_POST['registerUsername']) && !empty($_POST['registerUsername'])) {
        $registerUsername = validate($_POST['registerUsername']);
        if (strlen($registerUsername) < 4 || strlen($registerUsername) > 64) {
            array_push($registerErrors, 'Username cannot be less than 4 characters or higher than 64!');
            $validRegister = FALSE;
        }

    } else {
        array_push($registerErrors, 'Username cannot be empty');
        $validRegister = FALSE;
    }
    /* Validating Email Address */
    if (isset($_POST['registerEmail']) && !empty($_POST['registerEmail'])) {
        $registerEmail = validate($_POST['registerEmail']);
        if (strlen($registerEmail) < 4 || strlen($registerEmail) > 64) {
            array_push($registerErrors, 'Email cannot be less than 4 characters or higher than 64!');
            $validRegister = FALSE;
        }

    } else {
        array_push($registerErrors, 'Email cannot be empty');
        $validRegister = FALSE;
    }

    /*Validating Password */
    if (isset($_POST['registerPassword']) && !empty($_POST['registerPassword'])) {
        $registerPassword = validate($_POST['registerPassword']);
        if (strlen($registerPassword) < 4 || strlen($registerPassword) > 64) {
            array_push($registerErrors, 'Password cannot be less than 4 characters or higher than 64!');
            $validRegister = FALSE;
        }

    } else {
        array_push($registerErrors, 'Password cannot be empty');
        $validRegister = FALSE;
    }

    /*Validating Repeat Password*/
    if (isset($_POST['registerRepeatPassword']) && !empty($_POST['registerRepeatPassword'])) {
        $registerRepeatPassword = validate($_POST['registerRepeatPassword']);
        if (strlen($registerRepeatPassword) < 4 || strlen($registerRepeatPassword) > 64) {
            array_push($registerErrors, 'Repeat Password cannot be less than 4 characters or higher than 64!');
            $validRegister = FALSE;
        }

    } else {
        array_push($registerErrors, 'Repeat Password cannot be empty');
        $validRegister = FALSE;
    }

    /*Checking if Passwords Match*/
    if (strcmp($registerPassword, $registerRepeatPassword) !== 0) {
        array_push($registerErrors, 'Your passwords do not match!');
        $validRegister = FALSE;
    }

    $displayRegisterMessage = '';

    if($validRegister == TRUE){
        $registerCheck = $user->userRegister($registerName, $registerUsername, $registerEmail, $registerPassword);
        if(is_bool($registerCheck)){
            $registered = true;
            $displayRegisterMessage = '<div class="alert alert-success" role="alert">
        <strong> <i class="fas fa-check-square"></i> Registration Successfull!</strong><br><small>You have been registered and might now login</small></div>';
        }
        else{
            $displayRegisterMessage = '<div class="alert alert-danger" role="alert">
        <strong> <i class="fas fa-exclamation-triangle"></i> Error while registering!</strong><br><small>We have encountered an error. Please contact the administrator for further details!</small></div>';
            $registered = false;
        }
    }
    else{
        $displayRegisterMessage = '<div class="alert alert-danger" role="alert">
        <strong> <i class="fas fa-exclamation-triangle"></i> Registration Error!</strong><br><small>';
        foreach($registerErrors as $currentError){
            $displayRegisterMessage = $displayRegisterMessage . '<li>' . $currentError . '</li>';
        }
        $displayRegisterMessage = $displayRegisterMessage. '</small></div>';
    }


}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Please log in</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.1.0/mdb.min.css" rel="stylesheet" />
    <!--Custom Style-->
    <link href="tpl/style.css" rel="stylesheet" />
</head>

<body>
    <div class="container ">
        <div class="row">
            <div class="col-md-4 offset-md-4 my-5 bg-white border shadow-4">
                <img src="tpl/img/logo.png" class="img-fluid mx-auto d-block" width=70% style="margin-top:10%">
                <hr>
                <!-- Pills navs -->
                <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo $tabs['login'];?>" id="tab-login" data-mdb-toggle="pill" href="#pills-login" role="tab"
                            aria-controls="pills-login" aria-selected="true">Login</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?php echo $tabs['register'];?>" id="tab-register" data-mdb-toggle="pill" href="#pills-register" role="tab"
                            aria-controls="pills-register" aria-selected="false">Register</a>
                    </li>
                </ul>
                <!-- Pills navs -->

                <!-- Pills content -->
                <div class="tab-content">
                    <div class="tab-pane fade <?php echo $tabs['login'];?>" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
                        <form method="POST">
                            <!-- Error Display -->
                            <?php

                            if (isset($_GET['loginError'])) {
                                echo '<div class="alert alert-danger" role="alert">
                                    <strong> <i class="fas fa-exclamation-triangle"></i> Credentials Error!</strong><br>
                                    The details you have provided do not match our records. Please try again!
                                    </div>';
                            }

                            if (isset($_GET['successLogout'])) {
                                echo '<div class="alert alert-success" role="alert">
                                    <strong> <i class="fas fa-check-square"></i> You have been logged out!</strong><br>
                                    Thank you for using our services. Feel free to come back at any time!
                                    </div>';
                            }


                            ?>
                            <input type="hidden" name="loginFormSubmit">
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="loginName" name="loginName" required class="form-control" />
                                <label class="form-label" for="loginName">Username</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="loginPassword" name="loginPassword" required
                                    class="form-control" />
                                <label class="form-label" for="loginPassword">Password</label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo $tabs['register'];?>" id="pills-register" role="tabpanel" aria-labelledby="tab-register">
                        <form method="POST">
                            <!-- Check if any errors on submit -->
                            <?php
                            if(isset($validRegister)){
                                echo $displayRegisterMessage;
                            }
                            ?>
                            <input type="hidden" name="registerFormSubmit">
                        
                            <!-- Name input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="registerName" name="registerName" required
                                    class="form-control" />
                                <label class="form-label" for="registerName">Full Name</label>
                            </div>

                            <!-- Username input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="registerUsername" name="registerUsername" required
                                    class="form-control" />
                                <label class="form-label" for="registerUsername">Username</label>
                            </div>

                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" id="registerEmail" name="registerEmail" required
                                    class="form-control" />
                                <label class="form-label" for="registerEmail">Email Address</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="registerPassword" name="registerPassword" required
                                    class="form-control" />
                                <label class="form-label" for="registerPassword">Password</label>
                            </div>

                            <!-- Repeat Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="registerRepeatPassword" name="registerRepeatPassword"
                                    required class="form-control" />
                                <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                            </div>

                            <!-- Checkbox -->
                            <div class="form-check d-flex justify-content-center mb-4">
                                <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck" checked
                                    aria-describedby="registerCheckHelpText" />
                                <label class="form-check-label" for="registerCheck">
                                    I have read and agree to the <a href="terms.php">Terms</a>
                                </label>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block mb-3">Register Now</button>
                        </form>
                    </div>
                </div>
                <!-- Pills content -->
            </div>
            <div class="text-center text-secondary">&copy;2022 <?php echo SITENAME; ?></div>
        </div>
    </div>
</body>

<?php

include 'tpl/Footer.html';
?>