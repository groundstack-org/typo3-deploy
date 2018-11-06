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

        <div class="">
            <!-- !! = for raw output without escaping -->
            @isset($message) {!! $message !!} @endisset
        </div>

        <router-view></router-view>
    </div>
    <script src="{{ url('/dist/app.js') }}"></script>
</body>
</html>
