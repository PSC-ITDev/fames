

    <form action="{{ route('savecategory') }}" method="post">
    @csrf
    <div class="row" >
              
        <div class="form-group mb-3 ">
            <label class="form-label">Name</label>
            <div >
                <input name="name" type="text" class="form-control" aria-describedby="Name" placeholder="Name">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group mb-3 ">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"  data-toggle="autosize" name="example-textarea-input" rows="5" placeholder="Description"> </textarea>
           
        </div>
    </div>
    <hr />
    <div class="form-footer text-right">
        <button type="submit" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path><circle cx="12" cy="14" r="2"></circle><polyline points="14 4 14 8 8 8 8 4"></polyline></svg>
        Submit</button>
    </div>
    </form>

