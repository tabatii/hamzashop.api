<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" />
	<style>
		* {
			font-family: 'Roboto', sans-serif;
			font-size: 16px;
		}
	</style>
</head>
<body>
	<div class="container">
		<p><b>Name :</b> {{ $name }}</p>
		<p><b>Email :</b> {{ $email }}</p>
		<p><b>Message :</b> {{ $msg }}</p>
	</div>
</body>
</html>