<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IoT Sensor Data</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="p-5 bg-light">
  <div class="container">
    <h2 class="mb-4">IoT Sensor Data Charts</h2>

    <div class="mb-5">
      <h5>Solar Voltage</h5>
      <canvas id="voltageChart"></canvas>
    </div>

    <div class="mb-5">
      <h5>Battery Current</h5>
      <canvas id="currentChart"></canvas>
    </div>

    <div class="mb-5">
      <h5>Temperature</h5>
      <canvas id="temperatureChart"></canvas>
    </div>

    <div class="mb-5">
      <h5>Humidity</h5>
      <canvas id="humidityChart"></canvas>
    </div>

    <div class="mb-5">
      <h5>Gas Level</h5>
      <canvas id="gasChart"></canvas>
    </div>
  </div>

  <?php
    $conn = new mysqli("localhost", "root", "", "iot_data");
    $result = $conn->query("SELECT * FROM sensor_readings ORDER BY created_at DESC LIMIT 10");

    $timestamps = [];
    $voltages = [];
    $currents = [];
    $temps = [];
    $humidity = [];
    $gas = [];

    while ($row = $result->fetch_assoc()) {
        $timestamps[] = $row['created_at'];
        $voltages[] = $row['solar_voltage'];
        $currents[] = $row['battery_current'];
        $temps[] = $row['temperature'];
        $humidity[] = $row['humidity'];
        $gas[] = $row['gas_level'];
    }

    $conn->close();
  ?>

  <script>
    const labels = <?php echo json_encode(array_reverse($timestamps)); ?>;

    const createChart = (canvasId, label, data, color) => {
      new Chart(document.getElementById(canvasId).getContext('2d'), {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: label,
            data: data,
            borderColor: color,
            backgroundColor: color + '33',
            fill: false,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          scales: {
            x: {
              title: {
                display: true,
                text: 'Time'
              }
            },
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Value'
              }
            }
          }
        }
      });
    };

    createChart("voltageChart", "Solar Voltage (V)", <?php echo json_encode(array_reverse($voltages)); ?>, "blue");
    createChart("currentChart", "Battery Current (A)", <?php echo json_encode(array_reverse($currents)); ?>, "green");
    createChart("temperatureChart", "Temperature (°C)", <?php echo json_encode(array_reverse($temps)); ?>, "red");
    createChart("humidityChart", "Humidity (%)", <?php echo json_encode(array_reverse($humidity)); ?>, "orange");
    createChart("gasChart", "Gas Level (PPM)", <?php echo json_encode(array_reverse($gas)); ?>, "purple");
  </script>
</body>
</html>
