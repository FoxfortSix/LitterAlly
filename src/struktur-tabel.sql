MariaDB [pilah_sampah]> show tables;
+-------------------------+
| Tables_in_pilah_sampah  |
+-------------------------+
| jenis_sampah            |
| jenis_sampah_multivalue |
| kelas                   |
| kelompok                |
| satuan                  |
| transaksi_sampah        |
| transaksi_sampah_jenis  |
| user_input              |
+-------------------------+

MariaDB [pilah_sampah]> desc jenis_sampah;
+--------------+--------------+------+-----+---------+----------------+
| Field        | Type         | Null | Key | Default | Extra          |
+--------------+--------------+------+-----+---------+----------------+
| id_jenis     | int(11)      | NO   | PRI | NULL    | auto_increment |
| jenis_sampah | varchar(255) | YES  |     | NULL    |                |
+--------------+--------------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from jenis_sampah;
+----------+--------------+
| id_jenis | jenis_sampah |
+----------+--------------+
|        1 | Anorganik    |
|        2 | Organik      |
|        3 | B3           |
+----------+--------------+

MariaDB [pilah_sampah]> desc jenis_sampah_multivalue;
+---------------+--------------+------+-----+---------+----------------+
| Field         | Type         | Null | Key | Default | Extra          |
+---------------+--------------+------+-----+---------+----------------+
| id_multivalue | int(11)      | NO   | PRI | NULL    | auto_increment |
| id_jenis      | int(11)      | NO   | MUL | NULL    |                |
| id_satuan     | int(11)      | YES  | MUL | NULL    |                |
| jumlah        | float        | YES  |     | NULL    |                |
| keterangan    | varchar(255) | YES  |     | NULL    |                |
| nama_subjenis | varchar(255) | NO   |     | NULL    |                |
+---------------+--------------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from jenis_sampah_multivalue;
+---------------+----------+-----------+--------+--------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------------------------------------------------------+
| id_multivalue | id_jenis | id_satuan | jumlah | keterangan                                                                                                                                                   | nama_subjenis                                                                |
+---------------+----------+-----------+--------+--------------------------------------------------------------------------------------------------------------------------------------------------------------+------------------------------------------------------------------------------+
|             1 |        1 |         3 |      4 | Sampah meningkat setelah acara Lebaran, banyak saudara datang. Botol Fanta 4 buah dari konsumsi saudara yang hadir                                           | Botol minuman                                                                |
|             2 |        1 |         3 |     60 | hari pertama lebaran, banyak tamu                                                                                                                            | Botol minuman                                                                |
|             3 |        1 |         3 |     14 | lebaran pertama lumayan banyak sampah dari tamu yang hadir                                                                                                   | Cup minuman                                                                  |
|             4 |        1 |         3 |      2 | botol sirup buat tamu jadi cuma 2                                                                                                                            | Botol kaca                                                                   |
|             5 |        1 |         3 |     13 | Setelah hari raya banyak anak anak berkumpul dirumah dan membawa makanan ringan                                                                              | Bungkus snack                                                                |

MariaDB [pilah_sampah]> desc kelas;
+----------+--------------+------+-----+---------+----------------+
| Field    | Type         | Null | Key | Default | Extra          |
+----------+--------------+------+-----+---------+----------------+
| id_kelas | int(11)      | NO   | PRI | NULL    | auto_increment |
| kelas    | varchar(255) | YES  |     | NULL    |                |
+----------+--------------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from kelas;
+----------+-------+
| id_kelas | kelas |
+----------+-------+
|        1 | C1    |
|        2 | C2    |
+----------+-------+

MariaDB [pilah_sampah]> desc kelompok;
+-------------+---------+------+-----+---------+----------------+
| Field       | Type    | Null | Key | Default | Extra          |
+-------------+---------+------+-----+---------+----------------+
| id_kelompok | int(11) | NO   | PRI | NULL    | auto_increment |
| kelompok    | int(11) | YES  |     | NULL    |                |
+-------------+---------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from kelompok;
+-------------+----------+
| id_kelompok | kelompok |
+-------------+----------+
|           1 |        1 |
|           2 |        2 |
|           3 |        3 |
|           4 |        4 |
|           5 |        5 |
|           6 |        6 |
|           7 |        7 |
|           8 |        8 |
|           9 |        9 |
+-------------+----------+

MariaDB [pilah_sampah]> desc satuan;
+-------------+--------------+------+-----+---------+----------------+
| Field       | Type         | Null | Key | Default | Extra          |
+-------------+--------------+------+-----+---------+----------------+
| id_satuan   | int(11)      | NO   | PRI | NULL    | auto_increment |
| nama_satuan | varchar(255) | YES  |     | NULL    |                |
+-------------+--------------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from satuan;
+-----------+-------------+
| id_satuan | nama_satuan |
+-----------+-------------+
|         1 | Kg          |
|         2 | Gr          |
|         3 | Buah        |
|         4 | Lembar      |
|         5 | Kantong     |
|         6 | L           |
+-----------+-------------+

MariaDB [pilah_sampah]> desc transaksi_sampah;
+--------------+----------+------+-----+---------+----------------+
| Field        | Type     | Null | Key | Default | Extra          |
+--------------+----------+------+-----+---------+----------------+
| id_transaksi | int(11)  | NO   | PRI | NULL    | auto_increment |
| timestamp    | datetime | YES  |     | NULL    |                |
| id_user      | int(11)  | YES  | MUL | NULL    |                |
+--------------+----------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from transaksi_sampah;
+--------------+---------------------+---------+
| id_transaksi | timestamp           | id_user |
+--------------+---------------------+---------+
|            1 | 2025-03-31 20:24:57 |      47 |
|            2 | 2025-03-31 20:45:39 |      67 |
|            3 | 2025-03-31 21:22:56 |      65 |
|            4 | 2025-03-31 21:25:58 |      65 |
|            5 | 2025-03-31 21:26:13 |      69 |

MariaDB [pilah_sampah]> desc transaksi_sampah_jenis;
+---------------+---------+------+-----+---------+-------+
| Field         | Type    | Null | Key | Default | Extra |
+---------------+---------+------+-----+---------+-------+
| id_transaksi  | int(11) | NO   | PRI | NULL    |       |
| id_multivalue | int(11) | NO   | PRI | NULL    |       |
+---------------+---------+------+-----+---------+-------+

MariaDB [pilah_sampah]> select * from transaksi_sampah_jenis;
+--------------+---------------+
| id_transaksi | id_multivalue |
+--------------+---------------+
|            1 |             1 |
|            2 |             2 |
|            3 |             3 |
|            4 |             4 |
|            5 |             5 |

MariaDB [pilah_sampah]> desc user_input;
+-----------------+--------------+------+-----+---------+----------------+
| Field           | Type         | Null | Key | Default | Extra          |
+-----------------+--------------+------+-----+---------+----------------+
| id_user         | int(11)      | NO   | PRI | NULL    | auto_increment |
| email           | varchar(255) | YES  |     | NULL    |                |
| nama_user       | varchar(255) | YES  |     | NULL    |                |
| password        | varchar(255) | YES  |     | NULL    |                |
| timestamp_input | datetime     | YES  |     | NULL    |                |
| id_kelas        | int(11)      | YES  | MUL | NULL    |                |
| id_kelompok     | int(11)      | YES  | MUL | NULL    |                |
+-----------------+--------------+------+-----+---------+----------------+

MariaDB [pilah_sampah]> select * from user_input;
+---------+------------------------------+---------------------------------------+------------+---------------------+----------+-------------+
| id_user | email                        | nama_user                             | password   | timestamp_input     | id_kelas | id_kelompok |
+---------+------------------------------+---------------------------------------+------------+---------------------+----------+-------------+
|       1 | aryaps@upi.edu               | Arya Purnama Sauri                    | ilkompc1c2 | 2025-05-06 16:56:34 |        1 |           1 |
|       2 | bintangfajarputra@upi.edu    | Bintang Fajar Putra Pamungkas         | ilkompc1c2 | 2025-05-06 16:56:34 |        1 |           5 |
|       3 |                              | Daffa Naufal Muammar                  | ilkompc1c2 | 2025-05-06 16:56:34 |        1 |        NULL |
|       4 | daffadhiaacandra@upi.edu     | Daffa Dhiyaa Chandra                  | ilkompc1c2 | 2025-05-06 16:56:34 |        1 |           2 |
|       5 | dhiyau27@upi.edu             | Dhiya Ulhaq                           | ilkompc1c2 | 2025-05-06 16:56:34 |        1 |           8 |