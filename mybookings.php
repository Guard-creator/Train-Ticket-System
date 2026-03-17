<?php
// ── Session & Config ──────────────────────────────────────────────
include 'config.php';
session_start();

// ── Auth Guard ────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ── Fetch Bookings ────────────────────────────────────────────────
$user_id = intval($_SESSION['user_id']);
$result  = $conn->query("SELECT * FROM bookings WHERE user_id = $user_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings — Ticket System</title>
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
            padding: 2.5rem 1rem;
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
            max-width: 860px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        /* ── Heading ─────────────────────────────────────────────── */
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.8rem;
            letter-spacing: 0.5px;
        }

        /* ── Table ───────────────────────────────────────────────── */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14.5px;
        }

        thead tr {
            background: rgba(255, 255, 255, 0.1);
        }

        th {
            padding: 12px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255, 255, 255, 0.65);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }

        td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        tbody tr:hover td {
            background: rgba(255, 255, 255, 0.05);
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        /* ── Empty State ─────────────────────────────────────────── */
        .empty {
            text-align: center;
            padding: 2.5rem 0;
            color: rgba(255, 255, 255, 0.45);
            font-size: 15px;
        }

        /* ── Actions ─────────────────────────────────────────────── */
        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 11px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
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
        @media (max-width: 600px) {
            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.4rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🎫 My Bookings</h1>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>City</th>
                    <th>Passengers</th>
                    <th>Subtotal</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows === 0): ?>
                    <tr>
                        <td colspan="5" class="empty">No bookings found. Book your first ticket!</td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['city']) ?></td>
                            <td><?= intval($row['count']) ?></td>
                            <td>Rs. <?= number_format($row['subTotal'], 0) ?></td>
                            <td>Rs. <?= number_format($row['tax'], 0) ?></td>
                            <td>Rs. <?= number_format($row['total'], 0) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="actions">
        <a class="btn btn-primary" href="book.php">+ Book Again</a>
        <a class="btn btn-ghost" href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
