<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payslip - {{ $payroll->user->name }} - {{ $payroll->month_year->format('F Y') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 24px; font-weight: bold; }
        .payslip-title { font-size: 20px; }
        .details { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #000; padding: 8px; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .section { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">OfficeBuddy</div>
        <div class="payslip-title">PAYSLIP</div>
        <div>For the month of {{ $payroll->month_year->format('F Y') }}</div>
    </div>

    <div class="details">
        <table width="100%">
            <tr>
                <td width="50%">
                    <strong>Employee Details:</strong><br>
                    Name: {{ $payroll->user->name }}<br>
                    Employee ID: {{ $payroll->user->employee_id }}<br>
                    Joining Date: {{ $payroll->user->joining_date->format('d M Y') }}
                </td>
                <td width="50%">
                    <strong>Payroll Details:</strong><br>
                    Payment Date: {{ $payroll->disbursement_date ? $payroll->disbursement_date->format('d M Y') : 'N/A' }}<br>
                    Status: {{ ucfirst($payroll->status) }}<br>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>Earnings</th>
                    <th class="text-right">Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="text-right">{{ number_format($payroll->basic_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>House Rent</td> <!-- Changed from House Allowance -->
                    <td class="text-right">{{ number_format($payroll->house_rent, 2) }}</td>
                </tr>
                <tr>
                    <td>Transport Allowance</td>
                    <td class="text-right">{{ number_format($payroll->transport_allowance, 2) }}</td>
                </tr>
                <tr>
                    <td>Medical Allowance</td>
                    <td class="text-right">{{ number_format($payroll->medical_allowance, 2) }}</td>
                </tr>
                <tr>
                    <td>Other Allowance</td>
                    <td class="text-right">{{ number_format($payroll->other_allowance, 2) }}</td>
                </tr>
                <tr>
                    <td>Overtime Earnings</td>
                    <td class="text-right">{{ number_format($payroll->overtime_earnings, 2) }}</td>
                </tr>
                <tr>
                    <td>Festival Bonus</td> <!-- Changed from Bonus -->
                    <td class="text-right">{{ number_format($payroll->festival_bonus, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Earnings</strong></td>
                    <td class="text-right"><strong>{{ number_format($payroll->total_earnings, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>Deductions</th>
                    <th class="text-right">Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tax Deduction</td>
                    <td class="text-right">{{ number_format($payroll->tax_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Provident Fund</td>
                    <td class="text-right">{{ number_format($payroll->provident_fund, 2) }}</td>
                </tr>
                <tr>
                    <td>Other Deduction</td>
                    <td class="text-right">{{ number_format($payroll->other_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Late Deduction</td>
                    <td class="text-right">{{ number_format($payroll->late_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td>Early Leave Deduction</td>
                    <td class="text-right">{{ number_format($payroll->early_leave_deduction, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Deductions</strong></td>
                    <td class="text-right"><strong>{{ number_format($payroll->total_deductions, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <table class="table">
            <tr>
                <td width="80%"><strong>Net Salary</strong></td>
                <td width="20%" class="text-right"><strong>৳ {{ number_format($payroll->net_salary, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>This is a computer generated payslip and does not require a signature.</p>
    </div>
</body>
</html>