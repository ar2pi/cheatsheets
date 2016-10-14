/*
 * AngularJS memento
 * https://angularjs.org/
 */

// js/app.js
var app = angular.module("myApp", ['ngRoute']);

app.config(function ($routeProvider) {
	$routeProvider
		.when('/', {
  		controller: 'HomeController',
  		templateUrl: 'views/home.html'
  	})
  	.when('/photos/:id', {
  		controller: 'PhotoController',
  		templateUrl: 'views/photo.html'
  	})
		.otherwise({
  		redirectTo: '/'
  	});
});

// js/controller/MainController.js

app.controller('MainController', ['$scope', function($scope) { 
  $scope.title = 'My very own title';
  $scope.promo = 'This be the promo propertie';
  $scope.products =
  [
    {
      name: 'The Book of Trees',
      price: 19,
      pubdate: new Date('2014', '03', '08'),
      cover: 'img/the-book-of-trees.jpg',
      likes: 0,
      dislikes: 0
    },
    {
      name: 'Program or be Programmed',
      price: 8,
      pubdate: new Date('2013', '08', '01'),
      cover: 'img/program-or-be-programmed.jpg',
      likes: 0,
      dislikes: 0
    }
  ];
  $scope.plusOne = function(index) {
  $scope.products[index].likes += 1;
  };
  $scope.minusOne = function(index) {
  $scope.products[index].dislikes += 1;
  };
}]);

// js/directives/appInfo.js

app.directive('appInfo', function() {
	return {
  	restrict: 'E',
  	scope: {
    	info: '='
    },
  	templateUrl: 'js/directives/appInfo.html'
  };
});

// js/directives/installApp.js

app.directive('installApp', function() {
	return {
  	restrict: 'E',
  	scope: {},
  	templateUrl: 'js/directives/installApp.html',
    
    link: function(scope, element, attrs) {
    	scope.buttonText = "Install",
    	scope.installed = false,
      
   		scope.download = function() {
      	element.toggleClass('btn-active');
        if(scope.installed) {
        	scope.buttonText = "Install";
        	scope.installed = false;
        } else {
        	scope.buttonText = "Uninstall";
        	scope.installed = true;
        }
      }
    }
  };
});

// js/services/forecast.js

app.factory('forecast', ['$http', function($http) {
	return $http.get('http://s3.amazonaws.com/codecademy-content/courses/ltp4/forecast-api/forecast.json')
		    .success(function(data) {
		      return data;
		    })
		    .error(function(err) {
		      return err;
		    });
}]);

// js/services/photos.js

app.factory('photos', ['$http', function($http) {
  return $http.get('http://s3.amazonaws.com/codecademy-content/courses/ltp4/photos-api/photos.json')
         .success(function(data) {
           return data;
         })
         .error(function(data) {
           return data;
         });
}]);

// js/controller/HomeController.js
app.controller('HomeController', ['$scope', 'photos', function($scope, photos) {
  photos.success(function(data) {
    $scope.photos = data;
  });
}]);

// js/controller/PhotoController.js
app.controller('PhotoController', ['$scope', 'photos', '$routeParams', function($scope, photos, $routeParams) {
  photos.success(function(data) {
    $scope.detail = data[$routeParams.id];
  });
}]);


// index.html

// 1
<!doctype html>
<html>
  <head>
      <link href="http://s3.amazonaws.com/codecademy-content/projects/bootstrap.min.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Roboto:500,300,700,400' rel='stylesheet' type='text/css'>
    <link href="css/main.css" rel="stylesheet" />

    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
  </head>
  <body ng-app="myApp">
    <div class="header">
      <div class="container">
        <h1>Book End</h1>
      </div>
    </div>

    <div class="main" ng-controller="MainController">
      <div class="container">

        <h1>{{ title }}</h1>
        <h2>{{ promo }}</h2>

        
        <div ng-repeat="product in products" class="col-md-6">
          <div class="thumbnail">
            <img ng-src="{{ product.cover }}">
            <p class="title">{{ product.name }}</p>
            <p class="price">{{ product.price | date }}</p>
            <p class="date">{{ product.pubdate | date }}</p>
            <div class="rating"> 
              <p class="likes" ng-click="plusOne($index)">+ {{ product.likes }}</p> 
            	<p class="dislikes" ng-click="minusOne($index)">- {{ product.dislikes }}</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="footer">
      <div class="container">
        <h2>Available for iPhone and Android.</h2>
        <img src="http://s3.amazonaws.com/codecademy-content/projects/shutterbugg/app-store.png" width="120px" />
        <img src="http://s3.amazonaws.com/codecademy-content/projects/shutterbugg/google-play.png" width="110px" />
      </div>
    </div>


    <!-- Modules -->
    <script src="js/app.js"></script>

    <!-- Controllers -->
    <script src="js/controllers/MainController.js"></script>
  </body>
</html>

// 2
<!doctype html>
<html>
  <head>
    <link href="http://s3.amazonaws.com/codecademy-content/projects/bootstrap.min.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />

    <!-- Include the AngularJS library -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
  </head>
  <body ng-app="AppMarketApp">
    <div class="header">
      <div class="container">
        <h1>App Market</h1>
      </div>
    </div>

    <div class="main" ng-controller="MainController">
      <div class="container">

        <div class="card" ng-repeat="app in apps">
          <app-info info="app"></app-info>
          <install-app></install-app>
        </div>

      </div>
    </div>

    <!-- Modules -->
    <script src="js/app.js"></script>

    <!-- Controllers -->
    <script src="js/controllers/MainController.js"></script>

    <!-- Directives -->
    <script src="js/directives/appInfo.js"></script>
    <script src="js/directives/installApp.js"></script>

  </body>
</html>

// appInfo.html
<img class="icon" ng-src="{{ info.icon }}">
<h2 class="title">{{ info.title }}</h2>
<p class="developper">{{ info.developper }}</p>
<p class="price">{{ info.price | currency }}</p>

// installApp.html
<button class="btn btn-active" ng-click="download()">
  {{ buttonText }}
</button>

// 3
<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://s3.amazonaws.com/codecademy-content/projects/bootstrap.min.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />

    <!-- Include the AngularJS library -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>
  </head>
  <body ng-app="ForecastApp">

    <div class="main" ng-controller="MainController">
      <div class="container">
        <div class="row">
          <div class="col-sm-5 col-sm-offset-7">
            <h1>{{ fiveDay.city_name }}</h1>
            <h2>5-day forecast</h2>
            <div class="forecast" ng-repeat="day in fiveDay.days">
              <div class="day row">
                
                <!-- datetime -->
                <div class="weekday col-xs-4">
								{{ day.datetime | date }}
                </div>

                <!-- icon -->
                <div class="weather col-xs-3">
								<img ng-src="{{ day.icon }}">
                </div>

                <div class="col-xs-1"></div>

                <!-- high -->
                <div class="high col-xs-2">
								{{ day.high }}
                </div>

                <!-- low -->
                <div class="low col-xs-2">
								{{ day.low }}
                </div>
                
              </div>
            </div>
          </div>
        </div>
        </ul>
      </div>
    </div>

    <!-- Modules -->
    <script src="js/app.js"></script>

    <!-- Controllers -->
    <script src="js/controllers/MainController.js"></script>

    <!-- Services -->
    <script src="js/services/forecast.js"></script>

    <!-- Directives -->


  </body>
</html>

// 4
<!doctype html>
<html>
  <head>
    <link href="http://s3.amazonaws.com/codecademy-content/projects/bootstrap.min.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,300' rel='stylesheet' type='text/css'>
    <link href="css/main.css" rel="stylesheet" />

    <!-- Include the core AngularJS library -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.3.5/angular.min.js"></script>

    <!-- Include the AngularJS routing library -->
    <script src="https://code.angularjs.org/1.2.28/angular-route.min.js"></script>
  </head>
  <body ng-app="GalleryApp">

    <div class="header">
      <div ng-view></div>
      <div class="container">
        <a href="/#/">
          <img src="img/logo.svg" width="80" height="80"> &#12501; &#65387; &#12488; &#12501; &#65387; &#12488;
        </a>
      </div>
    </div>



    <!-- Modules -->
    <script src="js/app.js"></script>

    <!-- Controllers -->
    <script src="js/controllers/HomeController.js"></script>
    <script src="js/controllers/PhotoController.js"></script>

    <!-- Services -->
    <script src="js/services/photos.js"></script>

  </body>
</html>