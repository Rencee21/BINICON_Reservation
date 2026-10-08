<?php
session_start();
include('db.php');

// Fetch reserved seats from the DB
$reserved_seats = [];
$res = $conn->query("SELECT ticket_type, seat_no FROM reservations");
while ($row = $res->fetch_assoc()) {
    $reserved_seats[] = $row['ticket_type'] . $row['seat_no'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Concert Reservation</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="BINICON.png">
    <style>
        .tab-menu button.active {
            background-color: #555;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="BINICON.png" alt="Concert Logo" class="logo">
        <h1>Concert Reservation</h1>
    </div>

    <?php if (isset($_SESSION['user'])): ?>
    <div style="position:absolute; right:20px; top:20px;">
        Welcome, <?= $_SESSION['user'] ?> | <a href="logout.php">Logout</a>
    </div>
    <?php endif; ?>

    <div class="tab-menu">
        <button class="tab-btn" data-tab="home">HOME</button>
        <button class="tab-btn" data-tab="event">EVENT</button>
        <button id="buyTicketTab">BUY TICKETS</button>
    </div>

    <div id="home" class="content">
        <div class="hero">
            <h2>Watch the most <span class="highlight">magnificent</span> concert you've never seen</h2>
            <p>Get your chance to see the most incredible concert you've never seen in your life before.</p>
        </div>

        <div class="event-container">
                <div class="event-card">
                    <img src="Rob.jfif" alt="RD">
                    <h3>Rob Daniel</h3>
                    <p>Calamba Science Highschool</p>
                    <span class="event-date">JUL 30, 2025</span>
                    <button class="buyBtn">BUY TICKETS</button>
                </div>
                 <div class="event-card">
                    <img src="BINI.jpg" alt="BINI">
                    <h3>BINI</h3>
                    <p>Calamba Institute</p>
                    <span class="event-date">APR 29, 2025</span>
                    <button class="buyBtn">BUY TICKETS</button>
                </div>
                 <div class="event-card">
                    <img src="pne.jfif" alt="pne">
                    <h3>Parokya Ni Edgar</h3>
                    <p>Calamba Doctor College</p>
                    <span class="event-date">SEPT 21, 2025</span>
                    <button class="buyBtn">BUY TICKETS</button>
                </div>
        </div>
    </div>

    <div id="event" class="content hidden">
        <section class="event-section">
            <h2>Other Venues / Online</h2>
            <div class="event-container">
                <div class="event-card">
                    <img src="tj.jpg" alt="TJ">
                    <h3>TJ Monterde Live</h3>
                    <p>PHINMA Rizal College of Laguna</p>
                    <span class="event-date">JUN 21, 2025</span>
                    <button class="buyBtn">BUY TICKETS</button>
                </div>
                <div class="event-card">
                    <img src="coj.jpg" alt="CUP OF JOE">
                    <h3>Cup of Joe</h3>
                    <p>City College of Calamba</p>
                    <span class="event-date">JUL 24, 2025</span>
                    <button class="buyBtn">BUY TICKETS</button>
                </div>
            </div>
        </section>
    </div>

    <div id="buy-tickets" class="content hidden">
        <!-- buy-tickets content is in buy-tickets.php -->
    </div>

    <script>
    const isLoggedIn = <?= isset($_SESSION['user']) ? 'true' : 'false' ?>;

    function showTab(tabName) {
        // Hide all content
        const contents = document.querySelectorAll('.content');
        contents.forEach(c => c.classList.add('hidden'));

        // Show selected
        const target = document.getElementById(tabName);
        if (target) target.classList.remove('hidden');

        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

        // Add active to clicked button
        document.querySelector(`.tab-btn[data-tab="${tabName}"]`)?.classList.add('active');

        // Update URL hash without reloading
        history.replaceState(null, '', '#' + tabName);
    }

    // Handle tab button clicks
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tabName = btn.getAttribute('data-tab');
            showTab(tabName);
        });
    });

    // BUY TICKETS button
    document.getElementById('buyTicketTab').addEventListener('click', function () {
        if (!isLoggedIn) {
            alert("You must be logged in to buy tickets.");
            window.location.href = "login.php";
        } else {
            window.location.href = "buy-tickets.php";
        }
    });

    // Buy buttons in event cards
    document.querySelectorAll('.buyBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!isLoggedIn) {
                alert("You must be logged in to buy tickets.");
                window.location.href = "login.php";
            } else {
                window.location.href = "buy-tickets.php";
            }
        });
    });

    // Load tab based on URL hash
    const hash = window.location.hash.replace('#', '');
    const validTabs = ['home', 'event'];
    if (validTabs.includes(hash)) {
        showTab(hash);
    } else {
        showTab('home'); // default tab
    }
</script>

</body>
</html>
