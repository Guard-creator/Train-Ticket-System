<?php
// ── Session & Auth ────────────────────────────────────────────────
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket</title>
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

                /* ── Topbar ──────────────────────────────────────────────────────── */
        .topbar {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .topbar span {
            font-size: 30px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        .logout-btn {
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.28);
            transform: translateY(-1px);
        }

                /* ── Topbar Actions ──────────────────────────────────────────────── */
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .topbar-btn {
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }

        .topbar-btn:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-1px);
        }

        /* ── Container ───────────────────────────────────────────── */
        .container {
            position: relative;
            z-index: 1;
            width: 88%;
            max-width: 860px;
            margin: 3rem auto;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        /* ── Heading ─────────────────────────────────────────────── */
        h1 {
            text-align: center;
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
        }

        /* ── City Boxes ──────────────────────────────────────────── */
        .cities {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }

        .city-box {
            flex: 1;
            min-width: 140px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            padding: 1.2rem 1rem;
            text-align: center;
            transition: background 0.2s, transform 0.2s;
        }

        .city-box:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-3px);
        }

        .city-box h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #fff;
        }

        .city-box p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.55);
        }

        /* ── Form ────────────────────────────────────────────────── */
        form {
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
        }

        label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 4px;
            display: block;
        }

        input,
        select {
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

        input:focus,
        select:focus {
            border-color: rgba(255, 255, 255, 0.45);
            background: rgba(255, 255, 255, 0.15);
        }

        input[readonly] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        select option {
            background: #1a1a2e;
            color: #fff;
        }

        /* ── Dynamic Age Inputs ──────────────────────────────────── */
        #ageInputs {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
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
            margin-top: 0.5rem;
            transition: background 0.2s, transform 0.15s;
        }

        button[type="submit"]:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }

        button[type="submit"]:active {
            transform: scale(0.99);
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 600px) {
            .container {
                width: 95%;
                padding: 1.5rem;
            }

            .cities {
                flex-direction: column;
            }

            h1 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>

<div class="topbar">
    <span>👋 Welcome, <?= htmlspecialchars($_SESSION['name']) ?></span>
    <div class="topbar-actions">
        <a class="topbar-btn" href="mybookings.php">My Bookings</a>
        <a class="logout-btn" href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <h1>🎫 Book Your Ticket</h1>

    <div class="cities">
        <div class="city-box">
            <h2>Karachi</h2>
            <p>Rs. 2,000 / per person</p>
        </div>
        <div class="city-box">
            <h2>Lahore</h2>
            <p>Rs. 4,000 / per person</p>
        </div>
        <div class="city-box">
            <h2>Sukkur</h2>
            <p>Rs. 1,500 / per person</p>
        </div>
    </div>

    <form action="summary.php" method="POST">

        <div>
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name"
                   value="<?= htmlspecialchars($_SESSION['name']) ?>" required readonly>
        </div>

        <div>
            <label for="city">Destination City</label>
            <select id="city" name="city" required>
                <option value="">-- Select a City --</option>
                <option value="Karachi">Karachi</option>
                <option value="Lahore">Lahore</option>
                <option value="Sukkur">Sukkur</option>
            </select>
        </div>

        <div>
            <label for="count">Number of Passengers</label>
            <input type="number" id="count" name="count" min="1" max="10"
                   placeholder="Enter number of passengers" required>
        </div>

        <div id="ageInputs"></div>

        <button type="submit">Book Now →</button>

    </form>

</div>

<script>
    const countInput = document.getElementById("count");
    const ageInputs  = document.getElementById("ageInputs");

    countInput.addEventListener("input", () => {
        ageInputs.innerHTML = '';
        const count = parseInt(countInput.value);

        if (!count || count < 1) return;

        for (let i = 1; i <= count; i++) {
            const wrapper = document.createElement("div");

            const label = document.createElement("label");
            label.textContent = `Age of Passenger ${i}`;

            const input = document.createElement("input");
            input.type        = "number";
            input.name        = `age${i}`;
            input.placeholder = `Enter age of passenger ${i}`;
            input.min         = "1";
            input.required    = true;

            wrapper.appendChild(label);
            wrapper.appendChild(input);
            ageInputs.appendChild(wrapper);
        }
    });
</script>

</body>
</html>