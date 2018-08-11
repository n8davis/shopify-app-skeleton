<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://sdks.shopifycdn.com/polaris/1.5.1/polaris.min.css" />
    {% block header %} {% endblock %}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


    <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<!--    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.js"></script>-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>

    </script>
</head>
<body>
    <div class="container">
        {% block body %}{% endblock %}
    </div>
</body>
<script>
    var key   = '{{ key }}',
        shop  = '{{ shop }}' ,
        title = '{{ title }}';
</script>
<script src="views/assets/js/shopify.js"></script>

