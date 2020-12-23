<x-guest-layout>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="#"><b>Cylix Technologies </b>Inc.</a>
            </div>
            
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start your session</p>

                    <x-jet-validation-errors class="mb-4" />
                    <form action="{{route('login')}}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Username" :value="old('email')" required autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="current-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
 
                        <div class="form-group">
                            <label for="reportingManager">Company</label>
                            <select id="reportingManager" name="reportingManager" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                                @foreach ($companyList as $cl)
                                    <option value="{{$cl->title_id}}">{{$cl->title_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input name="remember" type="checkbox" id="remember" value="forever">
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>

                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>

                    <p class="mb-1"><a href="{{route('password.request')}}">I forgot my password</a></p>
                    <p class="mb-0"><a href="/register" class="text-center">Register</a></p>
                    {{-- <p class="mb-0"><a href="#" class="text-center">Change Connection</a></p> --}}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>