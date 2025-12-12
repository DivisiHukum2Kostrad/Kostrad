<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perkara;
use App\Models\Kategori;
use App\Models\Personel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $stats = [
            'total_perkara' => Perkara::count(),
            'perkara_selesai' => Perkara::selesai()->count(),
            'perkara_proses' => Perkara::proses()->count(),
            'perkara_bulan_ini' => Perkara::whereMonth('tanggal_masuk', now()->month)
                                        ->whereYear('tanggal_masuk', now()->year)
                                        ->count(),
            'total_personel' => Personel::count(),
            'total_kategori' => Kategori::count(),
        ];

        // Calculate completion rate
        $stats['completion_rate'] = $stats['total_perkara'] > 0
            ? round(($stats['perkara_selesai'] / $stats['total_perkara']) * 100, 1)
            : 0;

        // Average completion time (in days)
        $completedCases = Perkara::selesai()
            ->whereNotNull('tanggal_selesai')
            ->get();

        if ($completedCases->count() > 0) {
            $totalDays = $completedCases->sum(function($perkara) {
                return \Carbon\Carbon::parse($perkara->tanggal_masuk)->diffInDays(\Carbon\Carbon::parse($perkara->tanggal_selesai));
            });
            $stats['avg_completion_days'] = round($totalDays / $completedCases->count(), 1);
        } else {
            $stats['avg_completion_days'] = 0;
        }

        // Data perkara per kategori (for pie chart)
        $perkara_per_kategori = Kategori::withCount('perkaras')
            ->having('perkaras_count', '>', 0)
            ->get();

        // Monthly trend data (last 6 months)
        $monthly_data = $this->getMonthlyTrend();

        // Status distribution
        $status_distribution = Perkara::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Recent activities (latest 5 cases)
        $latest_perkaras = Perkara::with('kategori')
            ->latest()
            ->take(5)
            ->get();

        // Top categories by case count
        $top_categories = Kategori::withCount('perkaras')
            ->orderByDesc('perkaras_count')
            ->take(5)
            ->get();

        // Cases by month comparison (current year vs last year)
        $yearly_comparison = $this->getYearlyComparison();

        return view('admin.dashboard', compact(
            'stats',
            'perkara_per_kategori',
            'latest_perkaras',
            'monthly_data',
            'status_distribution',
            'top_categories',
            'yearly_comparison'
        ));
    }

    private function getMonthlyTrend()
    {
        $months = [];
        $data = [
            'labels' => [],
            'masuk' => [],
            'selesai' => [],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $data['labels'][] = $date->format('M Y');

            // Cases received
            $masuk = Perkara::whereYear('tanggal_masuk', $date->year)
                ->whereMonth('tanggal_masuk', $date->month)
                ->count();
            $data['masuk'][] = $masuk;

            // Cases completed
            $selesai = Perkara::whereYear('tanggal_selesai', $date->year)
                ->whereMonth('tanggal_selesai', $date->month)
                ->where('status', 'Selesai')
                ->count();
            $data['selesai'][] = $selesai;
        }

        return $data;
    }

    private function getYearlyComparison()
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        return [
            'current_year' => Perkara::whereYear('tanggal_masuk', $currentYear)->count(),
            'last_year' => Perkara::whereYear('tanggal_masuk', $lastYear)->count(),
            'current_year_label' => $currentYear,
            'last_year_label' => $lastYear,
        ];
    }
}
