<?php require_once "header.html"; ?>

    <p class="introduction">Your flashcards are waiting. Log in or sign up to start studying.</p>
    <hr class="centered-line">

    <section id="login-container">
        <h2>Login</h2>
        <p class="form-error" id="loginError"></p>
        <div class="form-group">
            <label for="login-username">Username or Email</label>
            <input type="text" id="login-username" placeholder="Enter username or email" required>
        </div>
        <div class="form-group">
            <label for="login-password">Password</label>
            <input type="password" id="login-password" placeholder="Enter password" required>
        </div>
        <button class="loginbutton" id="loginBtn" onclick="submitLogin()">Log In</button>
        <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
    </section>

    <script>
        async function submitLogin() {
            const username = document.getElementById("login-username").value.trim();
            const password = document.getElementById("login-password").value.trim();
            const errorEl  = document.getElementById("loginError");
            const btn      = document.getElementById("loginBtn");

            errorEl.textContent = "";

            if (!username || !password) {
                errorEl.textContent = "Please fill in all fields.";
                return;
            }

            btn.disabled    = true;
            btn.textContent = "Logging in...";

            try {
                const res  = await fetch("php/auth_login.php", {
                    method:  "POST",
                    headers: { "Content-Type": "application/json" },
                    body:    JSON.stringify({ username, password })
                });
                const data = await res.json();

                if (data.success) {
                    window.location.href = "decks.php";
                } else {
                    errorEl.textContent = data.error || "Something went wrong.";
                    btn.disabled    = false;
                    btn.textContent = "Log In";
                }
            } catch (e) {
                errorEl.textContent = "Could not connect to server.";
                btn.disabled    = false;
                btn.textContent = "Log In";
            }
        }

        document.addEventListener("keydown", e => {
            if (e.key === "Enter") submitLogin();
        });
    </script>

</body>
</html>