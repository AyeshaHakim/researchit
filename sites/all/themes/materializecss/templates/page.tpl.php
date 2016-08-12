<?php
$name = '';
$email = '';
$pictureUrl = 'https://projects.nesi.org.nz/sites/default/files/nesi_avatar.png';
$projects = array();

if (user_is_logged_in()) {
    global $user;
    $projects = researchit_user_get_projects();
    $userData = $user->data['projectdb_info'];

    if($userData && $userData->fullName)
    {
        $name = $userData->fullName;
    }

    if($userData && $userData->email)
    {
        $email = $userData->email;
    }

    if($userData && $userData->pictureUrl)
    {
        $pictureUrl = $userData->pictureUrl;
    }
}
?>

<nav class="top-nav bg-light-grey" id="nav">
    <ul id='slide-out' class='side-nav'>
        <li><div class="userView">
                <img class="background" src="/sites/default/files/abstract-background.jpg">
                <?php echo "<a href='/'><img src='{$pictureUrl}' class='circle'></a>"; ?>
                <?php echo "<a href='/'><span class='white-text name'>$name</span></a>" ?>
                <?php echo "<a href='/'><span class='white-text email'>$email</span></a>" ?>
            </div></li>
        <li class="bold"><a href="/" class="waves-effect waves-teal"><i class="material-icons">home</i>Home</a></li>
        <li class="bold"><a href="/projects" class="waves-effect waves-teal"><i class="material-icons">library_books</i>Projects</a></li>
        <li class="bold"><a href="/#category_accordion" class="waves-effect waves-teal"><i class="material-icons">settings</i>Services</a></li>
        <li class="bold"><a href="/#education" class="waves-effect waves-teal"><i class="material-icons">school</i>Education</a></li>
        <li><div class="divider"></div></li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold">
                    <a class="collapsible-header waves-effect waves-teal">My Projects<i class="material-icons right" style="margin-right: 5px;">arrow_drop_down</i></a>
                    <div class="collapsible-body" style="">
                        <ul>
                            <?php
                                if (!empty($projects)) {
                                    foreach ($projects as $i => $p) {
                                        $pName = $p->name;

                                        if (strlen($pName) > 30)
                                        {
                                            $pName = substr($pName, 0, 30) . '...';
                                        }

                                        echo "<li><a href='/projects/{$p->projectCode}'>{$pName}</a></li>";
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    </ul>

    <div class="nav-wrapper">
        <a href="#" data-activates="slide-out" class="button-collapse brand-logo show-on-large"><i class="material-icons" style="font-size: 24px;">menu</i></a>
        <a href="#" class="nav-left brand-logo">The Research Hub</a>

        <a class="waves-effect waves-circle btn-floating nav-user-btn white right dropdown-button" data-activates='user-details' data-beloworigin="true" data-constrainwidth="false">
            <?php echo "<img src='{$pictureUrl}' class='circle'>"; ?>
        </a>

        <ul id='user-details' class='dropdown-content collection' style="width: 300px;"> 
            <?php
                if (user_is_logged_in()) {
                    echo "<li class='collection-item avatar'><img src='{$pictureUrl}' class='circle'><span class='title'>$name</span></li>";
                }
                else
                {
                    echo "<li class='collection-item avatar'><img src='{$pictureUrl}' class='circle'/><span class='title'>Not logged in</span></li><li class='collection-item' href='Shibboleth.sso/Login'>Login</li>";
                }
            ?>
         </ul>
    </div>
</nav>

<nav class="secondary-header bg-white">
    <div class="nav-wrapper">
        <img class="uni-logo" src="/sites/default/files/logo.png">
        <ul id="nav-mobile" class="nav-container hide-on-med-and-down">
            <li><a class='nav-link' href='/#lifecycle'>Research Lifecycle</a></li>
            <li><a class='nav-link' href='/#category_accordion'>Service Types</a></li>
            <li><a class='nav-link' href='/#education'>Education</a></li>
            <li><a class='nav-link' href='/#guides'>Guides</a></li>
            <li><a class='nav-link' href='/#policies'>Policies</a></li>
        </ul>
    </div>
</nav>

<?php if (!empty($page['header'])): ?>
    <div class="top">
        <?php print render($page['header']); ?>
    </div>
<?php endif; ?>

<div class="row searchBox">
    <?php
    $form = drupal_get_form('search_block_form');
    print drupal_render($form); ?>
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
                            <input placeholder="Project Title" id="projectName" type="text" name="projectName"
                                   class="validate" required/>
                            <label for="projectName">Project title</label>
                        </div>
                        <div class="input-field col s12">
                            <textarea id="projectDescription" name="projectDescription" class="materialize-textarea"
                                      required></textarea>
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
                                <input placeholder="Supervisor" id="supervisor" name="supervisor" type="text"
                                       class="validate" autocomplete="off" required/>
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
                            <input placeholder="Collaborator" id="collaborator" type="text" class="validate"
                                   autocomplete="off"/>
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

<div class="row page grid">
    <section class="<?php if (!empty($main_grid)) print $main_grid; ?> main" role="main" style="padding: 0;">
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
            <div class="action-links"><i class="mdi-action-note-add small"></i><?php print render($action_links); ?>
            </div>
        <?php endif; ?>
        <?php print render($tabs_secondary); ?>
        <?php if (drupal_is_front_page()) {
            unset($page['content']['system_main']['default_message']);
        }
        print render($page['content']); ?>
    </section>
</div>


<footer class="page-footer bg-dark-grey" style="padding: 0;margin: 0;">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Explore</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="#!">About the Centre for eResearch</a></li>
                </ul>
            </div>
            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Help & Support</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="http://www.eresearch.auckland.ac.nz/en/centre-for-eresearch/contact-us.html">Contact us</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">AskAuckland</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Donate to the Centre for eResearch</a></li>
                    <li><a class="grey-text text-lighten-3" href="#!">Provide feedback</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-copyright bg-darker-grey">
        <div class="container">
            © 2016 The Centre for eResearch
        </div>
    </div>
</footer>
