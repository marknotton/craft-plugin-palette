var vibrant = { 
  limit : null,
  hud : null,
  pallete : null,
  init: function(limit) {
    var $this = vibrant;
    if ($('.hud').length >= 2) {
      $('.hud:first, .hud-shade:first').remove();
    }
    $this.limit = limit;
    $this.hud = $('.hud:last');
    $this.pallete = $this.hud.find('.field[id$=pallete-field] .pallete');

    $this.loadImage();
  },
  loadImage: function() {
    var $this = vibrant;
    // This will search and find the id for the currently selected asset
    var id = parseInt($this.hud.find(".elementeditor input[name='elementId']:last").val());
    var data = { assetId: id };
    $this.pallete.addClass('loading');
    // This is an ajax request which also targets the specific action url
    Craft.postActionRequest('Palette/Asset/GetAssetById', data, function(response) {       
      if (response['success']) {
        // Put the image in the HUD popup
        $this.pallete.html('<img src="'+ response['asset'] +'" alt="'+ response['title'] +'">');
        $this.pallete.find('img').on('load', function(e) {
          $this.pallete.removeClass('loading');
          $this.createPallete(this);
          //focusPoint.init();
        });
      } else {
        $this.pallete.html('An error occured with Vibrant');
      }
    });
  },
  createPallete: function(image) {
    var $this = vibrant,
        vPallete = new Vibrant(image),
        swatches = vPallete.swatches(),
        index = 0;

    $this.pallete.append('<nav></nav>');

    for (swatch in swatches) {
      index ++;
      if (swatches.hasOwnProperty(swatch) && swatches[swatch]) {
        var colour = swatches[swatch].getHex();
        $this.pallete.find(' nav').append('<input type="checkbox" name="colour'+index+'" id="colour'+index+'" value="'+colour+'"><label for="colour'+index+'" title="'+swatch+'" style="background-color:'+colour+'"><span style="color:'+swatches[swatch].getBodyTextColor()+'">'+colour+'</span></label>');
      } else { 
        if (swatch != 'LightMuted') {
          $this.pallete.find(' nav').append('<input type="checkbox" name="colour'+index+'" id="colour'+index+'" value="#FFFFFF" data-failed><label for="colour'+index+'" title="'+swatch+'" style="background-color:#FFFFFF"><span style="color:#000000">#FFFFFF</span></label>');
        }
      } 
    }

    var checkboxes = $this.pallete.find('nav input[type=checkbox]'),
        hiddenInput = $this.pallete.parent().find('input[type=hidden]');

    // Add the appropriate settings to the checkboxes and hidden fields, assuming they have been set/saved previously
    hiddenInput.each(function(i) {
      var hidden = $(this);
      if( hidden.val() && i < $this.limit) {
        checkboxes.each(function() {
          var checkbox = $(this);
          if ( checkbox.val() == hidden.val() ) {
            checkbox.prop('checked', true).attr('data-priority', i+1);
          }
        });
      }
      // If more colours were added before the limit was reduced, this will avoid 
      // rendering old colours that exceed the limit
      if ( i >= $this.limit ) { 
        hidden.val('');
      }
    });

    // Checkbox change handling
    checkboxes.on('change', function() {
      var button = $(this),
          buttons = button.siblings('input[type="checkbox"]').andSelf(),
          checked = buttons.filter(':checked'),
          colour = button.val(),
          count = checked.length;

      if ( button.is(':checked')) {
        // Disable the checkbox from checking
        button.prop('checked', false)
        // Only allow the check box to go, if the limit isn't exceeded
        if(count > 0 && count <= $this.limit) {
          button.prop('checked', true);
          button.siblings('[data-priority='+count+']').removeData('priority');
          button.attr('data-priority', count);
          hiddenInput.eq(count-1).val(colour);
        }
      } else {
        var current = button.attr('data-priority');
        var remaining = 0;

        // Clear the current buttons number
        button.attr('data-priority', '');
        // Clear all hidden fields
        hiddenInput.val('');

        // This loop makes sure all the numbers are in order
        buttons.each(function() {              
          remaining = $(this).attr('data-priority');
          if (remaining > current) {
            $(this).attr('data-priority', remaining-1);
          }
        });
        // This loop makes sure that now all the numbers are in order
        // that the hidden fields are still in their order or priority
        buttons.each(function() {
          remaining = $(this).attr('data-priority');
          if (remaining) {
            hiddenInput.eq(remaining-1).val($(this).val());
          }
        });

      }
     
    });
  }
}
