@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h4 font-weight-bold text-dark mb-1">My Payslips</h2>
                    <p class="text-muted mb-0">View and download your salary payslips</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fas fa-file-invoice me-2"></i>Total: {{ $payslips->total() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payslips Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow border-0 rounded-3">
                <div class="card-header bg-gradient-primary text-white py-3 rounded-top-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2"></i>Payslip History
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($payslips->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Pay Period</th>
                                        <th>Basic Salary</th>
                                        <th>Allowances</th>
                                        <th>Deductions</th>
                                        <th>Net Salary</th>
                                        <th>Status</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payslips as $payslip)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $payslip->pay_period }}</td>
                                        <td>৳ {{ number_format($payslip->basic_salary, 2) }}</td>
                                        <td>৳ {{ number_format($payslip->house_rent_allowance + $payslip->medical_allowance + $payslip->transport_allowance + $payslip->other_allowance, 2) }}</td>
                                        <td>৳ {{ number_format($payslip->tax_deduction + $payslip->provident_fund_deduction + $payslip->other_deduction, 2) }}</td>
                                        <td class="fw-bold text-success">৳ {{ number_format($payslip->net_salary, 2) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payslip->status == 'disbursed' ? 'success' : 'warning' }}">
                                                {{ ucfirst($payslip->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('employee.payslip.download', $payslip->id) }}" 
                                               class="btn btn-sm btn-success" 
                                               title="Download Payslip">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted">
                                    Showing {{ $payslips->firstItem() }} to {{ $payslips->lastItem() }} of {{ $payslips->total() }} entries
                                </div>
                                {{ $payslips->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="icon-circle bg-secondary mx-auto mb-4">
                                <i class="fas fa-file-invoice fa-2x text-white"></i>
                            </div>
                            <h5 class="text-muted mb-3">No Payslips Available</h5>
                            <p class="text-muted">Your payslips will appear here once they are processed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.rounded-top-3 {
    border-top-left-radius: 1rem !important;
    border-top-right-radius: 1rem !important;
}

.rounded-3 {
    border-radius: 1rem !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}
</style>
@endsection