@extends('layouts.app')

@section('content')
@include('layouts.sidebar')
<div id="layoutSidenav_content" style="background-color: rgb(240,236,236);">
    <main>
        <div class="container-fluid px-4">
            <div class="d">
                <h1 class="mt-4">{{ ucwords(auth()->user()->roles) }} | <span style="font-size:22px;">My Activity Logs</span></h1>
            </div>
            <hr style="border:1px solid black;">
            <div class="container">
                <p>All your activity logs recorded here.</p>
                
                <!-- Button to delete all logs -->
                <div class="shadow search-btn d-flex p-2 rounded" style="background-color:rgba(255, 255, 255, 0.4);">
                    <input autofocus onkeyup="search_btn();" onkeydown="if (event.keyCode === 13) search_btn();" type="text" id="search-input" autocomplete="off" placeholder="Search Name...">
                    <br>
                    <button id="delete-all-logs-btn" style="margin:5px;" title="Clear all my Activity" class="btn btn-danger mb-3"><i class="fas fa-trash"></i></button>
                    <button class="btn btn-info mb-3" style="margin:5px;" onclick="exportTableToPDF()" title="Download Table as PDF">
                        <i class="fas fa-file-pdf"></i> 
                    </button>
                </div>
                <div id="table-container" style="font-size:16px;" class="shadow rounded"></div>
            </div>
        </div>
    </main>
</div>

<style>
    .search-btn input {
        outline: none;
        border-radius: 5px;
        border: 1px solid rgba(0, 0, 0, 0.4);
    }
    .cstm-hover:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
    }
    .act-btn {
        font-size: 18px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/luxon@2.3.0/build/global/luxon.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.6.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.16/jspdf.plugin.autotable.min.js"></script>

<script>
    var tableData = @json($logs->items());

    var table = new Tabulator("#table-container", {
        data: tableData,
        placeholder: 'Empty Data',
        layout: "fitDataStretch",
        pagination: "local",
        paginationSize: 10,
        height: "100%",
        rowFormatter: function (row) {
            row.getElement().style.height = "60px";
        },
        columns: [
            { title: "Name", field: "name" },
            { title: "Activity", field: "activity" },
            { title: "Timestamp", field: "timestamp", sorter: "date", formatter: function(cell) {
                return cell.getData().formatted_timestamp + ' (' + cell.getValue() + ')';
            }},
        ],
        initialSort: [
            { column: "timestamp", dir: "desc" }
        ],
    });

    function search_btn() {
        var searchValue = document.getElementById("search-input").value;
        table.setFilter("name", "like", searchValue);
    }

    // SweetAlert2 delete confirmation
    document.getElementById('delete-all-logs-btn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action will delete all logs permanently.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form and submit it to the route
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('delete-my-logxz') }}";

                // Add CSRF token
                var csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                form.appendChild(csrfToken);

                // Append form to body and submit it
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Handle success and error messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}'
        });
    @endif
</script>

<script>
   function exportTableToPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    
    // Create a temporary HTML table
    const tableHtml = `
        <table id="temp-table" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Activity</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                ${tableData.map(row => `
                    <tr>
                        <td>${row.name}</td>
                        <td>${row.activity}</td>
                        <td>${row.timestamp}</td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    
    // Add temporary table to the body for pdf export
    const tempContainer = document.createElement('div');
    tempContainer.innerHTML = tableHtml;
    document.body.appendChild(tempContainer);
    
    // Generate PDF
    doc.text("My Activity Logs", 14, 16);
    doc.autoTable({ html: '#temp-table', startY: 20 });
    doc.save("myActivityLogs.pdf");
    
    // Remove the temporary table after export
    document.body.removeChild(tempContainer);
}

</script>
<!-- Stylesheets and Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
<script src="{{ asset('assets/js/sb-script.js') }}"></script>
<link href="{{ asset('assets/css/sb-style.css') }}" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{{ asset('assets/style.css') }}">
<script src="{{ asset('assets/js/a-dash-script.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('title','My Activity Logs')
