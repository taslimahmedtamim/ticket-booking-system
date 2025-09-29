<?php require 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Bus Ticket Booking</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h1>Bus Ticket Booking</h1>
  <p class="notice">Search available trips by route and date.</p>

  <form action="results.php" method="get">
    <div class="row">
      <div>
        <label>From</label>
        <input name="from" required placeholder="e.g., Dhaka">
      </div>
      <div>
        <label>To</label>
        <input name="to" required placeholder="e.g., Chittagong">
      </div>
      <div>
        <label>Date</label>
        <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
      </div>
    </div>
    <div style="margin-top:12px"><button type="submit">Search Trips</button></div>
  </form>
</div>
</body>
</html>