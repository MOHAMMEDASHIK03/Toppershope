<!DOCTYPE html>
<html>
<head>
    <title>Employees List</title>
    <style>
        body { font-family: sans-serif; position: relative; }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(200, 200, 200, 0.2);
            z-index: -1;
            white-space: nowrap;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { text-align: center; color: #333; }
    </style>
</head>
<body>
    <div class="watermark">TOPPERS HOPE</div>
    <h2>Employees Directory</h2>
    <p>Generated on {{ date('d M, Y H:i A') }}</p>

    <table>
        <thead>
            <tr>
                <th>EMP ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Status</th>
                <th>Panel Access</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $emp)
                @php
                    $panel = 'None';
                    if ($emp->account) {
                        $panel = class_basename($emp->account_type);
                        if ($panel === 'User') {
                            $panel = isset($emp->account->role) && $emp->account->role === 'faculty_head' ? 'Faculty Head' : 'Faculty';
                        } elseif ($panel === 'HrUser') {
                            $panel = 'HR Panel';
                        } elseif ($panel === 'AdsUser') {
                            $panel = 'Ads Panel';
                        } elseif ($panel === 'AdmissionUser') {
                            $panel = 'Admission CRM';
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $emp->employee_id }}</td>
                    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                    <td>{{ $emp->email }}</td>
                    <td>{{ $emp->department->name ?? '-' }}</td>
                    <td>{{ $emp->designation->name ?? '-' }}</td>
                    <td>{{ $emp->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>{{ $panel }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
