<h3>TM Parts</h3>

<div ng-app="tmApp" ng-cloak>
    <ng-view></ng-view>

    <script type="text/ng-template" id="list.html">
        <div class="row">
            <div class="col-xs-12 text-center">
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
                            <input class="form-control" placeholder="%1206%" ng-model="search.code"
                                   fast-enter="getData()">
                        </td>

                        <td>
                            <input class="form-control" placeholder="Поиск" ng-model="search.ved_name"
                                   fast-enter="getData()">
                        </td>

                        <td>
                            <input class="form-control" placeholder="Поиск" ng-model="search.synonym"
                                   fast-enter="getData()">
                        </td>

                        <td class="text-right">
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
                            <button class="btn btn-sm btn-default btn-primary" ng-click="editPart(row.id)">
                                <span class="glyphicon glyphicon-pencil"></span> Редактировать
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4">
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

    <script type="text/ng-template" id="edit.html">
        <to-list-button button-text="Назад"></to-list-button>

        <div class="row top-buffer">
            <div class="col-xs-4">
                <label for="">Основные данные</label>

                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Код:</b>
                        {{partData.code}}
                    </li>

                    <li class="list-group-item">

                        <b>Название по ведомости:</b> <br>
                        {{partData.raw_name}}
                    </li>
                </ul>
            </div>

            <div class="col-xs-8">
                <label for="">
                    Синонимы
                </label>

                <a class="pull-right" href click-to-prompt popup-title="Новый синоним" popup-text="Введите название"
                   callback="createSynonym">
                    <span class="glyphicon glyphicon-plus"></span>
                    Добавить
                </a>

                <div class="row">
                    <div class="col-xs-12">
                        <ul class="list-group">
                            <li class="list-group-item" ng-repeat="synonym in  partData.synonyms">
                                <span class="badge cursor-pointer" ng-click="deleteSynonym(synonym.id)">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </span>

                                <span class="badge cursor-pointer" click-to-prompt popup-title="Новый синоним"
                                      popup-text="asd"
                                      callback="updateSynonym" add-data="synonym">
                                    <span class="glyphicon glyphicon-pencil"></span>
                                </span>
                                {{synonym.name}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </script>
</div>

<script>
    function partsService($http) {
        const BASE_URL = '/tm/';

        this.getPartsDataPromise = function (search) {
            return $http({
                    url: BASE_URL + 'get-synonyms',
                    method: "GET",
                    params: search
                }
            );
        };

        this.getOnePartDataPromise = function (id) {
            return $http({
                    url: BASE_URL + 'get-part-data',
                    method: "GET",
                    params: {id: id}
                }
            );
        };

        this.createSynonymPromise = function (id, name) {
            return $http({
                    url: BASE_URL + 'create-part-synonym',
                    method: "POST",
                    params: {id: id, name: name}
                }
            );
        };

        this.deleteSynonymPromise = function (id) {
            return $http({
                    url: BASE_URL + 'delete-part-synonym',
                    method: "POST",
                    params: {id: id}
                }
            );
        };

        this.updateSynonymPromise = function (id, name) {
            return $http({
                    url: BASE_URL + 'update-part-synonym',
                    method: "POST",
                    params: {id: id, name: name}
                }
            );
        }
    }

    function editSynonymCtrl($scope, $routeParams, partsService, Notification) {
        $scope.createSynonym = function (name) {
            partsService.createSynonymPromise($routeParams.id, name)
                .then(function (res) {
                    Notification.success('Success');
                    $scope.getPartData();
                })
        };

        $scope.deleteSynonym = function (id) {
            partsService.deleteSynonymPromise(id).then(function () {
                    Notification.success('Success');
                    $scope.getPartData();
                },
                function () {
                    Notification.error('Error....');
                    $scope.getPartData();
                });
        };

        $scope.updateSynonym = function (name, record) {
            partsService.updateSynonymPromise(record.id, name).then(function () {
                    Notification.success('Success');
                    $scope.getPartData();
                },
                function () {
                    Notification.error('Error....');
                    $scope.getPartData();
                }
            );
        };

        $scope.getPartData = function () {
            partsService
                .getOnePartDataPromise($routeParams.id)
                .then(function (res) {
                        $scope.partData = res.data
                    }
                )
        };

        $scope.getPartData();
    }

    function SynonymsCtrl($scope, $location, $timeout, partsService) {
        $scope.search = {
            code: ''
        };

        $scope.totalItems = 100;
        $scope.currentPage = 3;

        $scope.editPart = function (id) {
            $location.path('/edit/' + id);
        };

        $scope.searchChanged = function () {
            $timeout(function () {
                $scope.getData();
            }, 0)
        };

        $scope.getData = function () {
            partsService
                .getPartsDataPromise($scope.search)
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
                templateUrl: 'edit.html',
                controller: editSynonymCtrl
            })
            .otherwise({
                redirectTo: '/'
            });

        // $locationProvider.html5Mode(true);
    }

    function NotificationsConfig(NotificationProvider) {
        NotificationProvider.setOptions({
            delay: 2000,
            startTop: 20,
            startRight: 10,
            verticalSpacing: 20,
            horizontalSpacing: 20,
            positionX: 'right',
            positionY: 'top'
        });
    }

    angular
        .module('tmApp', ['ngRoute', 'vz.directives', 'ui.bootstrap', 'ui-notification'])
        .service('partsService', partsService)
        .config(routeConfig)
        .config(NotificationsConfig)
        .config(httpConfig);
</script>