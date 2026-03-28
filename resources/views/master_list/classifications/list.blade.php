<x-app-layout>
   <div class="col-md-12">
  <div class="card">
    <div class="card-header">
        <h3 class="card-title">Register a PSC Classification </h3>
        <div class="card-options">
             <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Add Classification
            </button>
       </div>
    </div>
    <div class="card-body">   

        <div class="table-responsive">
            <table class="table card-table table-vcenter">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                @foreach($classifications as $index => $classification)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td class="text-muted">{{$classification->name}}</td>
                        <td class="text-muted">{{$classification->description}}</td>
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
        <h5 class="modal-title" id="exampleModalLabel">Register a PSC Classification </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        @include('master_list.classifications.create')

        
      </div>

    </div>
  </div>
</div>
<div>
    
</div>
</x-app-layout>