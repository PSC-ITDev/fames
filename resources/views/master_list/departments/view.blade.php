<x-app-layout>
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
          <h3 class="card-title">Register a PSC Department </h3>
      </div>
      <div class="card-body">   
        
        <form action="{{ route('savehierarchy', $department->id) }}" method="post">
          @csrf

          <div class="row" >

            <div class="col">
                <label class="form-label">Drafter </label>
                <div class="form-group mb-3 ">
                    <div >
                        <select name="hierarchy['user'][]" class="form-select" >
                            
                            <option value="" selected></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{$department->drafter->get(0)?->user_id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="form-group mb-3 ">
                    <div >
                        <select name="hierarchy['user'][]" class="form-select" >
                            
                            <option value="" selected></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{$department->drafter->get(1)?->user_id == $user->id ? "selected" : ""}}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col">
                <label class="form-label">Approver </label>
                <div class="form-group mb-3 ">
                    <div >
                        <select name="hierarchy['approver_user'][]" class="form-select" >
                            
                            <option value="" selected></option>
                            @foreach ($users as $user)

                                <option value="{{ $user->id }}" {{$department->approver->get(0)?->user_id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="form-group mb-3 ">
                    <div >
                        <select name="hierarchy['approver_user'][]" class="form-select" >
                            
                            <option value="" selected></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{$department->approver->get(1)?->user_id == $user->id ? "selected" : ""}}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <div class="col">
                <label class="form-label">Confirmer </label>
                <div class="form-group mb-3 ">
                    <div >
                        <select name="hierarchy['confirmer_user'][]" class="form-select" >
                            
                            <option value="" selected></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{$department->confirmer->get(0)?->user_id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="form-group mb-3 ">
                    <div >
                        <select name="hierarchy['confirmer_user'][]" class="form-select" >
                            
                            <option value="" selected></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{$department->confirmer->get(1)?->user_id == $user->id ? "selected" : ""}}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
                  

          </div>
          <hr />
          <div class="form-footer text-right">
              <button type="submit" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path><circle cx="12" cy="14" r="2"></circle><polyline points="14 4 14 8 8 8 8 4"></polyline></svg>
              Update</button>
          </div>
        </form>      
      </div>
    </div>
  </div>




</x-app-layout>