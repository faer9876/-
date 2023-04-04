<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>4월 달력</title>
	<style>
		th{
			border: 1px solid black;
		}
		span{
			border: 1px solid black;
		}
		td{
			border: 1px solid black;
			width: 50px;
			height: 50px;
		}
	</style>
</head>
<!-- 기본형식 만들기 -->
<body>
	<h1>달력</h1>
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
			// PDO로 데이터베이스 연동
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
			$startDate = date('Y-m-d', mktime(0, 0, 0, 4, 1, 2023)); //시작 날짜를 받아옴
			$endDate = date('Y-m-d', mktime(0, 0, 0, 4, 30, 2023)); //마지막 날짜를 입력
			$stmt->execute([$startDate, $endDate]); //prepare로 start와 end 입력받기
			$events = $stmt->fetchAll();

			// 달력 출력
			$timeMake = mktime(0, 0, 0, 4, 1, 2023); //maketime으로 처음 날 부터 끝까지 생성
			$dayOfWeek = date('w', $timeMake); //w 는 0~6 반환 즉 7개 단위로 끊어서 출력
			for ($i = 0; $i < 6; $i++) { //부터 6까지 칼럼 추가
				echo "<tr>"; //그리고 한줄 띄움
				for ($j = 0; $j < 7; $j++) {
					if (($i == 0 && $j < $dayOfWeek) || ($i * 7 + $j - $dayOfWeek + 1 > 30)) { //30일 보다 크면 종료
						echo "<td></td>"; //4월의 첫 날짜가 시작될 때까지 빈칸 출력함
					} else {
						$date = date('Y-m-d', mktime(0, 0, 0, 4, $i * 7 + $j - $dayOfWeek + 1, 2023)); //주 단위로 끊는 알고리즘
						$eventText = ""; //이벤트 텍스트 칸 지정
						foreach ($events as $event) { //날짜에서 받아온 값 만큼 이벤트값 입력
							if ($event['date'] == $date) { // 밑에서 입력한 값이랑 동일할 때 텍스트에 값 넣음
								$eventText .= $event['title'] . "<br>";
							}
						}
						echo "<td><span>" . ($i * 7 +$j - $dayOfWeek + 1) . "</span><br>" . $eventText . "<br><br>"; // 보더값 넣어줌
          }
          }
          echo "</tr>";
          }
          ?>
					<!-- html에서 버튼이랑 형식 만들기 -->
          </table> 
          <h4>일정 추가</h4>
          <form action="" method="POST">
						<label for="date">날짜:</label>
						<!-- 날짜 입력을 date통해서 가져오기 -->
						<input type="date" name="date" id="date"><br> 
						<label for="title">타이틀:</label>
						<!-- text넣어서 위로 값 올림 -->
						<input type="text" name="title" id="title"><br>
						<label for="description">내용:</label>
						<textarea name="description" id="description"></textarea><br>
						<input type="submit" value="일정 추가">
          </form>

					<!-- 일정정보 데이터 베이스에 저장하는 php구문 생성 -->
	<?php
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$date = $_POST['date'];
		$title = $_POST['title'];
		$description = $_POST['description'];
		$stmt = $pdo->prepare("INSERT INTO events (date, title, description) VALUES (?, ?, ?)");
		$stmt->execute([$date, $title, $description]);
		}
	?>
				<!-- 일정정보 삭제하는 형식 html에서 생성 -->
	<h4>일정 삭제</h4>
	<form action="" method="POST">
		<label for="delete_date">삭제할 날짜:</label>
		<input type="date" name="delete_date" id="delete_date"><br>
		<input type="submit" value="일정 삭제">
	</form>

	<?php
					// 일정정보 데이터 베이스에서 삭제하는 구문 생성
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if (isset($_POST['delete_date'])) {
				$delete_date = $_POST['delete_date'];
				$stmt = $pdo->prepare("DELETE FROM events WHERE date = ?");
				$stmt->execute([$delete_date]);
			}
		}
	?>

</body>
</html>


