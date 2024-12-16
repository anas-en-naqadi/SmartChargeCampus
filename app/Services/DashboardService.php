<?php

namespace App\Services;

use App\Models\ChargingStation;
use App\Models\Reservation;
use App\Models\Student;
use Carbon\Carbon;


class DashboardService
{
    private const  colors = [
        'rgba(76, 175, 80, 0.7)', // Vert doux
        'rgba(255, 193, 7, 0.7)', // Orange clair
        'rgba(33, 150, 243, 0.7)', // Bleu ciel
        'rgba(233, 30, 99, 0.7)', // Rose pâle
        'rgba(255, 235, 59, 0.7)', // Jaune clair
        'rgba(0, 188, 212, 0.7)', // Turquoise
        'rgba(156, 39, 176, 0.7)'  // Lavande
    ];

    private const    borderColors = [
        'rgba(56, 142, 60, 1)',  // Vert doux foncé
        'rgba(255, 140, 0, 1)',   // Orange doux foncé
        'rgba(21, 101, 192, 1)',  // Bleu doux foncé
        'rgba(194, 24, 91, 1)',   // Rose doux foncé
        'rgba(255, 193, 7, 1)',   // Jaune doux foncé
        'rgba(0, 150, 136, 1)',   // Turquoise foncé
        'rgba(123, 31, 162, 1)'   // Lavande foncée
    ];
    public function __construct() {}






    public function userDashboardData()
    {
        $cacheKey = 'user_dashboard_data';
        $cacheData = getCachedData($cacheKey, function () {

            $totalReservations = Reservation::where('student_id', getSimpleUser()?->student?->id)->count();

            $visitedChargeStations = getSimpleUser()->student->reservations->map(function ($query) {
                return $query->port->charge_station;
            })->unique('id')->count();
            $numOfPaidReservation = Reservation::where('student_id', getSimpleUser()?->student?->id)
            ->get()
                ->filter(function ($rese) {
                    return $rese->payment->payment_status === 'payé';
                })
                ->count();

            $totalPaid = $numOfPaidReservation * 5;

            $numberOfChargingTime = $numOfPaidReservation;

            return [
                'totalReservations' => $totalReservations,
                'visitedChargeStations' => $visitedChargeStations,
                'totalPaid' => $totalPaid,
                'numberOfChargingTime' => $numberOfChargingTime,
            ];
        });
        return $cacheData;
    }


    public function getWeeklyChargeData($colors, $borderColors)
    {
        $cacheKey = 'weekly_charge_data';
        $cacheData = getCachedData(
            $cacheKey,
            function () use ($colors, $borderColors) {
                $data = Reservation::where('student_id', getSimpleUser()?->student?->id)
                    ->whereHas('payment', function ($query) {
                        $query->where('payment_status', 'payé');
                    }) // Filtrer les réservations où le paiement est "payé"
                    ->selectRaw('DAYNAME(created_at) as day, COUNT(*) as total')
                    ->whereBetween('created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]) // Filtrer les réservations de cette semaine
                    ->groupBy('day')
                    ->orderByRaw("FIELD(DAYNAME(created_at), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                    ->get();

                // Transformer les résultats pour les graphiques
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $result = collect($days)->map(function ($day) use ($data) {
                    $dayData = $data->firstWhere('day', $day);
                    return [
                        'day' => $day,
                        'total' => $dayData ? $dayData->total : 0,
                    ];
                });
                // Retourner les données au frontend
                return [
                    'labels' => $result->pluck('day'), // Jours de la semaine
                    'data' => $result->pluck('total'), // Nombre de charges
                    'colors' => $colors,
                    'borderColors' => $borderColors
                ];
            }
        );

        return $cacheData;
    }


    public function getMonthlyChargeData($colors, $borderColors)
    {
        $cacheKey = 'monthly_charge_data';
        $cacheData = getCachedData(
            $cacheKey,
            function() use ($colors, $borderColors) {
                // Retrieve data grouped by month
                $data = Reservation::where('student_id', getSimpleUser()?->student?->id)
                    ->whereHas('payment', function ($query) {
                        $query->where('payment_status', 'payé');
                    })
                    ->selectRaw('MONTHNAME(created_at) as month, COUNT(*) as total')
                    ->whereYear('created_at', Carbon::now()->year) // Only current year
                    ->groupBy('month')
                    ->orderByRaw("FIELD(MONTHNAME(created_at), 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                    ->pluck('total', 'month'); // Get data as key-value pair (month => total)

                // Ensure all months are included with zero if no data exists
                $allMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $chartData = collect($allMonths)->mapWithKeys(function ($month) use ($data) {
                    return [$month => $data->get($month, 0)];
                });

                // Prepare chart data for the frontend
                return [
                    'labels' => $chartData->keys(), // Months as labels
                    'data' => $chartData->values(), // Counts as data
                    'colors' => $colors,
                    'borderColors' => $borderColors,
                ];
            }
        );
        return $cacheData;
    }


    public function adminDashboardData()
    {
        $cacheKey = 'admin_dashboard_data';
        $cacheData = getCachedData(
            $cacheKey,
            function () {
                $totalStudent = Student::count();
                $totalReservations = Reservation::count();
                $totalChargeStation = ChargingStation::count();

                // Calcul du total des gains
                $totalWin = Reservation::with('payment')->get()->reduce(function ($carry, $reservation) {
                    if ($reservation->payment && $reservation->payment->payment_status === 'payé') {
                        $carry += 5;
                    }
                    return $carry;
                }, 0);

                return [
                    'totalStudent' => $totalStudent,
                    'totalReservations' => $totalReservations,
                    'totalChargeStation' => $totalChargeStation,
                    'totalWin' => $totalWin,
                ];
            }
        );
        return $cacheData;
    }


    public function PaidAmountEachWeek()
    {
        $cacheKey = 'paid_amount_each_week';
        $cacheData = getCachedData(
            $cacheKey,
            function () {
                $data = Reservation::whereHas('payment', function ($query) {
                    $query->where('payment_status', 'payé');
                })
                    ->selectRaw('DAYNAME(reservations.created_at) as day, SUM(payments.amount) as total_paid') // Sum from payments table
                    ->join('payments', 'payments.reservation_id', '=', 'reservations.id') // Join with the payments table
                    ->whereBetween('reservations.created_at', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ])
                    ->groupBy('day')
                    ->orderByRaw("FIELD(DAYNAME(reservations.created_at), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
                    ->get();

                // List of days for correct ordering on the graph
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

                // Transform results for the graph
                $result = collect($days)->map(function ($day) use ($data) {
                    $dayData = $data->firstWhere('day', $day);
                    return [
                        'day' => $day,
                        'total_paid' => $dayData ? $dayData->total_paid : 0,
                    ];
                });




                // Return the data to the frontend
                return [
                    'labels' => $result->pluck('day'), // Days of the week
                    'data' => $result->pluck('total_paid'), // Paid amounts per day
                    'colors' => Self::colors, // Bar colors
                    'borderColors' => Self::borderColors, // Border colors of the bars
                ];
            }
        );
        return $cacheData;
    }

    public function PaidAmountEachMonth()
    {
        $cacheKey = 'paid_amount_each_month';
        $cacheData = getCachedData(
            $cacheKey,
            function () {
                $data = Reservation::whereHas('payment', function ($query) {
                    $query->where('payment_status', 'payé');
                })
                    ->selectRaw('MONTHNAME(reservations.created_at) as month, SUM(payments.amount) as total_paid') // Sum from payments table
                    ->join('payments', 'payments.reservation_id', '=', 'reservations.id') // Join with the payments table
                    ->whereBetween('reservations.created_at', [
                        Carbon::now()->startOfYear(),
                        Carbon::now()->endOfYear(),
                    ])
                    ->groupBy('month')
                    ->orderByRaw("FIELD(MONTHNAME(reservations.created_at), 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')")
                    ->get();

                // List of months for correct ordering on the graph
                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                // Transform results for the graph
                $result = collect($months)->map(function ($month) use ($data) {
                    $monthData = $data->firstWhere('month', $month);
                    return [
                        'month' => $month,
                        'total_paid' => $monthData ? $monthData->total_paid : 0,
                    ];
                });




                // Return the data to the frontend
                return [
                    'labels' => $result->pluck('month'), // Months of the year
                    'data' => $result->pluck('total_paid'), // Paid amounts per month
                    'colors' => Self::colors, // Bar colors
                    'borderColors' => Self::borderColors, // Border colors of the bars
                ];
            }
        );
        return $cacheData;
    }

    public function getPaymentStatusPercentages()
    {
        $cacheKey = 'payment_status_percentage';
        $cacheData = getCachedData(
            $cacheKey,
            function () {
                // Total des réservations
                $totalReservations = Reservation::count();

                if ($totalReservations === 0) {
                    return [
                        'labels' => ['en_attente', 'payé', 'échoué', 'annulé'],
                        'data' => [0, 0, 0, 0],
                        'colors' => [
                            'rgba(99, 180, 255, 0.8)',   // Soft Blue
                            'rgba(123, 201, 82, 0.8)',  // Soft Green
                            'rgba(255, 138, 128, 0.8)', // Soft Coral
                            'rgba(186, 104, 200, 0.8)', // Soft Purple
                        ],
                        'borderColors' => [
                            'rgba(33, 150, 243, 1)',  // Vibrant Blue
                            'rgba(76, 175, 80, 1)',   // Vibrant Green
                            'rgba(239, 83, 80, 1)',   // Vibrant Coral
                            'rgba(156, 39, 176, 1)',  // Vibrant Purple
                        ],


                    ];
                }

                // Récupérer les pourcentages des statuts
                $statuses = Reservation::with('payment')
                    ->whereHas('payment') // Ensure only reservations with payments are considered
                    ->join('payments', 'reservations.id', '=', 'payments.reservation_id') // Join with the payments table
                    ->selectRaw('payments.payment_status, COUNT(*) as count')
                    ->groupBy('payments.payment_status')
                    ->get();

                // Préparer les résultats
                $result = [
                    'labels' => ['en_attente', 'payé', 'échoué', 'annulé'], // Tous les statuts possibles
                    'data' => [0, 0, 0, 0], // Initialiser les pourcentages
                    'colors' => ['#FFB74D', '#4CAF50', '#E57373', '#90A4AE'], // Couleurs pour chaque statut
                    'borderColors' => ['#FF9800', '#388E3C', '#D32F2F', '#607D8B'] // Couleurs de bordure
                ];

                foreach ($statuses as $status) {
                    $index = array_search($status->payment_status, $result['labels']);
                    if ($index !== false) {
                        $result['data'][$index] = ($status->count / $totalReservations) * 100;
                    }
                }

                return $result;
            }
        );
        return $cacheData;
    }


    public function getPortsPerStation()
    {
        $cacheKey = 'ports_per_station';
        $cacheData = getCachedData(
            $cacheKey,
            function () {
                // Optimisation : utilisation de withCount pour compter les ports sans charger toutes les relations
                $stations = ChargingStation::with('ports') // Get stations with ports
                    ->get(['name', 'id']) // Retrieve all stations
                    ->map(function ($station) {
                        // Manually assign the ports_count
                        $station['ports_count'] = $station->ports->count();
                        return $station;
                    });

                // Préparer les données pour le graphique
                $labels = $stations->pluck('name'); // Les noms des stations
                $data = $stations->pluck('ports_count'); // Le nombre de ports dans chaque station

                // Couleurs dynamiques pour rendre le graphique plus attractif et lisible
                $colors = [
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 205, 86, 0.8)',
                    'rgba(201, 203, 207, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ];

                $borderColors = [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(201, 203, 207, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)'
                ];

                // Renvoyer les données dans le format requis par le frontend
                return [
                    'labels' => $labels, // Noms des stations
                    'data' => $data, // Nombre de ports
                    'colors' => $colors, // Couleurs de remplissage pour chaque segment du graphique
                    'borderColors' => $borderColors, // Bordures des segments
                ];
            }
        );
        return $cacheData;
    }
}
