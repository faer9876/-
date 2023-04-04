<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>4월 달력</title>
</head>
<body>
	<h1>4월 달력</h1>
	<table>
		<tr>
			<th>일</th>
			<th>월</th>
			<th>화</th>
			<th>수</th>
			<th>목</th>
			<th>금</th>
			<th>토</th>
		</tr>
		<?php
			// 데이터베이스 연동
			$dbHost = "localhost";
			$dbName = "mycalendar";
			$dbUser = "root";
			$dbPass = "dkf0fkf0fk";
			$dbCharset = "utf8mb4";

			$dsn = "mysql:host=$dbHost;dbname=$dbName;charset=$dbCharset";
			$opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
			];
			$pdo = new PDO($dsn, $dbUser, $dbPass, $opt);

			// 일정 정보 불러오기
			$stmt = $pdo->prepare("SELECT * FROM events WHERE date >= ? AND date <= ?");
			$startDate = date('Y-m-d', mktime(0, 0, 0, 4, 1, 2023));
			$endDate = date('Y-m-d', mktime(0, 0, 0, 5, 0, 2023));
			$stmt->execute([$startDate, $endDate]);
			$events = $stmt->fetchAll();

			// 달력 출력
			$timeStamp = mktime(0, 0, 0, 4, 1, 2023);
			$dayOfWeek = date('w', $timeStamp);
			for ($i = 0; $i < 6; $i++) {
				echo "<tr>";
				for ($j = 0; $j < 7; $j++) {
					if (($i == 0 && $j < $dayOfWeek) || ($i * 7 + $j - $dayOfWeek + 1 > 30)) {
						echo "<td></td>";
					} else {
						$date = date('Y-m-d', mktime(0, 0, 0, 4, $i * 7 + $j - $dayOfWeek + 1, 2023));
						$eventText = "";
						foreach ($events as $event) {
							if ($event['date'] == $date) {
								$eventText .= $event['title'] . "<br>";
							}
						}
						echo "<td><strong>" . ($i * 7 +$j - $dayOfWeek + 1) . "</strong><br>" . $eventText . "<br><br>";
          }
          }
          echo "</tr>";
          }
          ?>
          </table>
          <h2>일정 추가</h2>
          <form action="" method="POST">
          <label for="date">날짜:</label>
          <input type="date" name="date" id="date"><br>
          <label for="title">제목:</label>
          <input type="text" name="title" id="title"><br>
          <label for="description">설명:</label>
          <textarea name="description" id="description"></textarea><br>
          <input type="submit" value="일정 추가">
          </form>
<?php
  // 일정 정보 데이터베이스에 저장하기
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $date = $_POST['date'];
  $title = $_POST['title'];
  $description = $_POST['description'];
  $stmt = $pdo->prepare("INSERT INTO events (date, title, description) VALUES (?, ?, ?)");
  $stmt->execute([$date, $title, $description]);
  }
?>
</body>
</html>


