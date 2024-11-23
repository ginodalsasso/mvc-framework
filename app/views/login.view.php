<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Sign In</title>
	</head>
	<body>
		<main>
			<form method="POST">
			
				<?php if (!empty($data['errors'])): ?>
					<div class="alert alert-danger">
						<?php foreach ($data['errors'] as $error): ?>
							<p><?= htmlspecialchars($error) ?></p>
						<?php endforeach; ?>
					</div>
				<?php endif; ?> 

				<input name="email" type="email" placeholder="name@example.com">
				<label>Email address</label>
				<div><?= $user->getError('email') ?></div>

				<input name="password" type="password"placeholder="Password">
				<label>Password</label>
				<div><?= $user->getError('password') ?></div>
				<!--
				<div class="form-check text-start my-3">
					<input type="checkbox" value="remember-me">
					<label>
						Remember me
					</label>
				</div>
				-->
				<button type="submit">Sign in</button>
				<a href="<?= ROOT ?>">Home</a>
				<a href="<?= ROOT ?>/register">Register</a>
			</form>
		</main>
	</body>

</html>