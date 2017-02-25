<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<!DOCTYPE html>
<html>
<head>
    <title> admin </title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('bundles/velovito/css/style.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('bundles/velovito/vendor/bootstrap/css/bootstrap.min.css') }}"/>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}"/>

    <script src="{{ asset('bundles/velovito/js/common.js') }}"></script>
    <script src="{{ asset('bundles/velovito/js/filters.js') }}"></script>
    <script src="{{ asset('bundles/velovito/js/services.js') }}"></script>
    <script src="{{ asset('bundles/velovito/vendor/angular/angular.js') }}"></script>
    <script src="{{ asset('bundles/velovito/vendor/angular/angular-route.js') }}"></script>
    <script src="{{ asset('bundles/velovito/vendor/jquery/jquery-3.1.0.min.js') }}"></script>
    <script src="{{ asset('bundles/velovito/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('bundles/velovito/admin-static/js/services.js') }}"></script>
    <script src="{{ asset('bundles/velovito/admin-static/js/app.js') }}"></script>
</head>

<body>
<div class="container" ng-app="adminApp" ng-cloak>
    <ng-include src="'/bundles/velovito/admin-static/views/header.html'"></ng-include>

    <div class="row" ng-controller="commonCtrl">
        <div class="col-xs-12">
            {% verbatim %}<h4>{{pageName}}</h4>{% endverbatim %}
        </div>

        <ng-view class="col-xs-12"></ng-view>
    </div>

</div>
</body>
</html>
