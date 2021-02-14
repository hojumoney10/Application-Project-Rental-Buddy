<?php

/*
    Title:       search-bar.php
    Application: RentalBuddy
    Purpose:     Common search-bar for use in CRUD pages
    Author:      G. Blandford, Group 5, INFO-5139-01-21W
    Date:        February 13th, 2021 (February 13th, 2021) 
*/

function getSearch($fvalue)
{ ?>

    <div class="container-fluid">
        <!-- <form class="form form-inline" method="POST"> -->
            <table class="table table-light">
                <tbody>
                    <tr>
                        <div class="input-group">
                            <td>
                                <label for="searchtext">Search</label>
                                <input type="text" id="searchtext" style="width: 200px" class="form-inline" name="searchtext" value=" <?php echo $fvalue ?>">
                                <input type="submit" class="btn btn-primary" name="btn-search" value="Search">
                                <input type="submit" class="btn btn-secondary" name="btn-search-clear" value="Clear">
                            </td>
                        </div>
                    </tr>

                </tbody>
            </table>
        <!-- </form> -->
    </div>
<?php
}
?>