-- Cipta database;
CREATE DATABASE IF NOT EXISTS nsejarah;

-- guna database;
USE nsejarah;

-- table murid
CREATE TABLE IF NOT EXISTS murid (
    `m_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `m_nokp` VARCHAR(30) NOT NULL,
    `m_nama` VARCHAR(255) NOT NULL,
    `m_katalaluan` VARCHAR(255) NOT NULL,
    `m_kelas` INT(30) UNSIGNED NOT NULL,
    PRIMARY KEY(`m_id`)
);

-- table kelas
CREATE TABLE IF NOT EXISTS kelas (
    `k_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `k_nama` VARCHAR(255) NOT NULL,
    PRIMARY KEY(`k_id`)
);

-- table guru
CREATE TABLE IF NOT EXISTS guru (
    `g_id` INT(30) UNSIGNED NOT NULL AUTO_INCREMENT,
    `g_nokp` VARCHAR(30) NOT NULL,
    `g_nama` VARCHAR(255) NOT NULL,
    `g_katalaluan` VARCHAR(255) NOT NULL,
    `g_jenis` ENUM('admin', 'guru') DEFAULT 'guru',
    PRIMARY KEY(`g_id`)
);

-- table kuiz
CREATE TABLE IF NOT EXISTS kuiz (
    `kz_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `kz_nama` VARCHAR(255) NOT NULL,
    `kz_guru` INT(10) UNSIGNED NOT NULL,
    `kz_tarikh` DATE NOT NULL,
    `kz_jenis` ENUM('kuiz', 'latihan') DEFAULT 'kuiz',
    `kz_masa` INT(5) NULL,
    PRIMARY KEY(`kz_id`)
);

-- table soalan
CREATE TABLE IF NOT EXISTS soalan (
    `s_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `s_kuiz` INT(10) UNSIGNED NOT NULL,
    `s_teks` TEXT NOT NULL,
    `s_gambar` TEXT NULL,
    PRIMARY KEY(`s_id`)
);

-- table jawapan
CREATE TABLE IF NOT EXISTS jawapan (
    `j_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `j_soalan` INT(10) UNSIGNED NOT NULL,
    `j_teks` TEXT NOT NULL,
    PRIMARY KEY(`j_id`)
);

-- TABLE COMPOSIT
-- table kelas_tingkatan
CREATE TABLE IF NOT EXISTS kelas_tingkatan (
    `kt_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `kt_ting` TINYINT(1) UNSIGNED NOT NULL,
    `kt_kelas` INT(10) UNSIGNED NOT NULL,
    `kt_guru` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY(`kt_id`)
);

-- table soalan_jawapan
CREATE TABLE IF NOT EXISTS soalan_jawapan (
    `sj_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sj_soalan` INT(10) UNSIGNED NOT NULL,
    `sj_jawapan` INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY(`sj_id`)
);

-- table jawapan_murid
CREATE TABLE IF NOT EXISTS jawapan_murid (
    `jm_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `jm_murid` INT(10) UNSIGNED NOT NULL,
    `jm_soalan` INT(10) UNSIGNED NOT NULL,
    `jm_jawapan` INT(10) UNSIGNED NULL,
    `jm_status` BOOL NULL,
    PRIMARY KEY(`jm_id`)
);

-- table skor_murid
CREATE TABLE IF NOT EXISTS skor_murid (
    `sm_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `sm_murid` INT(10) UNSIGNED NOT NULL,
    `sm_kuiz` INT(10) UNSIGNED NOT NULL,
    `sm_skor` DOUBLE(5,2) NOT NULL,
    PRIMARY KEY(`sm_id`)
);

-- FOREIGN KEYS/KUNCI-KUNCI ASING table
-- table murid
ALTER TABLE `murid` ADD FOREIGN KEY(`m_kelas`) REFERENCES `kelas_tingkatan`(`kt_id`) ON UPDATE CASCADE ON DELETE CASCADE;


-- table  kuiz
ALTER TABLE `kuiz` ADD FOREIGN KEY(`kz_guru`) REFERENCES `guru`(`g_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- table soalan
ALTER TABLE `soalan` ADD FOREIGN KEY(`s_kuiz`) REFERENCES `kuiz`(`kz_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- table jawapan
ALTER TABLE `jawapan` ADD FOREIGN KEY(`j_soalan`) REFERENCES `soalan`(`s_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- table kelas_tingkatan
ALTER TABLE `kelas_tingkatan` ADD FOREIGN KEY(`kt_kelas`) REFERENCES `kelas`(`k_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `kelas_tingkatan` ADD FOREIGN KEY(`kt_guru`) REFERENCES `guru`(`g_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- table soalan_jawapan
ALTER TABLE `soalan_jawapan` ADD FOREIGN KEY(`sj_soalan`) REFERENCES `soalan`(`s_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `soalan_jawapan` ADD FOREIGN KEY(`sj_jawapan`) REFERENCES `jawapan`(`j_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- table jawapan_murid
ALTER TABLE `jawapan_murid` ADD FOREIGN KEY(`jm_murid`) REFERENCES `murid`(`m_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `jawapan_murid` ADD FOREIGN KEY(`jm_soalan`) REFERENCES `soalan`(`s_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `jawapan_murid` ADD FOREIGN KEY(`jm_jawapan`) REFERENCES `jawapan`(`j_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- table skor_murid
ALTER TABLE `skor_murid` ADD FOREIGN KEY(`sm_murid`) REFERENCES `murid`(`m_id`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `skor_murid` ADD FOREIGN KEY(`sm_kuiz`) REFERENCES `kuiz`(`kz_id`) ON UPDATE CASCADE ON DELETE CASCADE;

-- SAMPLE DATA
-- guru
INSERT INTO `guru`(`g_id`, `g_nokp`, `g_nama`, `g_katalaluan`, `g_jenis`)
VALUES ('1', '333333333333', 'Samad', '123', 'guru'),
        ('2', '444444444444', 'Aidil Admin', '123', 'admin');

-- kelas
INSERT INTO `kelas`(`k_id`, `k_nama`)
VALUES ('1', 'Amanah'),
        ('2', 'Bestari');

-- kelas_tingkatan
INSERT INTO `kelas_tingkatan`(`kt_ting`, `kt_kelas`, `kt_guru`)
VALUES ('1', '1', '1'),
       ('2', '1', '2'),
       ('1', '2', '1'),
       ('2', '2', '2');

-- murid
INSERT INTO `murid`(`m_id`, `m_nokp`, `m_nama`, `m_katalaluan`, `m_ting`, `m_kelas`)
VALUES ('1', '111111111111', 'Aidil', '123', '1', '1'),
        ('2', '222222222222', 'Sudin', '123', '1', '1');

-- kuiz
INSERT INTO `kuiz`(`kz_id`, `kz_nama`, `kz_guru`, `kz_tarikh`, `kz_jenis`, `kz_masa`)
VALUES ('1', 'Bab 3: GUNGGUNG', '1', '26-02-2021', 'latihan', NULL),
        ('2', 'Bab 3: Kuiz GUNGGUNG', '1', '26-02-2021', 'kuiz', '12');
        
-- soalan
INSERT INTO `soalan`(`s_id`, `s_kuiz`, `s_teks`, `s_gambar`)
VALUES ('1', '1', 'Apakah nama haiwan ini', 'https://upload.wikimedia.org/wikipedia/commons/1/1c/YosriCicak.jpg'),
       ('2', '1', 'Mengapakah saiz hidupan laut lebih besar daripada daratan', NULL),
       ('3', '2', 'Apakah nama haiwan ini. - Kuiz', 'https://upload.wikimedia.org/wikipedia/commons/1/1c/YosriCicak.jpg'),
       ('4', '2', 'Mengapakah saiz hidupan laut lebih besar daripada daratan - Kuiz', NULL);

-- jawapan
INSERT INTO `jawapan`(`j_id`, `j_soalan`, `j_teks`)
VALUES ('1', '1', 'Cicak'),
       ('2', '1', 'Ikan'),
       ('3', '2', 'Hidupan di air tidak dipengaruhi oleh graviti'),
       ('4', '2', 'Manalah saya tahu.'),
       ('5', '3', 'Cicak - Kuiz'),
       ('6', '3', 'Ikan - Kuiz'),
       ('7', '4', 'Hidupan di air tidak dipengaruhi oleh graviti - Kuiz'),
       ('8', '4', 'Manalah saya tahu. - Kuiz');

-- soalan_jawapan
INSERT INTO `soalan_jawapan`(`sj_id`, `sj_soalan`, `sj_jawapan`)
VALUES ('1', '1', '1'),
       ('2', '2', '3'),
       ('3', '3', '5'),
       ('4', '4', '7');

-- jawapan_murid 
INSERT INTO `jawapan_murid`(`jm_id`, `jm_murid`, `jm_soalan`, `jm_jawapan`, `jm_status`)
VALUES ('1', '1', '1', '1', '1'),
       ('2', '1', '2', '4', '0'),
       ('3', '2', '1', '2', '0'),
       ('4', '2', '2', '3', '1'),
       ('5', '1', '3', '6', '0'),
       ('6', '1', '4', NULL, NULL);

-- skor_murid
INSERT INTO `skor_murid`(`sm_id`, `sm_murid`, `sm_kuiz`, `sm_skor`)
VALUES ('1', '1', '1', '50.00'),
       ('2', '2', '1', '50.00'),
       ('3', '1', '2', '50.00');