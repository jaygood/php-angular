angular.module('myApp')
  .controller('MyController', ['$scope', 'DataService',
    function($scope, DataService){
      $scope.products = [];
      $scope.product = {};
      $scope.result = {};

      $scope.getAllData = function(){
        DataService.read(function(data){
          if(data.code == 1){
            $scope.products = data.data;
          }
        });
      };

      $scope.edit = function(product){
        $scope.product = product;
      };

      $scope.delete = function(id){
        var confirmed = confirm('Are you sure?');

        if(confirmed){
          DataService.delete(id, function(data){
            if(data.code == 1){
              alert('deleted');
            }
            else{
              alert('problem');
            }
          });
        }
      };

      $scope.save = function(){
        DataService.save($scope.product, function(data){

          if(data.code == 1){
            alert('saved');
          }
          else{
            alert('failed')
          }

          $scope.result = data;
          $scope.product = {};
        });
      };
    }]);
