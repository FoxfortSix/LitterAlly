-- QUERY UNTUK MENAMPILKAN SELURUH RECORD
SELECT 
    ts.id_transaksi AS ID_Transaksi,
    ts.timestamp AS Timestamp,
    ui.nama_user AS Nama_User,
    k.kelas AS Kelas,
    kp.kelompok AS Kelompok,
    jsmv.jumlah AS Jumlah,
    s.nama_satuan AS Satuan,
    jsmv.nama_subjenis AS Nama_Sampah,
    js.jenis_sampah AS Jenis_Sampah,
    jsmv.keterangan AS Keterangan
FROM transaksi_sampah ts
LEFT JOIN user_input ui ON ts.id_user = ui.id_user
LEFT JOIN kelas k ON ui.id_kelas = k.id_kelas
LEFT JOIN kelompok kp ON ui.id_kelompok = kp.id_kelompok
LEFT JOIN transaksi_sampah_jenis tsv ON ts.id_transaksi = tsv.id_transaksi
LEFT JOIN jenis_sampah_multivalue jsmv ON tsv.id_multivalue = jsmv.id_multivalue
LEFT JOIN jenis_sampah js ON jsmv.id_jenis = js.id_jenis
LEFT JOIN satuan s ON jsmv.id_satuan = s.id_satuan
ORDER BY ts.id_transaksi ASC;

-- NOMOR 1
SELECT 
    ui.nama_user,
    COUNT(ts.id_transaksi) AS total_lapor,
    ROUND((COUNT(ts.id_transaksi) * 100 / (SELECT COUNT(*) FROM transaksi_sampah)), 2) AS Persentase
FROM user_input ui
JOIN transaksi_sampah ts ON ui.id_user = ts.id_user
GROUP BY ui.id_user
ORDER BY total_lapor DESC;

-- NOMOR 2
SELECT 
    ui.nama_user,
    s.nama_satuan,
    SUM(jsmv.jumlah) AS total_sampah
FROM user_input ui
JOIN transaksi_sampah ts ON ui.id_user = ts.id_user
JOIN transaksi_sampah_jenis tsv ON ts.id_transaksi = tsv.id_transaksi
JOIN jenis_sampah_multivalue jsmv ON tsv.id_multivalue = jsmv.id_multivalue
JOIN satuan s ON jsmv.id_satuan = s.id_satuan
WHERE s.id_satuan = 3
GROUP BY ui.id_user, s.nama_satuan
ORDER BY total_sampah DESC;

-- NOMOR 3
SELECT
    ui.nama_user,
    COUNT(DISTINCT js.id_jenis) AS jumlah_jenis
FROM transaksi_sampah ts
JOIN user_input ui ON ui.id_user = ts.id_user
JOIN transaksi_sampah_jenis tsv ON tsv.id_transaksi = ts.id_transaksi
JOIN jenis_sampah_multivalue jsmv ON jsmv.id_multivalue = tsv.id_multivalue
JOIN jenis_sampah js ON js.id_jenis = jsmv.id_jenis
GROUP BY ui.nama_user
ORDER BY ui.nama_user;

-- procedure
DELIMITER //

CREATE PROCEDURE jumlah_jenis_sampah_per_mahasiswa(
    IN tanggal_awal DATE,
    IN tanggal_akhir DATE
)
BEGIN
    SELECT
        ui.nama_user,
        COUNT(js.id_jenis) AS jumlah_jenis_sampah
    FROM transaksi_sampah ts
    JOIN user_input ui ON ui.id_user = ts.id_user
    JOIN transaksi_sampah_jenis tsj ON tsj.id_transaksi = ts.id_transaksi 
    JOIN jenis_sampah_multivalue jsm ON jsm.id_multivalue = tsj.id_multivalue
    JOIN jenis_sampah js ON js.id_jenis = jsm.id_jenis
    WHERE DATE(ts.timestamp) BETWEEN tanggal_awal AND tanggal_akhir
    GROUP BY ui.nama_user
    ORDER BY ui.nama_user;
END //

DELIMITER ;

-- Call
CALL jumlah_jenis_sampah_per_mahasiswa('2025-04-01', '2025-04-30');

-- NOMOR 4
SELECT
    ui.nama_user,
    SUM(CASE WHEN js.jenis_sampah = 'organik' THEN 1 ELSE 0 END) AS Organik,
    SUM(CASE WHEN js.jenis_sampah = 'anorganik' THEN 1 ELSE 0 END) AS Anorganik,
    SUM(CASE WHEN js.jenis_sampah = 'B3' THEN 1 ELSE 0 END) AS B3
FROM transaksi_sampah ts
JOIN user_input ui ON ui.id_user = ts.id_user
JOIN transaksi_sampah_jenis tsv ON tsv.id_transaksi = ts.id_transaksi
JOIN jenis_sampah_multivalue jsmv ON jsmv.id_multivalue = tsv.id_multivalue
JOIN jenis_sampah js ON js.id_jenis = jsmv.id_jenis
GROUP BY ui.nama_user
ORDER BY ui.nama_user;

-- NOMOR 5
SELECT
    js.jenis_sampah,
    SUM(CASE WHEN s.nama_satuan = 'kg' THEN jsmv.jumlah ELSE 0 END) AS KG,
    SUM(CASE WHEN s.nama_satuan = 'gr' THEN jsmv.jumlah ELSE 0 END) AS Gr,
    SUM(CASE WHEN s.nama_satuan = 'lembar' THEN jsmv.jumlah ELSE 0 END) AS Lembar,
    SUM(CASE WHEN s.nama_satuan = 'buah' THEN jsmv.jumlah ELSE 0 END) AS Buah,
    SUM(CASE WHEN s.nama_satuan = 'kantong' THEN jsmv.jumlah ELSE 0 END) AS Kantong,
    'Sabtu' AS Hari
FROM transaksi_sampah ts
JOIN transaksi_sampah_jenis tsv ON ts.id_transaksi = tsv.id_transaksi
JOIN jenis_sampah_multivalue jsmv ON jsmv.id_multivalue = tsv.id_multivalue
JOIN jenis_sampah js ON js.id_jenis = jsmv.id_jenis
JOIN satuan s ON s.id_satuan = jsmv.id_satuan
WHERE DAYOFWEEK(ts.timestamp) = 7
GROUP BY js.jenis_sampah

UNION ALL

SELECT
    js.jenis_sampah,
    SUM(CASE WHEN s.nama_satuan = 'kg' THEN jsmv.jumlah ELSE 0 END) AS KG,
    SUM(CASE WHEN s.nama_satuan = 'gr' THEN jsmv.jumlah ELSE 0 END) AS Gr,
    SUM(CASE WHEN s.nama_satuan = 'lembar' THEN jsmv.jumlah ELSE 0 END) AS Lembar,
    SUM(CASE WHEN s.nama_satuan = 'buah' THEN jsmv.jumlah ELSE 0 END) AS Buah,
    SUM(CASE WHEN s.nama_satuan = 'kantong' THEN jsmv.jumlah ELSE 0 END) AS Kantong,
    'Minggu' AS Hari
FROM transaksi_sampah ts
JOIN transaksi_sampah_jenis tsv ON ts.id_transaksi = tsv.id_transaksi
JOIN jenis_sampah_multivalue jsmv ON jsmv.id_multivalue = tsv.id_multivalue
JOIN jenis_sampah js ON js.id_jenis = jsmv.id_jenis
JOIN satuan s ON s.id_satuan = jsmv.id_satuan
WHERE DAYOFWEEK(ts.timestamp) = 1
GROUP BY js.jenis_sampah;

-- NOMOR 6
SELECT
    nama_user,
    CASE
        WHEN Organik >= Anorganik AND Organik >= B3 THEN 'Organik'
        WHEN Anorganik >= Organik AND Anorganik >= B3 THEN 'Anorganik'
        WHEN B3 >= Organik AND B3 >= Anorganik THEN 'B3'
    END AS Jenis_Sampah_Paling_Sering
FROM (
    SELECT
        ui.nama_user,
        SUM(CASE WHEN js.jenis_sampah = 'organik' THEN 1 ELSE 0 END) AS Organik,
        SUM(CASE WHEN js.jenis_sampah = 'anorganik' THEN 1 ELSE 0 END) AS Anorganik,
        SUM(CASE WHEN js.jenis_sampah = 'B3' THEN 1 ELSE 0 END) AS B3
    FROM transaksi_sampah ts
    JOIN user_input ui ON ui.id_user = ts.id_user
    JOIN transaksi_sampah_jenis tsv ON tsv.id_transaksi = ts.id_transaksi
    JOIN jenis_sampah_multivalue jsmv ON jsmv.id_multivalue = tsv.id_multivalue
    JOIN jenis_sampah js ON js.id_jenis = jsmv.id_jenis
    GROUP BY ui.nama_user
) AS Temp
ORDER BY nama_user;