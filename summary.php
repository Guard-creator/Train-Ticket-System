<?php
// ── Session & Config ──────────────────────────────────────────────
include 'config.php';
session_start();

// ── Auth Guard ────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ── Handle Booking ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: book.php");
    exit;
}

$name   = $_POST['name'];
$count  = intval($_POST['count']);
$city   = $_POST['city'];

// ── Calculate Price ───────────────────────────────────────────────
$prices    = ["Karachi" => 2000, "Lahore" => 4000, "Sukkur" => 1500];
$basePrice = $prices[$city] ?? 0;
$subtotal  = 0;

for ($i = 1; $i <= $count; $i++) {
    $age       = intval($_POST["age$i"]);
    $subtotal += ($age >= 18) ? $basePrice : $basePrice / 2;
}

$tax   = $subtotal * 0.13;
$total = $subtotal + $tax;

// ── Save Booking ──────────────────────────────────────────────────
$stmt = $conn->prepare(
    "INSERT INTO bookings (user_id, city, count, subTotal, tax, total)
     VALUES (?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("isiddd", $_SESSION['user_id'], $city, $count, $subtotal, $tax, $total);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Summary — Ticket System</title>
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
            padding: 2rem 1rem;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);
            z-index: 0;
        }

        /* ── Summary Box ─────────────────────────────────────────── */
        .summary-box {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 520px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        /* ── Headings ────────────────────────────────────────────── */
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 0.3rem;
            letter-spacing: 0.5px;
        }

        h2 {
            font-size: 1rem;
            font-weight: 400;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2rem;
        }

        /* ── Divider ─────────────────────────────────────────────── */
        hr {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            margin: 1.5rem 0;
        }

        /* ── Row Items ───────────────────────────────────────────── */
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 0.7rem;
        }

        .row span:first-child {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: rgba(255, 255, 255, 0.55);
        }

        .row span:last-child {
            font-size: 15px;
            font-weight: 600;
            color: #fff;
        }

        /* ── Total Row ───────────────────────────────────────────── */
        .row.total {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.3);
            margin-top: 1rem;
        }

        .row.total span:first-child {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
        }

        .row.total span:last-child {
            font-size: 1.2rem;
        }

        /* ── Actions ─────────────────────────────────────────────── */
        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            letter-spacing: 0.3px;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-primary {
            background: #ffffff;
            color: #0f0f1a;
        }

        .btn-primary:hover {
            background: #e0e0e0;
            transform: translateY(-2px);
        }

        .btn-ghost {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-2px);
        }

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width: 480px) {
            .summary-box {
                padding: 1.8rem 1.4rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="summary-box">

    <h1>🎫 Ticket Summary</h1>
    <h2>Hello, <?= htmlspecialchars($name) ?>!</h2>

    <div class="row">
        <span>Destination</span>
        <span><?= htmlspecialchars($city) ?></span>
    </div>

    <div class="row">
        <span>Passengers</span>
        <span><?= $count ?></span>
    </div>

    <div class="row">
        <span>Price per Ticket</span>
        <span>Rs. <?= number_format($basePrice) ?></span>
    </div>

    <hr>

    <div class="row">
        <span>Subtotal</span>
        <span>Rs. <?= number_format($subtotal) ?></span>
    </div>

    <div class="row">
        <span>Tax (13%)</span>
        <span>Rs. <?= number_format($tax) ?></span>
    </div>

    <div class="row total">
        <span>Total</span>
        <span>Rs. <?= number_format($total) ?></span>
    </div>

    <div class="actions">
        <a class="btn btn-primary" href="mybookings.php">View My Bookings</a>
        <a class="btn btn-ghost" href="book.php">Book Again</a>
    </div>

</div>

</body>
</html>