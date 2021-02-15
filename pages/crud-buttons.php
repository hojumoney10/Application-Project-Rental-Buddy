<?php

/*
    Title:       crud-buttons.php
    Application: RentalBuddy
    Purpose:     Common buttons for use in CRUD pages
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 
*/
    
    function getCRUDButtons() {
?>
    <div class="container-fluid container-crud">
        <table>
                <tr>
                    <td><input type="submit" class="btn btn-success btn-crud" name="btn-add" value="Add"></td>
                    <td><input type="submit" class="btn btn-secondary btn-warning btn-crud" name="btn-edit" value="Edit"></td>
                    <td><input type="submit" class="btn btn-secondary btn-crud" name="btn-view" value="View"></td>
                    <!-- <td><input type="submit" class="btn btn-danger btn-crud" name="btn-delete" value="Delete"></td> -->
                </tr>
        </table>
    </div>        
<?php
    }
?>