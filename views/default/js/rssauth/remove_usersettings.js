define(['require', 'jquery'], function(require, $) {
   $('input[name="plugin_id"][value="rssauth"]').parents('.elgg-module').first().remove();
});