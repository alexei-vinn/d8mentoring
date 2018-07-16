(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.colored = {
    attach: function attach(context) {
      var colors = drupalSettings.study_blocks.colors;
      var classname = drupalSettings.study_blocks.classname;
      $('.' + classname + ' li').each(function (index, item) {
        $(this).css('color', colors[index + 1]);
      });
    }
  }
})(jQuery, Drupal, drupalSettings);