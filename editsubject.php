<select name="lecID">
            <?php
            require_once('config.php');

            $query = "SELECT `lec_id`, `lecname` FROM `lecturer`";
            $result = $mysqli->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["lec_id"] . "'>" . $row["lecname"] . "</option>";
            }
            ?>