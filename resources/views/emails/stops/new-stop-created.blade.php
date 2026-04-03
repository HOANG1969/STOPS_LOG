<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thong bao the STOP moi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <p>Kinh gui Can bo An toan,</p>

    <p>He thong vua ghi nhan mot the STOP moi can theo doi.</p>

    <table cellpadding="6" cellspacing="0" border="1" style="border-collapse: collapse; border-color: #d1d5db; margin: 12px 0;">
        <tr>
            <td><strong>Ma STOP</strong></td>
            <td>#{{ $stop->id }}</td>
        </tr>
        <tr>
            <td><strong>Nguoi quan sat</strong></td>
            <td>{{ $stop->observer_name }}</td>
        </tr>
        <tr>
            <td><strong>Ca/Kip</strong></td>
            <td>{{ $stop->observer_phone }}</td>
        </tr>
        <tr>
            <td><strong>Ngay quan sat</strong></td>
            <td>{{ $stop->observation_date ? $stop->observation_date->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Gio quan sat</strong></td>
            <td>{{ $stop->observation_time ? $stop->observation_time->format('H:i') : 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Vi tri</strong></td>
            <td>{{ $stop->location }}</td>
        </tr>
        <tr>
            <td><strong>Loai van de</strong></td>
            <td>{{ $stop->getCategoryLabel() }}</td>
        </tr>
        <tr>
            <td><strong>Noi dung ghi nhan</strong></td>
            <td>{{ $stop->issue_description }}</td>
        </tr>
        <tr>
            <td><strong>Hanh dong khac phuc</strong></td>
            <td>{{ $stop->corrective_action }}</td>
        </tr>
        <tr>
            <td><strong>Nguoi tao</strong></td>
            <td>{{ optional($stop->user)->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Trang thai</strong></td>
            <td>{{ $stop->getStatusLabel() }}</td>
        </tr>
    </table>

    <p>
        Xem chi tiet: <a href="{{ route('stops.show', $stop) }}">STOP #{{ $stop->id }}</a>
    </p>

    <p>Tran trong,<br>KDNVPP System</p>
</body>
</html>
