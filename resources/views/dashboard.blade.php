<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Blacoffer | Dashbaord</title>
</head>

<body>
    <h3>Visualization Dashboard for Blacoffer</h3>

    @foreach ($data as $item)
        <p>{{ $item->end_year }}</p>
    @endforeach
</body>

</html>
