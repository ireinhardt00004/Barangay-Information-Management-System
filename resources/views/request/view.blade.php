@extends('layouts.appres')

@section('content')

<div class="container">
    <h3 class="mb-4">Request Details</h3>
    <div class="m-3">
    @auth
    @if (auth()->user()->hasRole('resident'))
    <a href="{{ route('requestfile.index') }}" class="btn btn-secondary" ><i class="fa-solid fa-arrow-left"></i> Back to Requests</a>
    @endif 
    @endauth
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Request Type: {{ $request->request_type }}</h5>
                    <p class="card-text"><strong>Tracking Code:</strong> {{ $request->tracking_code }}</p>
                    <p class="card-text"><strong>Status:</strong> 
                        @if($request->status == 'pending')
                            <span class="badge status-pending">Pending</span>
                        @elseif($request->status == 'approved')
                            <span class="badge status-approved">Approved</span>
                        @elseif($request->status == 'declined')
                            <span class="badge status-declined">Declined</span>
                        @else
                            <span class="badge status-unknown">Unknown</span>
                        @endif
                    </p>
                    <p class="card-text"><strong>Comment:</strong> {{ $request->comment }}</p>
                    <p class="card-text"><strong>Filed Date:</strong> {{ $request->formatted_created_at }}</p>
                    <p class="card-text"><strong>Requester:</strong> {{ $request->users->fname }} {{ $request->users->lname }}</p>

                    <!-- Additional Details from JSON Data -->
                    <h5 class="mt-4">Clearance Additional Details</h5>

                    @if($request->request_type == 'Certificate of Residency')
                        <p class="card-text"><strong>Residency Full Name:</strong> {{ $request->data['residency_fullname'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Residency House Address:</strong> {{ $request->data['residency_houseAddress'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Residency Date:</strong> {{ $request->data['residency_date'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Residency Purpose:</strong> {{ $request->data['residency_purpose'] ?? 'N/A' }}</p>
                    @elseif($request->request_type == 'Barangay Clearance')
                        <p class="card-text"><strong>Barangay Full Name:</strong> {{ $request->data['barangay_fullname'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay DOB:</strong> {{ $request->data['barangay_dob'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay Age:</strong> {{ $request->data['barangay_age'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay POB:</strong> {{ $request->data['barangay_pob'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay House Address:</strong> {{ $request->data['barangay_houseAddress'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay Purpose:</strong> {{ $request->data['barangay_purpose'] ?? 'N/A' }}</p>
                    @elseif($request->request_type == 'Certificate of Indigency')
                        <p class="card-text"><strong>Indigency Full Name:</strong> {{ $request->data['indigency_fullname'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Indigency House Address:</strong> {{ $request->data['indigency_houseAddress'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Indigency Purpose:</strong> {{ $request->data['indigency_purpose'] ?? 'N/A' }}</p>
                    @elseif($request->request_type == 'First Time Job Seeker Certification')
                        <p class="card-text"><strong>Job Seeker Full Name:</strong> {{ $request->data['job_seeker_fullname'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Job Seeker House Address:</strong> {{ $request->data['job_seeker_houseAddress'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Job Seeker Purpose:</strong> {{ $request->data['job_seeker_purpose'] ?? 'N/A' }}</p>
                    @elseif($request->request_type == 'Barangay Business Clearance')
                        <p class="card-text"><strong>Business Name:</strong> {{ $request->data['business_name'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Business Address:</strong> {{ $request->data['business_address'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Owner Name:</strong> {{ $request->data['owner_name'] ?? 'N/A' }}</p>
                    @elseif($request->request_type == 'Barangay ID')
                        <p class="card-text"><strong>Barangay ID Surname:</strong> {{ $request->data['barangay_id_surname'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay ID First Name:</strong> {{ $request->data['barangay_id_firstName'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay ID Middle Name:</strong> {{ $request->data['barangay_id_middleName'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay ID Address:</strong> {{ $request->data['barangay_id_address'] ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Barangay ID Purpose:</strong> {{ $request->data['barangay_id_purpose'] ?? 'N/A' }}</p>
                    @else
                        <p class="card-text">No additional details available for this request type.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 p-1" style="overflow-y: auto; max-height: 100vh;">
            <img id="serviceLogo" src="/sys_logo/logo.png" alt="Logo" class="img-fluid rounded">
        </div>
    </div>
</div>

<style>
    .status-pending {
        background-color: #ffc107;
        color: #212529;
        padding: 0.2em 0.5em;
        border-radius: 0.25em;
    }

    .status-approved {
        background-color: #28a745;
        color: #ffffff;
        padding: 0.2em 0.5em;
        border-radius: 0.25em;
    }

    .status-declined {
        background-color: #dc3545;
        color: #ffffff;
        padding: 0.2em 0.5em;
        border-radius: 0.25em;
    }

    .status-unknown {
        background-color: #6c757d;
        color: #ffffff;
        padding: 0.2em 0.5em;
        border-radius: 0.25em;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
    }

    .card {
        margin-top: 20px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateServiceLogo('{{ $request->request_type }}');
    });

    function updateServiceLogo(serviceType) {
        const serviceLogo = document.getElementById('serviceLogo');
        const logoMap = {
            "Barangay Clearance": "/clearance/barangay_clearance_logo.jpg",
            "Certificate of Residency": "/clearance/certificate_residency_logo.jpg",
            "Certificate of Indigency": "/clearance/certificate_indigency_logo.jpg",
            "First Time Job Seeker Certification": "/clearance/first_time_job_seeker_logo.jpg",
            "Barangay Business Clearance": "/clearance/barangay_business_clearance_logo.jpg",
            "Barangay ID": "/clearance/barangay_id_logo.jpg"
        };
        serviceLogo.src = logoMap[serviceType] || "/sys_logo/logo.png";
    }
</script>

@endsection

@section('title', 'Request Details')
