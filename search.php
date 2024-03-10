<div class="container-fluid bg-white pt-3 px-lg-5">
                    <form action="car.php" method="post">
                <?php
                    $stmt3 = $conn->prepare("SELECT * from category ORDER by category_name");
                            $stmt3->execute();
                            $category_all = $stmt3->get_result();
                ?>
        <div class="row mx-n2">
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <select class="custom-select px-4 mb-3" style="height: 50px;">
                    <option selected>Localisation Récuperation</option>
                    <option value="1">Libreville</option>
                    <option value="2">Akanda</option>
                    <option value="3">Owendo</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <select class="custom-select px-4 mb-3" style="height: 50px;">
                    <option selected>Localisation Dépôt</option>
                    <option value="1">Libreville</option>
                    <option value="2">Akanda</option>
                    <option value="3">Owendo</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <div class="date mb-3" id="date" data-target-input="nearest">
                    <input type="text" class="form-control p-4 datetimepicker-input" placeholder="Date Récuperation"
                        data-target="#date" data-toggle="datetimepicker" />
                </div>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
            <select class="custom-select px-4 mb-3" style="height: 50px;">
                    <option selected>selectionner une plage horaire</option>
                    <option value="8h-9h">8h-9h</option>
                    <option value="10h-11h">10h-11h</option>
                    <option value="12h-13h">12h-13h</option>
                    <option value="15h-16h">15h-16h</option>
                    <option value="17h-18h">17h-18h</option>
                    <option value="20h-21h">20h-21h</option>
                    <option value="22h-23h">22h-23h</option>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <select class="custom-select px-4 mb-3" style="height: 50px;" name="category">
                    <!-- <option selected>Select A Car</option> -->
                    <?php foreach($category_all as $row) {   ?>
                    <option  value="<?php echo $row['category_name']; ?>"><?php  echo $row['category_name']; ?></option>
                    <?php  } ; ?>
                </select>
            </div>
            <div class="col-xl-2 col-lg-4 col-md-6 px-2">
                <button class="btn btn-success btn-block mb-3" name="search" type="submit" style="height: 50px;">Recherche</button>
            </div>
        </div>
    </form>
</div>