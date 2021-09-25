jQuery(document).ready(function( $ ){

  var CheckboxDropdown = function(el) {
    var _this = this;
    this.isOpen = false;
    this.areAllChecked = false;
    this.$el = $(el);
    this.$label = this.$el.find('.dropdown-label');
    this.$checkAll = this.$el.find('[data-toggle="check-all"]').first();
    this.$inputs = this.$el.find('[type="checkbox"]');

    // All checked initially
    this.$inputs.prop('checked', true);
    
    this.onCheckBox();
    
    this.$label.on('click', function(e) {
      e.preventDefault();
      _this.toggleOpen();
    });
    
    this.$checkAll.on('click', function(e) {
      e.preventDefault();
      _this.onCheckAll();
    });
    
    this.$inputs.on('change', function(e) {
      _this.onCheckBox();
    });
  };
  
  CheckboxDropdown.prototype.onCheckBox = function() {
    this.updateStatus();
  };
  
  CheckboxDropdown.prototype.updateStatus = function() {
    var checked = this.$el.find(':checked');
    
   	
    // for (var cat of Object.values(inputs)) {
    //   if (Object.values(checked).includes(cat)) {
    //    	$('div[name=' + cat.value + ']').show();
    //   }
    //   else {
    //     $('div[name=' + cat.value + ']').hide();
    //   }
    // }

    
    for (const option of this.$el.find(':not(:checked)')) {
      $('.' + option.value).hide();
    }
    for (const option of this.$el.find(':checked')) {
      $('.' + option.value).show();
    }

    const $dropdowns = $("[data-control='checkbox-dropdown']");
    for (const dropdown of $dropdowns) {
      var $dd = $(dropdown);
      if ($dd != this.$el) {
        var $unchecked = $dd.find(':not(:checked)');
        for (const option of $unchecked) {
          $('.' + option.value).hide();
        }
      }
    }
    
    
    this.areAllChecked = false;
    this.$checkAll.html('Check All');
    
    if(checked.length <= 0) {
      this.$label.html('Select Options');
    }
    else if(checked.length === 1) {
      this.$label.html(checked.parent('label').text());
    }
    else if(checked.length === this.$inputs.length) {
      this.$label.html('All Selected');
      this.areAllChecked = true;
      this.$checkAll.html('Uncheck All');
    }
    else {
      this.$label.html(checked.length + ' Selected');
    }
  };
  
  CheckboxDropdown.prototype.onCheckAll = function(checkAll) {
    if(!this.areAllChecked || checkAll) {
      this.areAllChecked = true;
      this.$checkAll.html('Uncheck All');
      this.$inputs.prop('checked', true);
    }
    else {
      this.areAllChecked = false;
      this.$checkAll.html('Check All');
      this.$inputs.prop('checked', false);
    }
    
    this.updateStatus();
  };
  
  CheckboxDropdown.prototype.toggleOpen = function(forceOpen) {
    var _this = this;
    
    if(!this.isOpen || forceOpen) {
       this.isOpen = true;
       const $dropdowns = $("[data-control='checkbox-dropdown']");
       for (const dropdown of $dropdowns) {
        var $dd = $(dropdown);
        if ($dd != this.$el) {
          $dd.find('.dropdown-list').addClass('rem');
          $dd.removeClass('on');
          $dd.removeClass('rem');
        }
       }
       this.$el.addClass('on');
      $(document).on('click', function(e) {
        if(!$(e.target).closest('[data-control]').length) {
         _this.toggleOpen();
        }
      });
    }
    else {
      this.isOpen = false;
      this.$el.removeClass('on');
      $(document).off('click');
    }
  };
  
  var checkboxesDropdowns = document.querySelectorAll('[data-control="checkbox-dropdown"]');
  for(var i = 0, length = checkboxesDropdowns.length; i < length; i++) {
    new CheckboxDropdown(checkboxesDropdowns[i]);
  }
});