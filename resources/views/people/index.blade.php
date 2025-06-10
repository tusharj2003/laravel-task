<!DOCTYPE html>
<html>

<head>
    <title>People List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Dashboard</a>

            <div class="d-flex ms-auto">
                <button class="btn btn-success me-2" id="addBtn">Add Person</button>

                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        🔍 Filter
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
                        <div class="mb-2">
                            <label for="roleFilter" class="form-label mb-1">Role</label>
                            <select id="roleFilter" class="form-select">
                                <option value="">All Roles</option>
                                <option value="Admin">Admin</option>
                                <option value="Designer">Designer</option>
                            </select>
                        </div>
                        <div>
                            <label for="statusFilter" class="form-label mb-1">Status</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="text-end mt-2">
                            <button class="btn btn-sm btn-outline-secondary d-none" id="clearFiltersBtn">Clear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-3">
        <div id="successAlert" class="alert alert-success d-none" role="alert"></div>
    </div>

    <div class="container mt-4">

        <table class="table table-bordered" id="peopleTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Designation</th>
                    <th>Photo</th>
                    <th>Activity</th>
                    <th>Status</th>
                    <th>DOB</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    @include('people.modal')

  
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this person?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let table = $('#peopleTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            ajax: {
                url: "{{ route('people.list') }}",
                data: function(d) {
                    d.role = $('#roleFilter').val();
                    d.status = $('#statusFilter').val();
                }
            },
            columns: [{
                    data: 'id'
                }, {
                    data: 'name'
                }, {
                    data: 'email'
                },
                {
                    data: 'role'
                }, {
                    data: 'designation'
                },
                {
                    data: 'photo',
                    render: function(data) {
                        return data ? `<img src="/storage/photos/${data}" width="50" height="50" />` : '';
                    }
                },
                {
                    data: 'status'
                },
                {
                    data: 'marital_status'
                }, {
                    data: 'dob'
                }, {
                    data: 'action',
                    orderable: false
                }
            ]
        });


        $('#roleFilter, #statusFilter').change(function() {
            table.ajax.reload();
        });

        $('#addBtn').click(() => {
            $('#personForm')[0].reset();
            $('#personModalLabel').text('Add Person');
            $('#person_id').val('');
            $('#personModal').modal('show');
        });

      

        $(document).on('submit', '#personForm', function(e) {
            e.preventDefault();
            e.stopPropagation();

            let form = $('#personForm')[0];
            let formData = new FormData(form);
            let id = $('#person_id').val();
            let url = id ? `/people/${id}` : "{{ route('people.store') }}";
            let method = id ? 'POST' : 'POST';

            if (id) {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        $('#personModal').modal('hide');
                        $('#personForm')[0].reset();
                        $('#person_id').val('');
                      
                        $('#roleFilter').val('');
                        $('#statusFilter').val('');
                        $('#peopleTable_filter input').val('');
                        table.search('').draw();
                        table.ajax.reload();
                        
                        let message = id ? 'Person updated successfully!' : 'Person added successfully!';
                        $('#successAlert').removeClass('d-none').text(message);
                        setTimeout(() => $('#successAlert').addClass('d-none').text(''), 3000);
                    },
                
                error: function(err) {
                    
                    $('#photo').removeClass('is-invalid');
                    $('#photoError').text('');

                  
                    if (err.responseJSON && err.responseJSON.errors) {
                        let errors = err.responseJSON.errors;

                        if (errors.photo) {
                            $('#photo').addClass('is-invalid');
                            $('#photoError').text(errors.photo[0]);
                        }
                    }
                }

            });
        });


        $(document).on('click', '.editBtn', function() {
            let id = $(this).data('id');
            $.get(`/people/${id}`, (data) => {
                $('#personModalLabel').text('Edit Person');
                $('#person_id').val(data.id);
                $('#name').val(data.name);
                $('#email').val(data.email);
                $('#role').val(data.role);
                $('#designation').val(data.designation);
                $('#status').val(data.status);
                $('input[name="marital_status"][value="' + data.marital_status + '"]').prop('checked',
                    true);
                $('#dob').val(data.dob);
                $('#personModal').modal('show');
            });
        });

        
        let deleteId = null;
        $(document).on('click', '.deleteBtn', function() {
            deleteId = $(this).data('id');
            $('#deleteConfirmModal').modal('show');
        });

        $('#confirmDeleteBtn').click(function() {
            if (deleteId) {
                $.ajax({
                    url: `/people/${deleteId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#deleteConfirmModal').modal('hide');
                        table.ajax.reload();
                    }
                });
            }
        });


        function toggleClearButton() {
            const role = $('#roleFilter').val();
            const status = $('#statusFilter').val();

            if (role || status) {
                $('#clearFiltersBtn').removeClass('d-none');
            } else {
                $('#clearFiltersBtn').addClass('d-none');
            }
        }


        $('#roleFilter, #statusFilter').change(function() {
            toggleClearButton();
            table.ajax.reload();
        });


        $('#clearFiltersBtn').click(function() {
            $('#roleFilter').val('');
            $('#statusFilter').val('');
            toggleClearButton();
            table.ajax.reload();
        });
    </script>
</body>

</html>
