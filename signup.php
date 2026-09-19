<?php require_once "header.html"; ?>

    <p class="introduction">Your flashcards are waiting. Log in or sign up to start studying.</p>
    <hr class="centered-line">

    <section id="login-container">
        <h2>Sign Up</h2>
        <p class="form-error" id="signupError"></p>
        <div class="form-group">
            <label for="signup-email">Email</label>
            <input type="email" id="signup-email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label for="signup-username">Username</label>
            <input type="text" id="signup-username" placeholder="Choose a username" required>
        </div>
        <div class="form-group">
            <label for="signup-password">Password</label>
            <input type="password" id="signup-password" placeholder="Choose a password" required>
        </div>
        <div class="form-group">
            <label for="signup-confirm">Confirm Password</label>
            <input type="password" id="signup-confirm" placeholder="Confirm your password" required>
        </div>
        <button class="loginbutton" id="signupBtn" onclick="submitSignup()">Sign Up</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </section>

    <script>
        async function submitSignup() {
            const email    = document.getElementById("signup-email").value.trim();
            const username = document.getElementById("signup-username").value.trim();
            const password = document.getElementById("signup-password").value.trim();
            const confirm  = document.getElementById("signup-confirm").value.trim();
            const errorEl  = document.getElementById("signupError");
            const btn      = document.getElementById("signupBtn");

            errorEl.textContent = "";

            if (!email || !username || !password || !confirm) {
                errorEl.textContent = "Please fill in all fields.";
                return;
            }

            if (password !== confirm) {
                errorEl.textContent = "Passwords do not match.";
                return;
            }

            btn.disabled    = true;
            btn.textContent = "Creating account...";

            try {
                const res  = await fetch("php/auth_signup.php", {
                    method:  "POST",
                    headers: { "Content-Type": "application/json" },
                    body:    JSON.stringify({ email, username, password, confirm })
                });
                const data = await res.json();

                if (data.success) {
                    window.location.href = "decks.php";
                } else {
                    errorEl.textContent = data.error || "Something went wrong.";
                    btn.disabled    = false;
                    btn.textContent = "Sign Up";
                }
            } catch (e) {
                errorEl.textContent = "Could not connect to server.";
                btn.disabled    = false;
                btn.textContent = "Sign Up";
            }
        }

        document.addEventListener("keydown", e => {
            if (e.key === "Enter") submitSignup();
        });
    </script>

</body>
</html>