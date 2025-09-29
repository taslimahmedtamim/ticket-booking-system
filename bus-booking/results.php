<?php
require 'db.php';

$from = trim($_GET['from'] ?? '');
$to   = trim($_GET['to'] ?? '');
$date = $_GET['date'] ?? '';

if ($from === '' || $to === '' || $date === '') { header('Location: index.php'); exit; }

$sql = "
SELECT 
  t.trip_id, r.from_city, r.to_city, t.trip_date, t.depart_time, t.arrive_time, t.price,
  b.bus_no, b.seats_total,
  (b.seats_total - IFNULL(x.booked,0)) AS seats_left
FROM trips t
JOIN routes r ON r.route_id = t.route_id
JOIN buses  b ON b.bus_id = t.bus_id
LEFT JOIN (
  SELECT trip_id, COUNT(*) AS booked
  FROM bookings
  GROUP BY trip_id
) x ON x.trip_id = t.trip_id
WHERE r.from_city = ? AND r.to_city = ? AND t.trip_date = ?
ORDER BY t.depart_time ASC
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('sss', $from, $to, $date);
$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Available Trips</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Trips: <?php echo htmlspecialchars($from) . " → " . htmlspecialchars($to) . " on " . htmlspecialchars($date); ?></h2>

  <?php if ($res->num_rows === 0): ?>
    <p class="notice">No trips found. <a href="index.php">Search again</a>.</p>
  <?php else: ?>
  <table>
    <thead>
      <tr>
        <th>Bus</th><th>Depart</th><th>Arrive</th><th>Price</th><th>Seats Left</th><th></th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['bus_no']); ?></td>
          <td><?php echo substr($row['depart_time'],0,5); ?></td>
          <td><?php echo substr($row['arrive_time'],0,5); ?></td>
          <td><?php echo number_format($row['price'],2); ?> BDT</td>
          <td><span class="badge"><?php echo (int)$row['seats_left']; ?></span></td>
          <td>
            <?php if ((int)$row['seats_left'] > 0): ?>
              <a href="book.php?trip_id=<?php echo (int)$row['trip_id']; ?>"><button>Book</button></a>
            <?php else: ?>
              <span class="badge">Full</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
</body>
</html>