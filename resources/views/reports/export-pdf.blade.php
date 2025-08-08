<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background-color: #eee; }
    </style>
</head>
<body>
    <h2>Laporan {{ ucfirst($type) }}</h2>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Operator</th>
                <th>Project</th>
                <th>Job</th>
                <th>Deskripsi Aktivitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $act)
            <tr>
                <td>{{ $act->activity_date }}</td>
                <td>{{ $act->user->name }}</td>
                <td>{{ $act->job->project->name }}</td>
                <td>{{ $act->job->title }}</td>
                <td>{{ $act->activity_note }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
