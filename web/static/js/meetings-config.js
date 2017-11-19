function BookController($scope) {
    $scope.foo = 1;
}

function IndexController($scope) {
    $scope.foo = 1;
}

function routeConfig($routeProvider, $locationProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/index.html',
            controller: IndexController
        })
        .when('/Book/:bookId', {
            templateUrl: 'book.html',
            controller: BookController
        })
        .when('/Book/:bookId/ch/:chapterId', {
            templateUrl: 'chapter.html',
            controller: 'ChapterController'
        });

    // $locationProvider.html5Mode(true);
};