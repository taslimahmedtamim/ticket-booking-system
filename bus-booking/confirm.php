<?php
require 'db.php';

$trip_id = (int)($_POST['trip_id'] ?? 0);
$name    = trim($_POST['passenger_name'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$seat_no = (int)($_POST['seat_no'] ?? 0);

if ($trip_id <= 0 || $name === '' || $phone === '' || $seat_no <= 0) {
  die('Invalid submission.');
}

$mysqli->begin_transaction();

try {
  // Seat range check (get bus seats)
  $q = $mysqli->prepare("
    SELECT b.seats_total
    FROM trips t JOIN buses b ON t.bus_id=b.bus_id
    WHERE t.trip_id=? FOR UPDATE
  ");
  $q->bind_param('i', $trip_id);
  $q->execute();
  $row = $q->get_result()->fetch_assoc();
  if (!$row) throw new Exception('Trip not found.');
  if ($seat_no < 1 || $seat_no > (int)$row['seats_total']) {
    throw new Exception('Invalid seat number.');
  }

  // Try insert (unique constraint prevents double-book)
  $ins = $mysqli->prepare("INSERT INTO bookings (trip_id, passenger_name, phone, seat_no) VALUES (?,?,?,?)");
  $ins->bind_param('issi', $trip_id, $name, $phone, $seat_no);
  $ins->execute();

  $mysqli->commit();
  $success = true;
} catch (mysqli_sql_exception $e) {
  $mysqli->rollback();
  $success = false;
  $error   = ($e->getCode() == 1062) ? 'That seat was just booked by someone else. Please pick another seat.' : 'Booking failed. ' . $e->getMessage();
} catch (Exception $e) {
  $mysqli->rollback();
  $success = false;
  $error   = $e->getMessage();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Booking Status</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <?php if (!empty($success)): ?>
    <h2>Booking Confirmed ✅</h2>
    <p class="notice">Name: <b><?php echo htmlspecialchars($name); ?></b><br>
    Trip ID: <?php echo (int)$trip_id; ?><br>
    Seat: <b><?php echo (int)$seat_no; ?></b><br>
    Time: <?php echo date('Y-m-d H:i'); ?></p>
    <a href="index.php"><button>Book Another</button></a>
  <?php else: ?>
    <h2>Booking Failed ❌</h2>
    <p class="notice"><?php echo htmlspecialchars($error); ?></p>
    <a href="book.php?trip_id=<?php echo (int)$trip_id; ?>"><button>Back</button></a>
  <?php endif; ?>
</div>
</body>
</html>