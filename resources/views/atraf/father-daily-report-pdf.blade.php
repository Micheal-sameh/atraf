<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>التقرير اليومي - {{ $date }}</title>
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
    </style>
</head>
<body>
    <h1>التقرير اليومي</h1>
    <h2>الأب: {{ $father->name }}</h2>
    <p>التاريخ: {{ $date }}</p>
    <p>إجمالي الاعترافات المكتملة: {{ $fatherAtraf->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>المستخدم</th>
                <th>من</th>
                <th>إلى</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fatherAtraf as $etraf)
            <tr>
                <td>{{ $etraf->user->name }}</td>
                <td>{{ \Carbon\Carbon::parse($etraf->from)->format('H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($etraf->to)->format('H:i') }}</td>
                <td>{{ $etraf->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
