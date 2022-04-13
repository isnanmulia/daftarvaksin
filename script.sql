/*** DDL ***/
CREATE TABLE if NOT EXISTS jenis_vaksin (
	id_vaksin INT NOT NULL AUTO_INCREMENT,
	nama_vaksin VARCHAR(15) NOT NULL,
	PRIMARY KEY (id_vaksin)
);

CREATE TABLE IF NOT EXISTS event_vaksin (
	id_event INT NOT NULL AUTO_INCREMENT,
	tanggal_mulai DATE NOT NULL,
	tanggal_selesai DATE NOT NULL,
	judul VARCHAR(100) NOT NULL,
	subjudul VARCHAR(100) DEFAULT NULL,
	syarat_ketentuan TEXT NOT NULL,
	status_event ENUM ('0', '1'),
	status_publish ENUM ('0', '1') DEFAULT '1',
	PRIMARY KEY (id_event)
);

CREATE TABLE if NOT EXISTS detail_event_vaksin (
	id_detail_event INT NOT NULL AUTO_INCREMENT,
	id_event INT NOT NULL,
	tanggal DATE NOT NULL,
	sesi ENUM ('9','10','11'),
	kuota INT NOT NULL,
	vaksin_1 VARCHAR(10) DEFAULT NULL,
	vaksin_2 VARCHAR(10) DEFAULT NULL,
	vaksin_booster VARCHAR(10) DEFAULT NULL,
	status_detail_event ENUM ('0', '1'),
	PRIMARY KEY (id_detail_event),
	FOREIGN KEY (id_event) REFERENCES event_vaksin (id_event)
);

CREATE TABLE if NOT EXISTS peserta_vaksin (
	id_peserta INT NOT NULL AUTO_INCREMENT,
	waktu_daftar TIMESTAMP NOT NULL,
	id_detail_event INT NOT NULL,
	nama VARCHAR(100) NOT NULL,
	jk ENUM ('L', 'P'),
	nik VARCHAR(16) NOT NULL,
	tanggal_lahir DATE NOT NULL,
	kelompok_usia ENUM ('umum', 'pralansia', 'lansia'),
	alamat VARCHAR(100) NOT NULL,
	no_hp VARCHAR(30) NOT NULL,
	email VARCHAR(100) NOT NULL,
	vaksin_ke ENUM ('1', '2', 'booster'),
	vaksin_primer INT NULL DEFAULT NULL,
	etiket_pl VARCHAR(15) DEFAULT NULL,
	status_peserta ENUM ('0', '1') DEFAULT '1',
	PRIMARY KEY (id_peserta),
	FOREIGN KEY (id_detail_event) REFERENCES detail_event_vaksin (id_detail_event)
);

CREATE TABLE if NOT EXISTS pengguna (
	id_pengguna INT NOT NULL AUTO_INCREMENT,
	nama_pengguna VARCHAR(10) NOT NULL,
	nama_lengkap VARCHAR(50) NOT NULL,
	password_pengguna VARCHAR(50) NOT NULL,
	jenis_pengguna ENUM ('1','2'),
	status_pengguna ENUM ('0','1') DEFAULT '1',
	PRIMARY KEY (id_pengguna)
);

/*************************************/
/*** DML ***/
INSERT INTO jenis_vaksin (nama_vaksin) VALUES ('Sinovac'), ('AstraZeneca'), ('Moderna'), ('Pfizer');

INSERT INTO pengguna (nama_pengguna, nama_lengkap, password_pengguna, jenis_pengguna, status_pengguna) VALUES ('admin', 'Admin Utama', MD5('admin'), '1', '1');
