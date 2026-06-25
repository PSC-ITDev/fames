<x-app-layout>
  <div>
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"> <i class="bi bi-clock-history"></i> System Activity</h4>
          </div>
          <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table id="systemActTable" class="table table-striped table-hover">
              <thead>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
              <tbody>
                @foreach($activities as $activity)
                  <tr onclick="window.location.href ='{{ route('evaluation-details', $activity->type_id) }}'" style="cursor:pointer;">
                    <td>
                        <p class="{{ strtolower(str_replace(' ', '-', $activity->activity)) }}">
                            <span class="p2"><b>{{$activity->activity}}</b></span> 
                            by <b>{{$activity->performer->name}} (EVAL-{{$activity->type_id}})</b>
                        </p> 
                      <small></small>
                    </td>
                    <td style="text-align: right;">
                          <span >{{$activity->created_at->diffForHumans()}}</span>
                    </td>
                  </tr>
                @endforeach
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"> Current Asset Status as of <b>{{$evaluation->department->name ?? ''}} {{$evaluation->quarter ?? ''}} Quarter {{$evaluation->year ?? ''}} </b></h4>
            </div>  
            <div class="card-body" >
              <div>
                <canvas id="doughnutChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>

              </div>
              <div id="noDataMessage" style="
                  position:absolute;
                  top:50%;
                  left:50%;
                  transform:translate(-50%,-50%);
                  font-size:16px;
                  color:#666;
                  display:none;
              ">
                  No data available
              </div>
              
            </div>                 
        </div>  
        
      </div>

    </div>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"><i class="bi bi-clock-history"></i>  Asset Status per Quarter</h4>
            
          </div>
          <div class="card-body">
              <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; width: 100%;"></canvas>
          </div>
        </div>
      </div>
      
    </div>
  </div>   

  <script>


    document.addEventListener("DOMContentLoaded", function () {

        $('#systemActTable').DataTable({
            searching: false,
            paging: true,
            pageLength: 20,
            lengthChange: false
        });

    });
    document.addEventListener("DOMContentLoaded", renderCharts);
    document.addEventListener("livewire:navigated", renderCharts);
      
      function renderCharts() {
        const barData = @json($barData);


        const labels = barData.map(item => item.name);
        const goodCondition = barData.map(item => item.statusData['Operational In Good Condition']);
        const underRepair = barData.map(item => item.statusData['Undergoing Repair']);
        const spareUnit = barData.map(item => item.statusData['Operational Spare Unit']);
        const unserviceable = barData.map(item => item.statusData['Unserviceable']);


        const ctx = document.getElementById('barChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                  
                    {
                        label: 'Operational In Good Condition',
                        data: goodCondition,
                        backgroundColor: '#198754'
                    },
                    {
                        label: 'Undergoing Repair',
                        data: underRepair,
                        backgroundColor: '#ffc107'
                    },
                    {
                        label: 'Operational Spare Unit',
                        data: underRepair,
                        backgroundColor: '#0d6efd'
                    },
                    {
                        label: 'Unserviceable',
                        data: unserviceable,
                        backgroundColor: '#dc3545'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                          generateLabels: function(chart) {
                              const datasets = chart.data.datasets;

                              return datasets.map((dataset, i) => {
                                  // sum all values in dataset
                                  const total = dataset.data.reduce((a, b) => a + b, 0);

                                  return {
                                      text: ` (${total}) ${dataset.label}`,
                                      fillStyle: dataset.backgroundColor,
                                      strokeStyle: dataset.backgroundColor,
                                      hidden: !chart.isDatasetVisible(i),
                                      datasetIndex: i
                                  };
                              });
                          }
                      }
                    }
                }
            }
        });


        const doughnutData = Object.values(@json($doughnutData));
        const doughnutLabels = Object.keys(@json($doughnutData));
        console.log(doughnutData);
        if (doughnutData) {
          new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: ['#198754', '#ffc107','#0d6efd', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                          generateLabels: function(chart) {
                              const data = chart.data;

                              return data.labels.map((label, i) => {
                                  const value = data.datasets[0].data[i] || 0;

                                  return {
                                      text: `(${value}) ${label} `,
                                      fillStyle: data.datasets[0].backgroundColor[i],
                                      hidden: false,
                                      index: i
                                  };
                              });
                          }
                      }
                    },
                  
                },
                
            }
          });
        } else {
          document.getElementById('doughnutChart').parentElement.innerHTML =
            '<div class="text-center text-muted py-5">No data available</div>';
        }
      }
  </script>



            
          
             
</x-app-layout>
