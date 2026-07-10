<x-app-layout>
   <div class="col-md-12">
  <div class="card">
    <div class="card-header">
        <h3 class="card-title">Register a PSC Location </h3>
        <div class="card-options">
             <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRoleModal">
            Add Role
            </button>
       </div>
    </div>
    <div class="card-body">   

        <div class="table-responsive">
            <table id="roleTable" class="table card-table table-vcenter table-sm">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Name</th>
                        <th>Description</th>
                    </tr>
                </thead>
                @foreach($roles as $index => $role)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td class="text-muted">{{$role->name}}</td>
                        <td class="text-muted">{{$role->description}}</td>
                    </tr>
                @endforeach


            </table>
        </div>
    </div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="newRoleModal" tabindex="-1" aria-labelledby="newRoleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="newRoleModalLabel">Register a PSC Role </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        @include('master_list.role.create')

        
      </div>

    </div>
  </div>
</div>
<div>
    
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
      document.getElementById('newRoleModal').addEventListener('show.bs.modal', function (event) {
          check_duplicate();
      });

      $('.live-input').on('input', function () {
          check_duplicate();
      });

      function check_duplicate(){
        let formData = {};

        $('#roleForm')
          .serializeArray()
          .filter(field => field.name !== '_token')
          .forEach(field => {
              formData[field.name] = field.value;
        });

        console.log(formData);

        let roles = @json($roles);

        console.log(roles);

        const result = roles.find(item =>
            item.name.toLowerCase() == formData.name.toLowerCase() 
        );

        console.log(result);
            
        if(result){
          $('#duplicate').show();
          $('#submitRole').prop('disabled', true);
        }else{
          $('#duplicate').hide();
          $('#submitRole').prop('disabled', false);
        }

      }


      $('#roleTable').DataTable({
          searching: false,
          paging: true,
          pageLength: 20,
          lengthChange: false
      });
    });
        

  </script>
</x-app-layout>