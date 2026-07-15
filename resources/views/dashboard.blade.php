<x-app-layout>
  <div>
    <div class="row">
      <div class="col-6">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title"> <i class="bi bi-clock-history"></i> System Activity</h4>
          </div>
          <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table id="systemActTable" class="table table-striped table-hover table-sm">
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
            lengthChange: false,
            order: [[2, 'desc']] 
        });
      renderCharts();

    });
    document.addEventListener("livewire:navigated", renderCharts);
    // document.addEventListener("livewire:navigated", () => {
    //     renderCharts();
    // });
    
      
    function renderCharts() {
      const barData = @json($barData);

      if(barData){
        const labels = barData.map(item => item.name);
        const goodCondition = barData.map(item => item.statusData['Operational In Good Condition']);
        const underRepair = barData.map(item => item.statusData['Undergoing Repair']);
        const spareUnit = barData.map(item => item.statusData['Operational Spare Unit']);
        const unserviceable = barData.map(item => item.statusData['Unserviceable']);
        const writeOff = barData.map(item => item.statusData['Write Off']);

      console.log('barData:', barData);
      const ctx = document.getElementById('barChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                  {
                      label: 'Operational - Good Condition',
                      data: goodCondition,
                      backgroundColor: '#6FC9AB' // Pastel Green
                  },
                  {
                      label: 'Operational - Spare Unit',
                      data: spareUnit, // Changed from underRepair
                      backgroundColor: '#ACC3E5' // Pastel Blue
                  },
                  {
                      label: 'Undergoing Repair',
                      data: underRepair,
                      backgroundColor: '#F9DF80' // Soft Pastel Yellow
                  },
                  {
                      label: 'Unserviceable',
                      data: unserviceable,
                      backgroundColor: '#292B68' // Soft Lavender
                  },
                  {
                      label: 'Write Off',
                      data: writeOff,
                      backgroundColor: '#9C2A29' // Pastel Coral
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
                        
                    }
                }
            }
        });
      } else {
        document.getElementById('barChart').parentElement.innerHTML =
          '<div class="text-center text-muted py-5">No data available</div>';
      }

      const data = @json($doughnutData);
      const doughnutData = Object.values(data || {});
      const doughnutLabels = Object.keys(data || {});

      if (data) {
        new Chart(document.getElementById('doughnutChart'), {
          type: 'doughnut',
          data: {
              labels: doughnutLabels,
              datasets: [{
                  data: doughnutData,
                  backgroundColor: [
                      '#6FC9AB', // Pastel Green
                      '#F9DF80', // Pastel Yellow
                      '#ACC3E5', // Pastel Blue
                      '#9C2A29', // Pastel Coral
                      '#292B68'  // Pastel Lavender
                  ],
                  borderColor: '#e5e7eb',   // Outline color
                  borderWidth: 2            // Outline thickness
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
