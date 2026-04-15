<x-app-layout>
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="mx-2">
              
              <label for="quarter-select" class="form-label">Quarter</label>
              <select class="form-select" name="qrt" i>
                <option value="1st">1st</option>
                <option value="2nd">2nd</option>
                <option value="3rd">3rd</option>
                <option value="4th">4th</option>
              </select>
              
            </div>
            <div>
              <label for="yearSelect" class="form-label">Select Year</label>
              <select name="year" class="form-select" >
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
              </select>
            </div>
            <div class="card-options"> 
              <label for="departmentSelect" class="form-label">Department</label>
              <select name="department_id" class="form-select   selectized" >
                @foreach($departments as $department)
                  <option value="{{ $department->id }}">{{ $department->description }}</option>
                @endforeach
              </select>
            </div>
        </div>
        <div class="card-body">   
            <div class="table-responsive">
                <div>
                  <h5><b>Evaluation List </b></h5><br>
                </div>
                <table class="table card-table table-vcenter table-hover" id="table1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Quarter</th>
                            <th>Approval Status</th>
                            <th>Created By</th>
                            <th>Confirmed Date</th>
                            <th>Confirmed By</th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($evaluations as $index => $evaluation)

                          <tr onclick="window.location.href ='{{ route('evaluation-details', $evaluation->id) }}'" style="cursor:pointer;">
                              <td>{{$index}}</td>
                              <td>{{$evaluation->department->name
                                }}</td>
                              <td class="text-muted ">{{$evaluation->year}}</td>
                              <td class="text-muted">{{$evaluation->quarter}}</td>
                              <td class="text-muted qty">{{$evaluation->approval_status == 0 ? "For Approval" : "Approved"}}</td>
                              <td class="text-muted bum">{{$evaluation->creator->name ?? ''}}</td>
                              <td class="text-muted">{{$evaluation->confirmed_date}}</td>
                              <td class="text-muted ">{{$evaluation->confirmed_by}}</td>
                              
                          </tr>
                      @endforeach
                    </tbody>


                </table>
            </div>
            <hr>
            <div class="table-responsive my-3">
                <div>
                  <h5><b>B. Items Written off or Dispose within this quarter. </b></h5><br>
                </div>
                <table class="table card-table table-vcenter" id="table2">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Asset No.</th>
                            <th>Reason for Write-off</th>
                            <th>Qty</th>
                            <th>BUn</th>
                            <th>Asset Description</th>
                            <th>Date of Turn-over to Holding Station</th>

                            <th>Date of ADWF</th>
                            <th>ADWF Document No.</th>
                        </tr>
                    </thead>
                  <tbody>

                  </tbody>
                </table>
            </div>
            <div class="row">
              <div class="col-4">
                <table class="table card-table table-vcenter">
                  <thead>
                    <tr>
                      <th>Prepared By:</th>
                    </tr>
                  </thead>
                  <tbody id="incharge">
                    <tr>
                      <td>JD Jabinao / AM Zaragoza</td>
                    </tr>
                     <tr>
                      <td>Name of Department Incharge</td>
                    </tr>
                  </tbody>
           
                </table>

              </div>
              <div class="col-4">
                  <table class="table card-table table-vcenter">
                  <thead>
                    <tr>
                      <th>Confirmed By:</th>
                    </tr>
                  </thead>
                  <tbody id="departmentHead"> 
                    <tr>
                      <td>GMO Salvana</td>
                    </tr>
                     <tr>
                      <td>Name of Department Head</td>
                    </tr>
                  </tbody>
           
                </table>
              </div>
              <div class="col-4">
                  <table class="table card-table table-vcenter">
                  <thead>
                    <tr>
                      <th>Reviewed By:</th>
                    </tr>
                  </thead>
                  <tbody id="auditor"> 
                    <tr>
                      <td>HL Madera</td>
                    </tr>
                     <tr>
                      <td>Name of Auditor</td>
                    </tr>
                  </tbody>
           
                </table>
              </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
             
            </div>
        </div>
        <div class="card-footer text-right">
  <div class="d-flex">
    {{-- <a href="#" class="btn btn-link">Cancel</a> --}}
    <button type="submit" class="btn btn-primary ml-auto">Submit</button> 
  </div>
</div>
      </form>
        </div>
    </div>
  </div>
    <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Register New Evaluation </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
          @include('fixed_assets.create')

          
        </div>

      </div>
    </div>
  </div>






</x-app-layout>