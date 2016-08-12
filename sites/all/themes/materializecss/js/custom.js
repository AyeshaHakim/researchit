/**
 * @file
 * Contains helper functions to work with theme. NB: javascript in this file is included on every page, sitewide
 */

(function ($) {

    /**
     * Changes caret icon for fieldset when it is being collapsed or expanded.
     */
    Drupal.behaviors.materializeFieldset = {
        attach: function (context) {
            jQuery('fieldset', context).on('collapsed', function (data) {
                var $caretIcon = jQuery('.fieldset-title i', this);
                if (data.value === true) {
                    $caretIcon.removeClass('mdi-hardware-keyboard-arrow-down').addClass('mdi-hardware-keyboard-arrow-right');
                }
                else {
                    $caretIcon.removeClass('mdi-hardware-keyboard-arrow-right').addClass('mdi-hardware-keyboard-arrow-down');
                }
            });
        }
    };

    // console.log('setup sidenav');
    // $('.button-collapse').sideNav({
    //         menuWidth: 500,
    //         closeOnClick: true
    //     }
    // );

    // $(".button-collapse").sideNav({menuWidth: 300, closeOnClick: true});
    if (window.location.pathname.indexOf('projects') >= 0) {
        // $('.button-collapse').sideNav('show');
        // $('.page.container').addClass('side-nav-out');
    }
    // $(".button-collapse").click(function() {
    //   $('.page.container').toggleClass('side-nav-out');
    // });
    // $('.button-collapse').click(function() {
    //   $('body').css('overflow', 'auto');
    // });
    $('body').css('overflow', 'auto');
    $('.modal-trigger').leanModal();
    $('select').material_select();
    $('.datepicker').pickadate({
        selectMonths: true,
        selectYears: 15,
        formatSubmit: 'yyyy-mm-dd',
        hiddenName: true
    });
    $('#request_service_form').submit(function (e) {
        e.preventDefault();
        var pid = $('#project', this).val();
        var projectCode = $('select#project option:selected', this).attr('projectCode');
        var url = '/projects/' + pid + '/' + $(e.target).attr('action');
        $.post(url, $(this).serialize(), function (data) {
                if (data.status == 'OK') {
                    window.location = '/projects/' + projectCode;
                } else {
                    console.error(data);
                }
            },
            'json'
        );
    });
    /*
     var offset = 220;
     var lastP = 0;
     $(window).scroll(function(){
     var p = $(window).scrollTop();
     $(".research_lifecycle_stage.name").each(function() {
     var t = $(this).offset().top - offset;
     if (t > lastP && t < p || t < lastP && t > p) {
     var d = $(this).next();
     $('.research_lifecycle_stage.services').not(d).fadeOut();
     d.fadeIn();
     }
     });
     lastP = p;
     });
     */
    $(".research_lifecycle_stage.name").mouseover(function () {
        var d = $(this).next();
        $('.research_lifecycle_stage.services').not(d).fadeOut();
        d.fadeIn();
    });
    $(".research_lifecycle_stage.name").click(function () {
        var d = $(this).next();
        $('.research_lifecycle_stage.services').not(d).fadeOut();
        d.fadeIn();
    });
    var research_lifecycle = ['Plan_Design', 'Create_Collect_Capture', 'Analyze_Interpret', 'Publish_Report', 'Discover_Re-use'];
    $.each(research_lifecycle, function (i, stage) {
        $('.lifecycle_selector.' + stage).mouseover(function () {
            var target = $('.research_lifecycle_stage.' + stage);
            $('.research_lifecycle_tip').hide();
            $('.research_lifecycle_stage').not(target).hide();
            target.show();
            target = $('.triangle-down.' + stage);
            $('.triangle-down').not(target).css("visibility", "hidden");
            target.css("visibility", "visible");
        });
    });
    var categories = ["Research_Computing", "Research_Data", "Analysis_and_Visualisation", "Specialized_Equipment", "Consultation_Services", "Communication_and_Publishing"];
    $.each(categories, function (i, category) {
        $('.category_selector.' + category).mouseover(function () {
            $(this).addClass('selected');
            $('.category_selector').not(this).removeClass('selected');
            var target = $('.category.' + category);
            $('.category').not(target).hide();
            target.show();
        });
    });
    $('.card.Research_Computing').show();
    $('.category_selector.Research_Computing').addClass('selected');
    $('.search_button').click(function (e) {
        e.preventDefault();
        $('.searchBox').slideToggle();
    });
    var researchersRaw = $('#researchers').val();

    if(researchersRaw) {
        var researchers = JSON.parse(researchersRaw);
        var supervisors = [];
        var researcherNames = [];
        for (var r of researchers) {
            if (r.institutionalRoleId == 1) {
                supervisors.push({id: r.id, value: r.fullName});
            }
            researcherNames.push({id: r.id, value: r.fullName});
        }
    }
    $(document).on("keypress", ":input:not(textarea)", function (event) {
        return event.keyCode != 13;
    });
    $('#supervisor').autocomplete({
        source: supervisors,
        select: function (e, ui) {
            $('#supervisor-id').val(ui.item.id);
        }
    });
    $('#collaborator').autocomplete({
        source: researcherNames,
        select: function (e, ui) {
            $(this).before('<p class="collaborator">' + ui.item.value + '<input type="hidden" name="collaborator[]" value="' + ui.item.id + '"/> <a class="btn-floating btn-large waves-effect waves-light red remove" onclick="jQuery(this).parent().remove();"><i class="material-icons">delete</i></a></p>');
            setTimeout(function () {
                $('#collaborator').val('')
            }, 10);
        }
    });
    $('#create_project form').submit(function (e) {
        e.preventDefault();
        $.post('/projects/create_project', $(this).serialize(), function (data) {
                if (data.status == 'OK') {
                    window.location = '/projects/' + data.project.project.projectCode;
                } else {
                    console.error(data);
                }
            },
            'json'
        );
    });
})(jQuery);
