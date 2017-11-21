<h3>TM</h3>
<div ng-app="tmApp" ng-cloak>
    <ng-view></ng-view>

    <script type="text/ng-template" id="list.html">
        <div class="row">
            <div class="col-xs-4">
                <admin-text-input output-model="partName" label-text="Новый тип"></admin-text-input>
            </div>

            <div class="col-xs-2 text-right">
                <add-button click-callback="createPartType()"></add-button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">

                <ul class="list-group">
                    <li class="list-group-item" ng-repeat="type in partTypes">
                        <span class="pull-right">
                            <span class="glyphicon glyphicon-edit" ng-click="deletePartType()"></span>
                            <span class="glyphicon glyphicon-off" ng-click="toggleActive()"></span>
                        </span>

                        {{type.name}}
                    </li>
                </ul>
            </div>
        </div>
    </script>
</div>

<script>
    function editPartTypeCtrl($scope, $http) {

    }

    function partTypesCtrl($scope, $http) {
        $scope.partTypes = [];

        $scope.getPartTypes = function () {
            $http
                .get('/tm/get-part-types')
                .then(function (response) {
                    $scope.partTypes = response.data.data;
                })

        };

        $scope.getPartTypes = function () {
            $scope.partName = '';


            $http
                .get('/tm/get-part-types')
                .then(function (response) {
                    $scope.partTypes = response.data.data;
                })
        };

        $scope.createPartType = function () {
            $http
                .post('/tm/create-part-type',{name:$scope.partName})
                .then($scope.getPartTypes);
        };

        $scope.toggleActive = function () {
            $http
                .post('/tm/toggle-part-type-active',{name:$scope.partName})
                .then($scope.getPartTypes);

        };

        $scope.getPartTypes()
    }


    function routeConfig($routeProvider) {
        $routeProvider
            .when('/', {
                templateUrl: 'list.html',
                controller: partTypesCtrl
            })
            .when('/edit/:id', {
                templateUrl: 'views/tm/part-types.html',
                controller: editPartTypeCtrl
            })
            .otherwise({
                redirectTo: '/'
            });

        // $locationProvider.html5Mode(true);
    }

    var app = angular
        .module('tmApp', ['ngRoute', 'meetings.directives'])
        .controller('partTypesCtrl', partTypesCtrl)
        .config(routeConfig)
        .config(httpConfig);
</script>