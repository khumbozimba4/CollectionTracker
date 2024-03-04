<x-app-layout>

    <div class="page-header">
        <h3 class="page-title"> Download Reports </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('reports') }}">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Download Reports</li>
            </ol>
        </nav>
    </div>
    {{-- NEW ADMIN FORM --}}
    @if (session('feedback'))
        <div class="alert alert-success">
            {{ session('feedback') }}
        </div>
    @endif

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">



                <div class="row">
                    <div class="col-md-2 col-lg-2 text-center">
                        <div class="nav-profile-text">
                            <h4 class="card-title"></h4>

                        </div>
                    </div>
                    <div class="col-md-10 col-lg-10">
                        <div class="row">
                            <form action="{{ route('reports.search') }}" class="form-horizotal" method="post">
                                @csrf
                                <div class="col-md-4 col-lg-4">
                                    <label>Year:</label>
                                    <input type="number" style="@error('year')border:1px red solid;@enderror"
                                        min="2024" max="{{ \Carbon\Carbon::now()->year }}" name="year"
                                        class="form-control" required placeholder="Enter year">
                                    @error('year')
                                        <div style="color: red;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mt-2 col-lg-4">
                                    <label>Select Month:</label>
                                    <select id="months" style="height: 43px"
                                        style="@error('month')border:1px red solid;@enderror" class="form-control"
                                        name="month">
                                        <option value="1">January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                    @error('month')
                                        <div style="color: red;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mt-2 col-lg-4">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>

    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @isset($stackedDataJson)
                    <div class="float-right">

                        <button onclick="downloadPDF()" class="btn btn-warning">Export to PDF</button>
                        <button id="exportChart" class="btn btn-warning">Export to PNG</button>
                    </div>
                    <br>
                    <canvas id="Report"></canvas>
                @endisset
            </div>
        </div>
    </div>
    {{-- END OF NEW ADMIN FORM --}}

    @isset($stackedDataJson)
        @section('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

            <script>
                const plugin = {
                    id: 'custom_canvas_background_color',
                    beforeDraw: (chart) => {
                        const {
                            ctx
                        } = chart;
                        ctx.save();
                        ctx.globalCompositeOperation = 'destination-over';
                        ctx.fillStyle = 'white';
                        ctx.fillRect(0, 0, chart.width, chart.height);
                        ctx.restore();
                    },
                    title: {
                        display: true,
                        text: 'Account Manager Vs Amount Collected Vs Amount Remaining for the month of ' + currentMonthName +
                            " " + currentYear
                    },
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                };

                var groupedData = {!! $stackedDataJson !!};

                // Iterate through the datasets to adjust the colors based on the target achievement
                groupedData.datasets.forEach(dataset => {
                    let collectedAmount = dataset.data[0];
                    let remainingAmount = dataset.data[1];
                    let target = collectedAmount + remainingAmount;
                    let percentCollected = (collectedAmount / target) * 100;

                    console.log("collected amount: " + collectedAmount)
                    console.log("remaining amount: " + remainingAmount)
                    // Set colors based on target achievement
                    if (percentCollected >= 90) {
                        dataset.backgroundColor = ['rgba(0, 251, 30, 0.97)', 'rgba(rgba(0, 251, 30, 0.97)']; // Green
                    } else if (percentCollected >= 70) {
                        dataset.backgroundColor = ['rgba(255, 206, 86, 0.5)', 'rgba(255, 206, 86, 0.5)']; // Yellow amber
                    } else {
                        dataset.backgroundColor = ['rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)']; // Red
                    }
                });
                var currentDate = new Date();

                var monthNames = [
                    "January", "February", "March", "April", "May", "June", "July",
                    "August", "September", "October", "November", "December"
                ];

                var currentMonthIndex = currentDate.getMonth();

                var currentMonthName = monthNames[currentMonthIndex];
                var currentYear = currentDate.getFullYear();
                const config = {
                    type: 'bar',
                    data: {
                        labels: groupedData.labels,
                        datasets: [{
                                label: 'Total Collected',
                                data: groupedData.datasets.map(data => data.data[0]),
                                backgroundColor: groupedData.datasets.map(dataset => dataset.backgroundColor[0])
                            },
                            {
                                label: 'Total Remaining',
                                data: groupedData.datasets.map(data => data.data[1]),
                                backgroundColor: 'rgba(54, 162, 235, 0.5)' // Static blue color
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        interaction: {
                            intersect: false,
                        },
                        scales: {
                            x: {
                                stacked: false,
                            },
                            y: {
                                stacked: false,
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value, index, values) {
                                        return 'MWK ' + value;
                                    }
                                }
                            }
                        },
                        plugins: [plugin]

                    }
                };
                new Chart(document.getElementById('Report'), config);
            </script>
        @endsection

        <script>
            document.getElementById('exportChart').addEventListener('click', () => {
                const chartCanvas = document.getElementById('Report');
                // Convert canvas to Blob
                chartCanvas.toBlob((blob) => {
                    const url = URL.createObjectURL(blob);
                    // Create a link element and trigger the download
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'report_' + uuidv4() + '.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                });
            });

            function downloadPDF() {
                const canvas = document.getElementById('Report');
                const canvasImage = canvas.toDataURL('image/jpeg', 1.0);
                let pdf = new jspdf.jsPDF({
                    orientation: 'landscape'
                });
                pdf.setFontSize(20);
                pdf.addImage(canvasImage, 'jpeg', 15, 15, 280, 150);
                pdf.save('report_' + uuidv4() + '.pdf');
            }

            function uuidv4() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'
                    .replace(/[xy]/g, function(c) {
                        const r = Math.random() * 16 | 0,
                            v = c == 'x' ? r : (r & 0x3 | 0x8);
                        return v.toString(16);
                    });
            }
        </script>


    @endisset



</x-app-layout>
