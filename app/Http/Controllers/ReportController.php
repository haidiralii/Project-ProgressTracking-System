<?php

namespace App\Http\Controllers;

use App\Models\JobActivity;
use App\Models\Project;
use Illuminate\Http\Request;
use Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    // Halaman utama laporan (gabungan daily/monthly/project)
    public function index(Request $request)
    {
        $type = $request->get('type');
        $date = $request->get('date');
        $month = $request->get('month');
        $projectId = $request->get('project_id');

        $activities = $this->getFilteredActivities($type, $date, $month, $projectId);
        $projects = Project::orderBy('name')->get();

        return view('reports.index', compact(
            'type', 'date', 'month', 'projectId', 'activities', 'projects'
        ));
    }

    // Laporan Harian Khusus (optional legacy)
    public function daily(Request $request)
    {
        $date = $request->get('date', now()->toDateString());
        $activities = $this->getFilteredActivities('daily', $date);
        return view('reports.daily', compact('activities', 'date'));
    }

    // Export laporan aktivitas ke Excel
    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', now()->toDateString());
        $month = $request->get('month', now()->format('Y-m'));
        $projectId = $request->get('project_id');

        $activities = $this->getFilteredActivities($type, $date, $month, $projectId);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['Tanggal', 'Operator', 'Project', 'Job', 'Deskripsi Aktivitas']
        ], null, 'A1');

        $row = 2;
        foreach ($activities as $act) {
            $sheet->setCellValue("A{$row}", $act->activity_date);
            $sheet->setCellValue("B{$row}", $act->user->name);
            $sheet->setCellValue("C{$row}", $act->job->project->name);
            $sheet->setCellValue("D{$row}", $act->job->title);
            $sheet->setCellValue("E{$row}", $act->activity_note);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'report_' . now()->format('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // Export laporan aktivitas ke PDF
    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', now()->toDateString());
        $month = $request->get('month', now()->format('Y-m'));
        $projectId = $request->get('project_id');

        $activities = $this->getFilteredActivities($type, $date, $month, $projectId);

        $pdf = Pdf::loadView('reports.export-pdf', compact('activities', 'type', 'date', 'month'));
        return $pdf->download('report_' . now()->format('Ymd_His') . '.pdf');
    }

    // Export data project untuk role director
    public function projectReport()
    {
        if (Auth::user()->role !== 'director') {
            abort(403, 'Unauthorized access.');
        }

        // Tambahkan eager load jobs agar progress_percentage bekerja
        $projects = Project::with('jobs')
            ->select('id', 'name', 'description', 'start_date', 'end_date', 'status')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Tambahkan kolom "Progress (%)"
        $sheet->fromArray([
            ['ID', 'Name', 'Description', 'Start Date', 'End Date', 'Status', 'Progress (%)']
        ], null, 'A1');

        $row = 2;
        foreach ($projects as $project) {
            $sheet->setCellValue("A{$row}", $project->id);
            $sheet->setCellValue("B{$row}", $project->name);
            $sheet->setCellValue("C{$row}", $project->description);
            $sheet->setCellValue("D{$row}", $project->start_date);
            $sheet->setCellValue("E{$row}", $project->end_date);
            $sheet->setCellValue("F{$row}", $project->status);
            $sheet->setCellValue("G{$row}", $project->progress_percentage); // progress dari accessor
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'project_report_' . now()->format('Ymd_His') . '.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment;filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // Helper untuk semua laporan
    private function getFilteredActivities($type = null, $date = null, $month = null, $projectId = null)
    {
        $query = JobActivity::with(['job.project', 'user']);

        if ($type === 'daily' && !empty($date)) {
            $query->whereDate('activity_date', $date);
        } elseif ($type === 'monthly' && !empty($month)) {
            [$year, $monthNum] = explode('-', $month);
            $query->whereYear('activity_date', $year)
                ->whereMonth('activity_date', $monthNum);
        } elseif ($type === 'project' && !empty($projectId)) {
            $query->whereHas('job', fn($q) => $q->where('project_id', $projectId));
        }

        if (Auth::user()->role === 'operator') {
            $query->where('user_id', Auth::id());
        }

        return $query->orderBy('activity_date', 'desc')->get();
    }
}
