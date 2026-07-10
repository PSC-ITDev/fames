<x-app-layout>
   <div class="col-md-12">
  <div class="card">
    <div class="card-header">
        <h3 class="card-title">Register a PSC Location </h3>
        <div class="card-options">
             <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Register User
            </button>
       </div>
    </div>
    <div class="card-body">   

        <div class="table-responsive">
            <table id="userTable" class="table card-table table-vcenter table-sm">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Email</th>
                    </tr>
                </thead>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td class="text-muted">{{$user->name}}</td>
                        <td class="text-muted">{{$user->department?->name}}</td>
                        <td class="text-muted">{{$user->role?->name}}</td>
                        <td class="text-muted">{{$user->email}}</td>
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
        <h5 class="modal-title" id="exampleModalLabel">Register a User </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        @include('auth.register')

        
      </div>

    </div>
  </div>
</div>
<div>
    
</div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    $('#userTable').DataTable({
        searching: false,
        paging: true,
        pageLength: 20,
        lengthChange: false
    });
  });
</script>
</x-app-layout>