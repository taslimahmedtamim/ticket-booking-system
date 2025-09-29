<?php
require 'db.php';
$trip_id = (int)($_GET['trip_id'] ?? 0);
if ($trip_id <= 0) { header('Location: index.php'); exit; }

$infoSql = "
SELECT t.trip_id, r.from_city, r.to_city, t.trip_date, t.depart_time, t.arrive_time,
       b.bus_no, b.seats_total
FROM trips t
JOIN routes r ON r.route_id=t.route_id
JOIN buses  b ON b.bus_id=t.bus_id
WHERE t.trip_id = ?";
$st = $mysqli->prepare($infoSql);
$st->bind_param('i', $trip_id);
$st->execute();
$trip = $st->get_result()->fetch_assoc();
if (!$trip) { die('Trip not found'); }

$booked = [];
$bs = $mysqli->prepare("SELECT seat_no FROM bookings WHERE trip_id=?");
$bs->bind_param('i', $trip_id);
$bs->execute();
$res = $bs->get_result();
while ($r = $res->fetch_assoc()) { $booked[(int)$r['seat_no']] = true; }

$availableSeats = [];
for ($i=1; $i <= (int)$trip['seats_total']; $i++) {
  if (!isset($booked[$i])) $availableSeats[] = $i;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Book Seat</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Book: <?php echo htmlspecialchars($trip['from_city'])." → ".htmlspecialchars($trip['to_city']); ?></h2>
  <p class="notice">
    Bus <?php echo htmlspecialchars($trip['bus_no']); ?> · 
    <?php echo htmlspecialchars($trip['trip_date']); ?> · 
    <?php echo substr($trip['depart_time'],0,5); ?> → <?php echo substr($trip['arrive_time'],0,5); ?>
  </p>

  <?php if (empty($availableSeats)): ?>
    <p class="notice">Sorry, this trip is full.</p>
  <?php else: ?>
    <form action="confirm.php" method="post">
      <input type="hidden" name="trip_id" value="<?php echo (int)$trip_id; ?>">
      <div class="row">
        <div>
          <label>Your Name</label>
          <input name="passenger_name" required>
        </div>
        <div>
          <label>Phone</label>
          <input name="phone" required>
        </div>
        <div>
          <label>Seat</label>
          <select name="seat_no" required>
            <?php foreach ($availableSeats as $s): ?>
              <option value="<?php echo $s; ?>"><?php echo $s; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div style="margin-top:12px"><button type="submit">Confirm Booking</button></div>
    </form>
  <?php endif; ?>
</div>
</body>
</html>