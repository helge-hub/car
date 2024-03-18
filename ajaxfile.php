<?php

session_start();
include "server/connection.php";
 
$userid = $_POST['userid'];

$stmt2 = $conn->prepare("SELECT * FROM `cars`  WHERE car_id=? ");
$stmt2->bind_param('i',$userid);
$stmt2->execute();
$products = $stmt2->get_result();



while( $row = $products->fetch_assoc()){ 
?>

<div>
<img src="assets/imgs/<?php echo $row['car_image1']; ?>" style="max-width: 100%;
height: auto;">
</div>
<div class="mt-5">
    <?php  
    $car_date =$row['car_drop_date'];
    $date_valid =date("d/m/Y", strtotime($car_date));
    if(!$car_date==""){
        $message ="Disponible le : ". $date_valid;
    }else {
        $message = "Disponible maintenant";
    }
    
    
    ?>
    <p style="color:green"><?php echo $message;   ?></p>

</div>
<div>Description</div>
<hr>
<div><p><?php echo $row['car_model']; ?></p></div>
<hr>
<div>prix</div>
<hr>
<div>
<?php   
                            $stmt41 = $conn->prepare("SELECT * from workspace left join location on workspace.location_id = location.location_id left join cars on workspace.car_id = cars.car_id  where cars.car_id=? ");
                            $stmt41->bind_param("i",$userid);
                            $stmt41->execute();
                            $products41 = $stmt41->get_result();
                        ?>
                        <?php  while($row11 = $products41->fetch_assoc()){?>
                        <div class="row">
                            <div class="col">Lieu : <?php  echo $row11['location_name'] ;?></div>
                            <div class="col">Prix : <?php   echo $row11['location_price']; ?> CFA / Jour</div>
                        </div>
                        <?php  } ;?>
</div>
<hr>
<div>Plus d'informations</div>
<hr>
    <div class="row mt-n3 mt-lg-0 pb-4">
            <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-car text-success mr-2"></i>
                            <span>Model: <?php echo $row['an'] ;?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-cogs text-success mr-2"></i>
                            <span><?php  echo $row['trans'] ?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-road text-success mr-2"></i>
                            <span><?php echo number_format($row['car_km']);  ?></span>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <i class="fa fa-eye text-success mr-2"></i>
                            <span>GPS: <?php echo $row['car_gps'];  ?></span>
                        </div>
            </div>
    </div>
           <div>Détail de la réservation</div>
                    <div class="mb-5">
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="text" class="form-control p-4" value="<?php echo $_SESSION['user_name']; ?>" placeholder="Nom" required="required">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="email" class="form-control p-4"value="<?php echo $_SESSION['user_email']; ?>" placeholder=" Email" required="required">
                            </div>
                            <div class="col-6 form-group">
                                <input type="text" class="form-control p-4" placeholder="Tel" required="required">
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <div class="row">
                            <!-- <div class="col-6 form-group">
                                <select class="custom-select px-4" style="height: 50px;">
                                    <option selected>Pickup Location</option>
                                    <option value="1">Location 1</option>
                                    <option value="2">Location 2</option>
                                    <option value="3">Location 3</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <select class="custom-select px-4" style="height: 50px;">
                                    <option selected>Drop Location</option>
                                    <option value="1">Location 1</option>
                                    <option value="2">Location 2</option>
                                    <option value="3">Location 3</option>
                                </select>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <input type="hidden" name="car_id" value="<?php echo $userid; ?>">
                                <input type="hidden" name="car_date_o" value="<?php echo $car_date; ?>">
                                <div class="date" id="date2" data-target-input="nearest">
                                    <input type="date" class="form-control p-4 datepicker-input" name="reservation_date" placeholder="Date de récuperation"
                                        data-target="#date2" data-toggle="datepicker" required>
                                </div>
                            </div>
                            <div class="col-6 form-group">
                            <select class="custom-select px-4" name="time" style="height: 50px;">
                                    <option selected>Selectioner une plage horaire</option>
                                    <option value="8h-9h">8h-9h</option>
                                    <option value="10h-11h">10h-11h</option>
                                    <option value="12h-13h">12h-13h</option>
                                    <option value="15h-16h">15h-16h</option>
                                    <option value="17h-18h">17h-18h</option>
                                    <option value="20hh-21h">20h-21h</option>
                                    <option value="22h-23h">22h-23h</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group">
                                <select class="custom-select px-4" name="age" style="height: 50px;">
                                    <option selected>Selectioner votre age</option>
                                    <option value="1">18-23</option>
                                    <option value="2">24-30</option>
                                    <option value="3">30-60</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <select class="custom-select px-4" name="location_price" style="height: 50px;">
                                    <option selected>Selectioner un lieu</option>

                                        <?php   
                                            $stmt4 = $conn->prepare("SELECT * from workspace left join location on workspace.location_id = location.location_id left join cars on workspace.car_id = cars.car_id  where cars.car_id=? ");
                                            $stmt4->bind_param("i",$userid);
                                            $stmt4->execute();
                                            $products4 = $stmt4->get_result();
                                        ?>
                                     <?php  while($row1 = $products4->fetch_assoc()){?>
                                
                                            <option value="<?php  echo $row1['location_price']  ?>"><?php  echo $row1['location_name'] ?></option>
                            
                                    <?php  } ;?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            
                        
                            <div class="col-6 form-group">
                                <div class="number" id="nbrs">
                                    <input type="number" name="duration" class="form-control p-4 datetimepicker-input" placeholder="Nombre de jours"
                                        data-target="#nbrs" />
                                </div>
                            </div>

                            <div class="col-6 form-group">
                                    <label>Permis de conduire</label>
                                    <input type="file" class="form-control" id="image1"  name="image1" placeholder="image 1" required>
                                </div>

                            </div>
                        
                        <div class="form-group">
                            <textarea class="form-control py-3 px-4"name="description" rows="3" placeholder="Description spéciale" required="required"></textarea>
                        </div>
                    </div>
                    

 
<?php } ?>



