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

        

     //Get account by email
     $app->get('/User/{Email}', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('Email');
        $sth= $this->db->prepare("SELECT FirstName FROM Users WHERE Email = '$Email'");
        $sth->bindParam("Email", $args['Email']);
        $sth->execute();
        $Users = $sth->fetchAll();
        return $this->response->withJson($Users);
        });

        	//display nannys in certain zipcode
    $app->get('/idk/{RecipeId}', function ($request, $response, $args){
        $zip = $request->getAttribute('RecipeId');
        $sth = $this->db->prepare("SELECT * FROM Recipes WHERE RecipeId = $RecipeId" );
        $sth->execute();  
        $zip = $sth->fetchAll();
        return $this->response->withJson($zip);
        });
        
        $app->get('/GetUser/[{Email}]', function ($request, $response, $args) 
        {$sth = $this->db->prepare("SELECT * FROM Users WHERE Email=:Email");
            $sth->bindParam("Email", $args['Email']);
            $sth->execute();
            $user = $sth->fetchObject();
            return $this->response->withJson($user);
        });

        /*
        $app->get('/GetUser/:Email', function ($request, $response, $Email) 
        {$sth = $this->db->prepare("SELECT * FROM Users WHERE Email=$Email");
            //$sth->bindParam("Email", $args['Email']);
            $sth->execute();
            $user = $sth->fetchObject();
            return $this->response->withJson($user);
        });*/


     //Get all recipe info by RecipeId
     $app->get('/Recipe/{RecipeId}', function ($request, $response, $RecipeId)
        {
        $Email = $request->getAttribute('RecipeId');
        $sth= $this->db->prepare("SELECT * FROM Recipes WHERE RecipeId = '$RecipeId'");
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

        //Get all recipes
     $app->get('/RecipesByIngredient/{IngredientName}', function ($request, $response, $args)
        {
        $IngredientName = $request->getAttribute('IngredientName');
        $sth= $this->db->prepare("SELECT * FROM Recipes r
        join Ingredients i
        on i.RecipeId = r.RecipeId
        where i.IngredientName = '$IngredientName'");
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });

        //Get all recipe info by RecipeId
     $app->get('/RecipeImages/{RecipeId}', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('RecipeId');
        $sth= $this->db->prepare("SELECT * FROM RecipeImages WHERE RecipeId = '$RecipeId'");
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });

    

    //Get User Ingredients by email
    $app->get('/UserIngredients/{Email}', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('Email');
        $sth= $this->db->prepare("SELECT IngredientNames FROM UserIngredients WHERE Email = '$Email'");
        $sth->execute();
        $IngredientNames = $sth->fetchAll();
        return $this->response->withJson($IngredientNames);
        });


    //Get recipes containting all user ingredients
    $app->get('/Recipes/{Email}', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('Email');
        $sth= $this->db->prepare("SELECT DISTINCT r.RecipeName, r.RecipeId FROM Recipes r
        WHERE NOT EXISTS (
        SELECT 1 FROM Ingredients i WHERE r.RecipeId=i.RecipeId AND i.IngredientName 
        NOT IN (SELECT IngredientName FROM UserIngredients WHERE Email = '$Email')
        ");
        $sth->execute();
        $Recipes = $sth->fetchAll();
        return $this->response->withJson($Recipes);
        });



    //Get users favorite recipes
    $app->get('/UserFavorites/{Email}', function ($request, $response, $args)
        {
        $Email = $request->getAttribute('Email');
        $sth= $this->db->prepare("SELECT Recipes.RecipeName, Recipes.RecipeId
        FROM Recipes
        INNER JOIN  UserFavorites 
        ON Recipes.RecipeId = UserFavorites.RecipeId
        WHERE UserFavorites.Email = '$Email'
        )
        ");
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
         $app->delete('/DeleteUserIngredients/{Email}/{IngredientName}', function ($request, $response, $args) {
            $Email = $request->getAttribute('Email');
            $IngredientName = $request->getAttribute('IngredientName');
            $sql = "delete from UserIngredients 
            where Email = '$Email' and IngredientName = '$IngredientName";
            $sth = $this->db->prepare($sql);
            $sth->execute();
        
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
    



            






    
    

    

    

    

    


    

       









   

    
    
    

        

    
    
















    


    

    
    
    
    
   