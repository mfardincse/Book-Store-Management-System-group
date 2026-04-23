<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container">
  <div class="card">
    <h2>Login</h2>

    <form method="post" id="loginForm" novalidate>
      <input type="email" id="email" name="email" placeholder="Email" required>
      <small id="emailErr" style="color:red;display:block;margin-top:6px;"></small>
      <br>

      <input type="password" id="password" name="password" placeholder="Password" required>
      <small id="passErr" style="color:red;display:block;margin-top:6px;"></small>
      <br><br>

      <button type="submit">Login</button>
    </form>

    <p>
      New user?
      <a href="index.php?page=register">Create new account</a>
    </p>
  </div>
</div>

<script>
(function () {
  const form = document.getElementById("loginForm");
  const email = document.getElementById("email");
  const password = document.getElementById("password");

  const emailErr = document.getElementById("emailErr");
  const passErr  = document.getElementById("passErr");

  function setError(el, errEl, msg) {
    errEl.textContent = msg;
    el.style.border = msg ? "1px solid red" : "";
  }

  function isValidEmail(v) {
    // simple email pattern
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
  }

  function validate() {
    let ok = true;

    const e = email.value.trim();
    const p = password.value;

    // Email
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
    if (p.trim() === "") {
      setError(password, passErr, "Password is required.");
      ok = false;
    } else if (p.length < 4) {   // change min length if you want
      setError(password, passErr, "Password must be at least 4 characters.");
      ok = false;
    } else {
      setError(password, passErr, "");
    }

    return ok;
  }

  // validate while typing
  email.addEventListener("input", validate);
  password.addEventListener("input", validate);

  // validate on submit
  form.addEventListener("submit", function (e) {
    if (!validate()) e.preventDefault();
  });
})();
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
