<?php

/*
    Title:       crud-buttons.php
    Application: RentalBuddy
    Purpose:     Common buttons for use in CRUD pages
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 
*/
    
    function getCRUDButtons() {
        echo '<table>
                <tr>
                    <td><button type="submit" class="btn btn-secondary btn-crud" name="btn-view">View</button></td>
                    <td><button type="submit" class="btn btn-primary btn-crud" name="btn-edit">Edit</button></td>
                    <td><button type="submit" class="btn btn-danger btn-crud" name="btn-delete">Delete</button></td>
                    <td><button type="submit" class="btn btn-success btn-crud" name="btn-add">Add</button></td>
                </tr>
            </table>';
    }
?>