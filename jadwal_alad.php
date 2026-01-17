<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal Sholat Otomatis</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
  font-family:'Segoe UI',Arial,sans-serif;
  background:linear-gradient(135deg,#0f9b8e,#0a6d68);
  padding:20px;
}
.box{
  max-width:420px;
  margin:auto;
  background:white;
  padding:0;
  border-radius:16px;
  box-shadow:0 15px 35px rgba(0,0,0,.25);
  overflow:hidden;
}
.header{
  background:linear-gradient(135deg,#1fa463,#0b7a52);
  color:white;
  text-align:center;
  padding:20px 15px;
}
.header h2{
  margin:0;
  font-size:22px;
}
.city{
  font-size:14px;
  opacity:.95;
}
.date{
  font-size:13px;
  opacity:.9;
}
.content{
  padding:20px;
}
ul{list-style:none;padding:0;margin:0}
li{
  padding:12px 10px;
  border-bottom:1px solid #eee;
  display:flex;
  justify-content:space-between;
  font-size:16px;
}
li span{
  font-weight:bold;
  color:#0b7a52;
}
li:last-child{border-bottom:none}
.footer{
  text-align:center;
  padding:15px;
  background:#f3fdf9;
}
.badge{
  display:inline-block;
  background:linear-gradient(135deg,#f7b733,#fc4a1a);
  color:white;
  padding:6px 18px;
  border-radius:30px;
  font-size:13px;
  font-weight:bold;
  letter-spacing:.5px;
}
.loading{
  text-align:center;
  color:white;
}
</style>
</head>

<body>

<?php
if(isset($_GET['lat']) && isset($_GET['lon'])){

  $lat = $_GET['lat'];
  $lon = $_GET['lon'];

  /* ================== AMBIL NAMA KOTA ================== */
  $geo = @file_get_contents(
    "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon"
  );
  $city = "Lokasi Anda";

  if($geo){
    $g = json_decode($geo,true);
    $addr = $g['address'] ?? [];
    $city = $addr['city'] 
         ?? $addr['town'] 
         ?? $addr['village'] 
         ?? $addr['county'] 
         ?? "Sekitarnya";
  }

  /* ================== JADWAL SHOLAT ================== */
  $url = "https://api.aladhan.com/v1/timings?latitude=$lat&longitude=$lon&method=11";
  $json = @file_get_contents($url);

  if($json){
    $data = json_decode($json,true);
    $t = $data['data']['timings'];
    $date = $data['data']['date']['readable'];

    echo "<div class='box'>
            <div class='header'>
              <h2>🕌 Jadwal Sholat</h2>
              <div class='city'>📍 <b>$city</b> & sekitarnya</div>
              <div class='date'>$date</div>
            </div>

            <div class='content'>
              <ul>
                <li>Subuh <span>{$t['Fajr']}</span></li>
                <li>Dzuhur <span>{$t['Dhuhr']}</span></li>
                <li>Ashar <span>{$t['Asr']}</span></li>
                <li>Maghrib <span>{$t['Maghrib']}</span></li>
                <li>Isya <span>{$t['Isha']}</span></li>
              </ul>
            </div>

            <div class='footer'>
              <span class='badge'>DKM AL HIDAYAH</span>
            </div>
          </div>";
  }else{
    echo "<p>Gagal mengambil data jadwal sholat</p>";
  }

}else{
?>
  <div class="loading">
    <h3>📡 Mengambil lokasi...</h3>
    <p>Aktifkan GPS & izinkan lokasi</p>
  </div>
<?php } ?>

<script>
if(!location.search){
  navigator.geolocation.getCurrentPosition(
    function(pos){
      location.href = "?lat=" + pos.coords.latitude + "&lon=" + pos.coords.longitude;
    },
    function(){
      alert("Lokasi tidak diizinkan");
    }
  );
}
</script>

</body>
</html>