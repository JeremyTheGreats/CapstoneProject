<?php
include '../config/db.php';

$message = "";
$isSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['first'] ?? '');
    $lname = trim($_POST['last'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['mobile'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!empty($fname) && !empty($lname) && !empty($email) && !empty($phone) && !empty($address) && !empty($password) && !empty($confirm)) {
        if ($password !== $confirm) {
            $message = "Passwords do not match.";
        } else {
            $check_sql = "SELECT id FROM users WHERE email = ?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $message = "That email is already registered. Please choose another.";
            } else {
                $hashpass = password_hash($password, PASSWORD_DEFAULT);
                $role = "Resident";
                $status = "pending";

                $insert_sql = "INSERT INTO users (fname, lname, email, phone, address, password, role, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = mysqli_prepare($conn, $insert_sql);
                mysqli_stmt_bind_param($insert_stmt, "ssssssss", $fname, $lname, $email, $phone, $address, $hashpass, $role, $status);

                if (mysqli_stmt_execute($insert_stmt)) {
                    $message = "Registration successful! Your account is pending admin approval.";
                    $isSuccess = true;
                } else {
                    $message = "Something went wrong. Please try again.";
                }
                mysqli_stmt_close($insert_stmt);
            }
            mysqli_stmt_close($check_stmt);
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign up – BarangayEcoCare</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../style/global.css">
    <link rel="stylesheet" href="../style/auth.css">
    <style>
        .or-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0 14px;
            color: #9fb3bc;
            font-size: 12px;
            letter-spacing: 0.06em;
        }

        .or-divider::before,
        .or-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.12);
        }

        .social-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .social-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: transform 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
        }

        .social-btn svg {
            flex-shrink: 0;
        }

        .social-google {
            background: #13262f;
            color: #e6f1f5;
            border: 1px solid rgba(255, 255, 255, 0.16);
        }

        .social-google:hover {
            background: #19313d;
            border-color: rgba(255, 255, 255, 0.28);
            transform: translateY(-1px);
        }

        .social-facebook {
            background: #1877f2;
            color: #ffffff;
            border: 1px solid #1877f2;
        }

        .social-facebook:hover {
            background: #1568d6;
            transform: translateY(-1px);
        }

        @media (max-width: 520px) {
            .social-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="auth">
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

        <section class="auth-panel" data-aos="zoom-in">
            <form class="auth-card" action="" method="post">
                <h2>Create your account</h2>
                <p class="sub">Join your barangay in keeping it clean and green.</p>

                <?php if (!empty($message)) { ?>
                    <div style="
                        background: <?php echo $isSuccess ? '#e8f5e9' : '#ffebee'; ?>;
                        color: <?php echo $isSuccess ? '#1b5e20' : '#c62828'; ?>;
                        padding: 10px 12px;
                        border-radius: 6px;
                        margin-bottom: 15px;
                        font-size: 14px;
                    ">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php } ?>

                <div class="field-row">
                    <label class="field"><input type="text" name="first" placeholder="First name"
                            autocomplete="given-name" required></label>
                    <label class="field"><input type="text" name="last" placeholder="Last name"
                            autocomplete="family-name" required></label>
                </div>
                <label class="field"><input type="email" name="email" placeholder="Email" autocomplete="email"
                        required></label>
                <label class="field"><input type="tel" name="mobile" placeholder="Mobile number (09XX XXX XXXX)"
                        pattern="09[0-9\s]{9,12}" autocomplete="tel" required></label>
                <label class="field">
                    <select name="address" required>
                        <option value="" disabled selected>Select Purok</option>
                        <option value="Purok 1">Purok 1</option>
                        <option value="Purok 2">Purok 2</option>
                        <option value="Purok 3">Purok 3</option>
                        <option value="Purok 4">Purok 4</option>
                        <option value="Purok 5">Purok 5</option>
                        <option value="Purok 6">Purok 6</option>
                        <option value="Purok 7">Purok 7</option>
                    </select>
                </label>
                <label class="field"><input type="password" name="password"
                        placeholder="Password (at least 8 characters)" minlength="8" autocomplete="new-password"
                        required></label>
                <label class="field"><input type="password" name="confirm" placeholder="Confirm password" minlength="8"
                        autocomplete="new-password" required></label>

                <label class="check" style="margin-bottom:18px"><input type="checkbox" required> I agree to the Terms
                    and Privacy Policy</label>
                <button class="btn btn-blue btn-block" type="submit">Create Account</button>

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

                <p class="switch" style="margin-top:24px">Already have an account? <a href="login.php" class="link">Log
                        in</a></p>
            </form>
        </section>
    </div>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="../aos-init.js"></script>

</body>

</html>