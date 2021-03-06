<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<a id='education' class='anchor'></a>
<div class="row" style="margin-bottom:35px;">
    <div class="col s12">
      <h5 style="color:rgb(124, 179, 66)">Education, Training and Events</h5>
        <!--<div class="card">
            <div class="card-image white-text" style="max-height:60px">
                <img typeof="foaf:Image" src="https://researchit.cer.auckland.ac.nz/sites/default/files/light-green-darken1.jpg" alt="" height="60" width="400">
                <span class="card-title" style="padding: 15px 15px 15px 20px;">Education, Training and Events</span>
            </div>
        </div>-->
    </div>
</div>

<?php foreach ($rows as $id => $row): ?>
  <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
    <?php print $row; ?>
  </div>
<?php endforeach; ?>

<h6 style="float:right; margin-left:80%; position:absolute; color:rgb(124, 179, 66); font-weight:300; margin-top:350px">View All Education opportunities <i class="material-icons" style="font-size:1rem">info_outline</i></h6>
