var app = angular.module('myApp',[]);
        //envia comentarios
        app.controller('postComentarios', function($scope, $http){
            $scope.insertData=function(){
              if($scope.comentario!=null)
              {
                $http.post(base_url+"videos/post_comentario",
                {'video_id_video':video,
                'comentario':$scope.comentario})
                .success(
                function(data,status,headers,config)
                {
                  $scope.comentario = " ";
                });
                $scope.comentario = " ";
              }
            }
        });
        //envia comentarios
        app.controller('postLike', function($scope, $http){
            $scope.likeUp=function(){
                $http.post(base_url+"videos/post_like",
                {'video_id_video':video,
                  'id_user':id_user})
                .success(
                function(data,status,headers,config)
                {
                  //console.log("like");
                });
            },
            $scope.likeDown=function(){
              $http.post(base_url+"videos/post_not_like",
                {'video_id_video':video,
                  'id_user':id_user})
                .success(
                function(data,status,headers,config)
                {
                  //console.log("not like");
                });
            }
        });
        //obtiene like
        app.controller('getLikes', function ($scope, $http, $interval){
            $scope.get_Likes = function()
            {
              $scope.likes;
              //console.log(base_url+"videos/get_like/"+path);
              $http.get(base_url+"videos/get_like/"+path).success(
              function resultados(result)
              {
                $scope.likes = result[0].likes;
              }); 
            };
          setInterval($scope.get_Likes, 1000);
        });
        //obtiene comentarios
        app.controller('getNotLikes', function ($scope, $http, $interval){
            $scope.get_Not_Likes = function()
            {
              $scope.likes;
              //console.log(base_url+"videos/get_not_like/"+path);
              $http.get(base_url+"videos/get_not_like/"+path).success(
              function resultados(result)
              {
                $scope.not_like = result[0].not_like;
              }); 
            };
          setInterval($scope.get_Not_Likes, 1000);
        });
        //obtiene comentarios
        app.controller('getComentarios', function get_coments($scope, $http, $interval){
            $scope.getData = function()
            {
              $scope.list_data;
              //console.log(base_url+"videos/comentarios/"+path);
              $http.get(base_url+"videos/comentarios/"+path).success(
              function resultados(result)
              {
                $scope.list_data = result;
              });
            };
          setInterval($scope.getData, 1000);
        });
        //obtiene comentarios
        app.controller('getVisitas', function ($scope, $http, $interval){
            $scope.get_visitas = function()
            {
              $scope.likes;
              //console.log(base_url+"videos/get_not_like/"+path);
              $http.get(base_url+"videos/get_visitas/"+path).success(
              function resultados(result)
              {
                //console.log(result[0].visitas);
                $scope.visitas = result[0].visitas;
              }); 
            };
          setInterval($scope.get_visitas, 1000);
        });