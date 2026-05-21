@extends('Login')

@section('title', 'Đăng ký - Luma Shoes')

@section('auth_form')
    <div class="form signup">
        <div class="form-content">
            <h2>Đăng Ký</h2>

            <!-- Hiển thị thông báo từ Session -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('signup.post') }}" method="POST">
                @csrf <!-- CSRF Token -->
                <div class="field input-field">
                    <input id="username" name="username" type="text" placeholder="Tên đăng nhập"
                        value="{{ old('username') }}" required />
                </div>
                <div class="field input-field">
                    <input id="email" name="email" type="email" placeholder="Email" value="{{ old('email') }}"
                        required />
                </div>
                <div class="field input-field">
                    <input id="password" name="password" type="password" placeholder="Mật Khẩu" required />
                </div>
                <div class="field input-field">
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="Nhập lại mật khẩu" required />
                </div>
                <div class="field button-field">
                    <button type="submit" class="btn btn-signup">Đăng Ký</button>
                </div>
            </form>

            <div class="form-link">
                <span>Bạn đã có tài khoản? <a href="{{ route('login') }}" class="login-link">Đăng Nhập</a></span>
            </div>
        </div>
    </div>
@endsection
