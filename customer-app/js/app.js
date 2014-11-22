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
	
	$stateProvider.state('events-cat', {
        url: '/events/:cat_id',
        templateUrl: 'events.html',
        controller: 'EventController'
    });
	
	$stateProvider.state('events-page', {
        url: '/event/:event_id',
        templateUrl: 'event-page.html',
        controller: 'EventPageController'
    });
	
	$stateProvider.state('events-page-details', {
        url: '/event/:event_id/details',
        templateUrl: 'event-page-details.html',
        controller: 'EventDetailsController'
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
	
	$stateProvider.state('tickets', {
        url: '/tickets',
        templateUrl: 'tickets.html',
        controller: 'TicketController'
    });
	
	$stateProvider.state('ticket', {
        url: '/ticket/:ticket_id',
        templateUrl: 'ticket.html',
        controller: 'TicketController'
    });
	
});

app.controller('AppController', function($rootScope, $scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicSideMenuDelegate) {

	$rootScope.event = {};//Empty on loadding
	$rootScope.events = {};//Empty on loadding
	$rootScope.user = {};
	$rootScope.user.email = "kpchamy.php@gmail.com";
	$rootScope.user.password = "123456";
	
	
	$rootScope.cart = [];
	$rootScope.total = 0;
	
		
	$scope.showProfile = function() {
		
		if($rootScope.user.session){
			
			$ionicSideMenuDelegate.toggleRight();
			
		}else {
		
			//$state.go('login');
			$ionicSideMenuDelegate.toggleRight();
		}
	};
	
	$scope.showCart = function(){
		$state.go('cart');
	};
	
});

app.controller('SplashController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    
	$rootScope.events = {};//Empty on loadding
	
	setTimeout(function(){
		$state.go('events');
	}, 3000);
	
	
	$scope.goBack = function() {
        $state.go('events');
    };
	
});


app.controller('LoginController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {


    $scope.goBack = function() {
        $state.go('events');
    };
	
	
});

app.controller('TicketController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$sce,$ionicLoading,$stateParams) {
    
	$scope.tickets = {};
	
	$scope.goBack = function() {
        $state.go('events');
    };
	
	$ionicLoading.show({
		template: '<i class="icon ion-loading-c"></i>'
	});
    
		
    
	
	
	$scope.ticket_id = $stateParams.ticket_id;
	if($scope.ticket_id)
	{
		$ionicLoading.hide();
		$scope.ticket = $sce.trustAsResourceUrl(basepath +"app/mytickets/"+$scope.ticket_id+"?email="+$rootScope.user.email+"&password="+$rootScope.user.password);		
		
	}else{	

			$http({
				method: 'jsonp',
				url: basepath + 'app/mytickets?callback=JSON_CALLBACK',
				params: {
					"email": $rootScope.user.email,		
					"password": $rootScope.user.password		
				}
			}).success(function(data, status, header, config) {
				
				$ionicLoading.hide();
				$scope.tickets = data;
				
			}).error(function(data, status, header, config) {
				$ionicLoading.hide();
				var alertPopup = $ionicPopup.alert({
					title: 'Network Error',
					template: 'Please check data connection'
				});
			});	
	}
	
	
});



app.controller('EventController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading,$ionicSideMenuDelegate) {


	
	$scope.events = {};
	$rootScope.event = {};//Empty the data
		
	
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
		$rootScope.events = $scope.events;//Assign to root scope
		//console.log($rootScope.events);
		
	}).error(function(data, status, header, config) {
		$ionicLoading.hide();
		var alertPopup = $ionicPopup.alert({
			title: 'Network Error',
			template: 'Please check data connection'
		});
	});
	
	$scope.showCategory = function() {
		$ionicSideMenuDelegate.toggleLeft();
	};
	
	
	
});

app.controller('EventPageController', function($scope,$rootScope,$state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading,$stateParams,$sce) {

	$scope.data = {};
	$scope.image = {};
	$scope.total = 0;
	$scope.selected = {};
	
	//console.log($rootScope.events);

	if(Object.keys($rootScope.event).length === 0){	

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
			$rootScope.event = $scope.data;//Assing to root scope
					
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
		
		
	}else{

		
		$scope.data = $rootScope.event;//Assing to root scope
		$scope.image = angular.fromJson($scope.data.event.image);

		$scope.image.medium = [];
		$scope.image.large = [];
		$scope.image.small = [];
		
		angular.forEach($scope.image, function(value, key) {
			
			if(value.mode == "medium") $scope.image.medium.push(value.url);
			else if(value.mode == "large") $scope.image.large.push(value.url);
			else if(value.mode == "small") $scope.image.small.push(value.url);
			
		});
			
	}//In Cache
	
	$scope.selectBox = function(from,to) {
		var select = "";
		for(i=0;i<=to;i++){
			select+="<option value='"+i+"'>"+i+"</option>";
		}
        return $sce.trustAsHtml(select);
    };	
	
	
	$scope.updatePrice = function(event_ticket_id,price){
		
		$scope.total = 0;
		var $el = angular.element(document.querySelectorAll('.sell_tickets'));
	
		for(i=0;i<$el.length;i++){	
			
			
			var ticket = $el[i];
		
			var quantity = Number(ticket.value);
			var price = ticket.attributes.price.value;		
			var event_ticket_id = ticket.attributes.event_ticket_id.value;
			var event_id = ticket.attributes.event_id.value;	
			
			if(quantity > 0){
				$rootScope.cart.push(
					{
						"event_id":event_id,
						"event_ticket_id":event_ticket_id,
						"price":price,
						"quantity":quantity
					}
				);
				
				$scope.total+= quantity*price;
			}
			//console.log($rootScope.cart);
			
		};//Loop
	
		$rootScope.total = $scope.total;
		
	}
	
	$scope.buy = function() {
        $state.go('cart');		 
    };
	
	
    $scope.goBack = function() {
        $state.go('events');		 
    };
	
	
	
	//console.log($rootScope.events);
	
});

//Details of Event
app.controller('EventDetailsController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$stateParams) {

	$scope.data = $rootScope.event;
	$scope.image = {};
	$scope.event_id = $stateParams.event_id;
	
	
	
	$scope.image = angular.fromJson($scope.data.event.image);
		
	$scope.image.medium = [];
	$scope.image.large = [];
	$scope.image.small = [];

	
	angular.forEach($scope.image, function(value, key) {
		
		if(value.mode == "medium") $scope.image.medium.push(value.url);
		else if(value.mode == "large") $scope.image.large.push(value.url);
		else if(value.mode == "small") $scope.image.small.push(value.url);
		
		
	});
		
	
    $scope.goEventPage = function() {
		$state.go('events-page',{event_id:$scope.event_id});		 
    };
	
	
	
});




app.controller('CartController', function($scope, $rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$filter) {
	
	$scope.total = $rootScope.total;
	$scope.events = $rootScope.events;//Get all events
	$scope.cart = $rootScope.cart;//Get all cart
	
	$scope.items = [];
	
	//console.log($rootScope.events);
	
	angular.forEach($scope.cart, function(value, key) {
		var found = $filter('filter')($scope.events,function(data){
			return data.Event.event_id == value.event_id;
		});
		//$filter('filter')(foo.results, {id: 1})[0];
		console.log(found);
		
		$scope.items.push({
			"event_id":value.event_id,
			"event_ticket_id":value.event_ticket_id,
			"price":value.price,
			"quantity":value.quantity,
			"title":found[0].Event.title,
			"date":found[0].Event.date,
			"image":found[0].Event.cart
			}	
		);
		
		
		
	});
	
	
	
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

