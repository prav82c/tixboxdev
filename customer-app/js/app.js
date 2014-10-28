var basepath = "http://dev.tixbox.co/";

var app = angular.module('myApp', ['ionic']);

app.config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/');

    $stateProvider.state('splash', {
        url: '/',
        templateUrl: 'splash.html',
        controller: 'SplashController'
    });
	
	$stateProvider.state('login', {
        url: '/login',
        templateUrl: 'login.html',
        controller: 'LoginController'
    });
	
	$stateProvider.state('events', {
        url: '/events',
        templateUrl: 'events.html',
        controller: 'EventController'
    });
	
	$stateProvider.state('events-page', {
        url: '/events/:event_id',
        templateUrl: 'event-page.html',
        controller: 'EventController'
    });
	
	$stateProvider.state('events-page-details', {
        url: '/events/:event_id/t_eveDetails',
        templateUrl: 'event-page-details.html',
        controller: 'EventController'
    });
	
	$stateProvider.state('cart', {
        url: '/cart',
        templateUrl: 'cart.html',
        controller: 'CartController'
    });
	
	$stateProvider.state('checkout', {
        url: '/checkout',
        templateUrl: 'checkout.html',
        controller: 'CheckoutController'
    });
	
	$stateProvider.state('receipt', {
        url: '/receipt',
        templateUrl: 'receipt.html',
        controller: 'CheckoutController'
    });
	
	
	$stateProvider.state('profile', {
        url: '/profile',
        templateUrl: 'profile.html',
        controller: 'ProfileController'
    });
	
	$stateProvider.state('profile-edit', {
        url: '/profile/edit',
        templateUrl: 'profile-edit.html',
        controller: 'ProfileController'
    });
	
	
	$stateProvider.state('profile-wallet', {
        url: '/profile/wallet',
        templateUrl: 'profile-wallet.html',
        controller: 'ProfileController'
    });
	
	$stateProvider.state('profile-ticket', {
        url: '/profile/ticket',
        templateUrl: 'profile-ticket.html',
        controller: 'ProfileController'
    });
	
	$stateProvider.state('profile-wishlist', {
        url: '/profile/wishlist',
        templateUrl: 'profile-wishlist.html',
        controller: 'ProfileController'
    });
	
});

app.controller('AppController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});

app.controller('SplashController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    
	
	setTimeout(function(){
		$state.go('events');
	}, 3000);
	
	
	$scope.goBack = function() {
        $state.go('events');
    };
	
});


app.controller('LoginController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});


app.controller('EventController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {

	$scope.results = {};
	$ionicLoading.show({
		template: '<i class="icon ion-loading-c"></i>'
	});

	
	$http({
		method: 'jsonp',
		url: basepath + 'get/categories?callback=JSON_CALLBACK',
		params: {
			"lang": lang,
			"args[parent]": "0"
		}
	}).success(function(data, status, header, config) {
		
		$ionicLoading.hide();
		$scope.results = data.results;			
		
	}).error(function(data, status, header, config) {
		$ionicLoading.hide();
		var alertPopup = $ionicPopup.alert({
			title: 'Network Error',
			template: 'Please check data connection'
		});
	});
		
		
	
    $scope.goBack = function() {
        $state.go('events');
    };
	
	
	
});


app.controller('CartController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});

app.controller('CheckoutController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});

app.controller('ReceiptController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});


app.controller('ProfileController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});

