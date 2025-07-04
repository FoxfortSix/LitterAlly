<?php
require_once '../../function.php';

$id_user = isset($_GET['id_user']) ? (int)$_GET['id_user'] : 0;
$jenis_sampah = isset($_GET['jenis_sampah']) ? $_GET['jenis_sampah'] : [];

$data = getSampahDataByUser2($id_user, $jenis_sampah);

// Format data untuk chart
$labels = [];
$datasets = [
    'Organik' => [],
    'Anorganik' => [],
    'B3' => []
];

foreach ($data as $row) {
    $tanggal = $row['tanggal'];
    if (!in_array($tanggal, $labels)) {
        $labels[] = $tanggal;
    }

    $jenis = $row['jenis_sampah'];
    $total = (float)$row['total_jumlah'];

    if ($jenis == 'Organik') {
        $datasets['Organik'][] = $total;
    } elseif ($jenis == 'Anorganik') {
        $datasets['Anorganik'][] = $total;
    } elseif ($jenis == 'B3') {
        $datasets['B3'][] = $total;
    }
}

// Pastikan semua dataset memiliki jumlah data yang sama
foreach ($datasets as $jenis => $values) {
    if (count($values) < count($labels)) {
        $diff = count($labels) - count($values);
        for ($i = 0; $i < $diff; $i++) {
            $datasets[$jenis][] = 0;
        }
    }
}

$chartData = [
    'labels' => $labels,
    'datasets' => [
        [
            'label' => 'Organik',
            'data' => $datasets['Organik'],
            'borderColor' => 'rgba(75, 192, 192, 1)',
            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
            'fill' => false
        ],
        [
            'label' => 'Anorganik',
            'data' => $datasets['Anorganik'],
            'borderColor' => 'rgba(54, 162, 235, 1)',
            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
            'fill' => false
        ],
        [
            'label' => 'B3',
            'data' => $datasets['B3'],
            'borderColor' => 'rgba(255, 99, 132, 1)',
            'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
            'fill' => false
        ]
    ]
];

header('Content-Type: application/json');
echo json_encode($chartData);