<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         
            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                         <div class="col-xl-3 col-xxl-3 col-sm-6">
                              <div class="widget-stat card">
                                        <div class="card-body">
                                            <h4 class="card-title">Total Target</h4>
                                            <h3>{{$data["target"]}}</h3>
                                            <div class="progress mb-2">
                                                <div class="progress-bar progress-animated bg-primary" style="width: {{$data["target"]}}%"></div>
                                            </div>
                                            <small>{{$data["target"]}}% Increase</small>
                                        </div>
                                    </div>
                                </div>
                                @php
                                //dd($data["target"]);
                                if($data["target"] != 0){

                                    $percent = ($data["total_collected"]/$data["target"])*100;
                                }else{
                                    $percent = 0;

                                }
                                    //dd($percent);
                                @endphp
                                <div class="col-xl-3 col-xxl-3 col-sm-6">
                                    <div class="widget-stat card">
                                        <div class="card-body">
                                            <h4 class="card-title">Total Collected</h4>
                                            <h3>{{$data["total_collected"]}}</h3>
                                            <div class="progress mb-2">
                                                <div class="progress-bar progress-animated @if ($percent >=90)
                                                    bg-success
                                                @else
                                                bg-warning
                                                @endif" style="width: {{$data["total_collected"]}}%"></div>
                                            </div>
                                            <small>{{$data["total_collected"]}}% Increase</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-xxl-3 col-sm-6">
                                    <div class="widget-stat card">
                                        <div class="card-body">
                                            <h4 class="card-title">Total Remaining</h4>
                                            <h3>{{$data["total_remaining"]}}</h3>
                                            <div class="progress mb-2">
                                                <div class="progress-bar progress-animated bg-red" style="width: {{$data["total_remaining"]}}%"></div>
                                            </div>
                                            <small>{{$data["total_remaining"]}}% Increase</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-xxl-3 col-sm-6">
                                    <div class="widget-stat card">
                                        <div class="card-body">
                                            <h4 class="card-title">Total Customers</h4>
                                            <h3>{{$data["customers"]}}</h3>
                                            <div class="progress mb-2">
                                                <div class="progress-bar progress-animated bg-red" style="width: {{$data["customers"]}}%"></div>
                                            </div>
                                            <small>{{$data["customers"]}}% Increase</small>
                                        </div>
                                    </div>
                                </div>
                                

                                @if (auth()->user()->hasRole("admin"))
                                    
                                  <div class="col-md-12 col-lg-12 col-sm-12">
                                      <div class="mt-4">
                                          <canvas id="stacked"></canvas>
                                      </div>
                                  </div>
                                @endif

                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="mt-4">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>

                                

                               
                    </div>
                    
                 

                </div>
               
            </div>
           

           
        </div>
    </div>
    
@section('scripts')
<script>
    const ctx = document.getElementById('myChart');
  
    const data = {
    labels: [
      'Target',
      'Collected',
      'Remaining'
    ],
    datasets: [{
      label: 'Collected vs Remaining vs Target',
      data: [{{$data["target"]}}, {{$data["total_collected"]}}, {{$data["total_remaining"]}}],
      backgroundColor: [
        'rgb(255, 205, 86)',
        'rgb(54, 162, 235)',
        'rgb(255, 99, 132)'
      ],
      hoverOffset: 4
    }]
  };
  
    new Chart(ctx, {
      type: 'doughnut',
      data: data,
    });




var groupedData = {!! $stackedData !!};

// Iterate through the datasets to adjust the colors based on the target achievement
groupedData.datasets.forEach(dataset => {
    let collectedAmount = dataset.data[0];
    let remainingAmount = dataset.data[1];
    let target = collectedAmount + remainingAmount;
    let percentCollected = (collectedAmount / target) * 100;

    console.log("collected amount: "+collectedAmount)
    console.log("remaining amount: "+remainingAmount)
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
        datasets: [
            {
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
                beginAtZero: true
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Account Manager Vs Amount Collected Vs Amount Remaining for the month of '+currentMonthName+" "+currentYear
            },
            legend: {
                display: true,
                position: 'bottom'
            }
        }
    }
};
const groupedChart = new Chart(document.getElementById('grouped'), config);

const stacked = document.getElementById('stacked');
new Chart(stacked, config);

  </script>
@endsection
   
</x-app-layout>
