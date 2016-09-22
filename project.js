(function ($) {
  "use strict"
  $(document).ready(function() {
    $.fn.editable.defaults.mode = 'inline';
    function make_editable() {
      // Make all objects editable
      var pid = $('#project_id').val();
      $('.editable').editable({url: '/projects/' + pid + '/edit', onblur: 'submit'});
      $('.editable').on('save', function(e, params) {
        Materialize.toast('Saved', 4000)
      });
      $('.editable').on('shown', function(e, params) {
        var $input = params.input.$input;
        if ($input.hasClass('datepicker')) {
          $input.pickadate({
            selectMonths: true,
            selectYears: 15,
            format: 'yyyy-mm-dd'
          });
          var picker = $input.pickadate('picker');
          setTimeout(function() {
            picker.open();
          }, 50);
        }
      });
    }
    make_editable();
    var researchers = JSON.parse($('#researchers').val());
    var supervisors = [];
    var researcherNames = [];
    for (var r in researchers) {
      if (r.institutionalRoleId == 1) {
        supervisors.push({id: r.id, value: r.fullName});
      }
      researcherNames.push({id: r.id, value: r.fullName});
    }
    $('#existing_project_add_collaborator').autocomplete({
      source: researcherNames,
      select: function(e, ui) {
        $(this).before('<p class="collaborator">' + ui.item.value + '<input type="hidden" name="collaborator[]" value="' + ui.item.id + '"/> <a class="btn-floating btn-large waves-effect waves-light red remove" onclick="jQuery(this).parent().remove();"><i class="material-icons">delete</i></a></p>');
        setTimeout(function() { $('#existing_project_add_collaborator').val('') }, 10);
      }
    });
    $('form#add_collaborators, form#add_research_output, form#add_service').submit(function(e) {
      e.preventDefault();
      $('.research_outputs>.checkbox:not(:checked)').parent().remove();
      var pid = $('#project_id').val();
      var url = '/projects/' + pid + '/' + $(e.target).attr('action');
      $.post(url, $(this).serialize(), function(data) {
          if (data.status == 'OK') {
            location.reload();
          } else {
            console.error(data);
          }
        },
        'json'
      );
    });
    $('#service_type').change(function(e) {
      var selection = $('option:selected', this).val();
      var machine_name = selection.replace(' ', '_');
      var target = $('#' + machine_name);
      $('.service_form').not(target).hide();
      target.show();
    });
    $('.existing-collaborator .remove').click(function() {
      var name = $(this).siblings('.title').text();
      var confirmStr = 'Are you sure you wish to remove ' + name + ' from this project?';
      if (confirm(confirmStr)) {
        var pid = $('#project_id').val();
        var rid = $(this).attr('rid');
        $.post('/projects/' + pid + '/rm_collaborator', {rid: rid});
        $(this).parent().remove();
      }
    });
  });
}(jQuery));