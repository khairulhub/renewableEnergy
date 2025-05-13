<?php
$host = "localhost";       // or your server IP
$db = "iot_data";
$user = "root";            // your DB username
$pass = "";                // your DB password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$solar_voltage = $_GET['voltage'];
$battery_current = $_GET['current'];
$gas_level = $_GET['gas'];
$temperature = $_GET['temp'];
$humidity = $_GET['humidity'];

$sql = "INSERT INTO sensor_readings (solar_voltage, battery_current, gas_level, temperature, humidity)
        VALUES ('$solar_voltage', '$battery_current', '$gas_level', '$temperature', '$humidity')";

if ($conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>