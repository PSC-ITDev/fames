<x-app-layout>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
          <h3 class="card-title">Register a PSC Asset </h3>
          <div class="card-options">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary m2" data-bs-toggle="modal" data-bs-target="#registerAssetModal">
              Add Asset
              </button>
              <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#graphModal">
              <i class="bi bi-graph-up"></i>
              </button>
          </div>
        </div>
      <div class="card-body">   
          <div class="table-responsive">
<<<<<<< Updated upstream
              <table id="assetTable" class="table card-table table-vcenter table-sm">
=======
              <table id="" class="table card-table table-vcenter">
>>>>>>> Stashed changes
                  <thead>
                      <tr>
                          <th>Item</th>
                          <th>Asset No.</th>
                          <th>Capitalization Date</th>
                          <th>Qty</th>
                          <th>BUn</th>
                          <th>Asset Description</th>
                          <th>Acquired Value</th>
                          <th>End Book Value</th>
                          <th>Cost Center</th>
                          <th>Location</th>
                          <th>Asset Classification</th>
                          <th>Department</th>
                      </tr>
                  </thead>
                  @foreach($assets as $index => $asset)

                      <tr>
                          <td>{{$index + 1}}</td>
                          <td class="text-muted">{{$asset->asset_number}}</td>
                          <td class="text-muted">{{$asset->capitalization_date->format('Y-m-d') }}</td>
                          <td class="text-muted">{{$asset->qty}}</td>
                          <td class="text-muted">{{$asset->bum}}</td>
                          <td class="text-muted">{{$asset->asset_description}}</td>
                          <td class="text-muted">{{$asset->acquired_value}}</td>
                          <td class="text-muted">{{$asset->end_book_value}}</td>
                          <td class="text-muted">{{$asset->cost_center}}</td>
                          <td class="text-muted">{{$asset->location->name}}</td>
                          <td class="text-muted">{{$asset->classification->name ?? ''}}</td>
                          <td class="text-muted">{{$asset->department->name ?? ''}}</td>
                          
                      </tr>
                  @endforeach


            </table>
        </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade " id="registerAssetModal" tabindex="-1" role="dialog"
aria-labelledby="registerAssetModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-full-width modal-dialog-centered" role="document">
      
    <div class="modal-content">
      
      {{-- <div class="modal-header">
        <h5 class="modal-title" id="registerAssetModalLabel">Register a PSC Asset </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div> --}}
      <div class="modal-header">
            <h5 class="modal-title" id="registerAssetModalLabel">Register a PSC Asset </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>
      <div class="modal-body">
        @include('master_list.assets.create')        
      </div>

      </div>
    </div>
  </div>

  <!-- Modal -->
<div class="modal fade " id="graphModal" tabindex="-1" role="dialog"
aria-labelledby="graphModalLabel" aria-hidden="true">
 <div class="modal-dialog  modal-dialog-centered" role="document">
      
    <div class="modal-content">
    
      <div class="modal-header">
            <h5 class="modal-title" id="graphModalLabel">Graph</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>
      <div class="modal-body">
             <canvas id="doughnutChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
      </div>

      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      $('#assetTable').DataTable({
          searching: false,
          paging: true,
          pageLength: 20,
          lengthChange: false
      });

        const data = @json($assetData);
        const colors = [
            '#A4DBC7', // Pastel Green
            '#FFE5A5', // Pastel Yellow
            '#A5D8FF', // Pastel Blue
            '#FFB4A2', // Pastel Coral
            '#D1D5DB', // Soft Gray
            '#A8E6CF', // Pastel Mint
            '#D8C4F8'  // Pastel Lavender
        ];
        const doughnutData = Object.values(data || {});
        const doughnutLabels = Object.keys(data || {});
        const backgroundColors = doughnutData.map((_, index) => {
            return colors[index % colors.length];
        });

        if (data) {
          new Chart(document.getElementById('doughnutChart'), {
            type: 'doughnut',
            data: {
                labels: doughnutLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: backgroundColors
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

    });
  </script>
</x-app-layout>



  <!-- item
// asset_no
// serial_number
// capitalization_date
// qty
// bun	
// asset_description	
// acquired_value	
// end_book_value	
// cost_center	
// location
// other_identifier	
// classification_id
// salvage_value
// useful_life_years
// category_id	bigint
// location_id	bigint
// notes -->