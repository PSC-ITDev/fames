<x-app-layout>
  <div class="col-md-12">
    <div class="card">
        <div class="card-header">
            
            <div class="card-options">
                @if(strtolower($user->role->name) == 'user' )
                  <!-- Button trigger modal -->
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newEvalModal">
                    New Evaluation
                  </button>
                @endif
            </div>
        </div>
        <div class="card-body">   
            <div class="table-responsive">
                <div>
                  <h5><b>Evaluation List </b></h5><br>
                </div>
                <table class="table card-table table-vcenter table-hover table-sm table-striped" id="table1">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Quarter</th>
                            <th>Approval Status</th>
                            <th>Asset Count</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php
                          $approval_statuses = [
                              0 => 'Pending',
                              10 => 'For Approval',
                              20 => 'Approved',
                              30 => 'Confirmed',
                              50 => 'Rejected'
                          ];
                      @endphp
                      @foreach($evaluations as $index => $evaluation)
                          @php 
                          $approval_status = $approval_statuses[floor($evaluation->approval_status / 10) * 10]; @endphp
                          <tr onclick="window.location.href ='{{ route('evaluation-details', $evaluation->id) }}'" style="cursor:pointer;">
                              <td>{{ $index + 1 }}</td>
                              <td>{{ $evaluation->department->name }}</td>
                              <td class="text-muted ">{{ $evaluation->year }}</td>
                              <td class="text-muted">{{$evaluation->quarter}}</td>
                              <td class="text-muted qty"> {{$approval_status ?? 'Unknown' }}</td>
                              <td class="text-muted qty"> {{$evaluation->details_sum_qty}}</td>
                              
                          </tr>
                      @endforeach
                    </tbody>


                </table>
            </div>
            <hr>
        </div>
    </div>
  </div>
    <!-- Modal -->
  <div class="modal fade" id="newEvalModal" tabindex="-1" aria-labelledby="newEvalModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="newEvalModalLabel">Register New Evaluation </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        
        <div class="modal-body">
          @include('fixed_assets.create')

          
        </div>



      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      document.getElementById('newEvalModal').addEventListener('show.bs.modal', function (event) {
          check_duplicate();
      });

      $('select[name="year"], select[name="qrt"], select[name="department"]').on('change', function () {
          check_duplicate();
      });

      function check_duplicate(){
        let formData = {};

        $('#evalForm')
          .serializeArray()
          .filter(field => field.name !== '_token')
          .forEach(field => {
              formData[field.name] = field.value;
        });

        console.log(formData);

        let evaluations = @json($evaluations);

        console.log(evaluations);

        const result = evaluations.find(item =>
            item.department_id == formData.department &&
            item.quarter === formData.qrt &&
            item.year === formData.year
        );

        console.log(result);
            
        if(result){
          $('#duplicate').show();
          $('#submitEval').prop('disabled', true);
        }else{
          $('#duplicate').hide();
          $('#submitEval').prop('disabled', false);
        }

      }
    });

  </script>





</x-app-layout>