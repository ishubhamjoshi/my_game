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
                <span><i class="fa fa-calendar-check"></i> Draw Results</span>
            </div>
            <div class="card-body text-center">
                <div class="row mb-3">
                    <div class="col-md-4 text-md-end text-center">
                        <label for="date" class="fw-bold">Select Date:</label>
                    </div>
                    <div class="col-md-3 text-center">
                        <input type="date" id="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-5 text-md-start text-center">
                        <button class="btn text-light" style="background: linear-gradient(135deg, #007bff, #6610f2)" id="search"><i class="fa fa-search"></i> Search</button>
                        <a href="{{route('home')}}" class="btn text-light" style="background: linear-gradient(135deg, #007bff, #6610f2);">
                        <i class="fa fa-home"></i> Home
                        </a>
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
                        url: "{{ route('draw-results.resultData') }}",
                        data: {
                            date: $('#date').val()
                        }
                    },
                    columns: [{
                            data: 'formatted_time',
                            name: 'time'
                        },
                        {
                            data: 'result',
                            name: 'result'
                        }
                    ],
                    order: [
                        [0, 'asc']
                    ]
                });
            }
            loadTable($('#date').val());

            $('#search').click(function() {
                loadTable($('#date').val());
            });
        });
    </script>
</body>

</html>