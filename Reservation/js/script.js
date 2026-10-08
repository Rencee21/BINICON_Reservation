function showTab(tabId) {
  const tabs = document.querySelectorAll(".content");
  tabs.forEach(tab => tab.classList.add("hidden"));

  document.getElementById(tabId).classList.remove("hidden");
}
document.getElementById("buyTicketButton").addEventListener("click", function() {
    <?php session_start(); ?>
    if (!<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
        alert("You must be logged in to buy tickets.");
        window.location.href = "login.php"; // Redirect to login page
    } else {
        window.location.href = "buy-tickets.php"; // Proceed to the ticket buying page
    }
    function selectSeat(seatId) {
    // Deselect any previously selected seat
    const previouslySelected = document.querySelector('.seat.selected');
    if (previouslySelected) {
        previouslySelected.classList.remove('selected');
    }

    // Mark the clicked seat as selected
    var seatElement = document.getElementById(seatId);
    if (seatElement.classList.contains("reserved")) {
        alert("This seat is already taken. Please select a different one.");
        return;
    }
    seatElement.classList.add('selected');

    // Set the hidden input fields for seat number and seat type
    var seatNumber = seatId.slice(-2); // Extract seat number (e.g., 'SVIP1' -> '1')
    document.getElementById("seat_no").value = seatNumber;
    document.getElementById("seat_type").value = seatId;
}
});

