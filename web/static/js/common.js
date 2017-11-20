function httpConfig($httpProvider){
    $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    $httpProvider.defaults.headers.common['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr('content');
}

function range(start, count) {
    return Array.apply(0, Array(count))
        .map(function (element, index) {
            return index + start;
        });
}

function cloneObject(obj) {
    if (null == obj || "object" != typeof obj) {
        return obj
    }

    var copy = obj.constructor();

    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }

    return copy;
}


function isEmpty (value) {
    if (value === null || value === undefined) {
        return true;
    }
    if (undefined !== value.length) {
        return value.length === 0;
    }
    if (value.prop && value.prop.constructor === Array) { // deprecated?
        return value.length === 0;
    }
    else if (typeof value === 'object') {
        return Object.keys(value).length === 0 && value.constructor === Object
    }
    else if (typeof value === 'string') {
        return value.length === 0;
    }
    else if (typeof value === 'number') {
        return value === 0 || isNaN(value);
    } else if (!value) {
        return true;
    }
    return false;
}

function trimString(string, length) {
    if (!string) {
        return '';
    }

    return string.length > length
        ? string.substring(0, length - 2) + '...'
        : string;
}

/**
 * hexColor #f0aa00 | f0aa00
 */
function negativeColor(hexColor) {
    if (hexColor.substr(0, 1) !== '#') {
        hexColor = '#' + hexColor;
    }

    var r = parseInt(hexColor.substr(1, 2), 16);
    var g = parseInt(hexColor.substr(3, 2), 16);
    var b = parseInt(hexColor.substr(5, 2), 16);
    var yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

    return yiq >= 128
        ? 'black'
        : 'white';
}

(function() {
    /**
     * Корректировка округления десятичных дробей.
     *
     * @param {String}  type  Тип корректировки.
     * @param {Number}  value Число.
     * @param {Integer} exp   Показатель степени (десятичный логарифм основания корректировки).
     * @returns {Number} Скорректированное значение.
     */
    function decimalAdjust(type, value, exp) {
        // Если степень не определена, либо равна нулю...
        if (typeof exp === 'undefined' || +exp === 0) {
            return Math[type](value);
        }
        value = +value;
        exp = +exp;
        // Если значение не является числом, либо степень не является целым числом...
        if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
            return NaN;
        }
        // Сдвиг разрядов
        value = value.toString().split('e');
        value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
        // Обратный сдвиг
        value = value.toString().split('e');
        return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
    }

    // Десятичное округление к ближайшему
    if (!Math.round10) {
        Math.round10 = function(value, exp) {
            return decimalAdjust('round', value, exp);
        };
    }
    // Десятичное округление вниз
    if (!Math.floor10) {
        Math.floor10 = function(value, exp) {
            return decimalAdjust('floor', value, exp);
        };
    }
    // Десятичное округление вверх
    if (!Math.ceil10) {
        Math.ceil10 = function(value, exp) {
            return decimalAdjust('ceil', value, exp);
        };
    }
})();

function isFunction(functionToCheck) {
    var getType = {};
    return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

/**
 * Check XHR response according to Techy Result class
 */
function responseIsValid(response) {
    if (isEmpty(response.data)) {
        return false;
    }

    return Boolean(response.data.isSuccessful);
}

/**
 * XHR response according to Techy Result class
 */
function getResponseMessage(response) {
    if (!response || isEmpty(response.data) || isEmpty(response.data.message)) {
        return '';
    }

    return String(response.data.message);
}

/**
 * Get data from XHR response according to Techy Result class
 */
function getResponseData(response) {
    if (!response || isEmpty(response.data)) {
        return false;
    }

    return response.data.result;
}

function parseSearchParams() {
    var match;
    var pl = /\+/g; // Regex for replacing addition symbol with a space
    var search = /([^&=]+)=?([^&]*)/g;
    var query = window.location.search.substring(1);
    var urlParams = {};

    var decode = function (s) {
        return decodeURIComponent(s.replace(pl, " "));
    };

    while (match = search.exec(query)) {
        urlParams[decode(match[1])] = decode(match[2]);
    }

    return urlParams;
}

function parseFormData(formSelector) {
    var data = {};

    $(formSelector).find('input, select, textarea').map(function (id, elem) {
        var name = $(elem).attr('name');

        if (undefined === name) {
            return;
        }

        data[name] = $(elem).val();
    });

    return data;
}

function toHHMMSS(num) {
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

/**
 *
 * @param array array
 * @param int value
 * @param string field
 * @returns {*}
 */
function pick(array, value, field) {
    if (undefined === field) {
        field = 'id'
    }

    for (var i in array) {
        var obj = array[i];

        if (obj.hasOwnProperty(field) && obj[field] === value) {
            return obj;
        }
    }
}

function array_last(array) {
    return array[array.length - 1];
}

function array_first(array) {
    return array[0];
}

function array_clone(array) {
    return array.slice(0);
}

function scrollToElement(selector) {
    $('html, body').animate({
        scrollTop: $(selector).offset().top
    }, 250);
}

function insertAtCaret(elementId, text) {
    var element = document.getElementById(elementId);

    if (document.selection) {
        element.focus();
        var selection = document.selection.createRange();
        selection.text = text;
    } else if (element.selectionStart || element.selectionStart === 0) {
        var startPos = element.selectionStart;
        var endPos = element.selectionEnd;
        var scrollTop = element.scrollTop;
        element.value = element.value.substring(0, startPos) + text + element.value.substring(endPos, element.value.length);
        element.selectionStart = startPos + text.length;
        element.selectionEnd = startPos + text.length;
        element.scrollTop = scrollTop;
    } else {
        element.value += text;
    }

    $('#' + elementId).trigger('input'); // force event for angular

    return true;
}

function countObject(obj) {
    if (!obj) {
        return false;
    }

    return Object.keys(obj).length;
}

function fileHasBrowserPreview(filename) {
    var re =/(.+)\.(gif|jpe?g|png|bmp|pdf)$/ig;
    return re.test(filename);
}

function fileIsImage(filename) {
    var re =/(.+)\.(gif|jpe?g|png|bmp)$/ig;
    return re.test(filename);
}

// form data to object
$.fn.serializeObject = function () {
    var object = {};
    var array = this.serializeArray();

    $.each(array, function () {
        if (object[this.name] !== undefined) {
            if (!object[this.name].push) {
                object[this.name] = [object[this.name]];
            }

            object[this.name].push(this.value || '');
        } else {
            object[this.name] = this.value || '';
        }
    });

    return object;
};