<?php

use Core\Repositories\OrderRepository;
use Core\Repositories\UserRepository;

$type = $_GET['type'] ?? null;
$period = $_GET['period'] ?? 'month';

if (!$type) {
    response(['error' => "Invalid input. No 'type' query parameter for report found."], 400);
}

function bucket(string $period, array $rows, string $dateField = "date", ?string $accumulatorField = null): array
{
    if (empty($rows)) return [];

    $daysMap = [
        'week' => 7,
        'month' => 30,
        'year' => 365,
    ];
    $buckets = [];

    if ($period === 'year') {
        $buckets = array_fill(1, 12, 0);
        foreach ($rows as $row) {
            // Increment appropriate bucket for each order item by 1
            $month = (int) date('n', strtotime($row[$dateField]));
            $buckets[$month] += $row[$accumulatorField] ?? 1;
        }
    } else {
        // Group by day number 1–7 or 1–30
        $maxAge = $daysMap[$period];
        $buckets = array_fill(1, $maxAge, 0);
        foreach ($rows as $row) {
            $now = new DateTime();
            $orderDate = new DateTime($row[$dateField]);
            $age = (int) $now->diff($orderDate)->format('%a');
            $bucket = $maxAge - $age;

            // Check within range of maxAge
            if ($bucket >= 1 && $bucket <= $maxAge) {
                $buckets[$bucket] += $row[$accumulatorField] ?? 1;
            }
        }
    }

    return $buckets;
}

$orders = new OrderRepository();
$users = new UserRepository();

switch ($type) {

    case 'order-volume':
        $rows = $orders->volume($period);
        $volumeBuckets = bucket($period, $rows);
        response($volumeBuckets);

    case 'revenue':
        $rows = $orders->revenue($period);
        $revenueBuckets = bucket($period, $rows, 'date', 'total');
        response($revenueBuckets);

    case 'sales':
        $rows = $orders->sales($period);
        $salesBuckets = bucket($period, $rows, 'date', 'quantity');
        response($salesBuckets);

    case 'new-users':
        $rows = $users->new($period);
        $usersBuckets = bucket($period, $rows, 'date_joined');
        response($usersBuckets);

    case 'pending-sellers':
        $rows = $users->pendingSellers();
        $psBuckets = bucket($period, $rows, 'date_submitted');
        response($psBuckets);
        break;

    default:
        response(['error' => "Invalid input. Unrecognized report type '{$type}'."], 400);
}
