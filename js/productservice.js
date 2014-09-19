angular.module('myApp', [])

  .service('DataService', ['$http', function($http){
    this.save = function(data, callback){
      var postdata = {
        'op': 'save',
        'data': data
      };

      $http({
        method: 'POST',
        url: 'php/api.php',
        data: postdata,
        headers: {
          'Content-type': 'application/json'
        }
      })
        .success(function(resp){
          callback(resp);
        })
        .error(function(){
          callback(undefined);
        });
    };

    this.read = function(callback){
      var postdata = {
        op: 'getproducts'
      };

      $http({
        method: 'POST',
        url: 'php/api.php',
        data: JSON.stringify(postdata),
        headers: {
          'Content-type': 'application/json'
        }
      })
        .success(function(resp){
          callback(resp);
        })
        .error(function(){
          callback(undefined);
        });
    };

    this.delete = function(id, callback){
      var postdata = {
        op: 'delete',
        id: id
      };

      $http({
        method: 'POST',
        url: 'php/api.php',
        data: JSON.stringify(postdata),
        headers: {
          'Content-type': 'application/json'
        }
      })
        .success(function(resp){
          callback(resp);
        })
        .error(function(){
          callback(undefined);
        });
    };
  }]);
