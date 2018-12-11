<?php
 require_once('config.php'); 
 
function outputNameOfEmployee() {
    try {
        $pdo = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from employees order by LastName";
        $result = $pdo->query($sql);
        while ($row = $result->fetch()) {
            echo '<li><a href="' . $_SERVER["SCRIPT_NAME"] . '?employees=' . $row['EmployeeID'] . '" class="';
            if (isset($_GET['employees']) && $_GET['employees'] == $row['EmployeeID']) echo 'active ';
            echo 'item">';
            echo $row['FirstName'] .' '. $row['LastName'] . '</a></li>';
         }
        $pdo = null;
    } catch (PDOException $ex) {
        die($ex->getMessage());
    }
}

function outputAddressOfEmployee() {
    try {
        if (isset($_GET['employees']) && $_GET['employees'] > 0) {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'select * from employees where EmployeeID=:employees';
            $id =  $_GET['employees'];
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':employees', $id);
            $statement->execute();
            $row = $statement->fetch();
            
            echo '<h3>' . $row['FirstName'] . ' ' . $row['LastName'] . '</h1>'
                    . '<p>' . $row['Address'] . '<br>'
                    . $row['City'] . ', ' . $row['Region'] . '<br>'
                    . $row['Country'] . ', ' . $row['Postal'] . '<br>'
                    . $row['Email'] . '</p>';
            $pdo = null;
        } 
    } catch (PDOException $ex) {
        die($ex->getMessage());
    }
}

function outputToDoEmployee() {
    try {
        if (isset($_GET['employees']) && $_GET['employees'] > 0) {
            $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $sql = 'select * from employeetodo where EmployeeID=:employees order by EmployeeID';
            $id =  $_GET['employees'];
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':employees', $id);
            $statement->execute();
            
            while ($row = $statement->fetch()) {
                outputSingleToDo($row);
            }
            $pdo = null;
        } 
    } catch (PDOException $ex) {
        die($ex->getMessage());
    }
}

function outputSingleToDo($row) {
    echo '<tr><td>' . $row['DateBy'] . '</td><td>'
    . $row['Status'] .'</td><td>'
    . $row['Priority'] .'</td><td class="text-left">'
    . $row['Description'] .'</td></tr>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chapter 14</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.blue_grey-orange.min.css">

    <link rel="stylesheet" href="css/styles.css">
    
    
    <script   src="https://code.jquery.com/jquery-1.7.2.min.js" ></script>
       
    <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
    
</head>

<body>
    
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
            
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>
    
    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">

            <div class="mdl-grid">

              <!-- mdl-cell + mdl-card -->
              <div class="mdl-cell mdl-cell--3-col card-lesson mdl-card  mdl-shadow--2dp">
                <div class="mdl-card__title mdl-color--orange">
                  <h2 class="mdl-card__title-text">Employees</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <ul class="demo-list-item mdl-list">
						<li>
                         <?php  
                            outputNameOfEmployee();/* programmatically loop though employees and display each
                              name as <li> element. */
                         ?>            
						</li>
                    </ul>
                </div>
              </div>  <!-- / mdl-cell + mdl-card -->
              
              <!-- mdl-cell + mdl-card -->
              <div class="mdl-cell mdl-cell--9-col card-lesson mdl-card  mdl-shadow--2dp">

                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Employee Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
                          <div class="mdl-tabs__tab-bar">
                              <a href="#address-panel" class="mdl-tabs__tab is-active">Address</a>
                              <a href="#todo-panel" class="mdl-tabs__tab">To Do</a>
                          </div>
                        
                          <div class="mdl-tabs__panel is-active" id="address-panel">
                              
                           <?php   
                           
						   outputAddressOfEmployee(); 
                           
						   ?>
                           
         
                          </div>
                          <div class="mdl-tabs__panel" id="todo-panel">
                              
                               <?php                       
                                 /* retrieve for selected employee;
                                    if none, display message to that effect */
                               ?>                                  
                            
                                <table class="mdl-data-table  mdl-shadow--2dp">
                                  <thead>
                                    <tr>
                                      <th class="mdl-data-table__cell--non-numeric">Date</th>
                                      <th class="mdl-data-table__cell--non-numeric">Status</th>
                                      <th class="mdl-data-table__cell--non-numeric">Priority</th>
                                      <th class="mdl-data-table__cell--non-numeric">Content</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                   
                                    <?php 
									
									
									outputToDoEmployee();
									
									?>
                            
                                  </tbody>
                                </table>
                           
         
                          </div>
                        </div>                         
                    </div>    
  
                 
              </div>  <!-- / mdl-cell + mdl-card -->   
            </div>  <!-- / mdl-grid -->    

        </section>
    </main>    
</div>    <!-- / mdl-layout --> 
          
</body>
</html>