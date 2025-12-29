<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Meta tags, title, etc. --}}
    @include('partials.style')
</head>

<body>
    <main>
        <div class="container">
            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                            <div class="d-flex justify-content-center py-4">
                                <img src="{{ asset('images/images.jpg') }}" alt="logo safreco" height="90px">
                            </div><!-- End Logo -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Connectez-vous à votre compte</h5>
                                        <p class="text-center small">Entrez votre nom d'utilisateur et votre mot de
                                            passe pour vous connecter</p>
                                    </div>
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <strong>Erreur!</strong>
                                            <ul class="mb-0 mt-2">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <strong>Erreur!</strong> {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <strong>Succès!</strong> {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form class="row g-3 needs-validation" method="POST"
                                        action="{{ route('login_admin') }}">
                                        @csrf
                                        <div class="col-12">
                                            <label for="email" class="small">email</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                                    value="{{ old('email', 's-admin@gmail.com') }}" required>
                                                @error('email')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Please enter your email.</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="password" class="small">Password</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                                    id="password" value="password" required>
                                                @error('password')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @else
                                                    <div class="invalid-feedback">Please enter your password.</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Se souvenir de
                                                    moi</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">
                                                <i class="bi bi-box-arrow-in-right"></i>&nbsp; Se connecter
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">N'avez-vous pas de compte? <a
                                                    href="pages-register.html">Créer un compte</a></p>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="credits">
                                Dévéloppé par <a href="#">Safreco SARL</a>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <!-- Vendor JS Files -->
    @include('partials.script')

</body>

</html>
