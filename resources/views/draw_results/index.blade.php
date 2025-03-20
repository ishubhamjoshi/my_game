<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Draw Results</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-warning btn-custom" id="changePasswordBtn">
                    <i class="fa fa-key"></i> Change Password
                </button>
                <span><i class="fa fa-calendar-check"></i> Draw Results</span>
                <button class="btn btn-add btn-custom" id="addEntry"><i class="fa fa-plus"></i> Add</button>
            </div>
            <div class="card-body text-center">
                <div class="row mb-3">
                    <div class="col-md-4 offset-md-2 text-md-end text-center">
                        <label for="date" class="fw-bold">Select Date:</label>
                    </div>
                    <div class="col-md-3 text-center">
                        <input type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-2 text-md-start text-center">
                        <button class="btn btn-search btn-custom" id="search"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="drawTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Draw Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="entryTime" class="form-label">Time</label>
                        <select class="form-control" id="entryTime"></select>
                    </div>
                    <div class="mb-3">
                        <label for="entryResult" class="form-label">Result</label>
                        <input type="text" class="form-control" id="entryResult" maxlength="2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEntry">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="updatePassword">Update Password</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            function loadTable(date) {
                $('#drawTable').DataTable({
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    searching: false,
                    paging: false,
                    info: false,
                    ajax: {
                        url: "{{ route('draw-results.data') }}",
                        data: { date: $('#date').val() }
                    },
                    columns: [
                        { data: 'formatted_time', name: 'time' },
                        { data: 'result', name: 'result' }
                    ],
                    order: [[0, 'asc']]
                });
            }
            loadTable($('#date').val());

            $('#search').click(function() {
                loadTable($('#date').val());
            });

            function generateTimeDropdown() {
                var timeDropdown = $("#entryTime");
                timeDropdown.empty();

                $.ajax({
                    url: "{{ route('draw-results.getTimes') }}",
                    method: "GET",
                    data: { date: $('#date').val() },
                    success: function(response) {
                        var existingTimes = response.times;

                        for (var i = 540; i <= 1290; i += 15) {
                            var hours = Math.floor(i / 60);
                            var minutes = i % 60;
                            var ampm = hours >= 12 ? "PM" : "AM";
                            var displayHours = hours > 12 ? hours - 12 : hours;
                            var formattedTime = `${displayHours}:${minutes < 10 ? '0' + minutes : minutes} ${ampm}`;

                            if (!existingTimes.includes(formattedTime)) {
                                timeDropdown.append(new Option(formattedTime, formattedTime));
                            }
                        }
                    }
                });
            }

            $("#addEntry").click(function() {
                generateTimeDropdown();
                $("#addModal").modal("show");
            });

            $('#saveEntry').click(function() {
                $.post("{{ route('draw-results.store') }}", {
                    _token: "{{ csrf_token() }}",
                    date: $('#date').val(),
                    time: $('#entryTime').val(),
                    result: $('#entryResult').val()
                }, function() {
                    $('#addModal').modal('hide');
                    $('#entryResult').val('')
                    loadTable();
                }).fail(function(response) {
                    alert(response.responseJSON.message);
                });
            });

            // Open Change Password Modal
            $("#changePasswordBtn").click(function() {
                $("#changePasswordModal").modal("show");
            });

            // Handle Change Password Request
            $("#updatePassword").click(function() {
                var currentPassword = $("#currentPassword").val();
                var newPassword = $("#newPassword").val();
                var confirmPassword = $("#confirmPassword").val();

                if (newPassword !== confirmPassword) {
                    alert("New passwords do not match!");
                    return;
                }

                $.ajax({
                    url: "{{ route('change-password') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        current_password: currentPassword,
                        new_password: newPassword,
                        confirm_password: confirmPassword
                    },
                    success: function(response) {
                        alert(response.message);
                        $("#changePasswordModal").modal("hide");
                        $("#currentPassword").val("");
                        $("#newPassword").val("");
                        $("#confirmPassword").val("");
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON.message);
                    }
                });
            });
        });

    </script>
</body>
</html>
