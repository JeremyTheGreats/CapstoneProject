<?php
session_start();
include "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT ID, fname, lname, password, role, status, provider FROM users WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            if (empty($user['password'])) {
                $provider = ucfirst($user['provider'] ?? 'Google or Facebook');
                $message = "This account uses $provider Sign-In. Please log in with $provider.";
            } elseif (password_verify($password, $user['password'])) {
                if (strtolower($user['status']) === 'active') {
                    $_SESSION['User_fname'] = $user['fname'];
                    $_SESSION['User_lname'] = $user['lname'];
                    $_SESSION['User_role'] = $user['role'];
                    $_SESSION['User_id'] = $user['ID'];

                    $role = strtolower($user['role']);
                    if ($role === "official") {
                        header("Location: official-dashboard.php");
                        exit();
                    } elseif ($role === "resident") {
                        header("Location: resident-dashboard.php");
                        exit();
                    } elseif ($role === "admin") {
                        header("Location: admin-dashboard.php");
                        exit();
                    } elseif ($role === "collector") {
                        header("Location: collector-dashboard.php");
                        exit();
                    } else {
                        $message = "Invalid user role assigned. Please contact Admin.";
                    }
                } else {
                    $message = "Your account is pending approval. Please contact the Admin.";
                }
            } else {
                $message = "Incorrect password. Please try again.";
            }
        } else {
            $message = "No account found with that email!";
        }
        mysqli_stmt_close($stmt);
    } else {
        $message = "Please enter both email and password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in – BarangayEcoCare</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/auth.css">

</head>

<body>

    <div class="auth">
        <!-- Brand panel -->
        <section class="auth-brand" data-aos="fade-right">
            <a href="../index.php" class="logo">Barangay<b>EcoCare</b></a>
            <span class="tag">CLEAN BARANGAY · GREEN FUTURE</span>
            <h1>Together for a cleaner, greener, healthier barangay</h1>
            <p class="lead">Segregate waste properly, report concerns, and earn rewards while your barangay keeps track
                of the progress.</p>
            <ul class="perks">
                <li><span class="ico">🗑</span>Report waste concerns</li>
                <li><span class="ico">🍃</span>Segregate properly</li>
                <li><span class="ico">★</span>Earn rewards</li>
                <li><span class="ico">ıl</span>Monitor progress</li>
            </ul>
            <p class="quote">Small actions make a big impact.</p>
            <div class="hills"></div>
        </section>

        <!-- Form panel -->
        <section class="auth-panel">
            <form class="auth-card" action="" method="post" data-aos="zoom-in">
                <h2>Welcome!</h2>
                <p class="sub">Log in to your account to continue.</p>

                <?php if (!empty($message)) { ?>
                    <div
                        style="background: #ffebee; color: #c62828; padding: 10px 12px; border-radius: 6px; margin-bottom: 15px; font-size: 14px;">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php } ?>

                <label class="field"><span class="sr" hidden>Email</span>
                    <input type="email" name="email" placeholder="Email" autocomplete="email" required></label>
                <label class="field"><span hidden>Password</span>
                    <input type="password" name="password" placeholder="Password" autocomplete="current-password"
                        required></label>

                <div class="row">
                    <label class="check"><input type="checkbox" name="remember" checked> Remember me</label>
                    <a href="#" class="link">Forgot password?</a>
                </div>

                <button class="btn btn-blue btn-block" type="submit">Log In</button>

                <div class="or-divider"><span>OR</span></div>

                <div class="social-row">
                    <a href="../connection/google_login.php" class="social-btn social-google">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                            <path fill="#4285F4"
                                d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.54-1.14 2.85-2.4 3.72v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.67z" />
                            <path fill="#34A853"
                                d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.11-6.72-4.96H1.29v3.14C3.26 21.3 7.31 24 12 24z" />
                            <path fill="#FBBC05"
                                d="M5.28 14.24c-.24-.72-.38-1.49-.38-2.24s.14-1.52.38-2.24V6.62H1.29C.47 8.24 0 10.06 0 12s.47 3.76 1.29 5.38l3.99-3.14z" />
                            <path fill="#EA4335"
                                d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.7 1.29 6.62l3.99 3.14c.95-2.85 3.6-4.96 6.72-4.96z" />
                        </svg>
                        <span>Continue with Google</span>
                    </a>

                    <a href="../connection/facebook_login.php" class="social-btn social-facebook">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
                            <path fill="#ffffff"
                                d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.267h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z" />
                        </svg>
                        <span>Continue with Facebook</span>
                    </a>
                </div>

                <p class="switch">New to BarangayEcoCare? <a href="signup.php" class="link">Sign up</a></p>
            </form>
        </section>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../aos-init.js"></script>
</body>

</html>