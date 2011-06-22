(function ($) {

////////////////////////////////////////////////////////////////////////////////
// Fieldset Helper functions.
////////////////////////////////////////////////////////////////////////////////

/**
 * Fieldset Helper
 */
Drupal.FieldsetHelper = {};

/**
 * Init collapsible fieldset states
 */
Drupal.FieldsetHelper.init = function(){
  var stateManager = new Drupal.FieldsetHelper.StateManager();

  $('fieldset.collapsible > legend:not(.collapse-saved)').each( function(){
    var fieldset = this.parentNode;

    // Auto generate fieldset id
    if(!fieldset.id) {
      fieldset.id = 'fieldset-' + $(this).text().toLowerCase().replace(/[^a-z0-9]+/g, '-');
    }

    // Look up the state.
    var collapsed = stateManager.get(fieldset.id);

    // Skip if fieldset state is not saved.
    if (collapsed === undefined) {return;}

    // Parse collapsed state
    var collapsed = (collapsed == '1') ? true : false;

    // Is collapsed
    var isCollapsed = $(fieldset).hasClass('collapsed');

    // If already collapsed or opened then continue.
    if (collapsed && isCollapsed) {return;}
    if (!collapsed && !isCollapsed) {return;}

    // Set the default collapse state for the fieldset.
    Drupal.FieldsetHelper.setFieldsetState(fieldset, collapsed);

    // Add collapse save to legend
    $(this).addClass('collapse-saved');
  });
};

/**
 * Set fieldset's collapsed state without animation.
 */
Drupal.FieldsetHelper.setFieldsetState = function(fieldset, collapsed) {
  if (collapsed) {
   $(fieldset).addClass('collapsed').children('.fieldset-wrapper').hide();
  } else {
   $(fieldset).removeClass('collapsed').children('.fieldset-wrapper').show();
  }
}

/**
 * Save a fieldset's state.
 */
Drupal.FieldsetHelper.saveFieldsetState = function(fieldset) {
  // Get fieldset collapsed state
  var collapsed = ( $(fieldset).hasClass('collapsed') ) ? 0 : 1;

  // Save fieldset collapsed.
  Drupal.FieldsetHelper.StateManager.set($(fieldset).attr('id'), collapsed);
};

/**
 * Toggle a group of collapsible fieldsets state within a container (id)
 */
Drupal.FieldsetHelper.toggleFieldsets = function(selector, state){
  // If no selector passed set it to toggle all collaspible fieldsets.
  selector = selector || 'fieldset.collapsible';

  $(selector).each( function(){
    // Make sure this is fieldset and it is collapsible.
    if (this.tagName == 'FIELDSET' && $(this).hasClass('collapsible')) {
      Drupal.FieldsetHelper.StateManager.set(this.id, state);
      Drupal.FieldsetHelper.setFieldsetState(this, state);
    }
  });
}

/**
 * Expand all fieldset within a container
 */
Drupal.FieldsetHelper.expandFieldsets = function(selector){
   Drupal.FieldsetHelper.toggleFieldsets(selector, 0);
}

/**
 * Collapse all fieldset within a container
 */
Drupal.FieldsetHelper.collapseFieldsets = function(selector){
  Drupal.FieldsetHelper.toggleFieldsets(selector, 1);
}


////////////////////////////////////////////////////////////////////////////////
// State Manager function
////////////////////////////////////////////////////////////////////////////////

/**
 * Fieldset Helper State Manager.
 *
 * A singleton object used to save collapsible fieldset states.
 * This code is somewhat re-usable for managing any element state.
 *
 * jQuery.cookie documentation at http://plugins.jquery.com/files/jquery.cookie.js.txt
 */
Drupal.FieldsetHelper.StateManager = function(){
  // Call singleton's init method only once
  if (Drupal.FieldsetHelper.StateManager.init) {
    Drupal.FieldsetHelper.StateManager.init();
    Drupal.FieldsetHelper.StateManager.init = null;
  }
  return Drupal.FieldsetHelper.StateManager;
}

/**
 * Initialize fieldset helper state manager
 */
Drupal.FieldsetHelper.StateManager.init = function() {
  // Localize settings
  var settings = Drupal.settings['fieldset_helper_state_manager'] || {};

  // Lookup tables for ids keyed by this.ids[containerId][itemID] = unique id
  this.ids = settings.ids || {};

  // Items states keyed by ids unique id
  this.states = {};

  // Load existing states stored in the client cookie.
  this.load();
}

/**
 * Load states stored in a client cookie.
 */
Drupal.FieldsetHelper.StateManager.load = function() {
  // Parse cookie value
  var result = /fieldset_helper_state_manager=(.*?)(;|$)/.exec(document.cookie);
  if (!result) {
    return;
  }
  var value = result[1];

  // Deserialize cookie state value
  var values = value.split('_');
  for (var i = 0, len = values.length; i < len; i++) {
    var param = values[i].split('.');
    this.states[param[0]] = param[1];
  }

  // DEBUG:
  // if (window.console) { console.log(this.states); }
}

/**
 * Save fieldset helper state manager in a cookie.
 */
Drupal.FieldsetHelper.StateManager.save = function() {
  // Serialize state values for cookie.
  var values = [];
  for(id in this.states) {
    values.push(id + '.' + this.states[id]);
  }

  // If duration is defined then set cookie to expire in X days.
  var expires = null;
  var duration = Drupal.settings['fieldset_helper_state_manager']['cookie_duration'];
	if (duration && duration !== '0') {
    expires = new Date();
    expires.setTime( expires.getTime() + duration
      * 1000 * 60 * 60 * 24 );
  }

  // Set cookie
  document.cookie = 'fieldset_helper_state_manager=' + values.join('_') +
    '; path=/' +
    ((expires)?'; expires=' + expires.toGMTString():'');

  // DEBUG:
  // if (window.console) { console.log(values); }
}

/**
 * Set and save item state
 *
 * @param elementId
 *   The id of the item
 * @param state
 *   Bit value representing true (1) or false (0) state.
 */
Drupal.FieldsetHelper.StateManager.set = function(elementId, state) {
  var id = this.ids[elementId] || elementId;
  this.states[id] = state;
  this.save();
},

/**
 * Get item state
 *
 * @param elementId
 *   The id of the item
 * @return
 *   Bit value representing true (1) or false (0) for the item's state.
 */
Drupal.FieldsetHelper.StateManager.get = function(elementId) {
  var id = this.ids[elementId] || elementId;
  return this.states[id];
}


////////////////////////////////////////////////////////////////////////////////
// Init function. Must execute after all scripts and related behaviors are loaded.
////////////////////////////////////////////////////////////////////////////////

$(function() {

  // Wrap Drupal's toggle fieldset function defined in misc/collapse.js
  // so that we can track fieldset states.
  if (Drupal.toggleFieldset) {
    var toggleFieldset = Drupal.toggleFieldset;
    Drupal.toggleFieldset = function(fieldset) {
      Drupal.FieldsetHelper.saveFieldsetState(fieldset);
      toggleFieldset(fieldset);
    };
  }

  // Add behavior if and after Drupal.behaviors.tao is defined which overrides
  // theme_fieldset and does not use misc/collapse.js.
  if (Drupal.behaviors.tao) {
		Drupal.behaviors.taoFieldsetHelper = function (context) {
      $('fieldset.collapsible > legend > .fieldset-title').click(function() {
        var fieldset = $(this).parents('fieldset').eq(0);

        // Get fieldset collapsed state which is reversed since the
        // tao click event has already executed.
        var collapsed = ( $(fieldset).hasClass('collapsed') ) ? 1 : 0;

        // Save fieldset collapsed.
        Drupal.FieldsetHelper.StateManager.set($(fieldset).attr('id'), collapsed);
      });
    };
    // Init the behavior.
    Drupal.behaviors.taoFieldsetHelper();
  }

	// Init default fieldset states.
	Drupal.FieldsetHelper.init();
});

})(jQuery);