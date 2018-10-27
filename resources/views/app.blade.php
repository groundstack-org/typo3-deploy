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
        <h1>Hello App! {{$test}}</h1>
        <p>
            <router-link to="/foo">Go to Foo</router-link>
            <router-link to="/bar">Go to Bar</router-link>
        </p>

        <router-view></router-view>
    </div>
    <script src="{{ url('/dist/app.js') }}"></script>
</body>
</html>
