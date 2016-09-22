<?php
/**
 * @file
 * Materialize theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see bootstrap_preprocess_page()
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see bootstrap_process_page()
 * @see template_process()
 * @see html.tpl.php
 *
 * @ingroup themeable
 */
 if (user_is_logged_in()) {
   global $user;
   $text = 'Logged in as ' . $user->name;
   $href = '/projects';
   $class = array('username', 'logged_in');
 } else {
   $text = 'Login';
   $href = '/Shibboleth.sso/Login';
   $class = array('username', 'not_logged_in');
 }
 $primary_nav[1000] = array('#theme' => 'menu_link__main_menu', '#title'=>$text, '#href'=>$href, '#below' => '', '#attributes' => array('class'=>$class));
 $primary_nav[1001] = array('#theme' => 'menu_link__main_menu', '#title'=>'Search', '#href'=>'search', '#below' => '', '#attributes' => array('class' => 'search_button'));
?>
<div id="page">
  <nav class="main-nav" id="nav" role="navigation">
    <a href="#" data-activates="slide-out" class="button-collapse show-on-large" style='margin-left:15px'><i class="mdi-navigation-menu"></i></a>
    <div class="nav-wrapper container">
      <?php if ($logo): ?>
        <a class="brand-logo" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
        <div style="float:left; position:absolute; display: inline-block; margin-left: 180px; font-size: 1.1rem;">The Centre for eResearch</div>
      <?php endif; ?>
      <?php if (!empty($primary_nav)): ?>
        <div class="right">
          <?php print render($primary_nav); ?>
        </div>
      <?php endif; ?>
    </div>
  </nav>
  <?php if (!empty($page['header'])): ?>
    <div class="top">
      <?php print render($page['header']); ?>
    </div>
  <?php endif; ?><!-- /.header  -->

  <div class="row searchBox">      
    <?php $block_form=drupal_get_form('search_block_form'); ?>
    <?php print drupal_render($block_form); ?>
  </div>

  <?php if (user_is_logged_in()): ?>
    <input type='hidden' id='researchers' value='<?php
      $json = json_encode($user->data['researchers']);
      $json = str_replace("'", ' ', $json);
      echo $json;
    ?>'/>

    <div id="create_project" class="modal">
      <div class="modal-content">
        <h4>Create a new project</h4>
        <div class="row">
          <form class="col s12" method="POST" action="#">
            <div class="row">
              <ul class="collection">
                <li class="collection-item avatar existing-collaborator">
                  <img src="<?php if (!empty($user->data['projectdb_info']->pictureUrl)) {
                    echo $user->data['projectdb_info']->pictureUrl;
                  } else if (!empty($user->data['ldap_attributes']['thumbnailPhoto'])) {
                    echo 'data:image/png;base64,' . $user->data['ldap_attributes']['thumbnailPhoto'];
                  }
                  ?>" alt="" class="circle">
                  <span class="title"><?php echo $user->data['ldap_attributes']['displayName'] ?></span>
                  <p style="font-weight:300">Project Owner</p>
                </li>
              </ul>
              <div class="input-field col s12">
                <input placeholder="Project Title" id="projectName" type="text" name="projectName" class="validate" required />
                <label for="projectName">Project title</label>
              </div>
              <div class="input-field col s12">
                <textarea id="projectDescription" name="projectDescription" class="materialize-textarea" required></textarea>
                <label for="projectDescription">Project description</label>
              </div>
              <div class="input-field col s12">
                <select id='scienceStudy' name="scienceStudy" required>
                  <?php
                    foreach ($user->data['science_domains'] as $d => $studies) {
                      echo "<optgroup label='$d'>";
                      foreach ($studies as $s) {
                        $selected = '';
                        if (!empty($user->data['ldap_attributes']['department']) && $s == $user->data['ldap_attributes']['department']) $selected = 'selected';
                        echo "<option value='$s' $selected>$s</option>";
                      }
                      echo "</optgroup>";
                    }
                  ?>
                </select>
                <label for='scienceStudy'>Field of science</label>
              </div>
              <?php if (!empty($user->data['projectdb_info']->institutionalRoleId) && $user->data['projectdb_info']->institutionalRoleId != 1): ?>
                <div class="input-field col s12">
                  <input type='hidden' id='supervisor-id' name='supervisor-id' required/>
                  <input placeholder="Supervisor" id="supervisor" name="supervisor" type="text" class="validate" autocomplete="off" required/>
                  <label for="supervisor">Supervisor's name</label>
                </div>
              <?php endif; ?>
              <div class="input-field col s12">
                <input type="date" class="datepicker" id="startDate" name="startDate" required/>
                <label for="startDate">First day</label>
              </div>
              <div class="input-field col s12">
                <input type="date" class="datepicker" id="endDate" name="endDate" required/>
                <label for="endDate">Last day</label>
              </div>
              Collaborators for this project:
              <div class="input-field col s12">
                <input placeholder="Collaborator" id="collaborator" type="text" class="validate" autocomplete="off" />
                <label for="collaborator">Name</label>
              </div>
              <div class="col s12 right">
                <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                  <i class="material-icons right">send</i>
                </button>
              </div>
            </div> <!-- row -->
          </form>
        </div> <!-- row -->
      </div> <!-- modal-content -->
    </div> <!-- modal-->

  <?php endif; ?>

  <div class="row page grid container">
      <!--<a href="#" data-activates="sidebar" class="button-collapse show-on-large" style="margin-left: 15px;"><i class="mdi-navigation-menu"></i></a>-->
      <ul id='slide-out' class='side-nav'>

        <div style="height: 75px; width:100%; background-color: #01457C">

        </div>

        <!-- Menu Bar Content-->
        <?php if (user_is_logged_in()): ?>
          <!-- When User is Signed in-->
          <div class='row'>
            <?php

              echo "<div class='collection'>";
              echo "<li class='collection-header'><h5>Manage Projects</h5></li>";
              echo "<a href='/projects/' class='collection-item avatar' style='min-height: 100px'>
                      <i class='material-icons circle' style='background-color: #ffb300 !important'>dashboard</i>
                      <span class='title' style='color: #ffb300; font-weight:500'>Open Project Dashboard</span>
                      <p style='font-weight:300; color:black'>View and manage all your research projects</p>
                    </a>";
              echo "<a href='#create_project' class='collection-item avatar waves-effect waves-light modal-trigger' style='min-height: 100px'>
                      <i class='material-icons circle' style='background-color: #ffb300 !important'>add</i>
                      <span class='title' style='color: #ffb300; font-weight:500'>Create New Project</span>
                      <p style='font-weight:300; color:black'>Create project in order to apply for research services</p>
                    </a>";

              echo "<li class='collection-header'><h5>My Projects</h5></li>";
              $projects = researchit_user_get_projects();
              if (!empty($projects)) {
                foreach ($projects as $i => $p) {
                  $desc = truncate_utf8($p->description, 100, TRUE, TRUE);
                  if (in_array($p->statusId, array(1,2,6))) {
                    $color = '#43a047';
                  } else if (in_array($p->statusId, array(4,10))) {
                    $color = '#e53935';
                  } else {
                    $color = '#1e88e5';
                  }
                  echo "<a href='/projects/{$p->projectCode}' class='collection-item avatar' style='min-height: 100px'>
                          <i class='material-icons circle' style='background-color: $color !important'>extension</i>
                          <span class='title' style='color: black; font-weight:500'>{$p->name}</span>
                          <p style='font-weight:300; color:black'>{$p->projectCode} </br>
                            <span style='font-style: italic;'>$desc</span>
                          </p>
                        </a>";
                }
              }
              echo "</div>";
            ?>
          </div>

        <?php else: ?>
          <!-- When User is not signed in-->

          You're not logged in. If you were, you'd be able to see your projects here.
        <?php endif; ?>
      </ul>

    <?php if (!empty($page['sidebar_first'])): ?>
      <aside class="<?php print $sidebar_left; ?> sidebar-first" role="complementary">
        <?php print render($page['sidebar_first']); ?>
      </aside>  <!-- /#sidebar-first -->
    <?php endif; ?>

    <section class="<?php if (!empty($main_grid)) print $main_grid; ?> main container" role="main">
      <?php if (!empty($page['highlighted'])): ?>
        <div class="highlighted"><?php print render($page['highlight']); ?></div>
      <?php endif; ?>

      <?php print render($secondary_navigation); ?>

      <?php if (!empty($breadcrumb)): print $breadcrumb; endif; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php print render($title_suffix); ?>
      <?php print $messages; ?>
      <?php if (!empty($tabs['#primary'])): ?>
        <?php print render($tabs_primary); ?>
      <?php endif; ?>

      <?php if (!empty($page['help'])): ?>
        <?php print render($page['help']); ?>
      <?php endif; ?>
      <?php if (!empty($action_links)): ?>
        <div class="action-links"><i class="mdi-action-note-add small"></i><?php print render($action_links); ?></div>
      <?php endif; ?>
      <?php print render($tabs_secondary); ?>
      <?php if(drupal_is_front_page()) {
              unset($page['content']['system_main']['default_message']);
            }
            print render($page['content']); ?>
    </section>

    <?php if (!empty($page['sidebar_second'])): ?>
      <aside class="<?php print $sidebar_right; ?> sidebar-last" role="complementary">
        <?php print render($page['sidebar_second']); ?>
      </aside>  <!-- /#sidebar-second -->
    <?php endif; ?>
  </div> <!-- /main  -->

  <div class="divider"></div>
  <footer class="page-footer">
    <div class="container">
      <?php print render($page['footer']); ?>
    </div>
  </footer>

</div> <!-- /#page -->
