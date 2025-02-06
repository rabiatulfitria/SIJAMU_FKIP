@extends('layout.index-login-auth')

@section('popup-content')
    <!-- Modal Sign Up -->
    @include('_partials.modal', [
        'modalId' => 'signupModal',
        'modalTitle' => 'Sign Up',
        'modalContent' => '
            <form action="' . route('signup') . '" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
        '
    ]);
@endsection