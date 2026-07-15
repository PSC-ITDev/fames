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
            <table id="userTable" class="table card-table table-vcenter table-sm table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{$index + 1}}</td>
                        <td class="text-muted">{{$user->name}}</td>
                        <td class="text-muted">{{$user->department?->name}}</td>
                        <td class="text-muted">{{$user->role?->name}}</td>
                        <td class="text-muted">{{$user->email}}</td>
                        <td >
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#EditModal" data-user='@json($user)'>
                            Edit
                            </button>
                        </td>
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

<!--Edit Modal -->
<div class="modal fade" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        
        <div class="modal-header">
            <h5 class="modal-title" id="EditModalLabel">Edit a User </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
        </div>
        
        <div class="modal-body">
            @include('master_list.users.edit')

            
        </div>

        </div>
    </div>
</div>

    
<script>
  document.addEventListener("DOMContentLoaded", function () {
    $('#userTable').DataTable({
        searching: false,
        paging: true,
        pageLength: 20,
        lengthChange: false
    });

    $("#picture").on("change", function () {
      const file = this.files[0];

      if (!file) return;

      const allowedTypes = ["image/png", "image/jpeg"];

      if (!allowedTypes.includes(file.type)) {
          alert("Only PNG, JPG, and JPEG files are allowed.");
          $(this).val(""); // Clear the selected file
          return;
      }

      // Max size: 2 MB
      if (file.size > 2 * 1024 * 1024) {
          alert("The image must not exceed 2 MB.");
          $(this).val(""); // Clear the selected file
          return;
      }
    });

    $("#signature").on("change", function () {
        const file = this.files[0];

        if (!file) return;

        // Allow only PNG
        if (file.type !== "image/png") {
            alert("Please upload a PNG signature.");
            $(this).val("");
            $("#signaturePreview").hide().attr("src", "");
            return;
        }

        // Optional: Max size 1 MB
        if (file.size > 1024 * 1024) {
            alert("Signature must not exceed 1 MB.");
            $(this).val("");
            $("#signaturePreview").hide().attr("src", "");
            return;
        }

        // Preview
        const reader = new FileReader();

        reader.onload = function (e) {
            $("#signaturePreview")
                .attr("src", e.target.result)
                .show();
        };

        reader.readAsDataURL(file);
    });

    // $('#EditModal').on('show.bs.modal', function () {
    //     var button = $(event.relatedTarget);

    //     var user = JSON.parse(button.attr('data-user'));
    //     console.log(user); 

    // });


    $('#EditModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);

        var user = JSON.parse(button.attr('data-user'));
         $('#editForm').attr('action', '/updateuser/' + user.id);



        $('input[name="name"]').val(user.name);
        $('input[name="email"]').val(user.email);

        $('#role').val(user.role_id).trigger('change');
        $('#department').val(user.deptid).trigger('change');
    });

  });
</script>
</x-app-layout>