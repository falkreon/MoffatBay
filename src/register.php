<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Moffat Bay Lodge - Register</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="register.css">
	<script>
	const VALID_FAIL = "validation-fail";

	function onPasswordInput() {
		var pass = document.getElementById("password").value;

		var req_8ch = document.getElementById("req-8ch");
		if (pass.length >= 8) {
			req_8ch.classList.remove(VALID_FAIL);
		} else {
			req_8ch.classList.add(VALID_FAIL);
		}

		var req_upp = document.getElementById("req-upp");
		if (pass.match(/[A-Z]/g)) {
			req_upp.classList.remove(VALID_FAIL);
		} else {
			req_upp.classList.add(VALID_FAIL);
		}

		var req_low = document.getElementById("req-low");
		if (pass.match(/[a-z]/g)) {
			req_low.classList.remove(VALID_FAIL);
		} else {
			req_low.classList.add(VALID_FAIL);
		}

		var req_num = document.getElementById("req-num");
		if (pass.match(/[0-9]/g)) {
			req_num.classList.remove(VALID_FAIL);
		} else {
			req_num.classList.add(VALID_FAIL);
		}
		updateButton();
	}

	function updateButton() {
		let valid = true;
		valid &= document.getElementById("email").checkValidity();
		valid &= document.getElementById("firstName").checkValidity();
		valid &= document.getElementById("lastName").checkValidity();
		valid &= document.getElementById("phone").checkValidity();
		valid &= document.getElementById("password").checkValidity();
		document.getElementById("register").disabled = !valid;
	}
	</script>
</head>
<body>
	<section>
		<h1>Create an Account</h1>
		<p>(or <a href="login.php">Log In</a> instead)
		<div class="form-2col">
			<p><label for="email">Email Address</label>
			<!-- Extremely simple and permissive email regex: -->
			<!-- ^[^\s@]+@[^\s@]+\.[^\s@]+$ -->

			<!-- Nonworking - Attempted to use a more robust regex, which fails in-browser but not in regexr: -->
			<!-- (?:[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*|&quot;(?:[!#$%&'*+/=?^_`{|}~\-\x20-\x7E]|\\[!#$%&'*+/=?^_`{|}~\-\x20-\x7E])*&quot;)@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])? -->
			   <input type="text" name="email" id="email"
			   pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
			   oninput="updateButton();"
			   required
			   ></input>
			<p><label for="firstName">First Name</label>
			   <input type="text" name="firstName" id="firstName"
			   oninput="updateButton();"
			   required
			   ></input>
			<p><label for="lastName">Last Name</label>
			   <input type="text" name="lastName" id="lastName"
			   oninput="updateButton();"
			   required
			   ></input>
			<p><label for="phone">Telephone</label>
			   <input type="text" name="phone" id="phone"
			   pattern="(?:\(([0-9]{3})\)[ ]?)|(?:([0-9]{3})[ ]?)?([0-9]{3})[ ]?-?[ ]?([0-9]{4})"
			   oninput="updateButton();"
			   required
			   ></input>
			<p><label for="password">Password</label>
			   <input type="password" name="password" id="password"
			   pattern="(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z]).{8,}"
			   oninput="onPasswordInput();"
			   required
			   ></input>

			<p class="req validation-fail" id="req-8ch">Must be at least 8 characters
			<p class="req validation-fail" id="req-num">Must include one number
			<p class="req validation-fail" id="req-upp">Must include one uppercase letter
			<p class="req validation-fail" id="req-low">Must include one lowercase letter
			<div class="buttons">
				<input type="button" value="Cancel" id="cancel" onclick="">
				<input type="button" value="Register" id="register" onclick="" disabled>
			</div>
		</div>
	</section>
</body>
</html>
