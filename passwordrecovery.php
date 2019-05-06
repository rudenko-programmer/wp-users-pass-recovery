<?php
/** Change login data here */
/** authorisation login */
define('PS_LOGIN', 'admin');
/** authorisation password */
define('PS_PASSWORD', 'password');
?>
<?php include(__DIR__.'/wp-config.php'); ?>
<?php session_start(); ?>
<?php
    /** Login rules */
    if(!isset($_SESSION['logout_time']) || $_SESSION['logout_time'] < time()){
	    $_SESSION['is_loged_in'] = false;
    }
    if(!empty($_POST) && empty($_SESSION['is_loged_in']) && isset($_POST['login']) && isset($_POST['pass'])){
        if (PS_LOGIN === $_POST['login'] && PS_PASSWORD === $_POST['pass']){
	        $_SESSION['is_loged_in'] = true;
	        $_SESSION['logout_time'] = time()+60*5;
        }
    }
    if(!empty($_POST) && isset($_POST['logout'])){
	    $_SESSION['is_loged_in'] = false;
	    $_SESSION['logout_time'] = 0;
    }
?>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>WordPress recovery tools</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .checkbox {
            font-weight: 400;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body>
<?php if($_SESSION['is_loged_in']): ?>
    <div class="container">
        <div class="text-center">
            <form class="form-signin xs-6 text-center" method="post">
                <input type="hidden" name="logout" value="1">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Log out</button>
            </form>
        </div>
    </div>
    <div class="container">
		<?php
		if(isset($_POST)){
			if(isset($_POST['pass']) && !empty($_POST['pass'])
			   && isset($_POST['user_id']) && !empty($_POST['user_id']) && $_POST['user_id'] > 0)
			{
				$user_id = wp_update_user([
					'ID' => $_POST['user_id'],
					'user_pass' => $_POST['pass']
				]);

				if ( is_wp_error( $user_id ) ) {
					?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Oooops!</strong> Something went wrong? please try again.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
					<?php
				} else {
					?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Congratulation!</strong> Password changed successfully.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
					<?php
				}
			}
		}
		$users = get_users();
		?>
        <table class="table table-hover table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Login</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Roles</th>
                <th scope="col">Date</th>
                <th scope="col">Activity</th>
            </tr>
            </thead>
            <tbody>
			<?php
			/**
			 * @var int $key
			 * @var \WP_User $user
			 */
			foreach ($users as $key => $user):
				?>
                <tr>
                    <th scope="row"><?php echo $key+1; ?></th>
                    <td><?php echo $user->user_login; ?></td>
                    <td><?php echo $user->display_name; ?></td>
                    <td><?php echo $user->user_email; ?></td>
                    <td><?php echo implode(',', $user->roles); ?></td>
                    <td><?php echo $user->user_registered; ?></td>
                    <td>
                        <form method="post" class="form-inline">
                            <div class="form-group sm-7">
                                <input type="password" class="form-control" id="inputPassword<?php echo $key; ?>" placeholder="Password" name="pass">
                                <input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary sm-5">Change</button>
                        </form>
                    </td>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
</div>
<?php else: ?>
    <div class="container">
        <div class="text-center">
            <form class="form-signin xs-6 text-center" method="post">
                <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
                <label for="inputLogin" class="sr-only">Login</label>
                <input type="text" id="inputLogin" class="form-control" placeholder="Login" required="" autofocus="" name="login">
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required="" name="pass">
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                <p class="mt-5 mb-3 text-muted">© <?php echo date('Y'); ?></p>
            </form>
        </div>
    </div>
<?php endif; ?>
</body>
</html>
