<?php
/**
 * Create a bootstrap success alert
 * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
 * @param  String $message - What will be used for the main description in the alert
 */
function successAlert($bold, $message){
  echo '<div class="container">
          <div class="alert alert-success" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>'.$bold.'</strong></br> '.$message.'
          </div>
        </div>';
}
/**
 * Create a bootstrap danger alert
 * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
 * @param  String $message - What will be used for the main description in the alert
 */
function dangerAlert($bold, $message){
  echo '<div class="container">
          <div class="alert alert-danger" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>'.$bold.'</strong></br>  '.$message.'
          </div>
        </div>';
}
/**
 * Create a bootstrap info alert
 * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
 * @param  String $message - What will be used for the main description in the alert
 */
function infoAlert($bold, $message){
  echo '<div class="container">
          <div class="alert alert-info" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>'.$bold.'</strong></br>  '.$message.'
          </div>
        </div>';
}
/**
 * Create a bootstrap warning alert
 * @param  String $bold  - First words in the alert. Will be put inside <strong> html tag for emphasis
 * @param  String $message - What will be used for the main description in the alert
 */
function warningAlert($bold, $message){
  echo '<div class="container">
          <div class="alert alert-warning" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>'.$bold.'</strong></br>  '.$message.'
          </div>
        </div>';
}
                
?>