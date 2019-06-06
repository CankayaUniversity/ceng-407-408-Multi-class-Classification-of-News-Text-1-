<?php include "includes/functions.php";
if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');}
?>

<?php include "includes/header.php"; ?>

<nav class="bar bar--sm bg--dark" id="menu5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-1 hidden-xs hidden-sm">
                            <div class="bar__module">
                                <a href="index.php">
                                    <img class="logo logo-dark" alt="logo" src="img/logo-dark.png" />
                                    <img class="logo logo-light" alt="logo" src="img/logo-light.png" />
                                </a>
                            </div>
                            <!--end module-->
                        </div>
                        <div class="col-lg-5">
                            <div class="bar__module">
                                <ul class="menu-horizontal">
                                    <li>
                                        <a href="#">
                                            <i class="stack-interface stack-plus-circled"></i> Create Project
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="stack-interface stack-cog"></i> My Documents
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end columns-->
                        <div class="col-lg-6 text-right text-left-xs">
                            <div class="bar__module">
                                <ul class="menu-horizontal">
                                    <li class="dropdown">
                                        <span class="dropdown__trigger">
<!--                                            <img alt="avatar" class="avatar image--xxs" src="img/avatar-round-1.png" />-->

                                        <?php echo $_SESSION['user']['uName']; ?>
                                        </span>
                                    </li>
                                  <!--  <li class="dropdown text-left">
                                        <span class="dropdown__trigger">
                                            <i class="stack-interface stack-bell"></i> Alerts
                                        </span>
                                       <div class=" dropdown__container">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-3 col-lg-2 dropdown__content">
                                                    <ul class="menu-vertical">
                                                        <li>
                                                         <a href="#">Create</a>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                       </div>

                                    </li>-->
                                </ul>
                            </div>
                            <div class="bar__module">
                                <a class="btn btn--primary btn--sm type--uppercase" href="index.php?logout='1'">
                                    <span class="btn__text">
                                        Logout
                                    </span>
                                </a>
                            </div>
                        </div>
                        <!--end columns-->
                    </div>
                    <!--end of row-->
                </div>
                <!--end of container-->
            </nav>



		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="" >
				<h3>
					<?php
						#echo $_SESSION['success'];
						#unset($_SESSION['success']);

                      echo "<script type='text/javascript'>notifier.success('Login is successful!');</script>";

                    ?>
				</h3>
			</div>
		<?php endif ?>
		<!-- logged in user information -->
		<div class="profile_info">
<!--			<img src="images/user_profile.png"  >-->

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['uName']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['uType']); ?>)</i>
						<br>
						<a href="index.php?logout='1'" style="color: red;">Logout</a>
					</small>

				<?php endif ?>
			</div>
		</div>



<?php include "includes/footer.php"; ?>
