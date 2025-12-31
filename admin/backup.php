<?php 
	ob_start();
	include('inc/myconnect.php');

	header('Content-Type: application/sql');
	header('Content-Disposition: attachment; filename="backup_' . date("Y-m-d_H-i-s") . '.sql"');

	// Lấy danh sách các bảng
	$tables = [];
	$result = $dbc->query("SHOW TABLES");
	while ($row = $result->fetch_array()) {
		$tables[] = $row[0];
	}

	// Khởi tạo dữ liệu SQL
	$output = "-- Exported database backup\n";
	$output .= "-- Export date: " . date("Y-m-d H:i:s") . "\n\n";
	$output .= "SET NAMES utf8mb4;\n";
	$output .= "SET CHARACTER SET utf8mb4;\n";
	$output .= "SET FOREIGN_KEY_CHECKS = 0;\n\n"; // Vô hiệu hóa kiểm tra khóa ngoại

	// Xuất từng bảng
	foreach ($tables as $table) {
		$result = $dbc->query("SELECT * FROM $table");
		$num_fields = $result->field_count;

		// Thêm câu lệnh DROP TABLE
		$output .= "DROP TABLE IF EXISTS `$table`;\n";

		// Tạo lệnh CREATE TABLE
		$create_table_result = $dbc->query("SHOW CREATE TABLE $table");
		$create_table_row = $create_table_result->fetch_array();
		$output .= $create_table_row[1] . ";\n\n";

		// Thêm dữ liệu INSERT INTO
		while ($row = $result->fetch_assoc()) {
			$output .= "INSERT INTO `$table` VALUES(";
			$values = [];
			foreach ($row as $value) {
				$values[] = $value === null ? "NULL" : "'" . $dbc->real_escape_string($value) . "'";
			}
			$output .= implode(", ", $values) . ");\n";
		}
		$output .= "\n\n";
	}

	$output .= "SET FOREIGN_KEY_CHECKS = 1;\n\n"; // Bật lại kiểm tra khóa ngoại

	// Xuất dữ liệu ra trình duyệt
	echo $output;
	ob_end_flush();

	// Đóng kết nối
	$dbc->close();
	exit;
?>
