const DIR_ASC = 'ASC';
const DIR_DESC = 'DESC';

function switchDir(order) {
    return DIR_ASC === order
        ? DIR_DESC
        : DIR_ASC;
}

function CommonAdminCtrl($scope, $rootScope, $q, $location, notify) {
    $scope.loading = false;
    $rootScope.loading = false;

    $scope._toItemList = function () {
        $location.path('/');
    };

    $scope._toEditItem = function (id) {
        $location.path('/edit/' + id);
    };

    // for list controllers
    $scope._getItems = function (promise) {
        return $scope._applyPromiseResult(promise, 'items')
    };

    $scope._performPost = function (promise, successCallback) {
        $scope._startLoading();

        return promise.then(function (response) {
            if (responseIsValid(response)) {
                $scope._shortNotify('Ok!');

                if (successCallback !== undefined) {
                    successCallback(response);
                }
            } else {
                $scope._errorCallback(getResponseMessage(response));
            }
        }).finally($scope._stopLoading)
    };

    $scope._applyPromises = function (promises) {
        var useHash;

        if (promises.$$state) {
            promises = Array(promises);
        } else if (angular.isArray(promises)) {
            useHash = false;
        } else if (angular.isObject(promises)) {
            useHash = true;
        } else {
            throw new Error('_applyPromisesResult Argument sould be Promise or [Promise, ...] or {hash:Promise, ...} but "'
                + typeof(promises) + '" given'
            );
        }

        $scope._startLoading();

        return $q.all(promises).then(function (responses) {
            var error = false;

            angular.forEach(responses, function (val) {
                if (!responseIsValid(val)) {
                    error = true;
                    console.log('Promise failed for url ' + val.config.url);
                }
            });

            if (error) {
                $scope._errorCallback();
                return false;
            }

            if (useHash) {
                angular.forEach(responses, function (val, hash) {
                    $scope[hash] = getResponseData(val);
                });
            }
        }).finally($scope._stopLoading);
    };

    $scope._applyPromiseResult = function (promise, varName) {
        $scope._startLoading();

        return promise.then(function (response) {
                if (responseIsValid(response)) {
                    $scope[varName] = getResponseData(response);
                } else {
                    $scope._errorCallback();
                }
            },
            $scope._errorCallback
        ).finally($scope._stopLoading);
    };

    $scope._shortNotify = function (msg) {
        $scope._notify({message: msg, duration: 1250});
    };

    $scope._notify = function (msg) {
        notify(msg);
    };

    $scope._errorCallback = function (msg) {
        if (undefined === msg || !msg.length) {
            msg = 'Error';
        }

        $scope._shortNotify(msg);
        $scope._stopLoading();
    };

    $scope._stopLoading = function () {
        $scope.loading = false;
        $rootScope.loading = false;
    };

    $scope._startLoading = function () {
        $scope.loading = true;
        $rootScope.loading = true;
    };
}

function CompileService($compile) {
    this.setClick = function (scope, elementSelector, funcName, preventDefault) {
        if (undefined === preventDefault) {
            preventDefault = true;
        }

        var element = angular.element(elementSelector);

        if (preventDefault) {
            funcName = '$event.preventDefault(); ' + funcName;
        }

        $compile(element.attr("ng-click", funcName))(scope);
    };

    this.setFormSubmit = function (scope, elementSelector, funcName, preventDefault) {
        if (undefined === preventDefault) {
            preventDefault = true;
        }

        var form = angular.element(elementSelector);

        if (preventDefault) {
            funcName = '$event.preventDefault(); ' + funcName;
        }

        $compile(form.attr("ng-submit", funcName))(scope);
    };

    this.setModel = function (scope, elementSelector, modelName) {
        var element = angular.element(elementSelector);
        $compile(element.attr("ng-model", modelName))(scope);
    };
}

function setHttpProviderDefaults(provider) {
    // make $http service behave like jQuery.ajax()
    var param = function (obj) {
        var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for (name in obj) {
            value = obj[name];

            if (value instanceof Array) {
                for (i = 0; i < value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value instanceof Object) {
                for (subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if (value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length
            ? query.substr(0, query.length - 1)
            : query;
    };

    provider.defaults.transformRequest = [function (data) {
        return angular.isObject(data) && String(data) !== '[object File]'
            ? param(data)
            : data;
    }];
    provider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
}

var adminAngularApp = function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13 && !event.ctrlKey) {
                scope.$apply(function () {
                    scope.$eval(attrs.iqEnter);
                });

                event.preventDefault();
            }
        });
    };
};

var directiveIqEnter = function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13 && !event.ctrlKey) {
                scope.$apply(function () {
                    scope.$eval(attrs.iqEnter);
                });

                event.preventDefault();
            }
        });
    };
};

function toggleActiveButtonDirective() {
    return {
        restrict: 'E',
        scope: {
            clickCallback: '&',
            inputModel: '=',
            buttonText: '@',
            buttonSize: '@'
        },
        link: function (scope, element, attrs) {
        },
        controller: function ($scope) {
            $scope.buttonClick = function () {
                $scope.clickCallback();
            }
        },
        templateUrl: '/views/common/buttons/toggle-active-button-simple.html'
    };
}

function editButtonDirective() {
    return {
        restrict: 'E',
        scope: {
            clickCallback: '&',
            buttonDisabled: '=',
            buttonText: '@',
            buttonSize: '@'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.buttonClick = function () {
                $scope.clickCallback();
            }
        },
        templateUrl: '/views/common/buttons/edit-button-simple.html'
    };
}

function blinderDirective() {
    return {
        restrict: 'E',
        scope: true,
        link: function (scope, element, attrs) {
        },
        controller: function ($rootScope, $scope) {
            $rootScope.$watch('loading', function (val) {
                $scope.loading = val;
            })
        },
        templateUrl: '/views/common/directives/blinder.html'
    };
}

function addButtonDirective() {
    return {
        restrict: 'E',
        scope: {
            clickCallback: '&',
            buttonDisabled: '=',
            buttonText: '@',
            buttonSize: '@'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.buttonClick = function () {
                $scope.clickCallback();
            }
        },
        templateUrl: '/views/common/buttons/add-button-simple.html'
    };
}

function deleteButtonDirective() {
    return {
        restrict: 'E',
        scope: {
            clickCallback: '&',
            buttonDisabled: '=',
            buttonText: '@',
            buttonSize: '@'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.buttonClick = function () {
                $scope.clickCallback();
            }
        },
        templateUrl: '/views/common/buttons/delete-button-simple.html'
    };
}

function saveButtonDirective() {
    return {
        restrict: 'E',
        scope: {
            clickCallback: '&',
            buttonDisabled: '=',
            buttonText: '@',
            buttonSize: '@'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.buttonClick = function () {
                $scope.clickCallback();
            }
        },
        templateUrl: '/views/common/buttons/save-button-simple.html'
    };
}

function toListButtonDirective($location) {
    return {
        restrict: 'E',
        scope: {
            clickCallback: '=',
            buttonDisabled: '=',
            buttonText: '@',
            buttonSize: '@'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.buttonClick = function () {
                if ($scope.clickCallback) {
                    console.log($scope.clickCallback)
                    $scope.clickCallback();
                } else {
                    $location.path('/');
                }
            }
        },
        templateUrl: '/views/common/buttons/to-list-button-simple.html'
    };
}

function deleteSmallDirective() {
    return {
        restrict: 'EA',
        scope: {
            onDelete: '&',
            needConfirm: '=',
            deleteIconClass: '=',
            isDisabled: '='
        },
        link: function (scope, element, attrs) {
            scope.deleteClicked = function () {
                scope.onDelete()
            }
        },
        controller: function ($scope) {

        },
        templateUrl: '/views/common/directives/delete-small.html'
    };
}

function yesNoSelectDirective($timeout) {
    return {
        restrict: 'E',
        scope: {
            outputModel: '=',
            asIntVal: '=',
            selectTitle: '@',
            callback: '&'
        },
        link: function (scope, element, attrs) {
        },
        controller: function ($scope) {
            $scope.options = [
                {id: -1, name: 'All'},
                {id: 0, name: 'No'},
                {id: 1, name: 'Yes'}
            ];

            if (undefined === $scope.asIntVal) {
                $scope.behaveAsInteger = true;
            } else {
                $scope.behaveAsInteger = $scope.asIntVal;
            }

            $scope.$watch('outputModel', function (newVal) {
                if ($scope.behaveAsInteger && undefined !== $scope.outputModel && typeof newVal !== 'number') {
                    $scope.outputModel = Number($scope.outputModel)
                }

                if ($scope.callback) {
                    $timeout($scope.callback);
                }
            });
        },
        templateUrl: '/views/common/directives/yes-no-select.html'
    };
}

function bootstrapCheckboxDirective($timeout) {
    return {
        restrict: 'E',
        scope: {
            outputModel: '=',
            asIntVal: '=',
            checkboxTitle: '@',
            callback: '&'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            if (undefined === $scope.asIntVal) {
                $scope.behaveAsInteger = true;
            } else {
                $scope.behaveAsInteger = $scope.asIntVal;
            }

            $scope.$watch('outputModel', function (newVal) {
                if ($scope.behaveAsInteger && undefined !== $scope.outputModel && typeof newVal !== 'number') {
                    $scope.outputModel = Number($scope.outputModel)
                }
            });

            $scope.toggle = function () {
                $scope.outputModel = $scope.behaveAsInteger
                    ? Number(!$scope.outputModel)
                    : !$scope.outputModel;

                if ($scope.callback) {
                    $timeout($scope.callback);
                }
            }
        },
        templateUrl: '/views/common/directives/bootstrap-checkbox.html'
    };
}

function clickToPopupDirective() {
    return {
        restrict: 'A',
        scope: {
            popupTitle: '@',
            popupText: '@'
        },
        link: function (scope, element, attrs) {
            element.on('click', scope.showPopup)
        },
        controller: function ($scope, $modal) {
            function popupCtrl($scope, $modalInstance, text, title) {
                $scope.text = text;
                $scope.title = title;

                $scope.close = function () {
                    $modalInstance.dismiss('cancel');
                };
            }

            $scope.showPopup = function () {
                $modal.open({
                    templateUrl: '/views/common/popups/simple-popup.html',
                    controller: popupCtrl,
                    resolve: {
                        text: function () {
                            return $scope.popupText;
                        },
                        title: function () {
                            return $scope.popupTitle;
                        }
                    }
                });
            };
        }
    };
}

function clickToPromptDirective() {
    function popupCtrl($scope, $modalInstance, text, title, label, placeholder, maxLength) {
        $scope.modal = {};
        $scope.modal.text = text;
        $scope.modal.title = title;
        $scope.modal.label = label;
        $scope.modal.placeholder = placeholder;

        $scope.modal.maxLength = maxLength > 0
            ? maxLength
            : '';

        $scope.onKeyPress = function (event) {
            if (event.which === 13) {
                event.preventDefault();
                $scope.close();
            }
        };

        $scope.close = function () {
            $modalInstance.close($scope.modal.value);
        };

        $scope.dismiss = function () {
            $modalInstance.dismiss();
        };
    }

    return {
        restrict: 'A',
        scope: {
            popupTitle: '@',
            popupText: '@',
            popupLabel: '@',
            popupPlaceholder: '@',
            popupMaxLength: '@',
            callback: '='
        },
        link: function (scope, element, attrs) {
            element.on('click', scope.showPopup)
        },
        controller: function ($scope, $modal) {
            var onPopupClose = function (value) {
                $scope.callback(value)
            };

            var settings = {
                templateUrl: '/views/common/popups/simple-prompt-popup.html',
                controller: popupCtrl,
                resolve: {
                    text: function () {
                        return $scope.popupText;
                    },
                    title: function () {
                        return $scope.popupTitle;
                    },
                    label: function () {
                        return $scope.popupLabel;
                    },
                    placeholder: function () {
                        return $scope.popupPlaceholder;
                    },
                    maxLength: function () {
                        return Number($scope.popupMaxLength);
                    }
                }
            };

            $scope.showPopup = function () {
                $modal.open(settings).result.then(onPopupClose);
            };
        }
    };
}

function ticketTemplatesDirective($http) {
    return {
        restrict: 'E',
        templateUrl: '/views/tickets/directives/ticket-templates-basic.html',
        scope: {
            title: '@', // val
            onSelect: '=' // call
        },
        link: function (scope, element, attrs) {
            scope.closeButtons = function () {
                $(element).find('[data-toggle="dropdown"]').parent().removeClass('open');
            }
        },
        controller: function ($scope) {
            $scope.templates = {};
            $scope.templatesVisibility = {};
            $scope.isLocked = true;
            $scope.locales = [];
            $scope.templatesTree = [];
            $scope.currentLocale = {};
            $scope.i18n = {};

            $scope.lockTemplatesMenu = function (state) {
                $scope.isLocked = Boolean(state);
            };

            $scope.loadTemplates = function () {
                var templates = [];
                $scope.templates[$scope.currentLocale.name] = templates;

                var addRecursively = function (tree) {
                    for (var i in tree) {
                        if (tree[i].type == 'question') {
                            for (var k in tree[i].children) {
                                templates.push({
                                    id: tree[i]['id'],
                                    title: tree[i]['name'],
                                    subject: tree[i]['parent']['name'],
                                    text: tree[i].children[k]['text']
                                });
                            }

                        }

                        if (tree[i].children) {
                            addRecursively(tree[i].children);
                        }
                    }
                };

                addRecursively($scope.templatesTree['common']);
                addRecursively($scope.templatesTree['personal']);

                $scope.lockTemplatesMenu(false);
            };

            $scope.setCurrentLocale = function (locale) {
                $scope.currentLocale = locale;
                $scope.load(locale);
            };

            $scope.getCurrentLocale = function () {
                return $scope.currentLocale;
            };

            // drop empty subjects and templates for currentLocale
            $scope.filterTreeData = function () {
                var localeName = $scope.getCurrentLocale().name;
                var templates = $scope.templates[localeName];

                angular.forEach(templates, function (template) {
                    if (isEmpty(template.text)) {
                        $scope.templatesVisibility[template.id] = !isEmpty(template.text);
                    }
                })
            };

            $scope.templateHasLocale = function (templateId) {
                var template = $scope.getTemplateByLocale(templateId);

                if (!template) {
                    return false;
                }

                return !isEmpty(template.text)
            };

            $scope.subjectHasLocale = function (subject) {
                for (var i in subject.templates) {
                    var template = subject.templates[i];

                    if ($scope.templateHasLocale(template.id)) {
                        return true;
                    }
                }

                return false;
            };

            $scope.closeTreeSubjects = function () {
                var belongs = ['common', 'personal'];

                for (var i in belongs) {
                    var belong = belongs[i];

                    for (var i in $scope.templatesTree[belong]) {
                        var itSubject = $scope.templatesTree[belong][i];
                        itSubject.expanded = false;
                    }
                }
            };

            $scope.getTemplateByLocale = function (templateId, localeName) {
                if (undefined === localeName) {
                    localeName = $scope.getCurrentLocale().name;
                }

                var templates = $scope.templates[localeName];

                return pick(templates, templateId);
            };

            $scope.useTemplate = function (template) {
                $scope.templatesSearch = '';
                $scope.onSelect(template);
                $scope.closeTreeSubjects();
                $scope.closeButtons();
            };

            $scope.useTemplateById = function (templateId) {
                var template = $scope.getTemplateByLocale(templateId);
                $scope.useTemplate(template)
            };

            $scope.toggleSubjectExpanded = function (subject, $event) {
                $event.stopPropagation();
                $scope.closeTreeSubjects();
                subject.expanded = !subject.expanded;
                $scope.expandRecursively(subject, subject.expanded);
            };

            $scope.expandRecursively = function (node, state) {
                node.expanded = state;
                if (node.parent) {
                    $scope.expandRecursively(node.parent, state);
                }
            };

            $scope.load = function (locale) {
                var localeName = locale
                    ? (locale.name || '')
                    : '';

                $http.get('/tickets/templates/templates-directive?' + $.param({locale: localeName})).then(
                    function (response) {
                        if (responseIsValid(response)) {
                            var data = getResponseData(response);
                            $scope.locales = data.locales;
                            $scope.templatesTree = data.templates_tree;
                            $scope.i18n = data.i18n;

                            for (var type in $scope.templatesTree) {
                                $scope.linkParentNodes($scope.templatesTree[type], null);
                            }

                            if (!locale) {
                                $scope.currentLocale = pick($scope.locales, data.default_locale, 'name');
                            }
                            $scope.loadTemplates();
                        }
                    }
                )
            };

            $scope.linkParentNodes = function (tree, parent) {
                for (var i in tree) {
                    if (tree[i].children) {
                        $scope.linkParentNodes(tree[i].children, tree[i]);
                    }

                    if (parent) {
                        tree[i].parent = parent;
                    }
                }
            };

            $scope.load();
        }
    };
}

function clickToConfirmPopupDirective() {
    return {
        restrict: 'A',
        scope: {
            popupTitle: '@',
            popupText: '@',
            onConfirm: '&onConfirm'
        },
        link: function (scope, element, attrs) {
            element.on('click', scope.showPopup)
        },
        controller: function ($scope, $modal) {
            function popupCtrl($scope, $modalInstance, text, title) {
                $scope.text = text;
                $scope.title = title;

                $scope.dismiss = function () {
                    $modalInstance.dismiss();
                };

                $scope.close = function () {
                    $modalInstance.close();
                };
            }

            $scope.showPopup = function () {
                $modal.open({
                    templateUrl: '/views/common/popups/confirm-popup.html',
                    controller: popupCtrl,
                    resolve: {
                        text: function () {
                            return $scope.popupText;
                        },
                        title: function () {
                            return $scope.popupTitle;
                        }
                    }
                }).result.then($scope.onConfirm);
            };
        }
    };
}

function iqPaginationDirective($location) {
    return {
        restrict: 'AE',
        templateUrl: '/admin/js/iq-pagination.html',
        scope: {
            maxButtons: '@',
            title: '@',
            pagesCount: '=',
            hidden: '=',
            boundaryLinks: '=',
            items: '=',
            onSelect: '&'
        },
        link: function (scope) {
            scope.maxButtons = 10;

            scope.getCurrentPage = function () {
                return $location.search().page;
            };

            scope.setCurrentPage = function (page) {
                $location.search('page', Number(page));
            };

            if (!scope.getCurrentPage()) {
                scope.setCurrentPage(1);
            }

            scope.buildButtons = function () {
                scope.buttons = [];

                for (var buttonNum = 1; buttonNum < scope.maxButtons; buttonNum++) {
                    var buttonText = scope.getCurrentPage() - scope.maxButtons / 2 + buttonNum;

                    if (buttonText < 1 || buttonText > scope.pagesCount) {
                        continue;
                    }

                    scope.buttons.push(buttonText);
                }
            };

            scope.Click = function (pageNum) {
                scope.setCurrentPage(pageNum);
                scope.onSelect({page: pageNum});
                scope.buildButtons();
            };

            scope.showFirstPage = function () {
                if (1 == scope.getCurrentPage()) {
                    return
                }

                scope.Click(1);
            };

            scope.showLastPage = function () {
                if (scope.pagesCount == scope.getCurrentPage()) {
                    return;
                }

                scope.Click(scope.pagesCount);
            };

            scope.buildButtons();

            scope.$watch('pagesCount', function (newValue, oldValue) {
                scope.buildButtons();
            });
        },
        controller: function ($scope) {

        }
    };
}

function timepickerDirective() {
    return {
        restrict: 'AE',
        templateUrl: '/views/timepicker/index.html',
        scope: {
            step: '@', //minutes
            utcOffset: '@', //minutes
            min: '@', //minutes
            max: '@' //minutes
        },
        link: function ($scope) {

        },
        controller: function ($scope) {
            $scope.step = Number($scope.step || '30');
            $scope.utcOffset = Number($scope.utcOffset || '0');
            $scope.min = convertTimeToMinutes($scope.min || '00:00');
            $scope.max = convertTimeToMinutes($scope.max || '24:00');
            $scope.time = {
                manager: '',
                trader: ''
            };

            $scope.list = getTimeList();

            $scope.$watch(function ($scope) {
                return $scope.time.manager;
            }, function (newValue, oldValue) {
                if (newValue.search(/^(((0|1)?[0-9])|(2[0-3])):[0-5][0-9]$/) !== -1) {
                    var time = factoryTimeObj(convertTimeToMinutes(newValue));
                    $scope.time.trader = time.trader;
                } else {
                    $scope.time.trader = 'hh:mm';
                }
            });

            $scope.onSelect = function (time) {
                $scope.time = angular.copy(time);
            };

            function getTimeList() {
                var values = [];
                var managerTime = $scope.min;

                while (managerTime < $scope.max) {
                    values.push(factoryTimeObj(managerTime));
                    managerTime += $scope.step;
                }

                values.push(factoryTimeObj($scope.max));

                return values;
            }

            function factoryTimeObj(managerTime) {
                var dayLimitInMinutes = 1440;
                var traderTime = managerTime + Number($scope.utcOffset);

                if (traderTime > dayLimitInMinutes) {
                    traderTime = traderTime - dayLimitInMinutes;
                }

                return {
                    manager: convertMinutesToTime(managerTime),
                    trader: convertMinutesToTime(traderTime)
                };
            }

            function convertTimeToMinutes(time) {
                var segments = time.split(':');
                return Number(segments[0]) * 60 + Number(segments[1]);
            };

            function convertMinutesToTime(minutes) {
                var m = minutes % 60;
                var h = (minutes - m) / 60;

                return timePad(h) + ':' + timePad(m);
            };

            function timePad(value) {
                return ('00' + value).substr(-2);
            };

        }
    }
}

var directiveIqCtrlEnter = function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if (event.which === 13 && event.ctrlKey) {
                scope.$apply(function () {
                    scope.$eval(attrs.iqCtrlEnter);
                });

                event.preventDefault();
            }
        });
    };
};

var sortingRowDirective = function ($location) {
    return {
        restrict: 'A',
        scope: true,
        link: function (scope, element, attrs) {
            var callback = attrs.callback;
            var buttons = element.find('[sort-name]');

            angular.forEach(buttons, function (item) {
                var element = angular.element(item);

                element.on('click', function () {
                    buttons.removeClass('sort-after-asc sort-after-desc');

                    var dir = scope.setCurrentSort(element.attr('sort-name'));

                    if (dir == DIR_ASC) {
                        element.addClass('sort-after-asc');
                    } else {
                        element.addClass('sort-after-desc');
                    }

                    if (callback) {
                        scope[callback]();
                    }
                });
            });
        },
        controller: function ($scope) {
            $scope.setCurrentSort = function (order) {
                var currentOrder = $location.search().order;
                var dir = (currentOrder == order)
                    ? switchDir($location.search().dir)
                    : DIR_ASC;
                $location.search('order', order);
                $location.search('dir', dir);

                return dir;
            };
        }
    };
};

var n2brFilter = function () {
    return function (text) {
        if (angular.isUndefined(text)) {
            return '';
        }

        return text.replace(/\n/g, '<br/>');
    }
};

var unsafeFilter = function ($sce) {
    return $sce.trustAsHtml;
};

var hhmmssFilter = function () {
    return function (num) {
        var sec_num = parseInt(num, 10); // don't forget the second param
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours < 10) {
            hours = "0" + hours;
        }

        if (minutes < 10) {
            minutes = "0" + minutes;
        }

        if (seconds < 10) {
            seconds = "0" + seconds;
        }

        return hours + ':' + minutes + ':' + seconds;
    }
};

function negativeColorFilter($sce) {
    return function (hexColor) {
        return $sce.trustAsHtml(negativeColor(hexColor));
    }
}

function newDateFilter() {
    return function (text) {
        return new Date(text);
    }
}

function capitalizeFilter() {
    return function (input) {
        return (!!input)
            ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase()
            : '';
    }
}

function searchFilter() {
    return function (text, needle) {
        return text.toLowerCase().search(needle.toLowerCase()) >= 0;
    }
}

function cropFilter() {
    return function (text, size) {
        if (!angular.isString(text)) {
            return '';
        }

        return text.length > size
            ? text.substr(0, size) + '...'
            : text;
    }
}

function fileSizeFilter() {
    return function (text, appendUnits) {
        var number = parseFloat(text);
        var mb = Math.pow(2, 20);
        var kb = Math.pow(2, 10);
        var size, units;

        switch (true) {
            case number > mb:
                size = Math.round10(number / mb, -1);
                units = 'Mb';
                break;
            case number > kb:
                size = Math.round10(number / kb);
                units = 'Kb';
                break;
            default:
                size = number;
                units = 'b';
        }

        return String(size).replace('\.', ',') + ' ' + units;
    }
}

function rangeFilter() {
    return function (input, total, begin) {
        input = [];
        total = parseInt(total);

        for (var i = begin; i < total; i++) {
            input.push(i);
        }

        return input;
    };
}

function adminSelectSimple() {
    return {
        restrict: 'E',
        scope: {
            trackBy: '@',
            labelText: '@',
            display: '@',
            asObject: '@',
            options: '=',
            outputModel: '=',
            onSelect: '&'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            if ($scope.trackBy === undefined) {
                $scope.trackBy = 'id';
            }

            if ($scope.display === undefined) {
                $scope.display = 'name';
            }

            if ($scope.asObject === undefined) {
                $scope.asObject = false;
            }

            if ($scope.asObject) {
                $scope.$watch('outputModel', function (newVal) {
                    $scope.outputModel = String(newVal)
                })
            }

            $scope.getNgOptionsExpression = function () {
                var expr = '';

                if ($scope.asObject) {
                    // as hash object  {1:'option1', key:'value'}. Notice key may be a string
                    expr = "key as value for (key, value) in options";
                } else {
                    // as array of objects  [{id:1, name:'option1', [...]}
                    expr = "item['" + $scope.trackBy + "'] as item['" + $scope.display + "'] for item in options";
                }

                return expr;
            };
        },
        templateUrl: '/views/common/forms/select.html'
    };
}

function adminInputSimple() {
    return {
        restrict: 'E',
        scope: {
            labelText: '@',
            outputModel: '=',
            onChange: '&'
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {

        },
        templateUrl: '/views/common/forms/text-input.html'
    };
}

function adminSelectTextInput() {
    return {
        restrict: 'E',
        scope: {
            trackBy: '@',
            display: '@',
            labelText: '@',
            placeholder: '@',
            outputModel: '=',
            options: '='
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.innerModel = {}; // need for scopes transclusion and scope issues... won't work without it

            if ($scope.trackBy === undefined) {
                $scope.trackBy = 'id';
            }

            if ($scope.display === undefined) {
                $scope.display = 'name';
            }

            $scope.$watch('innerModel.value', function (newVal) {
                $scope.outputModel = newVal;
            });

            $scope.$watch('outputModel', function (newVal) {
                $scope.innerModel.value = newVal;
            });
        },
        templateUrl: '/views/common/forms/select-text-input.html'
    };
}

function adminYesNoSelect() {
    return {
        restrict: 'E',
        scope: {
            labelText: '@',
            placeHolderText: '@',
            asNumber: '@',
            outputModel: '=',
            options: '='
        },
        link: function (scope, element, attrs) {

        },
        controller: function ($scope) {
            $scope.yesText = admin_i18n.yes;
            $scope.noText = admin_i18n.no;

            $scope.$watch('outputModel', function (newVal) {
                $scope.outputModel = Number(newVal);
            })
        },
        templateUrl: '/views/common/forms/yes-no-select.html'
    };
}

function adminSortingColumnDirective() {
    return {
        restrict: 'A',
        scope: {
            outputModel: '=',
            callback: '&',
            classes: '@',
            sort: '@',
            isDefault: '@',
            labelText: '@'
        },
        link: function (scope, element, attrs) {
        },
        controller: function ($scope, $timeout) {
            $scope.switchDir = function () {
                return $scope.outputModel.dir === DIR_ASC
                    ? DIR_DESC
                    : DIR_ASC;
            };

            $scope.onClickHandler = function () {
                $scope.outputModel.sort = $scope.sort;
                $scope.outputModel.dir = $scope.switchDir();
                $timeout($scope.callback, 0);
            };


            if (undefined === $scope.outputModel.sort && $scope.isDefault) {
                $scope.outputModel = {};
                $scope.onClickHandler();
            }
        },
        templateUrl: '/views/common/directives/admin-sorting-column.html'
    };
}

angular
    .module('meetings.directives', ['ngSanitize', 'ui.select', 'ui.bootstrap'])
    .directive('adminYesNoSelect', adminYesNoSelect)
    .directive('adminSelect', adminSelectSimple)
    .directive('adminInputSimple', adminInputSimple)
    .directive('adminTextInput', adminInputSimple) // deprecated
    .directive('adminSortingColumn', adminSortingColumnDirective)
    .directive('adminSelectTextInput', adminSelectTextInput) // deprecated
    .directive('adminLiveSelect', adminSelectTextInput)
    .directive('bootstrapCheckbox', bootstrapCheckboxDirective)
    .directive('toListButton', toListButtonDirective)
    .directive('saveButton', saveButtonDirective)
    .directive('deleteButton', deleteButtonDirective)
    .directive('addButton', addButtonDirective)
    .directive('editButton', editButtonDirective)
    .directive('toggleActiveButton', toggleActiveButtonDirective)
    .directive('clickToPrompt', clickToPromptDirective)
    .directive('blinder', blinderDirective)
;