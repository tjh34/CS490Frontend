<?php
$question = (isset($_POST['question'])) ? $_POST['question'] : 'no name';
$questionDiff = (isset($_POST['difficulty'])) ? $_POST['difficulty'] : 'no name';

$computedString = "Your Question: " . $question . " Difficulty: " . $questionDiff;
$array = ['question' => $question, 'difficulty' => $questionDiff, 'computedString' => $computedString];
echo json_encode($array);
?> 