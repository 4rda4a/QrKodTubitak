-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 22 Kas 2024, 09:28:55
-- Sunucu sürümü: 10.4.27-MariaDB
-- PHP Sürümü: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `qrkod`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bolumler`
--

CREATE TABLE `bolumler` (
  `bolum_id` int(11) NOT NULL,
  `bolum_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `bolumler`
--

INSERT INTO `bolumler` (`bolum_id`, `bolum_text`) VALUES
(1, 'Bilgisayar Programcılığı'),
(2, 'Elektrik');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `computers`
--

CREATE TABLE `computers` (
  `computer_id` int(11) NOT NULL,
  `computer_no` text NOT NULL,
  `computer_name` text NOT NULL,
  `computer_location` text NOT NULL,
  `computer_ram` text NOT NULL,
  `computer_harddisk` text NOT NULL,
  `computer_islemci` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `computers`
--

INSERT INTO `computers` (`computer_id`, `computer_no`, `computer_name`, `computer_location`, `computer_ram`, `computer_harddisk`, `computer_islemci`) VALUES
(4, 'BVBD-1731018692', 'Erenin PC', 'Oda Z05', '4', '256', 'i11-13540');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `computer_user`
--

CREATE TABLE `computer_user` (
  `computer_user_id` int(11) NOT NULL,
  `computer_user_no` text NOT NULL,
  `computer_user_name` text NOT NULL,
  `computer_user_numara` int(11) NOT NULL,
  `computer_user_sinif` text NOT NULL,
  `computer_user_bolum` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `computer_user`
--

INSERT INTO `computer_user` (`computer_user_id`, `computer_user_no`, `computer_user_name`, `computer_user_numara`, `computer_user_sinif`, `computer_user_bolum`) VALUES
(7, 'BVBD-1731018692', 'Sıla Su Çakan', 232516017, '2. Sınıf', '1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bakim` text NOT NULL,
  `bakim_zaman` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `maintenance`
--

CREATE TABLE `maintenance` (
  `maintenance_id` int(11) NOT NULL,
  `maintenance_no` text NOT NULL,
  `maintenance_text` text NOT NULL,
  `maintenance_user` int(11) NOT NULL,
  `maintenance_date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_password` text NOT NULL,
  `user_tc` varchar(11) NOT NULL,
  `yetki_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password`, `user_tc`, `yetki_id`) VALUES
(1, 'Arda Anıl', '202cb962ac59075b964b07152d234b70', '10088005002', 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yetkiler`
--

CREATE TABLE `yetkiler` (
  `yetki_id` int(11) NOT NULL,
  `yetki` varchar(150) NOT NULL,
  `aciklama` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `yetkiler`
--

INSERT INTO `yetkiler` (`yetki_id`, `yetki`, `aciklama`) VALUES
(1, 'Teknik Eleman', 'Bilgisayarların kontrolü ve bakımından sorunludur. Bakım ekleyebilir.'),
(2, 'Yetkili', 'Bilgisayarın genel kontrolünden sorumlu kişidir. Bilgisayar ekleyebilir ve düzenleyebilir.'),
(3, 'Yönetici', 'Sistemdeki bilgisayarların ve yöneticilerden sorumludur. En yüksek yetkidir.'),
(4, 'Destek', 'Platforum genel yapısından sorumludur.');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bolumler`
--
ALTER TABLE `bolumler`
  ADD PRIMARY KEY (`bolum_id`);

--
-- Tablo için indeksler `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`computer_id`);

--
-- Tablo için indeksler `computer_user`
--
ALTER TABLE `computer_user`
  ADD PRIMARY KEY (`computer_user_id`);

--
-- Tablo için indeksler `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`);

--
-- Tablo için indeksler `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`maintenance_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Tablo için indeksler `yetkiler`
--
ALTER TABLE `yetkiler`
  ADD PRIMARY KEY (`yetki_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bolumler`
--
ALTER TABLE `bolumler`
  MODIFY `bolum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `computers`
--
ALTER TABLE `computers`
  MODIFY `computer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `computer_user`
--
ALTER TABLE `computer_user`
  MODIFY `computer_user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Tablo için AUTO_INCREMENT değeri `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `maintenance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Tablo için AUTO_INCREMENT değeri `yetkiler`
--
ALTER TABLE `yetkiler`
  MODIFY `yetki_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
