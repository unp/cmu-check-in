<?php
  function semester() {
    $season = date('m') < 7 ? "Spring" : "Fall";
    return $season . ' ' . date('Y');
  }
?>