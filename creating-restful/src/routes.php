<?php
    use Slim\Http\Request;
    use Slim\Http\Response;
   
    
    $app->options('/{routes:.+}', function ($request, $response, $args) {
                  return $response;
                  });
    $app->add(function ($req, $res, $next) {
              $response = $next($req, $res);
              return $response
              ->withHeader('Access-Control-Allow-Origin', '*')
              ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
              ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
              });
    


     //------------POST----------------------------

  //TESTED
    //Add User
    $app->post('/User/new', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO Users (FirstName, LastName, Email, UserPassword) VALUES (:FirstName, :LastName, :Email, :UserPassword)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("FirstName", $input['FirstName']);
        $sth->bindParam("LastName", $input['LastName']);
        $sth->bindParam("Email", $input['Email']);
        $sth->bindParam("UserPassword", $input['UserPassword']);
        $sth->execute();
        return $this->response->withJson($input);
        });


    //TESTED
    //Add User ingredient
    $app->post('/UserIngredient/new', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO UserIngredients (Email, IngredientName) VALUES (:Email, :IngredientName)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("Email", $input['Email']);
        $sth->bindParam("IngredientName", $input['IngredientName']);
        $sth->execute();
        return $this->response->withJson($input);
        });

    //TESTED
     //Add User favorite
     $app->post('/UserFavorite/new', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO UserFavorites (Email, RecipeId) VALUES (:Email, :RecipeId)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("Email", $input['Email']);
        $sth->bindParam("RecipeId", $input['RecipeId']);
        $sth->execute();
        return $this->response->withJson($input);
        });


    //TESTED
    //Add recipe image
    $app->post('/RecipeImage/new', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO RecipeImages (ImageUrl, RecipeId) VALUES (:ImageUrl, :RecipeId)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("ImageUrl", $input['ImageUrl']);
        $sth->bindParam("RecipeId", $input['RecipeId']);
        $sth->execute();
        return $this->response->withJson($input);
        });

        //TESTED
    //Add recipe image
    $app->post('/ReviewedRecipe/new', function ($request, $response) {
        $input = $request->getParsedBody();
        $sql = "INSERT INTO UserPastRecipes (Email, RecipeId) VALUES (:Email, :RecipeId)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("Email", $input['Email']);
        $sth->bindParam("RecipeId", $input['RecipeId']);
        $sth->execute();
        return $this->response->withJson($input);
        });




    //------------GET----------------------------

    //TESTED
    //Get accounts 
     $app->get('/Users', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('Email');
        $sth= $this->db->prepare("SELECT * FROM Users");
        $sth->execute();
        $Users = $sth->fetchAll();
        return $this->response->withJson($Users);
        });
       

    //TESTED
     //Get all recipe info by RecipeId
     //SENDING FORMAT: http://localhost:8080/Recipe/RecipeId?RecipeId=1
     $app->get('/Recipe/[{RecipeId}]', function ($request, $response, $RecipeId)
        {
            $RecipeId = $_GET['RecipeId'];
        $sth= $this->db->prepare("SELECT * FROM Recipes WHERE RecipeId = '$RecipeId'");
        $sth->bindParam("RecipeId", $RecipeId);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });

    //TESTED
     //Get all recipes
     $app->get('/Recipes', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('RecipeId');
        $sth= $this->db->prepare("SELECT * FROM Recipes");
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });

        //TESTED
        //Get all recipes
     $app->get('/RecipesByIngredient/[{IngredientName}]', function ($request, $response, $args)
        {
        $IngredientName = $_GET['IngredientName']; 
        $sth= $this->db->prepare("SELECT r.RecipeName FROM Recipes r
        join Ingredients i
        on i.RecipeId = r.RecipeId
        where i.IngredientName = '$IngredientName'");
        $sth->bindParam("IngredientName", $IngredientName);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });




        //Get all recipe info by RecipeId
     $app->get('/RecipeImages/[{RecipeId}]', function ($request, $response, $args)
        {
        $RecipeId = $_GET['RecipeId'];
        $sth= $this->db->prepare("SELECT * FROM RecipeImages WHERE RecipeId = '$RecipeId'");
        $sth->bindParam("RecipeId", $RecipeId);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });

        $app->get('/Steps/[{RecipeId}]', function ($request, $response, $args)
        {
        $RecipeId = $_GET['RecipeId'];
        $sth= $this->db->prepare("SELECT * FROM Steps WHERE RecipeId = '$RecipeId'");
        $sth->bindParam("RecipeId", $RecipeId);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });

        $app->get('/IngredientInfo/[{RecipeId}]', function ($request, $response, $args)
        {
        $RecipeId = $_GET['RecipeId'];
        $sth= $this->db->prepare("SELECT * FROM IngredientInfo WHERE RecipeId = '$RecipeId'");
        $sth->bindParam("RecipeId", $RecipeId);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });




        //TESTED
        $app->get('/User/[{Email}]', function ($request, $response, $args) 
        {
            $Email = $_GET['Email']; 
            $sth= $this->db->prepare("SELECT *FROM Users where Email = '$Email'" );
            $sth->bindParam("Email", $Email);
            $sth->execute();
            $users = $sth->fetchAll();
            return $this->response->withJson($users);  
        });



        
    
    //TESTED
    //Get User Ingredients by email
    $app->get('/UserIngredients/[{Email}]', function ($request, $response, $args)
        {
            $Email = $_GET['Email']; 
            $sth= $this->db->prepare("SELECT IngredientName FROM UserIngredients where Email = '$Email'" );
            $sth->bindParam("Email", $Email);
            $sth->execute();
            $users = $sth->fetchAll();
            return $this->response->withJson($users); 
        });


    //TESTED
    //Get recipes containting all user ingredients
    $app->get('/Recipes/[{Email}]', function ($request, $response, $args)
        {
        $Email = $_GET['Email'];
        $sth= $this->db->prepare("SELECT DISTINCT r.RecipeName, r.RecipeId FROM Recipes r
        WHERE NOT EXISTS (
        SELECT 1 FROM Ingredients i WHERE r.RecipeId=i.RecipeId AND i.IngredientName 
        NOT IN (SELECT IngredientName FROM UserIngredients WHERE Email = '$Email')
        )");
        $sth->bindParam("Email", $Email);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });


    //TESTED
    //Get users favorite recipes
    $app->get('/UserFavorites/[{Email}]', function ($request, $response, $args)
        {
            $Email = $_GET['Email'];
        $sth= $this->db->prepare("SELECT Recipes.RecipeName, Recipes.RecipeId
        FROM Recipes
        INNER JOIN  UserFavorites 
        ON Recipes.RecipeId = UserFavorites.RecipeId
        WHERE UserFavorites.Email = '$Email'");
        $sth->bindParam("Email", $Email);
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });









         //------------PUT----------------------------

        //Add difficulty rating
        $app->put('/Recipes/DifficultyRating/{RecipeId}', function ($request, $response, $args) {
            $RecipeId = $request->getAttribute('RecipeId');
            $input = $request->getParsedBody();
            $DifficultyRating = $input ['DifficultyRating'];
            $sql = "update Recipes set DRatingCount = DRatingCount +1 where RecipeId = '$RecipeId'";
            $sth = $this->db->prepare($sql);
            $sth->bindParam("DifficultyRating", $input['DifficultyRating']);
            $sth->execute();
            
            $sql2 = "update Recipes set DifficultyRatingTotal = DifficultyRatingTotal + $DifficultyRating where RecipeId = '$RecipeId'";
            $sth2 = $this->db->prepare($sql2);
            $sth2->execute();
            
            $sql3 = "update Recipes set DifficultyRating = Recipes.DifficultyRatingTotal/ Recipes.DRatingCount where RecipeId = '$RecipeId'";
            $sth3 = $this->db->prepare($sql3);
            $sth3->execute();
            });

        //Add tasty rating
         $app->put('/Recipes/TastyRating/{RecipeId}', function ($request, $response, $args) {
            $RecipeId = $request->getAttribute('RecipeId');
            $input = $request->getParsedBody();
            $TastyRating = $input ['TastyRating'];
            $sql = "update Recipes set TRatingCount = TRatingCount +1 where RecipeId = '$RecipeId'";
            $sth = $this->db->prepare($sql);
            $sth->bindParam("TastyRating", $input['TastyRating']);
            $sth->execute();
            
            $sql2 = "update Recipes set TastyRatingTotal = TastyRatingTotal + $TastyRating where RecipeId = '$RecipeId'";
            $sth2 = $this->db->prepare($sql2);
            $sth2->execute();
            
            $sql3 = "update Recipes set TastyRating = Recipes.TastyRatingTotal/ Recipes.TRatingCount where RecipeId = '$RecipeId'";
            $sth3 = $this->db->prepare($sql3);
            $sth3->execute();
            });

            //delete user ingredients
         $app->delete('/DeleteUserIngredients', function ($request, $response) {
            
            /*
            $Email = $_GET['Email']; 
            $IngredientName = $_GET['IngredientName']; 
            $sql = "delete from UserIngredients 
            where Email = '$Email' and IngredientName = '$IngredientName";
            $sth->bindParam("Email", $Email);
            $sth->bindParam("IngredientName", $IngredientName);
            $sth = $this->db->prepare($sql);
            $sth->execute();*/


            $input = $request->getParsedBody();
       $sql = "DELETE from UserIngredients where Email = :Email and IngredientName = :IngredientName";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("Email",$input['Email']);
        echo $Email;
        $sth->bindParam("IngredientName", $input['IngredientName']);
        $sth->execute();
        return $this->response->withJson($input);
        
            });

//Delete user favorites
            $app->delete('/DeleteUserFavorites/{Email}/{RecipeId}', function ($request, $response, $args) {
                $Email = $request->getAttribute('Email');
                $RecipeId = $request->getAttribute('RecipeId');
                $sql = "delete from UserFavorites
                where Email = '$Email' and RecipeId = '$RecipeId";
                $sth = $this->db->prepare($sql);
                $sth->execute();
            
                });



                $app->delete('/deleteTemp/[{Email}]', function ($request, $response) 
                {
                    $Email = $_GET['Email']; 
                   // $IngedientName = $_GET['IngredientName']; 
                    $input = $request->getParsedBody();
                    $sql = "DELETE from UserIngredients where Email = '$Email'";
                    $sth = $this->db->prepare($sql);  
                    $sth->bindParam("Email", $input['Email']); 
                    //$sth->bindParam("IngredientName", $input['IngredientName']); 
                    $sth->execute();
                    return $this->response->withJson($input);});
    
                    $app->delete('/temp/{Email}', function($request){
                        //require_once('db.php');
                        $Email = $_GET['Email']; 
                        $get_id = $request->getAttribute('Email');
                        $query = "DELETE from UserIngredients WHERE Email= $Email";
                        $result = $connection->query($query);
                       });


                       $app->delete('/deleteT', function ($request, $response) 
                {
       
                    $input = $request->getParsedBody();
                    $sql = "DELETE from UserIngredients where IngredientName = :IngredientName and Email = :Email";
                    $sth = $this->db->prepare($sql);  
                    $sth->bindParam("Email", $input['Email']); 
                    $sth->bindParam("IngredientName", $input['IngredientName']); 
                    $sth->execute();
                    return $this->response->withJson($input);});

                    $app->put('/UserIngredients/delete', function ($request, $response) {
                        $input = $request->getParsedBody();
                        $deleted = "deleted";
                        $sql = "UPDATE UserIngredients set IngredientName = '$deleted' where Email = :Email AND IngredientName=:IngredientName";
                        $sth = $this->db->prepare($sql);
                        $sth->bindParam("Email", $input['Email']);
                        $sth->bindParam("IngredientName", $input['IngredientName']);
                        $sth->execute();
                        return $this->response->withJson($input);});



                        $app->put('/delete', function ($request, $response, $args) {
                            $input = $request->getParsedBody();
                            $sql = "UPDATE UserIngredients SET UserIngredient=:Deleted WHERE Email=:Email ";
                            $sth = $this->db->prepare($sql);
                            $sth->bindParam("Email", $input['Email']);
                            $sth->bindParam("UserIngredient", $input['UserIngredient']);
                            $sth->bindParam("Deleted", $input['Deleted']);
                            $sth->execute();
                            return $this->response->withJson($input);
                          });
    


            






    
    

    

    

    

    


    

       









   

    
    
    

        

    
    
















    


    

    
    
    
    
   