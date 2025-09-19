document.getElementById("registerForm").addEventListener("submit", function (e) {
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirm_password").value;
  const currency = document.getElementById("currency").value;

  if (password.length < 6) {
    alert("Password must be at least 6 characters long.");
    e.preventDefault();
    return;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    e.preventDefault();
    return;
  }

  if (!currency) {
    alert("Please select a default currency.");
    e.preventDefault();
    return;
  }
});
