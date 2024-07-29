@extends('layouts.appres')

@section('content')

<div class="container-fluid">
    <button class="btn btn-primary" onclick="history.back()"><i class="fa-solid fa-arrow-left"></i> Go Back</button>
    <div class="m-4">
        <h2>Request Form</h2>
    </div>
    <div class="container d-flex main-c justify-content-center mb-3">
        <div class="row p-1 rounded d-flex justify-content-center w-100" style="max-width: 900px;">
            <div class="col-md-4 p-1" style="overflow-y: auto; max-height: 100vh;">
                <img id="serviceLogo" src="/sys_logo/logo.png" alt="Logo" class="img-fluid rounded">
            </div>
            <div class="col p-0" style="background-color: rgba(255,255,255,0.8); border-radius:10px;">
                <form id="requestForm" method="POST" action="{{ route('requestfilezz.submit') }}">
                    @csrf
                    <div>
                        <div class="input-group mb-2">
                            <span class="input-group-text">Request Type:</span>
                            <select id="requestType" class="text-primary form-select" name="request_type" required>
                                <option selected value="" disabled>Select</option>
                                <option value="Barangay Clearance">Barangay Clearance</option>
                                <option value="Certificate of Residency">Certificate of Residency</option>
                                <option value="Certificate of Indigency">Certificate of Indigency</option>
                                <option value="First Time Job Seeker Certification">First Time Job Seeker Certification</option>
                                <option value="Barangay Business Clearance">Barangay Business Clearance</option>
                                <option value="Barangay ID">Barangay ID</option>
                            </select>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="form-group">
                                    <label>Tracking Code</label>
                                    <input  class="form-control" name="tracking_code" id="trackingCode" readonly>
                                </div>
                                <div id="service_view">
                                    <!-- Start of Service View Sections -->
                                    <div id="Barangay Clearance" class="service-section" style="display: none;">
                                        <div class="mt-3">
                                            <label>Full Name</label>
                                            <input class="form-control" type="text" name="barangay_fullname" placeholder="Enter your name" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 mt-3 mb-3">
                                                <label>Date of Birth</label>
                                                <input class="form-control" type="date" name="barangay_dob" required>
                                            </div>
                                            <div class="col-6 mt-3 mb-3">
                                                <label>Age</label>
                                                <input class="form-control" type="number" name="barangay_age" placeholder="Enter your age" required>
                                            </div>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>Place of Birth</label>
                                            <input class="form-control" type="text" name="barangay_pob" placeholder="Enter your place of birth" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>House Address</label>
                                            <input class="form-control" type="text" name="barangay_houseAddress" placeholder="House No, or Block, Lot, Phase, Street, Subdivision" required>
                                        </div>
                                        <div class="mt-2">
                                            <label>Purpose of getting barangay clearance</label>
                                            <textarea class="form-control" name="barangay_purpose" placeholder="Enter Purpose"></textarea>
                                        </div>
                                    </div>
                                    <div id="Certificate of Residency" class="service-section" style="display: none;">
                                        <div class="mt-3">
                                            <label>Full Name</label>
                                            <input class="form-control" type="text" name="residency_fullname" placeholder="Enter your name" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>House Address</label>
                                            <input class="form-control" type="text" name="residency_houseAddress" placeholder="House No, or Block, Lot, Phase, Street, Subdivision" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>Date of Residency</label>
                                            <input class="form-control" type="date" name="residency_date" required>
                                        </div>
                                        <div class="mt-2">
                                            <label>Purpose of getting certificate residency</label>
                                            <textarea class="form-control" name="residency_purpose" placeholder="Enter Purpose"></textarea>
                                        </div>
                                    </div>
                                    <div id="Certificate of Indigency" class="service-section" style="display: none;">
                                        <div class="mt-3">
                                            <label>Full Name</label>
                                            <input class="form-control" type="text" name="indigency_fullname" placeholder="Enter your name" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>House Address</label>
                                            <input class="form-control" type="text" name="indigency_houseAddress" placeholder="House No, or Block, Lot, Phase, Street, Subdivision" required>
                                        </div>
                                        <div class="mt-2">
                                            <label>Purpose of getting certificate indigency</label>
                                            <textarea class="form-control" name="indigency_purpose" placeholder="Enter Purpose"></textarea>
                                        </div>
                                    </div>
                                    <div id="First Time Job Seeker Certification" class="service-section" style="display: none;">
                                        <div class="mt-3">
                                            <label>Full Name</label>
                                            <input class="form-control" type="text" name="job_seeker_fullname" placeholder="Enter your name" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>House Address</label>
                                            <input class="form-control" type="text" name="job_seeker_houseAddress" placeholder="House No, or Block, Lot, Phase, Street, Subdivision" required>
                                        </div>
                                        <div class="mt-2">
                                            <label>Purpose of getting Oath of Undertaking</label>
                                            <textarea class="form-control" name="job_seeker_purpose" placeholder="Enter Purpose"></textarea>
                                        </div>
                                    </div>
                                    <div id="Barangay Business Clearance" class="service-section" style="display: none;">
                                        <div class="mt-3">
                                            <label>Business Name</label>
                                            <input class="form-control" type="text" name="business_name" placeholder="Enter your business name" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>Business Address</label>
                                            <input class="form-control" type="text" name="business_address" placeholder="Bldg No, or Block, Lot, Phase, Street, Subdivision" required>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>Name of The Owner</label>
                                            <input class="form-control" type="text" name="owner_name" placeholder="Owner's Name" required>
                                        </div>
                                    </div>
                                    <div id="Barangay ID" class="service-section" style="display: none;">
                                        <div class="row">
                                            <div class="col-3 mt-3">
                                                <label>Surname</label>
                                                <input class="form-control" type="text" name="barangay_id_surname" placeholder="" required>
                                            </div>
                                            <div class="col-4 mt-3">
                                                <label>First Name</label>
                                                <input class="form-control" type="text" name="barangay_id_firstName" placeholder="" required>
                                            </div>
                                            <div class="col-3 mt-3">
                                                <label>Middle Name</label>
                                                <input class="form-control" type="text" name="barangay_id_middleName" placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label>Civil Status</label>
                                            <select class="form-select" name="barangay_id_civilStatus" required>
                                                <option value="" disabled selected>Select</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Widowed">Widowed</option>
                                                <option value="Separated">Separated</option>
                                            </select>
                                        </div>
                                        <div class="mt-3 mb-3">
                                            <label>Complete Address</label>
                                            <input class="form-control" type="text" name="barangay_id_address" placeholder="House No, or Block, Lot, Phase, Street, Subdivision" required>
                                        </div>
                                        <div class="mt-2">
                                            <label>Purpose</label>
                                            <textarea class="form-control" name="barangay_id_purpose" placeholder="Enter Purpose"></textarea>
                                        </div>
                                    </div>
                                    <!-- End of Service View Sections -->
                                </div>
                                <div class="container mt-3 mb-3">
                                    <button type="submit" class="btn btn-success w-100" id="submitBtn">Submit Request</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const requestTypeSelect = document.getElementById('requestType');
    const serviceSections = document.querySelectorAll('.service-section');
    const trackingCodeInput = document.getElementById('trackingCode');

    function generateTrackingCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let segments = [];
        for (let i = 0; i < 6; i++) {
            let segment = '';
            for (let j = 0; j < 5; j++) {
                segment += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            segments.push(segment);
        }
        return segments.join('-');
    }

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

    requestTypeSelect.addEventListener('change', function () {
        const selectedServiceSection = document.getElementById(requestTypeSelect.value);
        serviceSections.forEach(section => {
            section.style.display = section.id === requestTypeSelect.value ? 'block' : 'none';
        });
        updateServiceLogo(requestTypeSelect.value);
        trackingCodeInput.value = generateTrackingCode();
    });

    // Initialize tracking code
    trackingCodeInput.value = generateTrackingCode();

    // Handle form submission with SweetAlert
    document.getElementById('submitBtn').addEventListener('click', function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to submit this request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, submit it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('requestForm').submit(); // Submit the form if confirmed
            }
        });
    });
});
</script>
@endsection
@section('title','Make Request')