<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=dq_export.csv');

System::increaseMemory(1024 * 10);
set_time_limit(600);

// Instantiate DataQuality object
$dq = new DataQuality();

$dags = $Proj->getGroups();

$sql = "select record, value from redcap_data where project_id = " . PROJECT_ID . " and field_name = '__GROUPID__'";
$q = db_query($sql);
while ($row = db_fetch_assoc($q))
{
    $this_group_id = $row['value'];
    // Make sure the DAG actually exists (in case was deleted but value remained in redcap_data)
    if (isset($dags[$this_group_id])) {
        $dag_records[$row['record']] = $this_group_id;
    }
}

foreach($_POST['rule'] as $rule_id) {

    // Get rule info
    $rule_info = $dq->getRule($rule_id);

    // Execute this rule
    $dq->executeRule($rule_id, NULL);

    $results = $dq->getLogicCheckResults();
    // var_export($results);


    foreach($results[$rule_id] as $result_num => $result_data) {
        $results[$rule_id][$result_num]['rule_id'] = $rule_id;
        $results[$rule_id][$result_num]['dag_id'] = $dag_records[$result_data['record']];
        $results[$rule_id][$result_num]['dag_name'] = $dags[$dag_records[$result_data['record']]];

        $results[$rule_id][$result_num]['data_display'] = strip_tags(nl2br(str_replace('"', "'", $results[$rule_id][$result_num]['data_display'])));

    }

   $header_printed = false;
    foreach($results[$rule_id] as $result) {
        if (!$header_printed) {
            echo '"' . implode('","', array_keys($result)) . '"' . PHP_EOL;
            $header_printed = true;
        }
        echo '"' . implode('","', $result) . '"' . PHP_EOL;
    }



}