directives.directive('breadcrum', ['$appLocation', function ($appLocation) {
        return {
            templateUrl: CONFIG.prepareViewTemplateUrl('directives/breadcrum')
        };
    }]).directive('minmaxLength', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, mCtrl) {

            function myValidation(value) {


                if (typeof value !== 'undefined' && value != null)
                {


                    if (value.toString().length == attr.minmaxLength) {

                        mCtrl.$setValidity('elementLength', true);

                    } else {

                        mCtrl.$setValidity('elementLength', false);

                    }
                }
                return value;
            }
            mCtrl.$parsers.push(myValidation);
        }
    };
})