
    <form method="POST" action="{{ route('saveuser') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>

            <label for="name">Name</label>
            <input id="name" class="mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="row mt-4">
            <!-- Profile Picture -->
            <div class="col-6">
                <label for="picture">Profile Picture</label>
                <input
                    id="picture"
                    type="file"
                    name="picture"
                    class="form-control"
                    accept="image/png,image/jpeg,image/jpg">
                <small class="text-muted">Accepted formats: PNG, JPG, JPEG</small>

                <x-input-error :messages="$errors->get('picture')" class="mt-2" />
            </div>

            <!-- Signature -->
            <div class="col-6">
                <label for="signature">Signature (PNG)</label>
                <input
                    id="signature"
                    type="file"
                    name="signature"
                    class="form-control"
                    accept=".png,image/png">

                <small class="text-muted">Please upload a transparent PNG signature.</small>

                <x-input-error :messages="$errors->get('signature')" class="mt-2" />
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email">Email</label>
            <input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="row">
            <!-- Password -->
            <div class="mt-4 col-6">
                <label for="password">Password</label>

                <input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4 col-6">
                <label for="password_confirmation">Confirm Password</label>

                <input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

        </div>


        <div class="row">
            <!-- Role -->
            <div class="mt-4 col-6">
                <label for="role">Role</label>
                <select name="role_id" class="form-select" >
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            

                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Department -->
            <div class="mt-4 col-6">
                <label for="department">Department</label>
                <select name="deptid" class="form-select" >
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            

                <x-input-error :messages="$errors->get('department')" class="mt-2" />
            </div>

        </div>
        

        <div class="flex items-center justify-end mt-4">

            <button class="btn btn-primary ms-4">
                {{ __('Register') }}
            </button>
        </div>
    </form>
