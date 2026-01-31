<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>تقرير العائلة - {{ $familyCode }}</title>
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
    <h1>تقرير العائلة</h1>
    <h2>رمز العائلة: {{ $familyCode }}</h2>
    <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>

    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>رمز العضوية</th>
                <th>البريد الإلكتروني</th>
                <th>إجمالي الاعترافات</th>
                <th>الاعترافات المكتملة</th>
                <th>تاريخ آخر اعتراف</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membersData as $memberData)
            <tr>
                <td>{{ $memberData['user']->name }}</td>
                <td>{{ $memberData['user']->membership_code }}</td>
                <td>{{ $memberData['user']->email }}</td>
                <td>{{ $memberData['total_atraf'] }}</td>
                <td>{{ $memberData['completed_atraf'] }}</td>
                <td>{{ $memberData['last_etraf_date'] ? \Carbon\Carbon::parse($memberData['last_etraf_date'])->format('Y-m-d') : 'لا يوجد' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
