<x-app-layout>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
          <h3 class="card-title">Register a PSC Asset </h3>
          <div class="card-options">
              <!-- Button trigger modal -->
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
              Add Asset
              </button>
        </div>
      </div>
      <div class="card-body">   
          <div class="table-responsive">
              <table class="table card-table table-vcenter">
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
                          <td class="text-muted">{{$asset->asset_no}}</td>
                          <td class="text-muted">{{$asset->capitalization_date}}</td>
                          <td class="text-muted">{{$asset->qty}}</td>
                          <td class="text-muted">{{$asset->bum}}</td>
                          <td class="text-muted">{{$asset->asset_description}}</td>
                          <td class="text-muted">{{$asset->acquired_value}}</td>
                          <td class="text-muted">{{$asset->end_book_value}}</td>
                          <td class="text-muted">{{$asset->cost_center}}</td>
                          <td class="text-muted">{{$asset->location_id}}</td>
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
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Register a PSC Asset </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
          @include('master_list.assets.create')

          
        </div>

      </div>
    </div>
  </div>
<div>
    
</div>
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