<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="/css/app.css">
  <title>Login</title>
</head>
<body>
  <div id="notification">
    <notification></notification>
  </div>
  <section class="hero is-fullheight">
    <div class="hero-body">
      <div class="container">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-grey has-text-centered">Login</h3>
          <div class="box">
            <form method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}
              <div class="field">
                <label class="label">Username or Email</label>
                <div class="control">
                  <input class="input is-large" type="text" name="email" value="{{ old('email') }}" autofocus="" required>
                  @if ($errors->has('email'))
                  <p class="help is-danger">{{ $errors->first('email') }}</p>
                  @endif
                </div>
              </div>

              <div class="field">
                <label class="label">Password</label>
                <div class="control">
                  <input class="input is-large" type="password" name="password" required>
                  @if ($errors->has('password'))
                  <p class="help is-danger">{{ $errors->first('password') }}</p>
                  @endif
                </div>
              </div>
              <div class="field">
                <label class="checkbox">
                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
              </div>
              <div class="field has-text-centered">
                <div class="control has-text-centered">
                  <button type="submit" class="button is-primary is-large" style="width: 100%;">Login</button>
                </div>
              </div>
            </form>
          </div>
          <p class="has-text-grey">
          </p>
        </div>
      </div>
    </div>
</section>
<script src="{{ asset('js/notification.js') }}"></script>
</body>
</html>
