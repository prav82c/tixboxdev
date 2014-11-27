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
	
	$stateProvider.state('wishlist', {
        url: '/wishlist',
        templateUrl: 'wishlist.html',
        controller: 'WishlistController'
    });
	
	$stateProvider.state('wallet', {
        url: '/wallet',
        templateUrl: 'wallet.html',
        controller: 'WalletController'
    });
	
	
});

app.controller('AppController', function($rootScope, $scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicSideMenuDelegate) {


	var email = window.localStorage.getItem("email");
    var password = window.localStorage.getItem("password");

	$rootScope.event = {};//Empty on loadding
	$rootScope.events = {};//Empty on loadding
	
	$rootScope.user = {};//User Details
	$rootScope.login = {};//Login Details
	$rootScope.user.email = "kpchamy.php@gmail.com";
	$rootScope.user.password = "123456";
	$rootScope.user.session_id = "";
	
	if(email && password)
	{
		$rootScope.user.email = email;
		$rootScope.user.password = password;		
	}
	
	
	$rootScope.cart = [];
	$rootScope.total = 0;
	
		
	$scope.showProfile = function() {
		
		if($rootScope.user.session_id){
			
			$ionicSideMenuDelegate.toggleRight();
			
		}else {
		
			$state.go('login');
			//$ionicSideMenuDelegate.toggleRight();
		}
	};
	
	$scope.showCart = function(){
		$state.go('cart');
	};
	
});

app.controller('SplashController', function($scope,$rootScope,$state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading) {
    
		$rootScope.events = {};//Empty on loadding
	
		setTimeout(function(){
			$state.go('events');		
		}, 3000);
		
		$scope.goBack = function() {
			$state.go('events');
		};

		$http({
			method: 'jsonp',
			url: basepath + 'applogin?callback=JSON_CALLBACK',
			params: {"email": $scope.user.email, "password": $scope.user.password}
		}).success(function(data, status, header, config) {
		$ionicLoading.hide();
		if (data.error == 0)
		{
			
		} else {

			//Local Storage
			window.localStorage.setItem("email", $scope.user.email);
			window.localStorage.setItem("password", $scope.user.password);
			
			$rootScope.login = data;
			$rootScope.user.session_id = $scope.login.session_id;
			console.log("Session:"+$rootScope.user.session_id);
			//Redirect to events page
			$state.go('events');
		}

		}).error(function(data, status, header, config) {
			$ionicLoading.hide();
			var alertPopup = $ionicPopup.alert({
				title: 'Network Error',
				template: 'Please check data connection'
			});
		});
	
});


app.controller('LoginController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading) {

	$scope.login = {};

	$scope.goBack = function() {
		$state.go('events');
	};
	

	$scope.onButton1Click = function(user) {
		
		$ionicLoading.show({
			template: '<i class="icon ion-android-timer"></i><br/>LOADING'
		});
		
		
		$http({
			method: 'jsonp',
			url: basepath + 'applogin?callback=JSON_CALLBACK',
			params: {"email": $scope.user.email, "password": $scope.user.password}
		}).success(function(data, status, header, config) {
		$ionicLoading.hide();
		if (data.error == 0)
		{
			var alertPopup = $ionicPopup.alert({
				title: 'Login Failed',
				template: 'Invalid Credentials'
			});
			alertPopup.then(function(res) {
				$scope.user.email = "";
				$scope.user.password = "";
			});

		} else {

			//Local Storage
			window.localStorage.setItem("email", $scope.user.email);
			window.localStorage.setItem("password", $scope.user.password);
			
			$scope.login = data;
			$rootScope.login = $scope.login;
			$rootScope.user.session_id = $scope.login.session_id;
			console.log("Login Session:"+$rootScope.user.session_id);
			//Redirect to events page
			$state.go('events');
		}

		}).error(function(data, status, header, config) {
			$ionicLoading.hide();
			var alertPopup = $ionicPopup.alert({
				title: 'Network Error',
				template: 'Please check data connection'
			});
		});

	};
	
	
	
	
});


app.controller('ProfileController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading) {
   
   $scope.profile = {};

   $scope.goBack = function() {
        $state.go('events');
    };
	
	$ionicLoading.show({
		template: '<i class="icon ion-android-timer"></i><br/>LOADING'
	});
	
	$http({
		method: 'jsonp',
		url: basepath + 'app/profile/modify?callback=JSON_CALLBACK',
		params: {
			"email": $rootScope.user.email,		
			"password": $rootScope.user.password		
		}
	}).success(function(data, status, header, config) {
		
		$ionicLoading.hide();
		$scope.profile = data;
		console.log($scope.profile);
		
	}).error(function(data, status, header, config) {
		$ionicLoading.hide();
		var alertPopup = $ionicPopup.alert({
			title: 'Network Error',
			template: 'Please check data connection'
		});
	});
	
});




app.controller('WishlistController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading,$stateParams) {

	$scope.wishlist = {};

    $scope.goBack = function() {
        $state.go('events');
    };
	
	
	$ionicLoading.show({
		template: '<i class="icon ion-android-timer"></i><br/>LOADING'
	});
	
	
	$http({
		method: 'jsonp',
		url: basepath + 'app/mytickets?callback=JSON_CALLBACK',
		params: {
			"email": $rootScope.user.email,		
			"password": $rootScope.user.password		
		}
	}).success(function(data, status, header, config) {
		
		$ionicLoading.hide();
		$scope.wishlist = data;
		console.log($scope.wishlist);
	}).error(function(data, status, header, config) {
		$ionicLoading.hide();
		var alertPopup = $ionicPopup.alert({
			title: 'Network Error',
			template: 'Please check data connection'
		});
	});	
	
	
	
});





app.controller('TicketController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$sce,$ionicLoading,$stateParams) {
    
	$scope.tickets = {};
	
	$scope.goBack = function() {
        $state.go('events');
    };
	
	$ionicLoading.show({
		template: '<i class="icon ion-android-timer"></i><br/>LOADING'
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
		template: '<i class="icon ion-android-timer"></i><br/>LOADING'
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
	$scope.event_date_id = "";
	$scope.choose = {};
	
	//console.log($rootScope.events);
	
	$scope.makeChanged = function(){
		$scope.event_date_id = $scope.choose.event_date.event_date_id;
	}

	if(Object.keys($rootScope.event).length === 0){	

		$ionicLoading.show({
			template: '<i class="icon ion-android-timer"></i><br/>LOADING'
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
			
			$scope.choose = $scope.data.event_dates[0];
			//console.log($scope.data);
			
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
		$scope.choose = $scope.data.event_dates[0];
		
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
			var event_date_id = $scope.event_date_id;	
						
			if(quantity > 0){
				$rootScope.cart.push(
					{
						"event_date_id":event_date_id,
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




app.controller('CartController', function($scope, $rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$filter,$sce,$ionicLoading,$stateParams) {
	
	$scope.total = $rootScope.total;
	$scope.events = $rootScope.events;//Get all events
	$scope.cart = $rootScope.cart;//Get all cart
	$scope.cartpage = "";//URL for cart page
	
	$scope.items = [];
	
	angular.forEach($scope.cart, function(value, key) {
		var found = $filter('filter')($scope.events,function(data){
			return data.Event.event_id == value.event_id;
		});
		//$filter('filter')(foo.results, {id: 1})[0];
		//console.log(found);
		
		$scope.items.push({
			"event_date_id":value.event_date_id,
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
	
	console.log($rootScope.cart);
	
	$http({
		method: 'jsonp',
		url: basepath + 'app/cart/add?callback=JSON_CALLBACK',
		params: {
			"session_id": $rootScope.user.session_id,
			"cart": JSON.stringify($scope.items),
			"email": $rootScope.user.email,		
			"password": $rootScope.user.password		
		}
	}).success(function(data, status, header, config) {
		
		alert("Loadded In Car");
		$scope.cartpage = $sce.trustAsResourceUrl(basepath +"app/cart?session_id="+$rootScope.user.session_id+"&email="+$rootScope.user.email+"&password="+$rootScope.user.password);	
		
		
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


app.controller('WalletController', function($scope,$rootScope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$ionicLoading,$stateParams) {

	
	$scope.wallet = {};
	$scope.wallet_amount = {};

    $scope.goBack = function() {
        $state.go('events');
    };
	
	
	$ionicLoading.show({
		template: '<i class="icon ion-android-timer"></i><br/>LOADING'
	});
	
	$http({
		method: 'jsonp',
		url: basepath + 'app/account/wallet?callback=JSON_CALLBACK',
		params: {
			"email": $rootScope.user.email,		
			"password": $rootScope.user.password		
		}
	}).success(function(data, status, header, config) {
		
		$ionicLoading.hide();
		$scope.wallet = data;
		console.log($scope.wallet);
		
	}).error(function(data, status, header, config) {
		$ionicLoading.hide();
		var alertPopup = $ionicPopup.alert({
			title: 'Network Error',
			template: 'Please check data connection'
		});
	});	
	
	
	
});

app.controller('CheckoutController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate,$sce) {

    $scope.goBack = function() {
        $state.go('wallet');
    };
	
	var amount = $stateParams.amount;
	var path = document.location.origin+document.location.pathname; 
	
	$scope.checkout = $sce.trustAsResourceUrl(basepath +"app/wallet/checkout?path="+path+"&amount="+amount+"&&email="+$rootScope.user.email+"&password="+$rootScope.user.password);		
	
	
});

app.controller('ReceiptController', function($scope, $state, $http, $ionicPopup, $rootScope, $ionicViewService, $ionicNavBarDelegate) {
    $scope.goBack = function() {
        $state.go('events');
    };
});



