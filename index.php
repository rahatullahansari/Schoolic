<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>Schoolic - Lets be School Holic!!!</title>
      <!--<link rel="icon" type="image/png" href="images/logo.png">-->
      
      <!-- Bootstrap Core CSS -->
      <link href="boot/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <!-- Theme CSS -->
      <link href="boot/css/custom.min.css" rel="stylesheet">
      <!-- Custom Fonts -->
      <link href="boot/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
      <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->

   </head>
   <body id="page-top" class="index">
      <!-- Navigation -->
      <nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
         <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
               <a class="navbar-brand" href="#page-top"> Schoolic </a>
               <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
               <span class="sr-only">Toggle navigation</span><i class="fa fa-bars"></i>
               </button>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
               <ul class="nav navbar-nav navbar-right">
                  <li class="hidden">
                     <a href="#page-top"></a>
                  </li>
                  <li class="page-scroll">
                     <a href="#page-top">Login & Signup</a>
                  </li>
                  <li class="page-scroll">
                     <a href="#services">Services</a>
                  </li>
                  <!-- <li class="page-scroll">
                     <a href="#about">About</a>
                  </li> -->
				  <!--
                  <li class="page-scroll">
                     <a href="#team">Team</a>
                  </li>
				  -->
                  <li class="page-scroll">
                     <a href="#contact">Contact</a>
                  </li>
               </ul>
            </div>
            <!-- /.navbar-collapse -->
         </div>
         <!-- /.container-fluid -->
      </nav>
      <!-- Header -->
  
      <header style="background-image:url(https://source.unsplash.com/1600x900/?school,study,class); background-repeat:no-repeat; background-position:center; background-attachment:fixed;">
         <div class="container">
            <div id="login_signup" class="row">
               <div class="col-lg-12 text-center">
                  <h3 style="color:#03a9f4;">Welcome to the Schoolic</h3>
                  <p>Space for School Holics</p>
                  <br>
                  <?php
require_once ('connectvars.php');

session_start();

$error_msg = "";

if (empty($_SESSION['hm_id']))
{
    if (!empty($error_msg))
    {
        echo '<div class="alert alert-danger" align="center"><strong>' . $error_msg . '</strong></div>';
        $error_msg = "";
    }
}
elseif (isset($_SESSION['hm_id']))
{
    echo ('<p class="login" align= "center"><br><br>You are logged in as <strong> ' . $_SESSION['id_proof'] . '</strong>.<br>Go to your home page: <a href="timeline/schoolic.php" style="cursor:pointer">Home</a><br><br>You can also logout here: <a href="logout.php" style="cursor:pointer">LogOut</a></p>');
}

//for signup
require_once ('appvars.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_POST['signup']))
{
    $_SESSION['first_name'] = $first_name = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
   //  $_SESSION['last_name'] = $last_name = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $_SESSION['id_proof'] = $id_proof = mysqli_real_escape_string($dbc, trim($_POST['idproof']));
    $_SESSION['password'] = $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $_SESSION['radio'] = $profile_type = mysqli_real_escape_string($dbc, trim($_POST['radio']));
    $password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

    if (!empty($first_name) && !empty($id_proof) && !empty($password1) && !empty($password2) && !empty($profile_type))
    {
        $email = $id_proof;
        $emailErr = 1;
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if ($password1 == $password2)
            {
               $query = "SELECT * FROM hm_profile WHERE id_proof = '$id_proof'";

               $data = mysqli_query($dbc, $query);

               if (mysqli_num_rows($data) == 0)
               {

                  $now = date("Y-m-d H:i:s");

                  $query = "INSERT INTO hm_profile(hm_id, first_name, id_proof, password, profile_type, date_of_join) VALUES" . "( 0, '$first_name', '$id_proof', SHA('$password1'), '$profile_type', '$now')";
                  mysqli_query($dbc, $query);

                  $user_id = mysqli_insert_id($dbc);
                  if (!empty($user_id))
                  {
                        $query1 = "INSERT INTO swap(user_id, box_0) VALUES($user_id, 10)";
                        mysqli_query($dbc, $query1);

                        $query2 = "INSERT INTO `chat`(`chat_id`, `sender_id`, `sent_on`, `message`, `reciver_id`, `seen`) VALUES ( 0 , 10, '$now', 'Hello, Welcome to Schoolic. If you need any support chat with me on Schoolic Support.', $user_id, 0)";
                        mysqli_query($dbc, $query2);

                        $now1 = date("l jS \of F Y");
                        $user_hm_id = $user_id;
                        $message = 'Joined Schoolic on <br><br>' . $now1;
                        $image = '';
                        $video = '';
                        $query3 = "INSERT INTO `posts` (`post_hm_id`, `desc`, `image_url`, `vid_url`,`date`,`special`) VALUES ('$user_hm_id', '$message', '$image', '$video', '$now', 1)";
                        mysqli_query($dbc, $query3);
                  }
               }
               else
               {
                  echo '<div class="alert alert-danger" align="center"><strong>An <b>account already exits</b> for this E-mail address. Please use a different address.</strong></div>';
                  $id_proof = "";
               }
               $query1 = "SELECT hm_id, id_proof FROM hm_profile WHERE id_proof = '$id_proof'";

               $data1 = mysqli_query($dbc, $query1);
               if (mysqli_num_rows($data1) == 1)
               {
                  $row1 = mysqli_fetch_array($data1);
                  $_SESSION['hm_id'] = $row1['hm_id'];
                  setcookie('hm_id', $row1['hm_id'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
                  setcookie('id_proof', $row1['id_proof'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
                  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/Work%20Space/Schoolic/timeline/schoolic.php';
                  header('Location: ' . $home_url);
                  mysqli_close($dbc);
                  exit();
               }
            }
            else
            {
               echo '<div class="alert alert-danger" align="center">You must enter the <b>same password</b> twice.</div>';
            }
        }
        else
        {
            echo '<div class="alert alert-danger" align="center">You must enter a <b>valid Email format</b>.</div>';
        }
    }
    else
    {
        echo '<div class="alert alert-danger" align="center">You must enter <b>all</b> of the signup data.</div>';
    }
}
mysqli_close($dbc);

//for login
if (!isset($_SESSION['hm_id']))
{
    if (isset($_POST['login']))
    {
        $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $user_id_proof = mysqli_real_escape_string($dbc, trim($_POST['idproof']));
        $user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));

        if (!empty($user_id_proof) && !empty($user_password))
        {
            $email = $user_id_proof;
            $emailErr = 1;
            if (filter_var($email, FILTER_VALIDATE_EMAIL))
            {
            //  $emailErr = 0;
            //  $domains = array(
            //      'kiet.edu'
            //  );
            //  $pattern = "/^[a-z0-9._%+-]+@[a-z0-9.-]*(" . implode('|', $domains) . ")$/i";
            //  if (!preg_match($pattern, $email))
            //  {
            //      echo '<div class="alert alert-danger" align="center">You must enter a <b> K.I.E.T Email format</b>.</div>';
            //  }
            //  else
            //  {
               $query = "SELECT hm_id, id_proof FROM hm_profile WHERE id_proof = '$user_id_proof' AND password = SHA('$user_password')";
               $data = mysqli_query($dbc, $query);

               if (mysqli_num_rows($data) == 1)
               {
                  $row = mysqli_fetch_array($data);
                  $_SESSION['hm_id'] = $row['hm_id'];
                  $_SESSION['id_proof'] = $row['id_proof'];
                  setcookie('id', $row['hm_id'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
                  setcookie('id_proof', $row['id_proof'], time() + (60 * 60 * 24 * 30)); // expires in 30 days
                  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/Work%20Space/Schoolic/timeline/schoolic.php';
                  header('Location: ' . $home_url);
                  mysqli_close($dbc);
                  exit();
               }
               else
               {
                  // The username/password are incorrect so set an error message
                  $error_msg = ' "Sorry, you must enter a valid username and password to log in." ';
               }
            }
        }
        else
        {
            // The username/password weren't entered so set an error message
            $error_msg = ' "Sorry, you must enter your username and password to log in." ';
        }
    }
}

if (empty($_SESSION['hm_id']))
{
    if (!empty($error_msg))
    {
        echo '<div class="alert alert-danger" align="center"><strong>' . $error_msg . '</strong></div>';
        $error_msg = "";
    }
?>
                  <div class="col-lg-6">
                     <div class="row">
                        <div class="col-lg-12 text-center">
                           <h2 style="color:#fff">Login</h2>
                           <br><br>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-11">
							      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="login_form" method="post" autocomplete="off">
                              <div class="row control-group">
                                 <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" name="idproof" value="<?php if (!empty($user_username)) echo $user_username; ?>" maxlength="40" placeholder="Email Address" id="email" required data-validation-required-message="Please enter your email address." title="Your Email Address" autofocus required="required" />
                                    <p class="help-block text-danger"></p>
                                 </div>
                              </div>
                              <div class="row control-group">
                                 <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" maxlength="16" placeholder="Password" id="password" required data-validation-required-message="Please enter your password." minlength="8" maxlength="15" title="It should be of 8 to 15 characters" required="required" />
                                    <p class="help-block text-danger"></p>
                                 </div>
                              </div>
                              <div class="row control-group">
                                 <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <br>
                                    <span>Remember me</span>
                                    <br>
                                    <input type="checkbox"  placeholder="Password">
                                    <p class="help-block text-primary"></p>
                                 </div>
                              </div>
                              <br><br>
                              <div id="success"></div>
                              <div class="row">
                                 <div class="form-group col-xs-12">
                                    <button name="login" type="submit" class="btn btn-success btn-lg" onClick="ValidateEmail(document.login_form.idproof)" title="Click me, to login into your home page">Go !</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
				  <?php
}
if (!isset($_SESSION['hm_id']))
{
?>
                  
                  <div class="col-lg-6" id="signup">
                     <div class="row">
                        <div class="col-lg-12 text-center">
                           <h2 style="color:#fff">Signup</h2>
                           <br><br>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-11">
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="signup_form" method="post" autocomplete="off">
                              <div class="row control-group">
                                 <div class="form-group col-xs-6 floating-label-form-group controls">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" id="firstname" name='firstname' maxlength="20" placeholder="Full Name" title="Enter your first name, like :- 'Rahatullah Ansari'"  required data-validation-required-message="Please enter your fullname." required/>
                                    <p class="help-block text-danger"></p>
                                 </div>
                                 <!-- <div class="form-group col-xs-6 floating-label-form-group controls">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name='lastname' maxlength="20" placeholder="Last Name" title="Enter your last name, like :- 'Ansari'" required data-validation-required-message="Please enter your lastname." required/>
                                    <p class="help-block text-danger"></p>
                                 </div> -->
                                 <div class="form-group col-xs-6 floating-label-form-group controls">
                                    <label>Email Address</label>
                                    <input type="email" class="form-control" id="idproof" name='idproof' size="20" maxlength="40" placeholder="Email Address" title="Like :- 'abcd@efgh.ijk'" required data-validation-required-message="Please enter your email address."  required/>
                                    <p class="help-block text-danger"></p>
                                 </div>
                              </div>
                              
                              <div class="row control-group">
                                 <div class="form-group col-xs-6 floating-label-form-group controls">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="password1" name='password1' minlength="8" maxlength="15" placeholder="Password" required data-validation-required-message="Please enter your password." required/>
                                    <p class="help-block text-danger"></p>
                                 </div>
                                 <div class="form-group col-xs-6 floating-label-form-group controls">
                                    <label>Conform Password</label>
                                    <input type="password" class="form-control" name='password2' minlength="8" maxlength="15" placeholder="Conform Password" required data-validation-required-message="Please enter your password." required/>
                                    <p class="help-block text-danger"></p>
                                 </div>
                              </div>
                              <div class="row control-group">
                                 <div class="form-group col-xs-12 floating-label-form-group controls">
                                    <br>
                                    <span>You are:</span>
                                    <br>
                                    <span style="color: #ffffff;">Student &nbsp;&nbsp; <input type="radio" name="radio" value="1" checked></span>  
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #ffffff;">Teacher &nbsp;&nbsp; <input type="radio" name="radio" value="2"> </span> 
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #ffffff;">Parent &nbsp;&nbsp; <input type="radio" name="radio" value="3"> </span> 
                                    <p class="help-block text-primary"></p>
                                 </div>
                              </div>
                              <br><br>
                              <div id="success"></div>
                              <div class="row">
                                 <div class="form-group col-xs-12">
                                    <button name="signup" type="submit" class="btn btn-success btn-lg" onClick="ValidateEmail(document.getElementById("idproof"))">Get in !</button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
                  <?php
}
?>
               </div>
            </div>
         </div>
      </header>
      <!-- Services Section -->
      <section id="services"  class="bg-light-gray">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 text-center">
                  <h2 class="section-heading">Services</h2>
                  <h3 class="section-subheading text-muted">Schoolic provides following services</h3>
               </div>
            </div>
            <div class="row text-center">
               <div class="col-md-4">
                  <span class="fa-stack fa-4x">
                  <i class="fa fa-circle fa-stack-2x text-primary"></i>
                  <i class="fa fa-shopping-cart fa-stack-1x fa-inverse"></i>
                  </span>
                  <h4 class="service-heading">Connectivity</h4>
                  <p class="text-muted"></p>
               </div>
               <div class="col-md-4">
                  <span class="fa-stack fa-4x">
                  <i class="fa fa-circle fa-stack-2x text-primary"></i>
                  <i class="fa fa-laptop fa-stack-1x fa-inverse"></i>
                  </span>
                  <h4 class="service-heading">Collaboration</h4>
                  <p class="text-muted"></p>
               </div>
               <div class="col-md-4">
                  <span class="fa-stack fa-4x">
                  <i class="fa fa-circle fa-stack-2x text-primary"></i>
                  <i class="fa fa-database fa-stack-1x fa-inverse"></i>
                  </span>
                  <h4 class="service-heading">Exploration</h4>
                  <p class="text-muted"></p>
               </div>
            </div>
         </div>
      </section>
      
      <!-- Team Section -->
	  <!--
      <section id="team" class="bg-light-gray">
         <div class="container">
            <div class="row">
               <div class="col-lg-12 text-center">
                  <h2 class="section-heading">Our Amazing Team</h2>
                  <h3 class="section-subheading text-muted">In search of team members.</h3>
               </div>
            </div><br><br>
            <div class="row">
               <div class="col-lg-12">
                  <div class="team-member">
                     <img src="boot/img/team/2.jpg" class="img-responsive img-circle" alt="">
                     <h4>Rahatullah Ansari</h4>
                     <p class="text-muted">Founder & C.E.O.</p>
                     <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-8 col-lg-offset-2 text-center">
                  <p class="large text-muted">We are creative minded.</p>
               </div>
            </div>
         </div>
      </section>
      <!-- Contact Section -->
      <style>
         #login_signup{
         z-index:9;
         background-color: rgb(f,f,f); /* Fallback color */
         background-color: rgba(0,0,0, 0.5); /* Black w/opacity/see-through */
         padding: 20px;
         }
         #contact_content{
         z-index:9;
         background-color: rgb(f,f,f); /* Fallback color */
         background-color: rgba(0,0,0, 0.5); /* Black w/opacity/see-through */
         padding: 50px;
         }
      </style>
      <section id="contact"  style="background-image:url(https://source.unsplash.com/1600x900/?student,teacher); background-repeat:no-repeat; background-position:center; background-attachment:fixed;"">
  
         <div class="container" id="contact_content">
            <div class="row">
               <div class="col-lg-12 text-center">
                  <h2 style="color: #fff;">Contact Us</h2>
                  <br>
                  <br>
               </div>
            </div>
            <div class="row">
               <div class="col-lg-8 col-lg-offset-2">
                  <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                  <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                  <form name="sentMessage" id="contactForm" novalidate>
                     <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                           <label>Name</label>
                           <input type="text" class="form-control" placeholder="Name" id="name" required data-validation-required-message="Please enter your name.">
                           <p class="help-block text-danger"></p>
                        </div>
                     </div>
                     <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                           <label>Email Address</label>
                           <input type="email" class="form-control" placeholder="Email Address" id="email" required data-validation-required-message="Please enter your email address.">
                           <p class="help-block text-danger"></p>
                        </div>
                     </div>
                     <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                           <label>Phone Number</label>
                           <input type="tel" class="form-control" placeholder="Phone Number" id="phone" required data-validation-required-message="Please enter your phone number.">
                           <p class="help-block text-danger"></p>
                        </div>
                     </div>
                     <div class="row control-group">
                        <div class="form-group col-xs-12 floating-label-form-group controls">
                           <label>Message</label>
                           <textarea rows="2" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Please enter a message."></textarea>
                           <p class="help-block text-danger"></p>
                        </div>
                     </div>
                     <br>
                     <div id="success"></div>
                     <div class="row">
                        <div class="form-group col-xs-12">
                           <button type="submit" class="btn btn-success btn-lg">Send</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </section>
      <!-- Footer -->
      <footer class="text-center">
         <div class="footer-above">
            <div class="container">
               <div class="row">
                  <div class="footer-col col-md-4">
                     <h3>Location</h3>
                     <p>
                        INDIA
                     </p>
                  </div>
                  <div class="footer-col col-md-4">
                     <h3>Around the Web</h3>
                     <ul class="list-inline">
                        <li>
                           <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-facebook"></i></a>
                        </li>
                        <li>
                           <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-google-plus"></i></a>
                        </li>
                        <li>
                           <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-twitter"></i></a>
                        </li>
                        <li>
                           <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-linkedin"></i></a>
                        </li>
                        <li>
                           <a href="#" class="btn-social btn-outline"><i class="fa fa-fw fa-dribbble"></i></a>
                        </li>
                     </ul>
                  </div>
                  <div class="footer-col col-md-4">
                     <h3>About Schoolic</h3>
                     <p>Schoolic is a free to use, open source of help for all.</p>
                  </div>
               </div>
            </div>
         </div>
         <div class="footer-below">
            <div class="container">
               <div class="row">
                  <div class="col-lg-12">
                     Schoolic © 2020
                  </div>
               </div>
            </div>
         </div>
      </footer>
      <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
      <div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
         <a class="btn btn-primary" href="#page-top">
         <i class="fa fa-chevron-up"></i>
         </a>
      </div>
	  <script type="text/javascript">
		  function ValidateEmail(inputText)  
			{  
			var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
			if(inputText.value.match(mailformat))  
			{  
			document.login_from.idproof.focus();  
			return true;  
			}  
			else  
			{  
			alert("You have entered an invalid Email Address!");  
			document.signup_form.idproof.focus();  
			return false;  
			}  
			}
		</script>
      <!-- jQuery -->
      <script src="boot/vendor/jquery/jquery.min.js"></script>
      <!-- Bootstrap Core JavaScript -->
      <script src="boot/vendor/bootstrap/js/bootstrap.min.js"></script>
      <!-- Plugin JavaScript -->
      <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
      <!-- Contact Form JavaScript -->
      <script src="boot/js/jqBootstrapValidation.js"></script>
      <!-- Theme JavaScript -->
      <script src="boot/js/freelancer.min.js"></script>
   </body>
</html>
