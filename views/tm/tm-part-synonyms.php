<h3>TM Synonyms</h3>

<div ng-app="tmApp" ng-cloak>
    <ng-view></ng-view>

    <script type="text/ng-template" id="list.html">

        <div class="row">
            <div class="col-xs-2 ">
                <span> Всего: {{totalItems}} элементов</span>
                <span> Всего: {{totalItems}} элементов</span>
            </div>

            <div class="col-xs-8 text-center">
                <ul uib-pagination total-items="totalItems" ng-model="search.page" ng-change="searchChanged()"
                    max-size="10">
                </ul>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered table-condensed table-hover " style="font-size: 14px">
                    <thead>
                    <tr>
                        <td>
                        </td>

                        <td>
                            <input class="form-control" placeholder="Поиск" ng-model="search.code" fast-enter="getData()">
                        </td>

                        <td>
                            <input class="form-control" placeholder="Поиск" ng-model="search.ved_name" fast-enter="getData()">
                        </td>

                        <td>
                            <input class="form-control" placeholder="Поиск" ng-model="search.synonym" fast-enter="getData()">
                        </td>

                        <td>
                            <button class="btn btn-sm btn-default btn-primary" ng-click="getData()">
                                Поиск <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </td>

                    </tr>

                    <tr>
                        <td class="text-center">
                            ID
                        </td>

                        <td class="text-center">
                            Код
                        </td>

                        <td class="text-center">
                            Назв. ведомости
                        </td>

                        <td class="text-center">
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

                        <td style="word-break: break-all">
                            <button class="btn btn-sm btn-default btn-primary">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

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
            return $http({
                    url: BASE_URL + 'get-synonyms',
                    method: "GET",
                    params: search
                }
            );
        }
    }

    function editSynonymCtrl($scope, $http) {

    }

    function SynonymsCtrl($scope, $http, $timeout, synonymsService) {
        $scope.search = {
            code:''
        };

        $scope.totalItems = 100;
        $scope.currentPage = 3;

        $scope.searchChanged = function () {
            $timeout(function () {
                $scope.getData();
            }, 0)
        };

        $scope.getData = function () {
            synonymsService
                .getSynsPromise($scope.search)
                .then(function (response) {
                    $scope.rows = response.data.items;
                    $scope.totalItems = response.data.total;
                    console.log($scope.rows)
                });
        };

        $scope.getData()
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
        .module('tmApp', ['ngRoute', 'vz.directives', 'ui.bootstrap'])
        .service('synonymsService', synonymsService)
        .config(routeConfig)
        .config(httpConfig);
</script>