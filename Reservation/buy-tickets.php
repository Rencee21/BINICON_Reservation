<?php
session_start();
include('db.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Fetch reserved seats
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
        .form-container {
            background: transparent;
            box-shadow: none;
            padding: 0;
            margin: 0 auto;
            border-radius: 0;
            width: 100%;
            max-width: 500px;
            color: black;
        }

        .section {
            display: grid;
            grid-template-columns: repeat(10, 1fr);
            gap: 10px;
            padding: 0 10px;
            width: fit-content;
            margin-left: -140px;
            justify-content: start;
        }

        .svip, .vip, .regular {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 8px;
            margin: 3px;
            border-radius: 5px;
            cursor: pointer;
            width: 45px;
            height: 35px;
            font-weight: bold;
            font-size: 12px;
        }

        .svip { background-color: gold; }
        .vip { background-color: lightblue; }
        .regular { background-color: lightgray; }

        .seat.selected {
            background-color: #00b894;
            color: white;
        }

        .reserved {
            background-color: red !important;
            color: white;
            cursor: not-allowed;
        }
        .stage {
            text-align: center;
            font-weight: bold;
            margin: 30px auto;
            padding: 20px;
            width: 400px;
            font-size: 24px;
            border: 4px solid black;
            border-radius: 12px;
            background-color: Black
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

    </style>
</head>
<body>
    <div class="header">
        <img src="BINICON.png" alt="Concert Logo" class="logo">
        <h1>Concert Reservation</h1>
    </div>

    <div class="tab-menu">
        <button onclick="window.location.href='index.php#home'">HOME</button>
        <button onclick="window.location.href='index.php#event'">EVENT</button>
        <button class="active">BUY TICKETS</button>
    </div>

    <div class="content" id="buy-tickets">
        <h1>Reserve your Ticket</h1>
        <form action="reserve.php" method="POST" class="form-container" id="reservationForm">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="legend" style="margin: 20px auto 20px 60px; display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
    <div><div style="width: 20px; height: 20px; background-color: gold; margin-right: 5px; border-radius: 3px;"></div> <span>SVIP</span></div>
    <div><div style="width: 20px; height: 20px; background-color: lightblue; margin-right: 5px; border-radius: 3px;"></div> <span>VIP</span></div>
    <div><div style="width: 20px; height: 20px; background-color: lightgray; margin-right: 5px; border-radius: 3px;"></div> <span>Regular</span></div>
    <div><div style="width: 20px; height: 20px; background-color: red; margin-right: 5px; border-radius: 3px;"></div> <span>Reserved</span></div>
    <div><div style="width: 20px; height: 20px; background-color: #00b894; margin-right: 5px; border-radius: 3px;"></div> <span>Selected</span></div>
</div>


            <input type="hidden" name="ticket_types" id="ticket_type" required>
            <input type="hidden" name="seat_nos" id="seat_no" required>

            <div class="seating">
                <div class="stage">Stage</div>

                <h3>SVIP</h3>
                <div class="section">
                    <?php for ($i = 1; $i <= 20; $i++):
                        $seat_id = "SVIP$i";
                        $is_reserved = in_array($seat_id, $reserved_seats);
                    ?>
                        <div class="seat svip <?= $is_reserved ? 'reserved' : '' ?>" data-type="SVIP" data-number="<?= $i ?>">
                            <?= $seat_id ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <h3>VIP</h3>
                <div class="section">
                    <?php for ($i = 1; $i <= 30; $i++):
                        $seat_id = "VIP$i";
                        $is_reserved = in_array($seat_id, $reserved_seats);
                    ?>
                        <div class="seat vip <?= $is_reserved ? 'reserved' : '' ?>" data-type="VIP" data-number="<?= $i ?>">
                            <?= $seat_id ?>
                        </div>
                    <?php endfor; ?>
                </div>

                <h3>Regular</h3>
                <div class="section">
                    <?php for ($i = 1; $i <= 100; $i++):
                        $seat_id = "Regular$i";
                        $is_reserved = in_array($seat_id, $reserved_seats);
                    ?>
                        <div class="seat regular <?= $is_reserved ? 'reserved' : '' ?>" data-type="Regular" data-number="<?= $i ?>">
                            <?= "R $i" ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <button type="submit" style="margin-top: 20px;">Reserve</button>
        </form>
    </div>

    <script>
        const seats = document.querySelectorAll('.seat:not(.reserved)');
        let selectedSeats = [];

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                if (seat.classList.contains('selected')) {
                    seat.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(s => s !== seat);
                } else {
                    seat.classList.add('selected');
                    selectedSeats.push(seat);
                }

                const ticketTypes = selectedSeats.map(seat => seat.dataset.type);
                const seatNos = selectedSeats.map(seat => seat.dataset.number);

                document.getElementById('ticket_type').value = JSON.stringify(ticketTypes);
                document.getElementById('seat_no').value = JSON.stringify(seatNos);
            });
        });

        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            if (selectedSeats.length === 0) {
                e.preventDefault();
                alert('Please select at least one seat before submitting!');
            }
        });
    </script>
</body>
</html>
