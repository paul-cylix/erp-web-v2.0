
<style>
section{
    position: relative;
    height: 100vh;
    width: 100%;
    background: #e8e4c9;
}
.title{
    text-align: center;
    color: #fff;
}
.form-container{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background:line;
    width: 380px;
    padding:50px;
    background-color: #2d2d2d;
    width: 380px;
    padding: 50px 30px;
    border-radius: 10px;
    box-shadow: 7px 7px 60px #000;

}
.container .section-bg{
    background-color: red;
}

.login-form{
    color: #fff;
}

.logo-login{
    width: 100px;
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 25%;
    margin-bottom: 20px;
}
.control{
    color: #fff;
}

.control input{
    width: 100%;
    display: block;
    padding: 10px;
    color: #000;
    border: none;
    margin:none;
    margin: 1em 0;
    /* font-family: 'Poppins', sans-serif; */
}
.link{
    text-align: center;

}
.link a{
    text-decoration: none;
    color: grey;
    transition: opacity .3s ease;
}
.link a:hover{
    opacity: 1;
    color: #fff;
}
</style>


<x-guest-layout>
    <section>
        <div class="form-container">
            <!-- <h1>Login Form</h1> -->
            <h4 class="title">Manage Workflow</h4>
            <form action="{{ route('auth.check') }}" method="POST">
                @if(Session::get('fail'))
                    <div class="alert alert-danger">
                        {{ Session::get('fail') }}
                    </div>
                @endif
                @csrf
                <div class="control">
                    <label for="name">Username</label>
                    <input type="email" name="email" class="form-control" placeholder="Username" value="{{old('email')}}" >
                    <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                </div>

                <div class="control">
                    <label for="psw">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" >
                    <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                </div>

                <span style="color: #fff;"> <input type="checkbox" name="#" id="#" > Remember me </span>
                <div class="control">
                <label for="company" style="margin-top: 5px;">Company</label>
                <select id="company" name="company" class="form-control select2 select2-default" data-dropdown-css-class="select2-default" style="width: 100%;">
                    @foreach ($companyList as $cl)
                        <option value="{{ $cl->title_id }}">{{ $cl->title_name }}</option>
                    @endforeach
                </select>
                </div>
                <div class="control">
                    <button type="submit" class="btn btn-secondary btn-block" style="margin-top: 5px;">Sign In</button>
                </div>
                
            </form>

            <div class="link">
                <a href="#">Forgot Password ?</a>
            </div>
        </div>
    </section>
    

</x-guest-layout>