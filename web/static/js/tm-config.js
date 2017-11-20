function PartTypes($scope, $http) {
    $scope.foo = 1;

    $scope.getTypes = function () {
        $http
            .get('/api/get-part-types')
            .then(function (response) {
                $scope.partTypes = response.data.data;
            })
    }

    $scope.getTypes();
}

function IndexController($scope, $location) {
    $scope.editCategories = function () {
        $location.url('/edit-categories');
    };
    $scope.setCurrentMonthNumber = function (id) {
        console.log(id)
        $scope.currentMonthNumber = id;
    };

    $scope.month = 11;
    $scope.monthName = 'nov';
    $scope.monthDaysList = range(1, 30);
    $scope.currentMonthNumber = 0;
    $scope.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

    $scope.categories = [
        {
            id: 1,
            name: 22630
        },
        {
            id: 2,
            name: '23040Г'
        }
    ];
}

function routeConfig($routeProvider, $locationProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'views/tm/index.html',
            controller: IndexController
        })
        .when('/part-types', {
            templateUrl: 'views/tm/part-types.html',
            controller: PartTypes
        })
        .when('/Book/:bookId/ch/:chapterId', {
            templateUrl: 'chapter.html',
            controller: 'ChapterController'
        })
        .otherwise({
            redirectTo: '/'
        });

    // $locationProvider.html5Mode(true);
};