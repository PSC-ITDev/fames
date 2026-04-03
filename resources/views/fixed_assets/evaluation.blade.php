<x-app-layout>
  <div class="col-md-12">
    <div class="card">
      <form action="{{ route('saveevaluation') }}" method="POST">
        @csrf
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
                <script>
                  let currentYear = new Date().getFullYear();
                  for (let i = currentYear; i >= currentYear -3 ; i--) {
                    document.write(`<option value="${i}">${i}</option>`);
                  }
                </script>
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
                  <h5><b>I. Assets On Inventory: </b></h5><br>
                </div>
                <table class="table card-table table-vcenter" id="table1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Asset No.</th>
                            <th>Capitalization Date</th>
                            <th>Qty</th>
                            <th>BUn</th>
                            <th>Asset Description</th>
                            <th>Other Identifiers</th>

                            <th>Status</th>
                            <th>Corrective Action Taken
                              <br><small>Unserviceable or Undergoing Repair</small>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                      @foreach($assets as $index => $asset)

                          <tr data-id="{{ $asset->id }}" data-info="{{$asset}}">
                              <td><input class="form-check-input move-check" type="checkbox" id="check{{$asset->id}}"></td>
                              <td class="text-muted asset_no">{{$asset->asset_no}}</td>
                              <td class="text-muted">{{$asset->capitalization_date}}</td>
                              <td class="text-muted qty">{{$asset->qty}}</td>
                              <td class="text-muted bum">{{$asset->bum}}</td>
                              <td class="text-muted asset_description">{{$asset->asset_description}}</td>
                              <td class="text-muted ">{{$asset->other_identifier}}</td>

                              <td class="text-muted"><input type="text" class="form-control" name="remainingAsset[{{$asset->id}}][status]"></td>
                              <td class="text-muted"><input type="text" class="form-control" name="remainingAsset[{{$asset->id}}][action_taken]"></td>
                              
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




<div>
    
</div>


<script>
  document.addEventListener("DOMContentLoaded", function () {

      let table1Body = document.querySelector("#table1 tbody");
      let table2Body = document.querySelector("#table2 tbody");

      // HANDLE ALL checkbox changes (both tables)
      document.addEventListener("change", function(e) {

          if (!e.target.classList.contains("move-check")) return;

          let checkbox = e.target;
          let row = checkbox.closest("tr");
          let id = row.dataset.id;
          let info = JSON.parse(row.dataset.info);

          console.log(info);
          // FROM TABLE 1 → TABLE 2
          if (row.closest("#table1")) {

              if (checkbox.checked) {

                  row.remove(); // remove from table1

                  let newRow = document.createElement("tr");
                  newRow.setAttribute("data-id", id);
                  newRow.setAttribute("data-info", JSON.stringify(info));

                  newRow.innerHTML = `
                      <td><input class="form-check-input move-check" type="checkbox" checked></td>
                      <td>${info.asset_no}</td>
                      <td><input type="text" class="form-control schedule-date" name="writtenOff[${id}][schedule_date]"></td>
                      
                      <td>${info.qty}</td>
                      <td>${info.bum}</td>
                      <td>${info.asset_description}</td>
                      <td><input name="writtenOff[${id}][turnove_date]" id="calendar-simple" type="date" value="2020-06-20" class="form-control mb-2" placeholder="Select a date" /></td>
                      <td><input name="writtenOff[${id}][date_adwf]" id="calendar-simple" type="date" value="2020-06-20" class="form-control mb-2" placeholder="Select a date" /></td>
                      <td><input type="text" class="form-control schedule-date" name="writtenOff[${id}][adwf_no]"</td>
                  `;

                  table2Body.appendChild(newRow);
              }

          }

          // FROM TABLE 2 → TABLE 1
          else if (row.closest("#table2")) {

              if (!checkbox.checked) {

                  let name = row.children[1].textContent;
                  row.remove(); // remove from table2

                  let newRow = document.createElement("tr");
                  newRow.setAttribute("data-id", id);
                  newRow.setAttribute("data-info", JSON.stringify(info));

                  newRow.innerHTML = `
                    <td><input class="form-check-input move-check" type="checkbox" id="check${id}"></td>
                    <td class="text-muted asset_no">${info.asset_no}</td>
                    <td class="text-muted">${info.capitalization_date ?? ''}</td>
                    <td class="text-muted qty">${info.qty ?? ''}</td>
                    <td class="text-muted bum">${info.bum ?? ''}</td>
                    <td class="text-muted asset_description">${info.asset_description ?? ''}</td>
                    <td class="text-muted ">${info.other_identifier ?? ''}</td>

                    <td class="text-muted"><input type="text" class="form-control" name="remainingAsset[${info.id}][status]"></td>
                    <td class="text-muted"><input type="text" class="form-control" name="remainingAsset[${info.id}][action_taken]"></td>
                  `;
                  console.log(newRow)
                  
                  table1Body.appendChild(newRow);
              }

          }

      });

  });
</script>
</x-app-layout>