<?php

/*
    Title:       search-bar.php
    Application: RentalBuddy
    Purpose:     Common search-bar for use in CRUD pages
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 
*/
    
    function getSearch($fvalue) { ?>
       
        <table class="table table-light table-striped" >
            <tbody>
                <tr style="overflow: hidden; white-space: nowrap;">
                    <th style="width: 50px; overflow: hidden; white-space: nowrap;">Search</th>
                    <td><input type="text" id="searchtext" style="width: 200px" class="form-inline" name="searchtext" value=" <?php echo $fvalue ?>">
                    <input type="submit" class="btn btn-primary" name="btn-search" value="Search">
                    <input type="submit" class="btn btn-secondary" name="btn-search-clear" value="Clear"></td>
                </tr>

            </tbody>
        </table>
<?php
    }
?>