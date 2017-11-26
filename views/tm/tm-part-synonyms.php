<h3>TM Synonyms</h3>
<div ng-app="tmApp" ng-cloak>
    <ng-view></ng-view>

    <script type="text/ng-template" id="list.html">
        <table class="table table-bordered table-condensed table-hover " >
            <thead>
            <tr>
                <td>
                </td>

                <td>
                    <input class="form-control" placeholder="Поиск">
                </td>

                <td>
                    <input class="form-control" placeholder="Поиск">
                </td>

                <td>
                    <input class="form-control" placeholder="Поиск">
                </td>

                <td>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    ID
                </td>

                <td class="text-center">
                    Код
                </td>

                <td class="text-center" >
                    Назв. ведомости
                </td>

                <td class="text-center" >
                    Синонимы
                </td>

                <td class="text-center">
                </td>
            </tr>
            </thead>

            <tr ng-repeat="row in rows">
                <td>
                    {{row.id}}
                </td>

                <td>
                    {{row.code}}
                </td>

                <td style="word-break: break-all">
                    {{row.raw_name}}
                </td>

                <td style="word-break: break-all">
                    <div ng-repeat="synonym in row.synonyms">
                        - <span>{{synonym.name}}</span>
                    </div>
                </td>

                <td >
                    <button class="btn btn-sm btn-default btn-primary">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                </td>
            </tr>
        </table>

        <div class="row">
            <div class="col-xs-4">

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


    function synonymsService($http) {
        const BASE_URL = '/tm/';

        this.getSynsPromise = function (search) {
            return $http.get(BASE_URL + 'get-synonyms', search);
        }
    }

    function editSynonymCtrl($scope, $http) {

    }

    function SynonymsCtrl($scope, $http, synonymsService) {
        $scope.partTypes = [];

        $scope.getSynonyms = function () {
            synonymsService
                .getSynsPromise()
                .then(function (response) {
                    $scope.rows = response.data.items;
                    console.log($scope.rows)
                });
        };

        $scope.createPartType = function () {
            $http
                .post('/tm/create-part-type', {name: $scope.partName})
                .then($scope.getSynonyms);
        };

        $scope.toggleActive = function () {
            $http
                .post('/tm/toggle-part-type-active', {name: $scope.partName})
                .then($scope.getSynonyms);

        };

        $scope.getSynonyms()
    }


    function routeConfig($routeProvider) {
        $routeProvider
            .when('/', {
                templateUrl: 'list.html',
                controller: SynonymsCtrl
            })
            .when('/edit/:id', {
                templateUrl: 'views/tm/part-types.html',
                controller: editSynonymCtrl
            })
            .otherwise({
                redirectTo: '/'
            });

        // $locationProvider.html5Mode(true);
    }

    var app = angular
        .module('tmApp', ['ngRoute', 'meetings.directives'])
        .service('synonymsService', synonymsService)
        .config(routeConfig)
        .config(httpConfig);
</script>