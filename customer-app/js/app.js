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
        controller: 'EventPageController'
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


app.controller('EventController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading) {

	$scope.events = {};
	$ionicLoading.show({
		template: '<i class="icon ion-loading-c"></i>'
	});

	
	$http({
		method: 'jsonp',
		url: basepath + 'appevents?callback=JSON_CALLBACK',
		params: {
			"event": "list"			
		}
	}).success(function(data, status, header, config) {
		
		$ionicLoading.hide();
		$scope.events = data.events;			
		console.log($scope.events);
		
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

app.controller('EventPageController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading,$stateParams) {

	$scope.data = {};
	$scope.image = {};
	
	$ionicLoading.show({
		template: '<i class="icon ion-loading-c"></i>'
	});

	$scope.event_id = $stateParams.event_id;
	
	$http({
		method: 'jsonp',
		url: basepath + 'appevent/'+$scope.event_id+'?callback=JSON_CALLBACK',
		params: {
			"event": "list"			
		}
	}).success(function(data, status, header, config) {
		
		$ionicLoading.hide();
		$scope.data = data;
		//console.log($scope.data);
		$scope.image = angular.fromJson($scope.data.event.image);
		
		$scope.image.medium = [];
		$scope.image.large = [];
		$scope.image.small = [];
		
		angular.forEach($scope.image, function(value, key) {
			
			if(value.mode == "medium") $scope.image.medium.push(value.url);
			else if(value.mode == "large") $scope.image.large.push(value.url);
			else if(value.mode == "small") $scope.image.small.push(value.url);
			
		});
		
		
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

