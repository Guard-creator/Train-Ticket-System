<?php
// ── Session & Config ──────────────────────────────────────────────
include 'config.php';
session_start();

// ── Handle Registration ───────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ── Check Existing Email ──────────────────────────────────────
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->get_result()->num_rows > 0
        ? $error = "An account with this email already exists."
        : null;

    // ── Insert New User ───────────────────────────────────────────
    if (empty($error)) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        $stmt->execute();

        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['name']    = $name;

        header("Location: book.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Ticket System</title>
    <style>
        /* ── Reset & Base ────────────────────────────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: url('train-background.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        /* ── Container ───────────────────────────────────────────── */
        .container {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 420px;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        /* ── Heading ─────────────────────────────────────────────── */
        h1 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.8rem;
            letter-spacing: 0.5px;
        }

        /* ── Error ───────────────────────────────────────────────── */
        .error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fca5a5;
            font-size: 13.5px;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 1.2rem;
            text-align: center;
        }

        /* ── Form ────────────────────────────────────────────────── */
        form {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            font-size: 15px;
            font-family: 'Segoe UI', sans-serif;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        input:focus {
            border-color: rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.15);
        }

        /* ── Submit Button ───────────────────────────────────────── */
        button[type="submit"] {
            width: 100%;
            padding: 13px;
            background: #ffffff;
            color: #0f0f1a;
            font-family: 'Segoe UI', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.4px;
            margin-top: 0.4rem;
            transition: background 0.2s, transform 0.15s;
        }

        button[type="submit"]:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }

        button[type="submit"]:active {
            transform: scale(0.99);
        }

        /* ── Footer Link ─────────────────────────────────────────── */
        .footer-link {
            text-align: center;
            margin-top: 1.4rem;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.55);
        }

        .footer-link a {
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            padding-bottom: 1px;
            transition: border-color 0.2s;
        }

        .footer-link a:hover {
            border-color: #fff;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 480px) {
            .container {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🎫 Register</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">

        <div>
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name"
                   placeholder="Jane Doe" required>
        </div>

        <div>
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email"
                   placeholder="you@example.com" required>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••" required>
        </div>

        <button type="submit">Create Account →</button>

    </form>

    <p class="footer-link">Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>