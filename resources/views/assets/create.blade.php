<x-app-layout>
   <div class="col-md-12">
  <div class="card">
    <div class="card-header">
        <h3 class="card-title">Register a PSC Asset </h3>
        <div class="card-options">
              <span class="badge bg-green">Good Condition</span> 
       </div>
    </div>
    <div class="card-body">
      <form action="{{ route('saveasset') }}" method="post">
        @csrf
        <div class="row" >
            <div class="col-md-6 col-6">     
                <div class="row">
                    <div class="col-md-6 col-6">
                        <div class="form-group mb-3 ">
                        <label class="form-label">Asset Number</label>
                        <div >
                            <input name="assetnumber" type="text" class="form-control" aria-describedby="Asset Number" placeholder="Asset Number">
                            {{-- <small class="form-hint">We'll never share your email with anyone else.</small> --}}
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="mb-3">
                            <label class="form-label">Capitalization Date</label>
                            <input name="capitalization_date" id="calendar-simple" type="date" value="2020-06-20" class="form-control mb-2" placeholder="Select a date" />                     
                        </div>
                    </div>
                </div>
                 <div class="mb-3">
                    <label class="form-label">Asset Description<span class="form-label-description"></span></label>
                    <textarea name="description" class="form-control"  data-toggle="autosize" name="example-textarea-input" rows="5" placeholder="Description"> </textarea>
                </div>
                <div class="row">
                    <div class="col-md-4 col-4">
                        <div class="form-group mb-3 ">
                            <label class="form-label">Department</label>
                            <div >
                                <select name="department" class="form-select">
                                    <option>Sinter</option>
                                    <option>Mechanical</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-8 col-8">
                        <div class="form-group mb-3 ">
                            <label class="form-label">Classification</label>
                            <div >
                                <select name="classification" class="form-select">
                                    <option>Furniture, Fixture & Other Office Equipment</option>
                                    <option>Mechanical</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-6">
            
                <div class="row">
                    <div class="col-md-6 col-6">                
                        <div class="form-group mb-3 ">
                            <label class="form-label">Asset Quantity</label>
                            <div >
                                <input name="quantity" type="number" class="form-control" aria-describedby="Asset Quantity" placeholder="0">
                                {{-- <small class="form-hint">We'll never share your email with anyone else.</small> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="form-group mb-3 ">
                            <label class="form-label">Basic Unit of Measure (BUM)</label>
                            <div >
                                <input name="bum" type="text" class="form-control" aria-describedby="Basic Unit of Measure" placeholder="BUM">
                                {{-- <small class="form-hint">We'll never share your email with anyone else.</small> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-6">
                        <div class="form-group mb-3 ">
                            <label class="form-label">Aquired Value</label>
                            <div >
                                <input name="acquired_value" type="text" class="form-control" placeholder="0.00">
                                <small class="form-hint">
                                
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="form-group mb-3 ">
                            <label class="form-label">End Book Value</label>
                            <div >
                                <input name="endbookvalue" type="text" class="form-control" placeholder="0.00">
                                <small class="form-hint">
                                
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-6">
                        <div class="form-group mb-3 ">
                            <label class="form-label">Salvage Value</label>
                            <div >
                                <input name="salvagevalue" type="text" class="form-control" placeholder="0.00">
                                <small class="form-hint">
                                
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="form-group mb-3 ">
                            <label class="form-label">Useful Life Years</label>
                            <div >
                                <input name="usefullifeyears" type="number" class="form-control" placeholder="1">
                                <small class="form-hint">
                                
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="form-group mb-3 ">
                    <label class="form-label">Cost Center</label>
                    <div >
                        <select name="costcenter" class="form-select">
                            <option>17000</option>
                            <option>43000</option>
                        </select>
                    </div>
                </div>
                
                {{-- <div class="form-group mb-3">
                    <label class="form-label">Checkboxes</label>
                    <div >
                        <label class="form-check">
                        <input class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Option 1</span>
                        </label>
                        <label class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <span class="form-check-label">Option 2</span>
                        </label>
                        <label class="form-check">
                        <input class="form-check-input" type="checkbox" disabled="">
                        <span class="form-check-label">Option 3</span>
                        </label>
                    </div>
                </div> --}}
            </div>
        </div>
        <hr />
        <div class="form-footer text-right">
          <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path><circle cx="12" cy="14" r="2"></circle><polyline points="14 4 14 8 8 8 8 4"></polyline></svg>
            Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
</x-app-layout>