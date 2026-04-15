

    <form action="{{ route('saveevaluation') }}" method="post">
    @csrf
    <div class="row">
        <div class="col-md-6 col-6">
            <div>
              
              <label for="quarter-select" class="form-label">Quarter</label>
              <select class="form-select" name="qrt" i>
                <option value="1st">1st</option>
                <option value="2nd">2nd</option>
                <option value="3rd">3rd</option>
                <option value="4th">4th</option>
              </select>
              
            </div>
        </div>
        
        <div class="col-md-6 col-6">
        

            <div>
              <label for="yearSelect" class="form-label">Select Year</label>
              <select name="year" class="form-select" >
                @foreach ($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
              </select>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="form-group ">
            <label class="form-label">Department</label>
            <div >
                <select name="department" class="form-select">
                    @foreach($departments as $department)
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <hr />
    <div class="form-footer text-right">
        <button type="submit" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"></path><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path><circle cx="12" cy="14" r="2"></circle><polyline points="14 4 14 8 8 8 8 4"></polyline></svg>
        Submit</button>
    </div>
    </form>
