<x-app-layout>
  <div class="col-md-12">
    

    <div class="card">
        @php   
            $approval_statuses = [
                0 => 'Pending',
                10 => 'For Approval',
                20 => 'For Confirmation',
                30 => 'Confirmed',
                50 => 'Rejected'
            ];

            
            $approval_status = $approval_statuses[floor($evaluation->approval_status / 10) * 10];

            $current_user = auth()->user();

            if($is_owner){
                $url = route('updateevaluation',$evaluation->id);
            }
                    
            elseif(isset($evaluation->draft_by2) && empty($evaluation->draft_date2)){

                $url = route('check-evaluation',$evaluation->id);
            }
            elseif($is_approver){
                $url = route('approve-evaluation',$evaluation->id);
            }
            elseif($is_confirmer){
                $url = route('confirm-evaluation',$evaluation->id);
            }



        @endphp
        
        <div id="printArea">
            <div class="ribbon ribbon-top-right
                @if($approval_status == 'For Confirmation') ribbon-approved
                @elseif($approval_status == 'Pending') ribbon-pending
                @elseif($approval_status == 'Rejected') ribbon-rejected
                @elseif($approval_status == 'Confirmed') ribbon-confirmed
                @else ribbon-forApproval
                @endif

            ">
                <span>{{ $approval_status }}</span>
            </div>
            @if($is_owner)
                <form id="{{$evaluation->approval_status > 1 ? 'myForm' : ($can_edit ? '' : 'myForm')}}" action="{{  route('updateevaluation',$evaluation->id) }}" method="POST">
                    
            @elseif(isset($evaluation->draft_by2) && empty($evaluation->draft_date2))
                <form id="myForm" action="{{  route('check-evaluation',$evaluation->id) }}" method="POST">
            @elseif($is_approver)
                <form id="{{$can_edit ? '' : 'myForm'}}" action="{{  route('approve-evaluation',$evaluation->id) }}" method="POST">
            @elseif($is_confirmer)
                <form id="{{$can_edit ? '' : 'myForm'}}" action="{{  route('confirm-evaluation',$evaluation->id) }}" method="POST">
            @endif



            @csrf
            <div class="card-header">
            <div>
                    <h1 style="font-size: 1.5rem;">Department: <b>{{$evaluation->department->name}} {{$evaluation->quarter}} {{$evaluation->year}} </b> (<b>{{$asset_count}}</b>) </h1>
                    
            </div>
                <div class="card-options">
                <b>
                    {{-- {{$approval_status}}{{$can_edit ? ' ' : 'Evaluation'}} --}}  
                    </b>
                </div>
                
            </div>
            <div class="card-body">   
                <div class="table-responsive">
                    <div>
                    <h5><b>I. Assets On Inventory: </b></h5><br>
                    </div>
                    <div >
                        <table class="table card-table table-vcenter table-hover table-striped table-sm " id="table1">
                            <thead>
                                <tr>
                                    @if($is_owner && $evaluation->approval_status < 1 )  
                                        <th></th>
                                    @endif
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
                                @php
                                    $asset->asset->asset_status = $asset->asset_status;
                                    $asset->asset->eval_detail_qty = $asset->qty;
                                @endphp
                                <tr data-id="{{ $asset->id }}" data-info="{{$asset->asset}}" >
                                    @if ($is_owner && $evaluation->approval_status < 1 )
                                        <td>
                                            <input class="form-check-input move-check" type="checkbox" id="check{{$asset->id}}">
                                        </td>    
                                    @endif                                   
                                    
                                    <td class="text-muted asset_no">{{$asset->asset->asset_number ?? ""}}</td>
                                    <td class="text-muted">{{$asset->asset->capitalization_date->format('M d, Y')}}</td>
                                    <td class="text-muted qty">{{$asset->qty}}</td>
                                    <td class="text-muted bum">{{$asset->asset->bun}}</td>
                                    <td class="text-muted asset_description">{{$asset->asset->asset_description}}</td>
                                    <td class="text-muted ">
                                        {{$asset->asset->other_identifier}}
                                    </td>
                                    <td class="text-muted">
                                        <a
                                            @if(($evaluation->approval_status == 0 && ($evaluation->draft_by1 == $current_user->id && empty($evaluation->draft_date1))))
                                                data-bs-toggle="modal" data-bs-target="#splitStatus" data-url="{{route('split-status',$asset->id)}}" data-assetName="{{$asset->asset->asset_description}}" data-maxQty="{{$asset->qty}}"
                                            @endif
                                        >
                                            {{ $statuses->find($asset->asset_status)?->name }}
                                        </a>
                                    </td>
                                    <td class="text-muted">
                                        @if ($is_owner  && $evaluation->approval_status < 1 )
                                            <input type="text" class="form-control" name="remainingAsset[{{$asset->id}}][corrective_actiion_taken]"  value="{{ old('remainingAsset[$asset->id][corrective_actiion_taken]', $asset->corrective_actiion_taken ?? '') }}">

                                        @else
                                            {{ old('remainingAsset[$asset->id][corrective_actiion_taken]', $asset->corrective_actiion_taken ?? '') }}

                                        @endif
                                    </td>                                
                                    <td class="hidden">
                                        <input type="text" class="hidden " name="remainingAsset[{{$asset->id}}][id]" value="{{$asset->id}}">
                                        <input type="hidden" name="remainingAsset[{{$asset->id}}][iswrite_off]" value="0">
                                        <input type="text" class="hidden " name="remainingAsset[{{$asset->id}}][asset_id]" value="{{$asset->asset->id}}">
                                        <input type="text" class="hidden " name="remainingAsset[{{$asset->id}}][asset_status]" value="{{$asset->asset_status}}">
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
                        <table class="table card-table table-vcenter table-striped  table-sm" id="table2">
                            <thead>
                                <tr>
                                    @if($is_owner && $evaluation->approval_status < 1 )  
                                        <th></th>
                                    @endif
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
                                        @if ($is_owner  && $evaluation->approval_status < 1 )                                                                            
                                        <td>
                                            <input class="form-check-input move-check" type="checkbox" checked>
                                        </td>
                                        @endif
                                        <td>
                                            {{ $asset->asset->asset_number }}
                                        </td>
                                        <td>
                                            
                                            @if($is_owner && $evaluation->approval_status < 1 )
                                                <input type="text" class="form-control" value="{{ old('writtenOff[$asset->id][reason_for_writeoff]', $asset->reason_for_writeoff ?? '') }}" name="writtenOff[{{$asset->id}}][reason_for_writeoff]">
                                            @else
                                                {{ $asset->reason_for_writeoff   }}
                                            @endif                                        
                                        </td>
                                        <td class="text-muted qty">
                                            {{$asset->writeoff_qty}}
                                        </td>
                                        <td>{{$asset->asset->bun}}</td>
                                        <td>{{$asset->asset->asset_description}}</td>                                    
                                        <td>
                                            @if($is_owner && $evaluation->approval_status < 1 )
                                                <input type="date" class="form-control" name="writtenOff[{{$asset->id}}][turnover_date]">
                                            @else
                                            {{  \Carbon\Carbon::parse($asset->turnover_date)->format('M d, Y') }} 
                                            @endif                                        
                                        </td>
                                        <td>
                                            @if($is_owner && $evaluation->approval_status < 1 )
                                            
                                                <input type="date" class="form-control" name="writtenOff[{{$asset->id}}][adwf_date]">  
                                            @else
                                                
                                            {{ \Carbon\Carbon::parse($asset->adwf_date)->format('M d, Y') }}
                                            @endif                                          
                                        </td>
                                        <td>
                                            @if($is_owner && $evaluation->approval_status < 1 )
                                            <input type="text" class="form-control" name="writtenOff[{{$asset->id}}][adwf_docno]"
                                                    value="{{ old('writtenOff[$asset->id][adwf_docno]', $asset->adwf_docno ?? '') }}">
                                            @else
                                                
                                                {{ $asset->adwf_docno }}
                                            @endif                                       
                                        </td>
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
                <hr>
                <br>

                <div class="row">
                    <div>
                    <h5><b>Attachments</b></h5><br>
                    </div>
                    <div class="{{ (!($evaluation->details_writtenOff->isEmpty()) && $notdraft) ? 'col-7' : 'col-12' }}">
                        
                        @if(strtolower($approval_status) == 'pending' )
                            <div id="dropZone" style="border:2px dashed #999;padding:40px;">
                                Drag files here or click to upload
                            </div>
                        @endif

                        <table id="preview" border="1" class="table card-table table-vcenter table-striped  table-sm">
                            <thead>
                                <tr>
                                    <th>Filename</th>
                                    @if(!$notdraft)
                                        <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>

                        <input type="file" id="files" multiple hidden>
                    </div>
                    @if( (!($evaluation->details_writtenOff->isEmpty()) && $notdraft))
                        <div class="col-5" >
                            <div class="row text-center " >
                                    <div style="position: relative; display: inline-block;">
                                        @if ($evaluation->review_date )
                                            <img src="{{ asset('storage/signatures/almario.png') }}" 
                                                    alt="Signature" 
                                                    class="sig-img"
                                                    style="width: 150px; z-index: 1; position: relative;" />
                                                <label style="
                                                    position: absolute; 
                                                    top: 50%; 
                                                    left: 50%; 
                                                    transform: translate(-50%, -50%); 
                                                    z-index: 2; 
                                                    white-space: nowrap;
                                                    font-weight: bold;
                                                    pointer-events: none;
                                                    color: rgba(0, 0, 0, 0.7);
                                                ">
                                                {{ $evaluation->reviewer?->name  }}
                                                </label>
                                                <small class="d-block">{{ $evaluation->review_date  }}</small>
                                        @else
                                            <label>Accounting</label>
                                        @endif
                                </div>
                            </div>
                            <div class="row">
                            @if(!($evaluation->review_date) && $is_accounting && !($evaluation->details_writtenOff->isEmpty()) && ($evaluation->approval_status >= 20 && $evaluation->approval_status < 30))
                                <div  class="col-6 align-right">
                                </div>
                            
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger mt-3 reason"  data-url="{{route('reject-evaluation',$evaluation->id)}}"  data-bs-toggle="modal" data-bs-target="#reasonModal">
                                    Reject
                                    </button>
                                    <button type="button" class="btn btn-primary mt-3 reason" data-bs-toggle="modal"  data-url="{{route('approve-evaluation',$evaluation->id)}}" data-reviewer="{{$is_accounting}}"  data-bs-target="#reasonModal">Approve</button>
                                </div>
                            @endif
                            </div>
                        </div>
                    @endif
                    
                </div>

                <div>
                <br>
                <hr>
                <br>

                <div>
                    <h5><b>Status</b></h5><br>
                    </div>
                    <table class="table table-vcenter text-center uniform-table table-sm">
                        <thead>
                            <tr>
                                <th>Submitted by:</th>
                                @if($evaluation->drafter2)
                                    <th class="spacer"></th>
                                    <th></th>
                                @endif
                            
                                <th class="spacer"></th>
                                <th>Confirmed by:</th>
                                
                                @if($evaluation->approved2 && $evaluation->approved1)
                                    <th class="spacer"></th>
                                    <th></th>
                                @endif

                                <th class="spacer"></th>
                                <th>Verified by:</th>
                                @if($evaluation->confirmed_by2 && $evaluation->confirmed_by1)
                                    <th class="spacer"></th>
                                    <th></th>
                                @endif
                                

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="position: relative; height: 100px; vertical-align: middle;">
                                    <div style="position: relative; display: inline-block;">
                                        @if( $evaluation->approval_status >= 1  || $evaluation->approval_status >= 50)
                                            <img src="{{ @asset('storage/signatures/RUIZ01.png ') }} " 
                                            alt="Signature" 
                                            class="sig-img" 
                                            style="width: 150px; z-index: 1; position: relative;">
                                            <label style="
                                                position: absolute;
                                                top: 50%;
                                                left: 50%;
                                                transform: translate(-50%, -50%);
                                                z-index: 2;
                                                white-space: nowrap;
                                                font-weight: bold;
                                                pointer-events: none;
                                                color: rgba(0, 0, 0, 0.7);
                                            ">
                                                {{ $evaluation->creator->name }}
                                            </label>
                                            <small class="d-block">{{ $evaluation->draft_date1 }}</small>
                                        @else
                                            <label>{{ $evaluation->creator?->name }}</label>
                                        @endif
                                    </div>
                                </td>

                                @if($evaluation->drafter2)
                                    <td class="spacer"></td>
                                    <td>
                                    
                                        <div style="position: relative; display: inline-block;">
                                            @if(!$notdraft && $is_owner) 
                                                <select name="user2" class="form-select text-center">
                                                    <option value="" selected></option>
                                                    @foreach ($users->filter(fn($u) => strtolower($u->role?->name) == 'user') && $u->deptid == $evaluation->department_id as $user)
                                                        <option value="{{ $user->id }}" {{$evaluation->drafter2?->id == $user->id ? "selected" : ""}}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif ($evaluation->approval_status >= 10  || $evaluation->approval_status >= 50)
                                                <img src="{{ asset('storage/signatures/almario.png') }}" 
                                                        alt="Signature" 
                                                        class="sig-img"
                                                        style="width: 150px; z-index: 1; position: relative;" />
                                                    <label style="
                                                        position: absolute; 
                                                        top: 50%; 
                                                        left: 50%; 
                                                        transform: translate(-50%, -50%); 
                                                        z-index: 2; 
                                                        white-space: nowrap;
                                                        font-weight: bold;
                                                        pointer-events: none;
                                                        color: rgba(0, 0, 0, 0.7);
                                                    ">
                                                    {{ $evaluation->drafter2->name  }}
                                                    </label>
                                                    <small class="d-block">{{ $evaluation->draft_date2  }}</small>
                                            @else
                                                <label>{{ $evaluation->drafter2?->name }}</label>
                                            @endif
                                        </div>
                                    </td>
                                @endif

                                <td class="spacer"></td>

                                    
                                <td style="vertical-align: middle; position: relative; height: 100px;">
                                    
                                    <div style="position: relative; display: inline-block;">
                                        @if(!$notdraft && $is_owner)
                                            <select name="approver_user1" class="form-select text-center" required>
                                                <option value="" selected></option>
                                                @foreach ($users->filter(fn($u) => strtolower($u->role?->name) == 'approver' && $u->deptid == $evaluation->department_id) as $user)
                                                    <option value="{{ $user->id }}" {{$evaluation->approved1?->id == $user->id ? "selected" : ""}}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif (($evaluation->approval_status >=20 ) && $evaluation->approval_status != 50)
                                            <img src="{{ asset('storage/signatures/almario.png') }}" 
                                                    alt="Signature" 
                                                    class="sig-img"
                                                    style="width: 150px; z-index: 1; position: relative;" />
                                                <label style="
                                                    position: absolute; 
                                                    top: 50%; 
                                                    left: 50%; 
                                                    transform: translate(-50%, -50%); 
                                                    z-index: 2; 
                                                    white-space: nowrap;
                                                    font-weight: bold;
                                                    pointer-events: none;
                                                    color: rgba(0, 0, 0, 0.7);
                                                ">
                                                {{ $evaluation->approved1->name  }}
                                                </label>
                                                <small class="d-block">{{ $evaluation->approved_date1  }}</small>
                                        @else
                                            <label>{{ $evaluation->approved1?->name }}</label>
                                        @endif
                                    </div>
                                </td>

                                @if($evaluation->approved2)
                                    <td class="spacer"></td>
                                    <td>
                                        
                                        <div style="position: relative; display: inline-block;">
                                            @if(!$notdraft && $is_owner)
                                                <select name="approver_user2" class="form-select text-center">
                                                    <option value="" selected></option>
                                                    @foreach ($users->filter(fn($u) => strtolower($u->role?->name) == 'approver') as $user)
                                                        <option value="{{ $user->id }}" {{$evaluation->approved2?->id == $user->id ? "selected" : ""}}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif (($evaluation->approval_status >=20 ) || $evaluation->approval_status >= 50)
                                                
                                                <div style="position: relative; display: inline-block;">
                                                    <img src="{{ asset('storage/signatures/almario.png') }}" 
                                                        alt="Signature" 
                                                        class="sig-img"
                                                        style="width: 150px; z-index: 1; position: relative;" />
                                                    <label style="
                                                        position: absolute; 
                                                        top: 50%; 
                                                        left: 50%; 
                                                        transform: translate(-50%, -50%); 
                                                        z-index: 3; 
                                                        white-space: nowrap;
                                                        font-weight: bold;
                                                        pointer-events: none;
                                                        color: rgba(0, 0, 0, 0.7);
                                                    ">
                                                    {{ $evaluation->approved2->name  }}
                                                    </label>
                                                    <small class="d-block">{{ $evaluation->approved_date2  }}</small>
                                                </div>
                                            @else
                                                <label>{{ $evaluation->approved2?->name }}</label>
                                            @endif
                                        </div>
                                    </td>
                                @endif


                                @if($evaluation->confirm1)
                                    <td class="spacer"></td>

                                    <td style="vertical-align: middle;height: 100px;">
                                        <div style="position: relative; display: inline-block;">
                                            @if(!$notdraft && $is_owner)
                                                <select name="confirmer_user1" class="form-select text-center">
                                                    <option value="" selected></option>
                                                    @foreach ($users->filter(fn($u) => strtolower($u->role?->name) == 'auditor') as $user)
                                                        <option value="{{ $user->id }}" {{$evaluation->confirm1?->id == $user->id ? "selected" : ""}} {{$evaluation->confirm1}} {{ $user->id}}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @elseif ($evaluation->confirmed_date1  && $evaluation->approval_status != 50)
                                                <img src="{{ asset('storage/signatures/mortola02.png') }}" 
                                                        alt="Signature" 
                                                        class="sig-img"
                                                        style="width: 150px; z-index: 1; position: relative;" />
                                                    <label style="
                                                        position: absolute; 
                                                        top: 50%; 
                                                        left: 50%; 
                                                        transform: translate(-50%, -50%); 
                                                        z-index: 2; 
                                                        white-space: nowrap;
                                                        font-weight: bold;
                                                        pointer-events: none;
                                                        color: rgba(0, 0, 0, 0.7);
                                                    ">
                                                    {{ $evaluation->confirm1?->name  }}
                                                    </label>
                                                    <small class="d-block">{{ $evaluation->confirmed_date1  }}</small> 
                                            @else
                                                <label>{{ $evaluation->confirm1?->name }}</label>
                                            @endif
                                        </div>
                                    </td>
                                    
                                @endif

                                @if($evaluation->confirm2)
                                    <td class="spacer"></td>
                                    <td>
                                        
                                        <div style="position: relative; display: inline-block;">
                                            @if(!$notdraft && $is_owner)
                                                <select name="confirmer_user2" class="form-select text-center">
                                                    <option value="" selected></option>
                                                    @foreach ($users->filter(fn($u) => strtolower($u->role?->name) == 'auditor') as $user)
                                                        <option value="{{ $user->id }}" {{$evaluation->confirm2?->id == $user->id ? "selected" : ""}}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            
                                            @elseif ($evaluation->approval_status >=30 || $evaluation->approval_status > 50)
                                                <img src="{{ asset('storage/signatures/almario.png') }}" 
                                                        alt="Signature" 
                                                        class="sig-img"
                                                        style="width: 150px; z-index: 1; position: relative;" />
                                                    <label style="
                                                        position: absolute; 
                                                        top: 50%; 
                                                        left: 50%; 
                                                        transform: translate(-50%, -50%); 
                                                        z-index: 2; 
                                                        font-weight: bold;
                                                        pointer-events: none;
                                                        color: rgba(0, 0, 0, 0.7);
                                                    ">
                                                    {{ $evaluation->confirm2->name  }}
                                                    </label>
                                                    <small class="d-block">{{ $evaluation->confirmed_date2  }}</small>
                                            @else
                                                <label>{{ $evaluation->confirm2?->name }}</label>
                                            @endif
                                        </div>
                                    </td>
                                @endif

                            </tr>
                        </tbody>

                    </table>
                    
                </div>

                
                <div class="d-flex justify-content-end mt-3">

                        @if(
                        (!($evaluation->approval_status >= 50)) 
                    && 
                        (
                                (
                                        $is_approver 
                                    && 
                                        ($evaluation->approval_status >= 10 && $evaluation->approval_status < 20)
                                )
                            || 
                                (
                                        $is_confirmer
                                    && 
                                        ($evaluation->approval_status >= 20 && $evaluation->approval_status < 30)
                                        
                                    &&
                                        (
                                            (
                                                    !($evaluation->details_writtenOff->isEmpty()) 
                                                && 
                                                    ($evaluation->review_date)
                                            ) 
                                            ||
                                            (
                                                ($evaluation->details_writtenOff->isEmpty()) 
                                            ) 
                                        )
                                )
                            || 
                                ($evaluation->approval_status == 1 && ($evaluation->draft_by2 == $current_user->id))
                        )
                )
                            <div  class="mr-3">
                                <button type="button" class="btn btn-danger mt-3 reason" data-url="{{route('reject-evaluation',$evaluation->id)}}" data-bs-toggle="modal" data-bs-target="#reasonModal">
                                Reject
                                </button>
                            </div>
                        
                            <div>
                                <button type="button" class="btn btn-primary mt-3 reason" data-bs-toggle="modal" data-url="{{$url}}"  data-bs-target="#reasonModal">{{$is_approver ? "Approve" :( $is_confirmer ? "Confirm" : "Submit")}}</button>
                            </div>
                        @elseif(($evaluation->approval_status == 0 && ($evaluation->draft_by1 == $current_user->id && empty($evaluation->draft_date1))))

                            <div>
                                <button type="submit" class="btn btn-primary mt-3">Submit</button>
                            </div>

                        @elseif( $evaluation->approval_status == 50 && $evaluation->draft_by1 == $current_user->id)
                            <div>
                                <button type="button" onclick="window.location.href='{{  route('edit-evaluation',$evaluation->id) }}'" class="btn btn-primary mt-3">Edit</button>
                            </div>
                        @endif

                    
                    


                </div>
            </div>
        </div>
            <div>
                
                <br>
                <hr>
                <br>
                <div class="text-center">
                    <h2><b>ACTIVITIES</b></h2>
                </div>
                <br>
                <table class="table table-vcenter table-sm">
                    <thead>
                        <tr>
                            
                            <th>Date</th>
                            <th>Performed By</th>
                            <th>Activity</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody >

                        @foreach($evaluation->activity as $index => $act)
                            <tr class="{{(\Illuminate\Support\Str::contains($act->activity, 'Rejected')) ? 'text-danger' : 'text-success' }}">
                                <td style="width: 10%;">{{$act->created_at}}</td>
                                <td style="width: 10%;">{{$act->performer->name}}</td>
                                <td style="width: 40%;">{{$act->activity}}</td>
                                <td style="width: 40%;">{{$act->reason}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <button type="button" class="btn btn-close btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>

            </div>

            <div class="modal-body">
                <input type="number" id="modalQty" class="form-control" min="1">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelQty" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="confirmQty">Confirm</button>
            </div>

        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="reasonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="approveForm"  method="POST">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reason</h5>
                    <button type="button" class="btn btn-close btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
                </div>

                <div class="modal-body">
                    <textarea name="reason" id="reason" class="form-control" data-toggle="autosize" rows="5" placeholder="Reason" required></textarea>
                </div>

                <div id="reasonHiddenDiv" >

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" >Confirm</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- split status modal -->
<div class="modal fade" id="splitStatus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title"></h5>
            <button type="button" class="btn btn-close btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
      </div>
      <div class="modal-body">
            <form id="splitStatusForm"   method="POST">
                @csrf
                <div class="mb-3">
                    Remaining: <span id="remaining"></span>
                </div>
                @foreach ($statuses as $status)
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-7">
                            <label for="{{ strtolower(str_replace(' ', '-', $status->name))}}" class="form-label mb-0">
                                {{$status->name}}
                            </label>
                        </div>

                        <div class="col-md-5">
                            <input
                                type="number"
                                id="{{ strtolower(str_replace(' ', '-', $status->name))}}"
                                name="status[{{$status->id}}]"
                                class="form-control"
                            >
                        </div>
                    </div>
                    <hr>
                    <br>
                @endforeach
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" >Confirm</button>
                </div>
            </form>
       
      </div>
    </div>
  </div>
</div>

<!-- Show Document -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Document View</h5>
                <button type="button" class="btn btn-close btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>

            <div class="modal-body">
                <div class="modal-body d-flex justify-content-center align-items-center" style="min-height: 600px;">
                    <img id="documentImage"
                        class="img-fluid d-none"
                        style="max-width: 100%; max-height: 600px;">
                        
                    <iframe id="documentFrame"
                            class="d-none"
                            width="100%"
                            height="600"
                            style="border: none;">
                    </iframe>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelQty" data-bs-dismiss="modal">Cancel</button>
            </div>

        </div>
    </div>
</div>




<script>


    document.getElementById('reasonModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const form = this.querySelector('form');
        const reviewer = button.getAttribute('data-reviewer');
        if(reviewer){
            const hiddenDiv = document.getElementById("reasonHiddenDiv");
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "reviewer";
            input.value = reviewer;

            hiddenDiv.appendChild(input);
        }

        form.setAttribute('action', url);
    });







    let maxTotal = 0;

    document.getElementById('splitStatus').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const url = button.getAttribute('data-url');
        const maxQty = parseInt(button.getAttribute('data-maxQty')) || 0;
        const assetName = button.getAttribute('data-assetName');
        const form = this.querySelector('form');
        form.setAttribute('action', url);

        maxTotal = maxQty;

        const remaining = document.getElementById('remaining');
        remaining.textContent = maxQty;

        const modalTitle = document.getElementById('modal-title');
        modalTitle.textContent = assetName;

        // Disable if remaining is not 0
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = parseInt(remaining.textContent) !== 0;
        

    
    });

    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('input[name^="status["]');
        const remaining = document.getElementById('remaining');
        const submitBtn = document.querySelector('#splitStatus button[type="submit"]');

        function updateTotals() {
            let total = 0;

            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });

            const remainingQty = Math.max(0, maxTotal - total);
            remaining.textContent = remainingQty;

            // Enable only when remaining is 0
            submitBtn.disabled = remainingQty !== 0;
        }

        inputs.forEach(input => {
            input.addEventListener('input', function () {
                let total = 0;

                inputs.forEach(i => {
                    total += parseInt(i.value) || 0;
                });

                if (total > maxTotal) {
                    const currentValue = parseInt(this.value) || 0;
                    const excess = total - maxTotal;

                    this.value = Math.max(0, currentValue - excess);

                    alert(`Total quantity cannot exceed ${maxTotal}.`);
                }

                updateTotals();
            });
        });

        // Initial state
        updateTotals();



        // $('#approveForm').validate({
        //     rules: {
        //         reason: {
        //             required: true
        //         }
        //     },
        //     submitHandler: function (form) {
        //         const value = $('#reason').val().trim();

        //         if (value === '' || /^\.+$/.test(value)) {
        //             alert('Reason is required.');
        //             return;
        //         }

        //         form.submit();
        //     }
        // });

        $('#approveForm').on('submit', function (e) {
            const reason = $('#reason').val();

            if (/^[.\s]*$/.test(reason)) {
                e.preventDefault();
                alert('Reason is required.');
                $('#reason').focus();
            }
        });



    });



    //disable buttons and inputs
    const form = document.getElementById("myForm");
    if (form) {


        const elements = form.querySelectorAll("input, select, textarea, button");

        elements.forEach(el => {
            if (el.type !== "submit" && el.type !== "hidden"  && el.type !== "button" && el.type !== "file" && !el.classList.contains("reason")) {
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
            console.log('info',info);

            if (row.closest("#table1")) {

                if (!checkbox.checked) return;

                if (info.eval_detail_qty > 1) {

                    // store for modal use
                    selectedRow = row;
                    selectedCheckbox = checkbox;
                    selectedInfo = info;
                    selectedId = id;

                    document.getElementById('modalQty').value = 1;
                    document.getElementById('modalQty').max = info.eval_detail_qty;

                    modal.show();

                } else {
                    moveToTable2(row, checkbox, id, info, table1Body, table2Body, info.eval_detail_qty);
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

            // ðŸš¨ validate
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
                <td>
                    <input class="form-check-input move-check" type="checkbox" checked>
                </td>
                <td>
                    ${info.asset_number}
                </td>            
                <td>
                    <input type="text" class="form-control" name="writtenOff[${id}][reason_for_writeoff]" required='required'>            
                </td>
                <td class="text-muted qty">
                    ${qtyToMove}
                </td>
                <td>
                    ${info.bun}
                </td>
                <td>${info.asset_description}</td>
                <td><input type="date" class="form-control" name="writtenOff[${id}][turnover_date]"  required='required'></td>
                <td><input type="date" class="form-control" name="writtenOff[${id}][adwf_date]"   required='required'></td>            
                <td><input type="text" class="form-control" name="writtenOff[${id}][adwf_docno]"  required='required'></td>
                <td class="hidden">
                    <input type="hidden" name="writtenOff[${id}][id]" value="${id}">
                    <input type="hidden" name="writtenOff[${id}][iswrite_off]" value="1">
                    <input type="hidden" name="writtenOff[${id}][writeoff_qty]" value="${qtyToMove}">
                    <input type="hidden" name="writtenOff[${id}][asset_id]" value="${info.id}">
                    <input type="hidden" name="writtenOff[${id}][asset_status]" value="${info.asset_status}">
                </td>
            `;

            // table2Body.appendChild(newRow);
            const rows = Array.from(table2Body.querySelectorAll("tr"));
            const newId = Number(id);

            // find correct position
            const index = rows.findIndex(row => Number(row.dataset.id) > newId);
            console.log('index',index);
            if (index === -1) {
                table2Body.appendChild(newRow); // largest â†’ end
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
            let newQty = existingQty + parseInt(info.eval_detail_qty);

            qtyEl.textContent = newQty;
            

            // update dataset
            let existingInfo = JSON.parse(existingRow.dataset.info);
            existingInfo.qty = newQty;
            existingRow.dataset.info = JSON.stringify(existingInfo);

        } else {

            
            let newRow = document.createElement("tr");

            newRow.setAttribute("data-id", id);
            newRow.setAttribute("data-info", JSON.stringify(info));
            const assetStatus =  statuses.find(status => status.id == info.asset_status); 
            const options = statuses.map(status => 
                `<option value="${status.id}" ${info.asset_status == status.id ? "selected" : ""}>${status.name}</option>`
            ).join('');
            console.log('info',info);
            console.log(assetStatus)

            const a_data_url = "{{ route('split-status', ':id') }}".replace(':id', id);

            newRow.innerHTML = `
                <td><input class="form-check-input move-check" type="checkbox"></td>
                <td class="text-muted">${info.asset_number}</td>
                <td class="text-muted">${formatDate(info.capitalization_date) ?? ''}</td>
                <td class="text-muted qty">${info.eval_detail_qty}</td>
                <td class="text-muted">${info.bun ?? ''}</td>
                <td class="text-muted">${info.asset_description ?? ''}</td>
                <td class="text-muted">${info.other_identifier ?? ''}</td>
                <td class="text-muted">
                    <a
                        @if(($evaluation->approval_status == 0 && ($evaluation->draft_by1 == $current_user->id && empty($evaluation->draft_date1))))
                            data-bs-toggle="modal" data-bs-target="#splitStatus" data-url="${a_data_url}" data-assetName="${info.asset_description}" data-maxQty="${info.qty}"
                        @endif
                    >
                        ${assetStatus.name ?? ''}
                    </a>
                </td>
                <td><input type="text" class="form-control" name="remainingAsset[${id}][corrective_action_taken]"></td>
                <td class="hidden">
                    <input type="hidden" name="remainingAsset[${id}][id]" value="${id}">
                    <input type="hidden" name="remainingAsset[${id}][iswrite_off]" value="0">
                    <input type="text" class="hidden " name="remainingAsset[${id}][asset_id]" value="${info.id}">
                    <input type="text" class="hidden " name="remainingAsset[${id}][asset_status]" value="${info.asset_status}">
                </td>
            `;


            // table1Body.appendChild(newRow);
            const rows = Array.from(table1Body.querySelectorAll("tr"));
            const newId = Number(id);

            // find correct position
            const index = rows.findIndex(row => Number(row.dataset.id) > newId);
            console.log('index',index);
            if (index === -1) {
                table1Body.appendChild(newRow); // largest â†’ end
            } else {
                table1Body.insertBefore(newRow, rows[index]); 
            }
        }
    }

    function formatDate(str) {
        const date = new Date(str);

        return date.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }

    const evaluation_id = @json($evaluation->id);
    const notDraft = @json($notdraft);
    document.addEventListener('DOMContentLoaded', function () {

        const dropZone = document.getElementById('dropZone');
        const preview = document.getElementById('preview');

        let allFiles = [];

        if (dropZone) {
            dropZone.addEventListener('dragover', function (e) {
                e.preventDefault();
                dropZone.style.background = "#f0f8ff";
            });

            dropZone.addEventListener('dragleave', function () {
                dropZone.style.background = "transparent";
            });

            dropZone.addEventListener('drop', function (e) {
                e.preventDefault();

                dropZone.style.background = "transparent";

                const files = Array.from(e.dataTransfer.files);

                uploadFiles(files);

                // merge multiple drops
                allFiles = allFiles.concat(files);

                
            });
        }
            
        updateFileList(evaluation_id);

        function uploadFiles(files) {
            files.forEach(file => {
                if (!file) return;
                const formData = new FormData();
                formData.append('evaluation_id', evaluation_id);
                formData.append('file', file); 
                

                axios.post('/file/upload', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                })
                .then(res => {
                    if (res.data.status === 'duplicate') {
                        console.log("Skipped:", file.name);
                        const choice = confirm(
                            "File already exists. Click OK to replace, Cancel to skip."
                        );

                        if (choice) {
                            replaceFile(file); // overwrite
                        } else {
                            console.log("Skipped:", file.name);
                        }
                        
                        return;
                    }
                    console.log("Uploaded:", res.data);
                    
                    updateFileList(evaluation_id);
                })
                .catch(function (error) {
                    console.log(error.response?.data);
                });
            });
        }

        function replaceFile(file) {
            const formData = new FormData();
            formData.append('evaluation_id', evaluation_id);
            formData.append('file', file);
            formData.append('replace', true);

            axios.post('/file/upload', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            }).then(res => {
            
                console.log("Uploaded:", res.data);
            })
            .catch(function (error) {
                console.log(error.response?.data);
            });
        }

    });

    function updateFileList(eval_id){
        axios.get(`/file-list/${eval_id}`)
        .then(response => {
            const tbody = preview.querySelector("tbody");
            console.log(response.data)
            // Clear existing list
            tbody.innerHTML = '';

            // Populate with updated files
            response.data.data.forEach(file => {
                const tr = document.createElement("tr");

                // const tdName = document.createElement("td");
                // tdName.textContent = file.file_name;


                const tdName = document.createElement("td");

                const link = document.createElement("a");
                link.href = "#";
                link.textContent = file.file_name;

                link.addEventListener("click", function (e) {
                    e.preventDefault();

                    const url = "/storage/" + file.file_path;

                    // Preview the document
                    previewDocument(url);

                    // Open the modal
                    $("#documentModal").modal("show"); // Bootstrap 4
                    
                });

                tdName.appendChild(link);
  
                    const tdAction = document.createElement("td");

                    const btn = document.createElement("button");
                    btn.type = "button";
                    btn.className = `btn btn-danger btn-sm ${notDraft ? "hidden" : ""}`;
                    btn.textContent = "Remove";
    

                    btn.onclick =  function () {
                        removeFile(file,evaluation_id);
                    };

                    tdAction.appendChild(btn);
                

                tr.appendChild(tdName);
                if(!notDraft){  
                    tr.appendChild(tdAction);
                }

                tbody.appendChild(tr);
            });

        



        })
        .catch(error => {
            console.error(error);
        });
    }

    function previewDocument(url) {
        const image = document.getElementById("documentImage");
        const iframe = document.getElementById("documentFrame");

        const extension = url.split(".").pop().toLowerCase().split("?")[0];

        if (["jpg", "jpeg", "png", "gif", "webp", "bmp"].includes(extension)) {
            image.src = url;
            image.classList.remove("d-none");

            iframe.classList.add("d-none");
            iframe.src = "";
        } else {
            iframe.src = url;
            iframe.classList.remove("d-none");

            image.classList.add("d-none");
            image.src = "";
        }
    }

    function removeFile(file,evaluation_id){
       const formData = new FormData();

        axios.delete(`/file/remove/${file.id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        }).then(res => {
            console.log("Uploaded:", res.data);
            updateFileList(evaluation_id);
            
        })
        .catch(function (error) {
            console.log(error.response?.data);
        });
        
    }

    function printDiv(divId) {
        const printContents = document.getElementById(divId).innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;

        location.reload();
    }


</script>


</x-app-layout>