# smsadli
Tools for send sms from your localhost, using your android mobile phone

Cara penggunaan:

1. Git clone ke localhost.

2. Buat database dengan nama smsadli. Username & Password sesuaikan dengan lokalhost.
   Query untuk buat table "outbox"
   
   CREATE TABLE `outbox` (
     `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
     `receiver` varchar(20) NOT NULL,
     `message` varchar(255) NOT NULL,
     `status` int(11) NOT NULL
   ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
   
3. Install Android Apps ke handphone ANDROID anda. File apk ada di folder "android-app". 
   Setelah install aplikasi, buka app. Disitu ada IP Server/Localhost. Masukan IP Localhost ke situ. Handphone & Komputer harus berada dalam 1 jaringan yang sama.
   Untuk durasi, default nya adalah 1. Yang artinya 1 menit. Silahkan rubah sesuai kebutuhan.
   
4. Cara kirim SMS:
   Endpointnya: http://localhost/smsadli/api/sms/insert/[nomertujuan]/[isipesan]
   
5. Buat cek SMS yang belum terkirim:
   Endpointnya: http://localhost/smsadli/api/sms/insert/read
   
   Hanya sms yang statusnya 0 (belum terkirim), yang ditampilkan di halaman tersebut. Yang sudah terkirim silahkan cek di database.