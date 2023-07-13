<?php
namespace JHU\DQExport;
if ($_POST['rule']) {
    require_once 'downloaddq.php';
    die();
}

require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

?>

    <h4>Data Quality Export</h4>
    <hr/>

<?php

// Instantiate DataQuality object
$dq = new \DataQuality();

// Get rules
$rules = $dq->getRules();

?>
<form method="post">
    <table>
        <thead>
            <tr>
                <th width="25%">Export?</th>
                <th width="75%">Rule Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($rules as $rule => $ruleData) {
                    print("<tr>");
                        printf('<td valign="top"><input type="checkbox" name="rule[]" value="%s"/></td>', $rule);
                        printf('<td valign="top">%s</td>', $ruleData['name']);

                    print("</tr>");
                }
            ?>
        </tbody>
    </table>
    <br/>
    <button type="submit">Execute and Download</button><br/> (may take some time depending on how many rules you choose)
</form>

<?php
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';