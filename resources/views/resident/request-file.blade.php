@extends('layouts.appres')

@section('content')

<div class="container">
    <div class="Requestsection mb-4">
        <h3 class="mb-4">Request</h3>
        
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('make-requestfile') }}"><button class="btn btn-primary btn-lg"><i class="fas fa-file-alt"></i> Request File</button></a>
        </div>
        
        <div class="table-responsive">
            <table id='myTable' class="display table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Request Type</th>
                        <th>Tracking Code</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Created At</th>
                        <th>View</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($requests as $request)
                    <tr>
                        <td>{{ $request->users->fname }} {{ $request->users->lname }}</td>
                        <td>{{ $request->request_type }}</td>
                        <td>{{ $request->tracking_code }}</td>
                        <td>
                            @if($request->status == 'pending')
                                <span class="badge status-pending">Pending</span>
                            @elseif($request->status == 'approved')
                                <span class="badge status-approved">Approved</span>
                            @elseif($request->status == 'declined')
                                <span class="badge status-declined">Declined</span>
                            @else
                                <span class="badge status-unknown">Unknown</span>
                            @endif
                        </td>
                        <td>{{ $request->comment }}</td>
                        <td>{{ $request->created_at->format('F j, Y g:i A') }}</td>
                        <td> <a href="{{ route('request.show', $request->id) }}" class="btn btn-info m-2" title="View Request"><i class="fas fa-eye"></i></a>
                        </td>
                        <td>
                            <form action="{{ route('request.cancel', $request->id) }}" method="POST" style="display:inline;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger m-2 delete-btn" title="Cancel Request"><i class="fas fa-times"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('s/Data Table/DataTables/datatables.css') }}">
<script src="{{ asset('js/Data Table/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/Data Table/DataTables/datatables.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

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

    .table-responsive {
        overflow-x: auto;
    }

    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1.25rem;
        border-radius: 0.3rem;
    }

    .btn-info {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-danger {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn {
        margin: 0.2em 0.1em;
    }

    form {
        display: inline;
    }
</style>

@endsection

@section('title', 'Request File')
