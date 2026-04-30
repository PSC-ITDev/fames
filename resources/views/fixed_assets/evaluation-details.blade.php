<x-app-layout>
  <div class="col-md-12">
    <div class="card">

        @if($is_approver)
            <form id="{{$can_edit ? '' : 'myForm'}}" action="{{  route('approve-evaluation',$evaluation->id) }}" method="POST">
        @elseif($is_confirmer)
            <form id="{{$can_edit ? '' : 'myForm'}}" action="{{  route('confirm-evaluation',$evaluation->id) }}" method="POST">
        @else
            <form id="{{$evaluation->approval_status > 1 ? 'myForm' : ($can_edit ? '' : 'myForm')}}" action="{{  route('updateevaluation',$evaluation->id) }}" method="POST">
        @endif

         @php   
            $approval_statuses = [
                0 => 'Pending',
                10 => 'For Approval',
                20 => 'Approved',
                30 => 'Confirmed',
                50 => 'Rejected'
            ];

            
            $approval_status = $approval_statuses[floor($evaluation->approval_status / 10) * 10];

            $current_user = auth()->user();
        @endphp


        @csrf
        <div class="card-header">
           <div>
                <h3>Department: <b>{{$evaluation->department->name}} </b></h3>
           </div>
            <div class="card-options">
               <b>{{$approval_status}}{{$can_edit ? 'Test' : 'myForm'}}</b>
            </div>
            <div class="card-options">
               <b>{{$approval_status}}</b>
            </div>
        </div>
        <div class="card-body">   
            <div class="table-responsive">
                <div>
                  <h5><b>I. Assets On Inventory: </b></h5><br>
                </div>
                <div >
                    <table class="table card-table table-vcenter table-hover" id="table1">
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
                                <th class="hidden"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($evaluation->details_remaining as $index => $asset)

                            <tr data-id="{{ $asset->id }}" data-info="{{$asset->asset}}">
                                <td><input class="form-check-input move-check" type="checkbox" id="check{{$asset->id}}"></td>
                                <td class="text-muted asset_no">{{$asset->asset->asset_number ?? ""}}</td>
                                <td class="text-muted">{{$asset->asset->capitalization_date}}</td>
                                <td class="text-muted qty">{{$asset->asset->qty}}</td>
                                <td class="text-muted bum">{{$asset->asset->bun}}</td>
                                <td class="text-muted asset_description">{{$asset->asset->asset_description}}</td>
                                <td class="text-muted ">{{$asset->asset->other_identifier}}</td>

                                <td class="text-muted">
                                    <select name="remainingAsset[{{$asset->id}}][asset_status]" class="form-select" >
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status->id }}" {{(int) $asset->asset_status == (int) $status->id ? "selected" : ""}}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-muted"><input type="text" class="form-control" name="remainingAsset[{{$asset->id}}][corrective_actiion_taken]"  value="{{ old('remainingAsset[$asset->id][corrective_actiion_taken]', $asset->corrective_actiion_taken ?? '') }}"></td>
                                
                                <td class="hidden">
                                    <input type="text" class="hidden " name="remainingAsset[{{$asset->id}}][id]" value="{{$asset->id}}">
                                    <input type="hidden" name="remainingAsset[{{$asset->id}}][iswrite_off]" value="0">
                                    <input type="text" class="hidden " name="remainingAsset[{{$asset->id}}][asset_id]" value="{{$asset->asset->id}}">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>


                    </table>
                </div>
                
            </div>
            <hr>
            <div class="table-responsive my-3" >
                <div>
                  <h5><b>B. Items Written off or Dispose within this quarter. </b></h5><br>
                </div>
                <div style="max-height: 300px;overflow-y: auto;">
                    
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
                                
                                <th class="hidden"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($evaluation->details_writtenOff as $index => $asset)

                                <tr data-id="{{ $asset->id }}" data-info="{{$asset->asset}}">
                                    <td><input class="form-check-input move-check" type="checkbox" checked></td>
                                    <td>{{$asset->asset->asset_number}}</td>
                                    <td><input type="text" class="form-control" value="{{ old('writtenOff[$asset->id][reason_for_writeoff]', $asset->reason_for_writeoff ?? '') }}" name="writtenOff[{{$asset->id}}][reason_for_writeoff]"></td>
                                    <td class="text-muted qty">{{$asset->writeoff_qty}}</td>
                                    <td>{{$asset->asset->bun}}</td>
                                    <td>{{$asset->asset->asset_description}}</td>
                                    <td><input type="date" class="form-control" name="writtenOff[{{$asset->id}}][turnover_date]"></td>
                                    <td><input type="date" class="form-control" name="writtenOff[{{$asset->id}}][adwf_date]"></td>
                                    <td><input type="text" class="form-control" name="writtenOff[{{$asset->id}}][adwf_docno]" value="{{ old('writtenOff[$asset->id][adwf_docno]', $asset->adwf_docno ?? '') }}"></td>
                                    <td class="hidden">
                                        <input type="hidden" name="writtenOff[{{$asset->id}}][id]" value="{{$asset->id}}">
                                        <input type="hidden" name="writtenOff[{{$asset->id}}][iswrite_off]" value="1">
                                        <input type="hidden" name="writtenOff[{{$asset->id}}][writeoff_qty]" value="{{$asset->asset->qty}}">
                                        <input type="text" class="hidden " name="writtenOff[{{$asset->id}}][asset_id]" value="{{$asset->asset->id}}">
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <br>
            <br>
            <hr>
            <br>

            <div >

                <table class="table table-vcenter text-center">
                    <thead>
                        <tr>
                            <th>Staff 1</th>
                            @if($evaluation->drafter2)
                                <th></th>
                                <th>Staff 2</th>
                            @endif
                            <th></th>
                            <th>Approver 1</th>
                            
                            @if($evaluation->approved2)
                                <th></th>
                                <th>Approver 2</th>
                            @endif
                            <th></th>
                            <th>Confirmer 1</th>
                            
                            @if($evaluation->confirmed_by2)
                                <th></th>
                                <th>Confirmer 2</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        <tr >
                            <td>
                                    <select name="user1" class="form-select text-center" disabled>
                            
                                        <option value="" selected></option>
                                        @foreach ($users->filter(function ($user) {
                                            return $user->role?->name === 'User';
                                        }) as $user)
                                            <option value="{{ $user->id }}" {{$evaluation->creator?->id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                            </td>
                            @if($evaluation->drafter2)
                                <td></td>
                                <td>

                                    <select name="user2" class="form-select text-center">
                                
                                        <option value="" selected></option>
                                        @foreach ($users->filter(function ($user) {
                                            return $user->role?->name === 'User';
                                        }) as $user)
                                            <option value="{{ $user->id }}" {{$evaluation->drafter2?->id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @endif
                            <td></td>
                            <td>
                                    <select name="approver_user1" class="form-select text-center">
                            
                                        <option value="" selected></option>
                                        @foreach ($users->filter(function ($user) {
                                            return $user->role?->name === 'Admin';
                                        }) as $user)
                                            <option value="{{ $user->id }}" {{$evaluation->approved1?->id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                            </td>
                            @if($evaluation->approved2)
                                <td></td>

                                <td>
                                    <select name="approver_user2" class="form-select text-center">
                                
                                        <option value="" selected></option>
                                        @foreach ($users->filter(function ($user) {
                                            return $user->role?->name === 'Admin';
                                        }) as $user)
                                            <option value="{{ $user->id }}" {{$evaluation->approved2?->id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @endif
                            <td></td>
                            <td>
                                <select name="confirmer_user1" class="form-select text-center">
                                
                                    <option value="" selected></option>
                                    @foreach ($users->filter(function ($user) {
                                        return $user->role?->name === 'SuperAdmin';
                                    }) as $user)
                                        <option value="{{ $user->id }}" {{$evaluation->confirm1?->id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            
                            @if($evaluation->confirm2)
                                <td></td>
                                <td>
                                    <!-- <span class="{{empty($evaluation->confirmed_date2) ? '': 'text-success'}}">
                                        @if(!empty($evaluation->confirmed_date2))
                                        <i class="bi bi-check-circle-fill"></i> 
                                        @endif
                                        <b>{{$evaluation->confirm2?->name}}</b>
                                    </span> -->

                                    
                                    <select name="confirmer_user2" class="form-select text-center">
                                    
                                        <option value="" selected></option>
                                        @foreach ($users->filter(function ($user) {
                                            return $user->role?->name === 'SuperAdmin';
                                        }) as $user)
                                            <option value="{{ $user->id }}" {{$evaluation->confirm2?->id == $user->id ? "selected" : ""}} >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                
                            @endif
                        </tr>

                    </tbody>
                </table>
  
            </div>

            <div>
                
                <br>
                <hr>
                <br>
                <div class="text-center">
                    <h2>ACTIVITIES</h2>
                </div>
                <br>
                <table class="table table-vcenter text-center">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Activity</th>
                            <th>Performed By</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($evaluation->activity as $index => $act)
                            <tr class="{{(\Illuminate\Support\Str::contains($act->activity, 'Rejected')) ? 'text-danger' : 'text-success' }}">
                                <td>{{$index}}</td>
                                <td>{{$act->activity}}</td>
                                <td>{{$act->performer->name}}</td>
                                <td>{{$act->created_at}}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">


                    @if((!($evaluation->approval_status >= 50)) && (($is_approver && ($evaluation->approval_status >= 10 && $evaluation->approval_status < 20)) || ($is_confirmer && ($evaluation->approval_status >= 20 && $evaluation->approval_status < 30))))
                        <div  class="mr-3">
                            <button type="button" class="btn btn-danger mt-3 reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            Reject
                            </button>
                        </div>
                    
                        <div>
                            <button type="submit" class="btn btn-primary mt-3">{{$is_approver ? "Approve" :( $is_confirmer ? "Confirm" : "Submit")}}</button>
                        </div>
                    @elseif($evaluation->approval_status < 10 && (($evaluation->draft_by1 == $current_user->id && empty($evaluation->draft_date1)) || ($evaluation->draft_by2 == $current_user->id && empty($evaluation->draft_date2))))

                        <div>
                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                        </div>
                    @endif

                
                


            </div>
        </div>
      </form>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Enter Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="number" id="modalQty" class="form-control" min="1">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelQty" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirmQty">Confirm</button>
            </div>

        </div>
    </div>
</div>


<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{  route('reject-evaluation',$evaluation->id) }}" method="POST">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <textarea name="reason" class="form-control"  data-toggle="autosize" name="example-textarea-input" rows="5" placeholder="Reason"> </textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" >Confirm</button>
                </div>
            </form>

        </div>
    </div>
</div>




<script>

 


    const form = document.getElementById("myForm");


    if (form) {


        const elements = form.querySelectorAll("input, select, textarea, button");

        elements.forEach(el => {
            if (el.type !== "submit" && el.type !== "hidden"  &&
            !el.classList.contains("reject")) {
                el.disabled = true;
            }
        });

        document.querySelectorAll("#myForm input.move-check").forEach(el => {
            if (!el.name) return; // skip if no name

            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = el.name;
            hidden.value = el.checked ? "1" : "0";

            el.after(hidden);
            el.disabled = true;
        });
    }


  document.addEventListener("DOMContentLoaded", function () {

    let table1Body = document.querySelector("#table1 tbody");
    let table2Body = document.querySelector("#table2 tbody");

    let selectedRow = null;
    let selectedCheckbox = null;
    let selectedInfo = null;
    let selectedId = null;

    const modalEl = document.getElementById('exampleModal');
    const modal = new bootstrap.Modal(modalEl);

    //checkbox listen event
    document.addEventListener("change", function (e) {

        if (!e.target.classList.contains("move-check")) return;

        let checkbox = e.target;
        let row = checkbox.closest("tr");
        let id = row.dataset.id;
        let info = JSON.parse(row.dataset.info);


        if (row.closest("#table1")) {

            if (!checkbox.checked) return;

            if (info.qty > 1) {

                // store for modal use
                selectedRow = row;
                selectedCheckbox = checkbox;
                selectedInfo = info;
                selectedId = id;

                document.getElementById('modalQty').value = 1;
                document.getElementById('modalQty').max = info.qty;

                modal.show();

            } else {
                moveToTable2(row, checkbox, id, info, table1Body, table2Body, info.qty);
            }
        }

        else if (row.closest("#table2")) {

            if (checkbox.checked) return;

            moveToTable1(row, checkbox, id, info, table1Body, table2Body);
        }

    });

    document.getElementById('cancelQty').addEventListener('click', function () {
        selectedCheckbox.checked = false;

    });

 
    document.getElementById('confirmQty').addEventListener('click', function () {

      let qtyInput = parseInt(document.getElementById('modalQty').value);

      if (!qtyInput || qtyInput < 1) return;

      let currentQty = parseInt(selectedInfo.qty);

      // 🚨 validate
      if (qtyInput > currentQty) {
          alert("Cannot move more than available qty");
          return;
      }

      // get remaining
      let remainingQty = currentQty - qtyInput;

      console.log("here",currentQty,qtyInput,remainingQty)
      // update original data
      selectedInfo.qty = remainingQty;
      selectedRow.dataset.info = JSON.stringify(selectedInfo);


    
      moveToTable2(
          selectedRow,
          selectedCheckbox,
          selectedId,
          { ...selectedInfo, qty: qtyInput }, // moved qty ONLY
          table1Body,
          table2Body,
          qtyInput
      );


      if (remainingQty <= 0) {
          selectedRow.remove();
      } else {
          selectedCheckbox.checked = false;
      }

      modal.hide();
  });


    document.getElementById('modalQty').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

  });


function moveToTable2(row, checkbox, id, info, table1Body, table2Body, qtyToMove) {

    let existingRow = table2Body.querySelector(`tr[data-id="${id}"]`);

    let qtyEl = row.querySelector('.qty');
    let currentQty = qtyEl ? parseInt(qtyEl.textContent) : parseInt(info.qty);


    if (currentQty <= qtyToMove) {
        row.remove();
    }

    else {
        let remaining = currentQty - qtyToMove;

        qtyEl.textContent = remaining;

        // update dataset
        let rowInfo = JSON.parse(row.dataset.info);
        rowInfo.qty = remaining;
        row.dataset.info = JSON.stringify(rowInfo);

        checkbox.checked = false;
    }

    if(existingRow){
        qtyEl = existingRow.querySelector('.qty');

        console.log(qtyEl);
        let existingQty = parseInt(qtyEl.textContent);
        let newQty = existingQty + parseInt(info.qty);

        qtyEl.textContent = newQty;
        

        // update dataset
        let existingInfo = JSON.parse(existingRow.dataset.info);
        existingInfo.qty = newQty;
        existingRow.dataset.info = JSON.stringify(existingInfo);
    }else{
        let newRow = document.createElement("tr");

        newRow.setAttribute("data-id", id);
        newRow.setAttribute("data-info", JSON.stringify(info));

        newRow.innerHTML = `
            <td><input class="form-check-input move-check" type="checkbox" checked></td>
            <td>${info.asset_number}</td>
            <td><input type="text" class="form-control" name="writtenOff[${id}][schedule_date]"></td>
            <td class="text-muted qty">${qtyToMove}</td>
            <td>${info.bun}</td>
            <td>${info.asset_description}</td>
            <td><input type="date" class="form-control" name="writtenOff[${id}][turnover_date]"></td>
            <td><input type="date" class="form-control" name="writtenOff[${id}][adwf_date]"></td>
            <td><input type="text" class="form-control" name="writtenOff[${id}][adwf_docno]"></td>
            <td class="hidden">
                <input type="hidden" name="writtenOff[${id}][id]" value="${id}">
                <input type="hidden" name="writtenOff[${id}][iswrite_off]" value="1">
                <input type="hidden" name="writtenOff[${id}][writeoff_qty]" value="${qtyToMove}">
                <input type="hidden" name="writtenOff[${id}][asset_id]" value="${info.id}">
            </td>
        `;

        // table2Body.appendChild(newRow);
        const rows = Array.from(table2Body.querySelectorAll("tr"));
        const newId = Number(id);

        // find correct position
        const index = rows.findIndex(row => Number(row.dataset.id) > newId);
        console.log('index',index);
        if (index === -1) {
            table2Body.appendChild(newRow); // largest → end
        } else {
            table2Body.insertBefore(newRow, rows[index]); // insert in order
        }
    }
}
  // MOVE BACK TO TABLE 1
  function moveToTable1(row, checkbox, id, info, table1Body, table2Body) {

    row.remove();

    
    const statuses = @json($statuses);
    let existingRow = table1Body.querySelector(`tr[data-id="${id}"]`);

    if (existingRow) {

        
        let qtyEl = existingRow.querySelector('.qty');
        let existingQty = parseInt(qtyEl.textContent);
        let newQty = existingQty + parseInt(info.qty);

        qtyEl.textContent = newQty;
        

        // update dataset
        let existingInfo = JSON.parse(existingRow.dataset.info);
        existingInfo.qty = newQty;
        existingRow.dataset.info = JSON.stringify(existingInfo);

    } else {

        
        let newRow = document.createElement("tr");

        newRow.setAttribute("data-id", id);
        newRow.setAttribute("data-info", JSON.stringify(info));
        const options = statuses.map(status => 
            `<option value="${status.id}" ${info.asset_status == status.id ? "selected" : ""}>${status.name}</option>`
        ).join('');
        console.log('info',info);

        newRow.innerHTML = `
            <td><input class="form-check-input move-check" type="checkbox"></td>
            <td class="text-muted">${info.asset_number}</td>
            <td class="text-muted">${formatDate(info.capitalization_date) ?? ''}</td>
            <td class="text-muted qty">${info.qty}</td>
            <td class="text-muted">${info.bun ?? ''}</td>
            <td class="text-muted">${info.asset_description ?? ''}</td>
            <td class="text-muted">${info.other_identifier ?? ''}</td>
            <td>
                <select name="remainingAsset[${id}][asset_status]" class="form-select" >
                    ${options}
                </select></td>
            <td><input type="text" class="form-control" name="remainingAsset[${id}][corrective_action_taken]"></td>
            <td class="hidden">
                <input type="hidden" name="remainingAsset[${id}][id]" value="${id}">
                <input type="hidden" name="remainingAsset[${id}][iswrite_off]" value="0">
            </td>
        `;

        // table1Body.appendChild(newRow);
        const rows = Array.from(table1Body.querySelectorAll("tr"));
        const newId = Number(id);

        // find correct position
        const index = rows.findIndex(row => Number(row.dataset.id) > newId);
        console.log('index',index);
        if (index === -1) {
            table1Body.appendChild(newRow); // largest → end
        } else {
            table1Body.insertBefore(newRow, rows[index]); 
        }
    }
}

function formatDate(str) {
    const safeDate = new Date("2004-05-04T00:00:00Z");

    return safeDate.toISOString().slice(0, 19).replace('T', ' ');
}
</script>


</x-app-layout>