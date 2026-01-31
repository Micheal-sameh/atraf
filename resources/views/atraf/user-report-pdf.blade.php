<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير المستخدم - {{ $user->name }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
        }
        h1, h2, h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: right;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>تقرير اعترافات المستخدم</h1>
    <h2>{{ $user->name }}</h2>
    <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>

    <div class="summary">
        <h3>ملخص</h3>
        <p><strong>إجمالي الاعترافات:</strong> {{ $atraf->count() }}</p>
        <p><strong>الاعترافات المكتملة:</strong> {{ $atraf->where('status', 'completed')->count() }}</p>
        <p><strong>الاعترافات المعلقة:</strong> {{ $atraf->where('status', 'pending')->count() }}</p>
        <p><strong>في الانتظار:</strong> {{ $atraf->where('status', 'waiting')->count() }}</p>
    </div>

    <h3>تفاصيل الاعترافات</h3>
    <table>
        <thead>
            <tr>
                <th>التاريخ</th>
                <th>الأب</th>
                <th>من</th>
                <th>إلى</th>
                <th>الحالة</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($atraf as $etraf)
            <tr>
                <td>{{ $etraf->date->format('Y-m-d') }}</td>
                <td>{{ $etraf->father->name }}</td>
                <td>{{ \Carbon\Carbon::parse($etraf->from)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($etraf->to)->format('H:i') }}</td>
                <td>
                    @if($etraf->status == 'pending')
                        معلق
                    @elseif($etraf->status == 'waiting')
                        في الانتظار
                    @else
                        مكتمل
                    @endif
                </td>
                <td>{{ $etraf->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
