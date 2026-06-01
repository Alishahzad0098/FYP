@extends('layout.app')

@section('title', 'Contact Messages')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Contact Messages</h2>
            <p class="text-muted">All messages submitted through the contact form.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table id="messages-table" class="table table-hover table-bordered align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Website</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <strong>Name:</strong> <span id="messageModalName"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> <span id="messageModalEmail"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Subject:</strong> <span id="messageModalSubject"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Website:</strong> <span id="messageModalWebsite"></span>
                    </div>
                    <div class="mb-3">
                        <strong>Message:</strong>
                        <div class="border rounded p-3 bg-light" id="messageModalBody" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            $(function () {
                const table = $('#messages-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route("datatable.messages") }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'subject', name: 'subject' },
                        { data: 'website', name: 'website', orderable: false, searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ]
                });

                $('#messages-table').on('click', '.view-message-btn', function () {
                    const button = $(this);
                    const name = button.data('name');
                    const email = button.data('email');
                    const subject = button.data('subject');
                    const website = button.data('website');
                    const message = button.data('message');

                    $('#messageModalName').text(name || '—');
                    $('#messageModalEmail').text(email || '—');
                    $('#messageModalSubject').text(subject || '—');
                    $('#messageModalWebsite').html(website ? '<a href="' + website + '" target="_blank">' + website + '</a>' : '—');
                    $('#messageModalBody').text(message || '—');
                });
            });
        </script>
    @endpush
@endsection