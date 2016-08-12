<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
 $vocab = taxonomy_vocabulary_machine_name_load('research_life_cycle');
 $terms = taxonomy_get_tree($vocab->vid, 0, NULL, TRUE);

 $files_dir = variable_get('file_public_path', conf_path() . '/files/background_images');
 $files = file_scan_directory($files_dir, '/\.(png|jpg)$/');
 $key = array_rand($files);
 $uri = $files[$key]->uri;
 echo "<div id='hero' style='background: url($uri);background-size: cover; width: 100%;height: 500px; position: absolute;left: 0;z-index: -10;'></div>";
?>

<!--<div class="card-panel uoa-brand-shape" style="position: absolute; padding-top: 0px; padding-botom: 15px; padding-left: 0px; width:350px; margin-top: 35px; margin-left: 60%">-->
<!--  <h5 class="center-align" style="color: white; margin-bottom: 15px; font-weight: 600">The Research Hub</h5>-->
<!---->
<!--  <a href="#lifecycle" class="waves-effect waves-light btn center-align" style="background-color: #0080a7; margin-bottom: 5px; min-width: 350px;"><i class="material-icons left">cached</i>Browse by Research Lifecycle</a>-->
<!---->
<!--  <a href='#category_accordion' class="waves-effect waves-light btn center-align" style="background-color: #0080a7; margin-bottom: 5px; min-width: 350px;"><i class="material-icons left">view_module</i>Browse by Service Type</a>-->
<!---->
<!--  <a href='#education' class="waves-effect waves-light btn center-align" style="background-color: #0080a7; margin-bottom: 5px; min-width: 350px;"><i class="material-icons left">school</i>Education and Training Opportunities</a>-->
<!---->
<!--  <a href='#guides' class="waves-effect waves-light btn center-align" style="background-color: #0080a7; margin-bottom: 5px; min-width: 350px;"><i class="material-icons left">import_contacts</i>Consult Guides</a>-->
<!--</div>-->

<?php
  echo "<div id='spacer' style='padding-top: 20%; min-height: 400px'></div><a id='lifecycle' class='anchor'></a>";
 ?>

<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>

<?php
echo "<div id='lifecycle_selector_menu'><div>";
foreach ($terms as $t) {
  $machine_name = str_replace(' &', '', $t->name);
  $machine_name = str_replace(' ', '_', $machine_name);
  echo "<span class='lifecycle_selector $machine_name' style='background-color:{$t->field_color['und'][0]['rgb']}'><i class='material-icons circle {$t->field_color['und'][0]['rgb']}' style='float:left'>{$t->field_glypth['und'][0]['value']}</i>{$t->name}</span>";
}
echo "</div></div>";
echo "<div class='triangles'>";
foreach ($terms as $t) {
  $machine_name = str_replace(' &', '', $t->name);
  $machine_name = str_replace(' ', '_', $machine_name);
  echo "<div class='triangle-down $machine_name'><div class='triangle-down-inner' style='border-top: 300px solid {$t->field_color['und'][0]['rgb']}'></div></div>";
}
echo "</div>";
?>
<?php foreach ($rows as $id => $row): ?>
  <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
    <?php print $row; ?>
  </div>
<?php endforeach; ?>
