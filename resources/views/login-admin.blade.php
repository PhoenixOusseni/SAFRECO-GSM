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
                                <a href="index.html" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('images/images.jpg') }}" alt="logo safreco">
                                    <span class="d-none d-lg-block">Safreco - GSM</span>
                                </a>
                            </div><!-- End Logo -->

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Connectez-vous à votre compte</h5>
                                        <p class="text-center small">Entrez votre nom d'utilisateur et votre mot de passe pour vous connecter</p>
                                    </div>
                                    <form class="row g-3 needs-validation" method="POST" action="{{ route('login_admin') }}">
                                        @csrf
                                        <div class="col-12">
                                            <label for="email" class="small">email</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input type="text" name="email" class="form-control"
                                                    id="email" value="s-admin@gmail.com" required>
                                                <div class="invalid-feedback">Please enter your email.</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="password" class="small">Password</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" name="password" class="form-control"
                                                    id="password" value="password" required>
                                                <div class="invalid-feedback">Please enter your password.</div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
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
