<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Panggil function.php dari parent folder
require_once __DIR__ . '/../function.php';

header('Content-Type: application/json');

// Ambil data dari database
$data = getSampahData();

$organized = [];

foreach ($data as $row) {
    $date = $row['tanggal'];
    $jenis = strtolower($row['jenis_sampah']);
    $jumlah = floatval($row['total_jumlah']);

    if (!isset($organized['labels'])) $organized['labels'] = [];
    if (!in_array($date, $organized['labels'])) {
        $organized['labels'][] = $date;
    }

    if (!isset($organized[$jenis])) {
        $organized[$jenis] = [];
    }

    $organized[$jenis][$date] = $jumlah;
}

$datasets = [];
$colors = [
    'organik' => 'rgb(20, 184, 166)',
    'anorganik' => 'rgb(251, 146, 60)',
    'b3' => 'rgb(239, 68, 68)'
];

foreach (['organik', 'anorganik', 'b3'] as $jenis) {
    $data = [];

    foreach ($organized['labels'] as $label) {
        $data[] = isset($organized[$jenis][$label]) ? $organized[$jenis][$label] : 0;
    }

    $datasets[] = [
        'label' => ucfirst($jenis),
        'data' => $data,
        'borderColor' => $colors[$jenis],
        'backgroundColor' => $colors[$jenis],
        'fill' => false
    ];
}

echo json_encode([
    'labels' => $organized['labels'],
    'datasets' => $datasets
]);