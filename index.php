<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome — Ticket System</title>
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
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            width: 90%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        /* ── Heading ─────────────────────────────────────────────── */
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
            color: #fff;
        }

        /* ── Buttons ─────────────────────────────────────────────── */
        .button {
            display: block;
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            letter-spacing: 0.4px;
            transition: background 0.2s, transform 0.15s;
        }

        .button:first-of-type {
            background: #ffffff;
            color: #0f0f1a;
            margin-bottom: 1rem;
        }

        .button:first-of-type:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }

        .button:last-of-type {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.25);
        }

        .button:last-of-type:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
        }

        /* ── Divider ─────────────────────────────────────────────── */
        hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1rem 0;
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 480px) {
            h1 {
                font-size: 1.4rem;
            }

            .container {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🎫 Futuristic Ticket System</h1>
    <a href="login.php" class="button">Login</a>
    <hr>
    <a href="register.php" class="button">Book a Ticket</a>
</div>

</body>
</html>