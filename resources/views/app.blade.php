<!doctype html>
<html>
<head>
    <!-- https://www.liquidlight.co.uk/blog/article/using-vue-js-with-a-lumen-powered-api/ -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Summit</title>
    <link rel="stylesheet" href="{{ url('/dist/app.css') }}" />
</head>
<body>
    <h1>My App</h1>
    <div id="app">
        <h1>Hello App! @isset($test) {{$test}} @endisset</h1>
        <p>
            <router-link to="/foo">Go to Foo</router-link>
            <router-link to="/bar">Go to Bar</router-link>
        </p>

        <form action="/api/v1/fileupload" method="post" enctype="multipart/form-data">
            <input type="file" name="file"><br>
            <input type="submit" value="Hochladen">
        </form>

        <form action="/api/v1/test/db" method="post" enctype="multipart/form-data">
            <label for="dbname">Database name</label>
            <input type="text" name="dbname" id="dbname"><br>
            <label for="dbname">Database user</label>
            <input type="text" name="dbuser" id="dbuser"><br>
            <label for="dbname">Database password // allowed: a - z, A - Z, 0 - 9, ?!#%$@-_ // minimum 8 characters</label>
            <input type="password" name="dbpassword" id="dbpassword"><br>
            <label for="dbname">Database host</label>
            <input type="text" name="dbhost" id="dbhost"><br>
            <label for="dbname">Database port</label>
            <input type="number" name="dbport" id="dbport"><br>
            <input type="submit" value="testDB">
        </form>

        <div class="">
            <!-- !! = for raw output without escaping -->
            @isset($message) {!! $message !!} @endisset
            @isset($messageinfo) <div class="message-info">{!! $messageinfo !!}</div> @endisset
        </div>

        <router-view></router-view>
    </div>
    <script src="{{ url('/dist/app.js') }}"></script>
</body>
</html>
