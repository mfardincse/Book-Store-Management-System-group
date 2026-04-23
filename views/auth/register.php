<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">
  <div class="card">
    <h2>Create Account</h2>

    <form method="post" id="registerForm" novalidate>
      <input type="text" id="name" name="name" placeholder="Name">
      <small id="nameErr" style="color:red;display:block;margin-top:6px;"></small>
      <br>

      <input type="email" id="email" name="email" placeholder="Email">
      <small id="emailErr" style="color:red;display:block;margin-top:6px;"></small>
      <br>

      <input type="password" id="password" name="password" placeholder="Password">
      <small id="passErr" style="color:red;display:block;margin-top:6px;"></small>
      <br><br>

      <button type="submit">Register</button>
    </form>
  </div>
</div>

<script>
(function () {
  const form = document.getElementById("registerForm");
  const name = document.getElementById("name");
  const email = document.getElementById("email");
  const password = document.getElementById("password");

  const nameErr = document.getElementById("nameErr");
  const emailErr = document.getElementById("emailErr");
  const passErr = document.getElementById("passErr");

  function setError(input, errEl, msg) {
    errEl.textContent = msg;
    input.style.border = msg ? "1px solid red" : "";
  }

  function isValidEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  function validate() {
    let ok = true;

    // Name
    if (name.value.trim() === "") {
      setError(name, nameErr, "Name is required.");
      ok = false;
    } else {
      setError(name, nameErr, "");
    }

    // Email
    const e = email.value.trim();
    if (e === "") {
      setError(email, emailErr, "Email is required.");
      ok = false;
    } else if (!isValidEmail(e)) {
      setError(email, emailErr, "Enter a valid email address.");
      ok = false;
    } else {
      setError(email, emailErr, "");
    }

    // Password
    if (password.value.trim() === "") {
      setError(password, passErr, "Password is required.");
      ok = false;
    } else if (password.value.length < 4) {
      setError(password, passErr, "Password must be at least 4 characters.");
      ok = false;
    } else {
      setError(password, passErr, "");
    }

    return ok;
  }

  // live validation
  name.addEventListener("input", validate);
  email.addEventListener("input", validate);
  password.addEventListener("input", validate);

  // on submit
  form.addEventListener("submit", function (e) {
    if (!validate()) e.preventDefault();
  });
})();
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
